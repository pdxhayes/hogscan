<?php

namespace Drupal\hs\Controller;

use Drupal;
use Drupal\Core\File\FileSystemInterface;
use Drupal\file\Entity\File;
use Drupal\user\Entity\User;


/**
 * Base Controller for HOG[SCAN].
 * This controller contains methods used by other HS modules.
 */
abstract class HsBaseController {

  /**
   * Get hogscan config
   */
  public static function getHogscanConfig() {

  }

  /**
   * createUser()
   * Creates a user record using data contained in $payload
   *
   * @param $payload
   *
   * @return array
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   * @throws \Drupal\Core\Entity\EntityStorageException
   */
  public static function createUser($payload): array {

    // prep the user record
    $account = User::create();
    $account->enforceIsNew();
    $account->set("status", "1");
    $account->set("init", "email");
    $account->activate();

    // make sure the username is unique
    $default_username = self::uniqueUsername($payload['field_member_first_name'], $payload['field_member_last_name']);
    $payload['username'] = $default_username;

    // set account field values
    self::setAccountFields($payload, $account);

    // save the new record
    $account->save();

    // get the new uid and return user data
    $new_uid = $account->id();
    return self::getUserData($new_uid);
  }

  /**
   * @param $payload
   *
   * @return array
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   * @throws \Drupal\Core\Entity\EntityStorageException
   */
  public static function updateUser($payload): array {
    $ext = '';
    if (!$user_id = $payload['uid']) {
      $user_id = Drupal::currentUser()->id();
    }

    // load the user account
    $account = User::load($user_id);

    $payload = self::setAccountFields($payload, $account);

    // update user image
    if (isset($payload['user_picture'])) {

      // make sure it looks like an image
      $file_info = getimagesize($payload['user_picture']);

      if ($file_info['mime']) {
        // get the file extension
        $mime = explode("/", $file_info['mime']);
        $ext = $mime[1];
      }
      // if we have a file extension, we should be ok to proceed  Send directly to S3, without creating any temp files.
      if ($ext) {
        // get the base64 string
        $data = explode(",", $payload['user_picture']);
        $base64_string = $data[1];
        $image_data = base64_decode($base64_string);


        // prepare the directory
        $directory = 'public://user_' . $user_id . '/uploads/profile_pictures/';
        Drupal::service('file_system')->prepareDirectory($directory, FileSystemInterface::CREATE_DIRECTORY);
        $file_destination = $directory . 'upload_' . md5(time() . rand(1, 10000)) . "." . $ext;

        // save the file
        $file = file_save_data($image_data, $file_destination);
        $account->set('user_picture', $file);
      }
    }

    // save the changes
    $account->save();

    return self::getUserData($user_id);

  }

  public static function getCurrentMembers() {

    $query = Drupal::entityQuery('user');

      $and = $query->andConditionGroup()
        ->condition('field_member_hog_member_until', date("Y-m-d"), '>')
        ->condition('field_member_until', date("Y-m-d"), '>')
        ->condition('field_member_release_until', date("Y-m-d"), '>');
      $query->condition($and);

    // regardless of all else, prevent user #1 from being returned
    $query->condition('uid', '1', '>');

    // add access check
    $query->accessCheck(TRUE);

    // execute the query
    $query_result = $query->execute();

    $result = [];
    foreach ($query_result as $id) {
      $result[] = self::getUserData($id, 'default');
    }

    return $result;

  }

  public static function getExpiredMembers() {

    $query = Drupal::entityQuery('user');

    $or = $query->orConditionGroup()
      ->condition('field_member_hog_member_until', date("Y-m-d"), '<')
      ->condition('field_member_until', date("Y-m-d"), '<')
      ->condition('field_member_release_until', date("Y-m-d"), '<');
    $query->condition($or);

    // regardless of all else, prevent user #1 from being returned
    $query->condition('uid', '1', '>');

    // add access check
    $query->accessCheck(TRUE);

    // execute the query
    $query_result = $query->execute();

    $result = [];
    foreach ($query_result as $id) {
      $result[] = self::getUserData($id, 'default');
    }

    return $result;

  }

  public static function getBlockedMembers() {
    $query = Drupal::entityQuery('user');
    // blocked
    $query->condition('status',0, '=');
    // regardless of all else, prevent user #1 from being returned
    $query->condition('uid', 1, '>');

    // add access check
    $query->accessCheck(TRUE);

    // execute the query
    $query_result = $query->execute();

    $result = [];
    foreach ($query_result as $id) {
      $result[] = self::getUserData($id, 'default');
    }

    return $result;
  }

  /**
   * getMemberList
   * Builds a list of members
   *
   * TODO This should be moved to api?
   *
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  public static function getMemberList(): array {

    $query = Drupal::entityQuery('user');
    if ("true" != $_GET['expired']) {
      $and = $query->andConditionGroup()
        ->condition('field_hog_member_until', date("Y-m-d"), '>')
        ->condition('field_member_until', date("Y-m-d"), '>')
        ->condition('field_member_release_until', date("Y-m-d"), '>');
      $query->condition($and);
    }
    if ("true" != $_GET['blocked']) {
      $query->condition('status', '1');
    }
    // regardless of all else, prevent user #1 from being returned
    $query->condition('uid', '1', '>');

    // add access check
    $query->accessCheck(TRUE);

    $query_result = $query->execute();

    $result = [];
    foreach ($query_result as $id) {
      $result[] = self::getUserData($id, $_GET['context']);
    }

    return $result;


  }

  /**
   * uniqueUsername()
   * Generates a unique username to be used as the default when creating users.
   *
   * @param $first_name
   * @param $last_name
   *
   * @return string
   */
  public static function uniqueUsername($first_name, $last_name): string {
    // start with first name, and first letter of last name
    $initial = substr($last_name, 0, 1);
    $default_username = $first_name . $initial;

    $iteration_username = $default_username;
    $i = 1;
    while (user_load_by_name($iteration_username)) {
      Drupal::logger('hogscan')->notice("iteration $i");
      $i++;
      $iteration_username = $default_username . "-" . $i;
    }
    return $iteration_username;

  }

  /**
   * @param $hog_id
   *
   * @return \Drupal\Core\Entity\EntityBase|\Drupal\Core\Entity\EntityInterface|\Drupal\user\Entity\User|false|null
   */
  public static function loadUserByHogId($hog_id) {
    $query = Drupal::entityQuery('user')
      ->condition('field_member_hog_id', $hog_id, '=');
    // add access check
    $query->accessCheck(TRUE);

    $result = $query->execute();

    if (empty($result)) {
      return FALSE;
    }
    else {
      return User::load(array_pop($result));
    }
  }

  /**
   * @param $target_id
   *
   * @return array
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  public static function getTaxonomyTermById($target_id): array {

    $term = Drupal::entityTypeManager()
      ->getStorage('taxonomy_term')
      ->load($target_id);
    $title = $term->name->value;
    $description = $term->description->value ?? "";

    return [
      "title" => $title,
      "description" => $description,
    ];

  }

  /**
   * @param $vocabulary
   * @param $term_name
   *
   * @return array
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  public static function getTaxonomyTermByName($vocabulary, $term_name): array {
    $term_id = NULL;
    $terms = Drupal::entityTypeManager()
      ->getStorage('taxonomy_term')
      ->loadTree($vocabulary);

    // find the term tid.
    foreach ($terms as $term) {
      if ($term->name == $term_name) {
        return self::getTaxonomyTermById($term->tid);
      }
    }

    // if we didn't return already, return empty.
    return [];

  }

  /**
   * @param $payload
   *
   * @return array
   * @throws \Drupal\Core\Entity\EntityStorageException
   */
  public static function deleteUser($payload): array {

    $user_id = $payload['uid'];
    if (1 === $user_id) {
      return ["error" => "You can't delete this user"];
    }

    $account = User::load($user_id);
    $account->delete();

    return ["deleted" => $user_id];

  }

  /**
   *  get consistent user data
   *
   * @param $user_id
   * @param string $context
   *    how much data to return
   *      - summary = small
   *      - default = medium
   *      - extended = large
   *
   * @return array
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  public static function getUserData($user_id = NULL, string $context = "default"): array {
    // if no user is passed in, get current user
    if (!$user_id) {
      $user_id = Drupal::currentUser()->id();
    }

    // if no context is passed in, set to default
    if (!$context) {
      $context = "default";
    }

    // load user data
    $account = User::load($user_id);

    // prepare result
    $result = [];
    $result['context'] = $context;
    $result['uid'] = $account->id();

    $result['field_member_hog_id'] = $account->get('field_member_hog_id')->value ?? '';
    $result['field_member_first_name'] = $account->get('field_member_first_name')->value ?? '';
    $result['field_member_nick_name'] = $account->get('field_member_nick_name')->value ?? '';
    $result['field_member_last_name'] = $account->get('field_member_last_name')->value ?? '';
    $result['field_member_points'] = $account->get('field_member_points')->value ?? '';
    $result['field_member_ice_name'] = $account->get('field_member_ice_name')->value ?? '';
    $result['field_member_ice_phone'] = $account->get('field_member_ice_phone')->value ?? '';
    $result['field_member_hog_member_until'] = $account->get('field_member_hog_member_until')->value ?? '';
    $result['field_member_until'] = $account->get('field_member_until')->value ?? '';
    $result['field_member_release_until'] = $account->get('field_member_release_until')->value ?? '';

    // get the user picture, or set default.
    $result['user_picture'] = "https://hsd9.s3.us-west-2.amazonaws.com/default/default_images/default_member_picture.jpg";
    $fid = $account->get('user_picture')->target_id;
    if ($fid) {
      $file = File::load($fid);
      if ($file) {
        $url = file_create_url($file->getFileUri());
        $result['user_picture'] = $url;
      }
    }


    $fid = $account->get('field_member_qr')->target_id;
    if ($fid) {
      $qr_file = File::load($fid);
      if ($qr_file) {
        $qr_url = file_create_url($qr_file->getFileUri());
        $result['qr_code'] = $qr_url;
      }
    }


    if ("summary" == $context) {
      return $result;
    }

    $result['username'] = $account->getDisplayName();
    $result['email'] = $account->getEmail();

    $result['field_member_phone'] = $account->get('field_member_phone')->value ?? '';
    $result['field_member_address']['address_line1'] = $account->get('field_member_address')->address_line1 ?? '';
    $result['field_member_address']['address_line2'] = $account->get('field_member_address')->address_line2 ?? '';
    $result['field_member_address']['locality'] = $account->get('field_member_address')->locality ?? '';
    $result['field_member_address']['administrative_area'] = $account->get('field_member_address')->administrative_area ?? '';
    $result['field_member_address']['postal_code'] = $account->get('field_member_address')->postal_code ?? '';
    $result['field_member_address']['country_code'] = $account->get('field_member_address')->country_code ?? '';
    $result['field_member_since'] = $account->get('field_member_since')->value ?? '';
    $result['roles'] = $account->getRoles();

    if ("default" == $context) {
      return $result;
    }

    // $result['field_ref_notification_group'] = $account->get('field_ref_notification_group')->value ?? '';
    $notification_groups = $account->get('field_ref_notification_group')->GetValue();

    $group_list = [];
    foreach ($notification_groups as $key => $group_id) {
      $term_data = self::getTaxonomyTermById($group_id['target_id']);
      $term_data['target_id'] = $group_id['target_id'];
      $group_list[] = $term_data;
    }
    $result['field_ref_notification_group'] = $group_list;

    $result['field_member_email_opt_in'] = $account->get('field_member_email_opt_in')->value ?? '';
    $result['field_member_notification_opt_in'] = $account->get('field_member_notification_opt_in')->value ?? '';
    $result['field_member_gen_card'] = $account->get('field_member_gen_card')->value ?? '';

    $result['field_member_hog_member_since'] = $account->get('field_member_hog_member_since')->value ?? '';
    $result['field_member_hog_life_member'] = $account->get('field_member_hog_life_member')->value ?? '';
    $result['field_member_hog_mileage_2018'] = $account->get('field_member_hog_mileage_2018')->value ?? '';
    $result['field_member_hog_mileage_2019'] = $account->get('field_member_hog_mileage_2019')->value ?? '';
    $result['field_member_hog_mileage_2020'] = $account->get('field_member_hog_mileage_2020')->value ?? '';
    $result['field_member_hog_mileage_2021'] = $account->get('field_member_hog_mileage_2021')->value ?? '';
    $result['field_member_hog_mileage_2022'] = $account->get('field_member_hog_mileage_2022')->value ?? '';
    $result['field_member_hog_mileage_life'] = $account->get('field_member_hog_mileage_life')->value ?? '';

    return $result;

  }

  /**
   * @param $payload
   * @param $account
   *
   * @return mixed
   */
  public static function setAccountFields($payload, $account) {

    if (isset($payload['username'])) $account->setUsername($payload['username']);
    if (isset($payload['password'])) $account->setPassword($payload['password']);
    if (isset($payload['email'])) $account->setEmail($payload['email']);
    if (isset($payload['field_member_hog_id'])) $account->set("field_member_hog_id", $payload['field_member_hog_id']);
    if (isset($payload['field_member_first_name'])) $account->set("field_member_first_name", $payload['field_member_first_name']);
    if (isset($payload['field_member_nick_name'])) $account->set("field_member_nick_name", $payload['field_member_nick_name']);
    if (isset($payload['field_member_last_name'])) $account->set("field_member_last_name", $payload['field_member_last_name']);
    if (isset($payload['field_member_points'])) $account->set("field_member_points", $payload['field_member_points']);
    if (isset($payload['field_member_source'])) $account->set("field_member_source", $payload['field_member_source']);
    if (isset($payload['field_member_since'])) $account->set("field_member_since", $payload['field_member_since']);
    if (isset($payload['field_member_until'])) $account->set("field_member_until", $payload['field_member_until']);
    if (isset($payload['field_member_release_until'])) $account->set("field_member_release_until", $payload['field_member_release_until']);
    if (isset($payload['field_member_phone'])) $account->set("field_member_phone", $payload['field_member_phone']);
    if (isset($payload['field_member_hog_first_name'])) $account->set("field_member_hog_first_name", $payload['field_member_hog_first_name']);
    if (isset($payload['field_member_hog_middle_name'])) $account->set("field_member_hog_middle_name", $payload['field_member_hog_middle_name']);
    if (isset($payload['field_member_hog_last_name'])) $account->set("field_member_hog_last_name", $payload['field_member_hog_last_name']);
    if (isset($payload['field_member_hog_member_since'])) $account->set("field_member_hog_member_since", $payload['field_member_hog_member_since']);
    if (isset($payload['field_member_hog_member_until'])) $account->set("field_member_hog_member_until", $payload['field_member_hog_member_until']);
    if (isset($payload['field_member_hog_member_type']))  $account->set("field_member_hog_member_type", $payload['field_member_hog_member_type']);
    if (isset($payload['field_member_hog_life_member']))  $account->set("field_member_hog_life_member", $payload['field_member_hog_life_member']);
    if (isset($payload['field_member_hog_mileage_life'])) $account->set("field_member_hog_mileage_life", $payload['field_member_hog_mileage_life']);
    if (isset($payload['field_member_hog_mileage_2018'])) $account->set("field_member_hog_mileage_2018", $payload['field_member_hog_mileage_2018']);
    if (isset($payload['field_member_hog_mileage_2019'])) $account->set("field_member_hog_mileage_2019", $payload['field_member_hog_mileage_2019']);
    if (isset($payload['field_member_hog_mileage_2020'])) $account->set("field_member_hog_mileage_2020", $payload['field_member_hog_mileage_2020']);
    if (isset($payload['field_member_hog_mileage_2021'])) $account->set("field_member_hog_mileage_2021", $payload['field_member_hog_mileage_2021']);
    if (isset($payload['field_member_hog_mileage_2022'])) $account->set("field_member_hog_mileage_2022", $payload['field_member_hog_mileage_2022']);
    if (isset($payload['field_member_ice_name'])) $account->set("field_member_ice_name", $payload['field_member_ice_name']);
    if (isset($payload['field_member_ice_phone'])) $account->set("field_member_ice_phone", $payload['field_member_ice_phone']);
    if (isset($payload['field_member_gen_card'])) $account->set("field_member_gen_card", $payload['field_member_gen_card']);
    if (isset($payload['field_member_admin_notes'])) $account->set("field_member_admin_notes", $payload['field_member_admin_notes']);
    if (isset($payload['field_member_address']['address_line1'])) $account->field_member_address->address_line1 = $payload['field_member_address']['address_line1'];
    if (isset($payload['field_member_address']['address_line2'])) $account->field_member_address->address_line2 = $payload['field_member_address']['address_line2'];
    if (isset($payload['field_member_address']['locality'])) $account->field_member_address->locality = $payload['field_member_address']['locality'];
    if (isset($payload['field_member_address']['administrative_area'])) $account->field_member_address->administrative_area = $payload['field_member_address']['administrative_area'];
    if (isset($payload['field_member_address']['postal_code'])) $account->field_member_address->postal_code = $payload['field_member_address']['postal_code'];
    if (isset($payload['field_member_address']['country_code'])) $account->field_member_address->country_code = $payload['field_member_address']['country_code'];

    return $account;
  }

}
