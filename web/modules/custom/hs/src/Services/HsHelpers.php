<?php

namespace Drupal\hs\Services;

use Drupal\user\Entity\User; // for working with users
use Drupal\node\Entity\Node; // for working with nodes
use Drupal\taxonomy\Entity\Term; // for working with terms
use Drupal\Core\File\FileSystemInterface;

class HsHelpers {

  // get a list of members, current, expired, blocked or all...
  public function getMemberList() {

    $query = \Drupal::entityQuery('user');
      if("true" != $_GET['expired']) {
        $and = $query->andConditionGroup()
          ->condition('field_member_hog_member_until', date("Y-m-d"), '>')
          ->condition('field_member_until', date("Y-m-d"), '>')
          ->condition('field_member_release_until', date("Y-m-d"), '>');
        $query->condition($and);
      }
      if("true" != $_GET['blocked']) {
        $query->condition('status','1');
      }
      // regardless of all else, prevent user #1 from being returned.
      $query->condition('uid', '2', '>');

    $query_result = $query->execute();

    $result=[];
    foreach($query_result as $usr => $id) {
      // $result[$usr] = $id;
      $result[] = HsHelpers::getUserData($id, $_GET['context']);
    }

    return $result;





    // set the default contect to "all"

        // create the query
    //     $query = \Drupal::entityQuery('user')
    //
    //
    //     // define the params
    //     if("all" == $context) {
    //
    //       // execute query
    //       $member_ids = $query->execute();
    //
    //       // loop through results, if any.
    //       $results = array();
    //       if ($member_ids) {
    //         foreach ($member_ids as $uid) {
    //           $results[] = $uid;
    //         }
    //       }
    //
    //       return $results;
    //
    //     }
    //
    //
    //       if("current" == $context) {
    //
    //         $andGroup = $query->andConditionGroup()
    //           ->condition('field_hog_member_until', time("Y-m-d"), >)
    //           ->condition('field_hog_chapter_until', time("Y-m-d"), >)
    //           ->condition('field_hog_member_release_until', time("Y-m-d"), >)
    //           ->condition('satus', 1, =);
    //
    //       } else if ("expired")
    //
    //     $result = array();
    //     foreach($query as $member) {
    //       $result[] = $member;
    //     }
    //
    //     return $result;



  }


  /*
    Generate a unique username. First name, Last initial, and if needed, a number
  */
  public function uniqueUsername($first_name, $last_name) {
    // start with first name, and first letter of last name
    $initial = substr($last_name, 0, 1);
    $default_username = $first_name . $initial;

    $iteration_username = $default_username;
    $i = 1;
    while(user_load_by_name($iteration_username)) {
     // \Drupal::logger('hogscan')->notice("iteration $i");
      $i++;
      $iteration_username = $default_username . "-" . $i;
    }
    return $iteration_username;

  }

  /*
    Load a user by HOG ID
  */
  public function loadUserByHogId($hogid) {
    $query = \Drupal::entityQuery('user')
      ->condition('field_member_hog_id', $hogid, '=');
    $result = $query->execute();

    if(empty($result)) {
      return false;
    } else {
      return User::load(array_pop($result));
    }
  }

  /*
   *  get a taxonomy term by by target_id
   *
   *  @param $target_id
   *
   *  @return array
   */
  public function getTaxonomyTermById($target_id) {

    $term = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->load($target_id);
    $title = $term->name->value;
    $description = $term->description->value ?? "";

    $result = array(
      "title" => $title,
      "description" => $description,
    );

    return $result;

  }

  /*
   *  get a taxonomy term by by target_id
   *
   *  @param $term_name
   *
   *  @return array
   */
  public function getTaxonomyTermByName($vocabulary, $term_name) {
    $term_id = NULL;
    $terms = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadTree($vocabulary);

    // find the term tid.
    foreach($terms as $term) {
      if($term->name == $term_name) {
        return HsHelpers::getTaxonomyTermById($term->tid);
      }
    }

    // if we didn't return already, return empty.
    return array();

  }


  /*
   *  Create a new user record
   */
  public function deleteUser($payload) {
    $result=[];

    $user_id = $payload['uid'];
    if($user_id < 43) {
      return array("error"=>"You can't delete this user");
    }

    $account = User::load($user_id);
    $account->delete();

    return array("deleted"=>$user_id);

  }


  /*
   *  Update a new user record
   */
  public function updateUser($payload) {

   if(!$user_id = $payload['uid']) { $user_id = \Drupal::currentUser()->id(); }

    // load the user account
    $account = User::load($user_id);

    if(isset($payload['username'])) {
      $account->setUsername($payload['username']);
    }

    if(isset($payload['password'])) {
      $account->setPassword($payload['password']);
    }

    if(isset($payload['email'])) {
      $account->setEmail($payload['email']);
    }

    if(isset($payload['field_member_hog_id'])) {
      $account->set("field_member_hog_id", $payload['field_member_hog_id']);
    }

    if(isset($payload['field_member_first_name'])) {
      $account->set("field_member_first_name", $payload['field_member_first_name']);
    }

    if(isset($payload['field_member_nick_name'])) {
      $account->set("field_member_nick_name", $payload['field_member_nick_name']);
    }

    if(isset($payload['field_member_last_name'])) {
      $account->set("field_member_last_name", $payload['field_member_last_name']);
    }

    if(isset($payload['field_member_points'])) {
      $account->set("field_member_points", $payload['field_member_points']);
    }

    if(isset($payload['field_member_source'])) {
      $account->set("field_member_source", $payload['field_member_source']);
    }

    if(isset($payload['field_member_since'])) {
      $account->set("field_member_since", $payload['field_member_since']);
    }

    if(isset($payload['field_member_until'])) {
      $account->set("field_member_until", $payload['field_member_until']);
    }

    if(isset($payload['field_member_release_until'])) {
      $account->set("field_member_release_until", $payload['field_member_release_until']);
    }

    if(isset($payload['field_member_phone'])) {
      $account->set("field_member_phone", $payload['field_member_phone']);
    }

    if(isset($payload['field_member_hog_first_name'])) {
      $account->set("field_member_hog_first_name", $payload['field_member_hog_first_name']);
    }

    if(isset($payload['field_member_hog_middle_name'])) {
      $account->set("field_member_hog_middle_name", $payload['field_member_hog_middle_name']);
    }

    if(isset($payload['field_member_hog_last_name'])) {
      $account->set("field_member_hog_last_name", $payload['field_member_hog_last_name']);
    }

    if(isset($payload['field_member_hog_member_since'])) {
      $account->set("field_member_hog_member_since", $payload['field_member_hog_member_since']);
    }

    if(isset($payload['field_member_hog_member_until'])) {
      $account->set("field_member_hog_member_until", $payload['field_member_hog_member_until']);
    }

    if(isset($payload['field_member_hog_member_type'])) {
      $account->set("field_member_hog_member_type", $payload['field_member_hog_member_type']);
    }

    if(isset($payload['field_member_hog_life_member'])) {
      $account->set("field_member_hog_life_member", $payload['field_member_hog_life_member']);
    }

    if(isset($payload['field_member_hog_mileage_life'])) {
      $account->set("field_member_hog_mileage_life", $payload['field_member_hog_mileage_life']);
    }

    if(isset($payload['field_member_hog_mileage_2018'])) {
      $account->set("field_member_hog_mileage_2018", $payload['field_member_hog_mileage_2018']);
    }

    if(isset($payload['field_member_hog_mileage_2019'])) {
      $account->set("field_member_hog_mileage_2019", $payload['field_member_hog_mileage_2019']);
    }

    if(isset($payload['field_member_hog_mileage_2020'])) {
      $account->set("field_member_hog_mileage_2020", $payload['field_member_hog_mileage_2020']);
    }

    if(isset($payload['field_member_hog_mileage_2021'])) {
      $account->set("field_member_hog_mileage_2021", $payload['field_member_hog_mileage_2021']);
    }

    if(isset($payload['field_member_hog_mileage_2022'])) {
      $account->set("field_member_hog_mileage_2022", $payload['field_member_hog_mileage_2022']);
    }

    if(isset($payload['field_member_ice_name'])) {
      $account->set("field_member_ice_name", $payload['field_ice_name']);
    }

    if(isset($payload['field_member_ice_phone'])) {
      $account->set("field_member_ice_phone", $payload['field_ice_phone']);
    }

    if(isset($payload['field_member_gen_card'])) {
      $account->set("field_member_gen_card", $payload['field_gen_card']);
    }

    if(isset($payload['field_member_admin_notes'])) {
      $account->set("field_member_admin_notes", $payload['field_member_admin_notes']);
    }

    if(isset($payload['field_member_address']['address_line1'])) {
      $account->field_member_address->address_line1 = $payload['field_member_address']['address_line1'];
    }

    if(isset($payload['field_member_address']['address_line2'])) {
      $account->field_member_address->address_line2 = $payload['field_member_address']['address_line2'];
    }

    if(isset($payload['field_member_address']['locality'])) {
      $account->field_member_address->locality = $payload['field_member_address']['locality'];
    }

    if(isset($payload['field_member_address']['administrative_area'])) {
      $account->field_member_address->administrative_area = $payload['field_member_address']['administrative_area'];
    }

    if(isset($payload['field_member_address']['postal_code'])) {
      $account->field_member_address->postal_code = $payload['field_member_address']['postal_code'];
    }

    if(isset($payload['field_member_address']['country_code'])) {
      $account->field_member_address->country_code = $payload['field_member_address']['country_code'];
    }

    // update user image
    if(isset($payload['user_picture'])) {

      // make sure it looks like an image
      $fileinfo = getimagesize($payload['user_picture']);

      if($fileinfo['mime']) {
        // get the file extension
        $mime = explode("/", $fileinfo['mime']);
        $ext = $mime[1];
      }
      // if we have a file extension, we should be ok to proceed  Send directly to S3, without creating any temp files.
      if($ext) {
        // get the base64 string
        $data = explode(",", $payload['user_picture']);
        $base64_string = $data[1];
        $image_data = base64_decode($base64_string);


        // prepare the directory
        $directory = 'public://user_' . $user_id . '/uploads/profile_pictures/';
        $ret = \Drupal::service('file_system')->prepareDirectory($directory, \Drupal\Core\File\FileSystemInterface::CREATE_DIRECTORY);
        $file_destination = $directory.'upload_' . md5(time() . rand(1,10000)) . "." . $ext;

        // save the file
        $file = file_save_data($image_data, $file_destination);

        $account->set('user_picture', $file);
      }
    }

    // save the changes
    $account->save();

    return HsHelpers::getUserData($user_id);


  }



  /*
   *  Create a new user record
   */
  public function createUser($payload) {


    // prep the user record
    $account = User::create();
    $account->enforceIsNew();
    $account->set("status","1");
    $account->set("init","email");
    $account->activate();

    if(isset($payload['username'])) {
      $account->setUsername($payload['username']);
    }

    if(isset($payload['password'])) {
      $account->setPassword($payload['password']);
    }

    if(isset($payload['email'])) {
      $account->setEmail($payload['email']);
    }

    if(isset($payload['field_member_hog_id'])) {
      $account->set("field_member_hog_id", $payload['field_member_hog_id']);
    }

    if(isset($payload['field_member_first_name'])) {
      $account->set("field_member_first_name", $payload['field_member_first_name']);
    }

    if(isset($payload['field_member_nick_name'])) {
      $account->set("field_member_nick_name", $payload['field_member_nick_name']);
    }

    if(isset($payload['field_member_last_name'])) {
      $account->set("field_member_last_name", $payload['field_member_last_name']);
    }

    if(isset($payload['field_member_points'])) {
      $account->set("field_member_points", $payload['field_member_points']);
    }

    if(isset($payload['field_member_source'])) {
      $account->set("field_member_source", $payload['field_member_source']);
    }

    if(isset($payload['field_member_since'])) {
      $account->set("field_member_since", $payload['field_member_since']);
    }

    if(isset($payload['field_member_until'])) {
      $account->set("field_member_until", $payload['field_member_until']);
    }

    if(isset($payload['field_member_release_until'])) {
      $account->set("field_member_release_until", $payload['field_member_release_until']);
    }

    if(isset($payload['field_member_phone'])) {
      $account->set("field_member_phone", $payload['field_member_phone']);
    }

    if(isset($payload['field_member_hog_first_name'])) {
      $account->set("field_member_hog_first_name", $payload['field_member_hog_first_name']);
    }

    if(isset($payload['field_member_hog_middle_name'])) {
      $account->set("field_member_hog_middle_name", $payload['field_member_hog_middle_name']);
    }

    if(isset($payload['field_member_hog_last_name'])) {
      $account->set("field_member_hog_last_name", $payload['field_member_hog_last_name']);
    }

    if(isset($payload['field_member_hog_member_since'])) {
      $account->set("field_member_hog_member_since", $payload['field_member_hog_member_since']);
    }

    if(isset($payload['field_member_hog_member_until'])) {
      $account->set("field_member_hog_member_until", $payload['field_member_hog_member_until']);
    }

    if(isset($payload['field_member_hog_member_type'])) {
      $account->set("field_member_hog_member_type", $payload['field_member_hog_member_type']);
    }

    if(isset($payload['field_member_hog_life_member'])) {
      $account->set("field_member_hog_life_member", $payload['field_member_hog_life_member']);
    }

    if(isset($payload['field_member_hog_mileage_life'])) {
      $account->set("field_member_hog_mileage_life", $payload['field_member_hog_mileage_life']);
    }

    if(isset($payload['field_member_hog_mileage_2018'])) {
      $account->set("field_member_hog_mileage_2018", $payload['field_member_hog_mileage_2018']);
    }

    if(isset($payload['field_member_hog_mileage_2019'])) {
      $account->set("field_member_hog_mileage_2019", $payload['field_member_hog_mileage_2019']);
    }

    if(isset($payload['field_member_hog_mileage_2020'])) {
      $account->set("field_member_hog_mileage_2020", $payload['field_member_hog_mileage_2020']);
    }

    if(isset($payload['field_member_hog_mileage_2021'])) {
      $account->set("field_member_hog_mileage_2021", $payload['field_member_hog_mileage_2021']);
    }

    if(isset($payload['field_member_hog_mileage_2022'])) {
      $account->set("field_member_hog_mileage_2022", $payload['field_member_hog_mileage_2022']);
    }

    if(isset($payload['field_member_ice_name'])) {
      $account->set("field_member_ice_name", $payload['field_ice_name']);
    }

    if(isset($payload['field_member_ice_phone'])) {
      $account->set("field_member_ice_phone", $payload['field_ice_phone']);
    }

    if(isset($payload['field_member_gen_card'])) {
      $account->set("field_member_gen_card", $payload['field_gen_card']);
    }

    if(isset($payload['field_member_admin_notes'])) {
      $account->set("field_member_admin_notes", $payload['field_member_admin_notes']);
    }

    if(isset($payload['field_member_address']['address_line1'])) {
      $account->field_member_address->address_line1 = $payload['field_member_address']['address_line1'];
    }

    if(isset($payload['field_member_address']['address_line2'])) {
      $account->field_member_address->address_line2 = $payload['field_member_address']['address_line2'];
    }

    if(isset($payload['field_member_address']['locality'])) {
      $account->field_member_address->locality = $payload['field_member_address']['locality'];
    }

    if(isset($payload['field_member_address']['administrative_area'])) {
      $account->field_member_address->administrative_area = $payload['field_member_address']['administrative_area'];
    }

    if(isset($payload['field_member_address']['postal_code'])) {
      $account->field_member_address->postal_code = $payload['field_member_address']['postal_code'];
    }

    if(isset($payload['field_member_address']['country_code'])) {
      $account->field_member_address->country_code = $payload['field_member_address']['country_code'];
    }

    // save the new record
    $account->save();
    $new_uid = $account->id();
    return HsHelpers::getUserData($new_uid);
  }

  /*
   *  get consistent user data
   *
   *  @param number $user_id
   *    the uid of a user
   *
   *  @param string $context
   *    how much data to return
   *      - summary = small
   *      - default = medium
   *      - extended = large
   *
   *  @return array of consumable user data
   */
  public function getUserData($user_id = null, $context = "default") {
    // if no user is passed in, get current user
    if(!$user_id) { $user_id = \Drupal::currentUser()->id(); }

    // if no context is passed in, set to default
    if(!$context) { $context = "default"; }

    // load user data
    $account = User::load($user_id);

    // prepare result
    $result=[];
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
    if($fid) {
      $file = \Drupal\file\Entity\File::load($fid);
      if($file) {
        $url = file_create_url($file->getFileUri());
        $result['user_picture'] = $url;
      }
    }


    $fid = $account->get('field_member_qr')->target_id;
    if($fid) {
      $qr_file = \Drupal\file\Entity\File::load($fid);
      if($qr_file) {
        $qr_url = file_create_url($qr_file->getFileUri());
        $result['qr_code'] = $qr_url;
      }
  }




    if("summary" == $context) {
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

    if("default" == $context) {
      return $result;
    }

    // $result['field_ref_notification_group'] = $account->get('field_ref_notification_group')->value ?? '';
    $notification_groups = $account->get('field_ref_notification_group')->GetValue();

    $group_list=[];
    foreach($notification_groups as $key=>$group_id) {
      $term_data = HsHelpers::getTaxonomyTermById($group_id['target_id']);
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

}
