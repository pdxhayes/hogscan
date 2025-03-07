<?php

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Entity\Query\QueryFactory;
use Drupal\hs\Controller\HsBaseController;

/**
 * Implements hook_form_FORM_ID_alter().
 * Modify the user register form
 */
function hs_form_user_register_form_alter(&$form, FormStateInterface $form_state, $form_id) {

  $form['#title'] = "Add Member";

  // attach the user register form javascript
  $form['#attached']['library'][] = 'hs/hs_user_register_form';

  // turn off autocomplete
  $form['#attributes']['autocomplete'] = 'off';
  $form['#prefix'] = t("This web page allows administrators to add members. National H.O.G. ID is required and must be unique. Email address is NOT required, but if included, it must be unique.");

  // Generate a random username for hidden form field. Final default username is determined on validate.
  $tmp_username = md5(time() . rand(1,1000));
  $form['account']['name']['#value'] = $tmp_username;
  $form['account']['mail']['#description'] = t("Email address is NOT required, but if included, it must be unique.");

  // hide form fields to streamline adding members. these will be set in other places
  $form['account']['name']['#access'] = FALSE;
  $form['account']['pass']['#access'] = FALSE;
  $form['account']['status']['#access'] = FALSE;
  $form['account']['notify']['#access'] = FALSE;
  $form['account']['roles']['#access'] = FALSE;
  $form['role_change']['#access'] = FALSE;

  // add a validate handler
  array_unshift($form['#validate'], 'hs_form_user_register_form_validate');

}


/**
 *  Implements hook_form_FORM_ID_alter()
 *  Validate the user register form, and add required field values
 *
 * @param $form
 * @param \Drupal\Core\Form\FormStateInterface $form_state
 *
 * @return void
 */
function hs_form_user_register_form_validate(&$form, FormStateInterface $form_state) {

  // we want to set a default username based on first name, last initial.
  $first_name = $form['field_member_first_name']['widget'][0]['value']['#value'];
  $last_name = $form['field_member_last_name']['widget'][0]['value']['#value'];

  // make sure the username is unique
  $default_username = HsBaseController::uniqueUsername($first_name, $last_name);

  // set the username
  $form_state->setValueForElement($form['account']['name'], $default_username);

  // copy the value of field_send_welcome_email to notify.
  $form_state->setValueForElement($form['account']['notify'], $form['field_member_send_welcome_email']['widget']['value']['#value']);

}


/**
 * Implements hook_form_FORM_ID_alter().
 * User Edit Form
 *
 * @param $form
 * @param \Drupal\Core\Form\FormStateInterface $form_state
 * @param $form_id
 *
 * @return void
 */
function hs_form_user_form_alter(&$form, FormStateInterface $form_state, $form_id) {

  // turn off autocomplete
  $form['#attributes']['autocomplete'] = 'off';

  if(1 != Drupal::currentUser()->id()) {
    // attach the user register and edit form javascript to pre-validate fields disable HOG fields
    $form['#attached']['library'][] = 'hs/hs_user_edit_form';
    $form['#attached']['library'][] = 'hs/hs_user_register_form';

    // disable user delete, for now...
    $form['actions']['delete']['#access'] = FALSE;
  }



}

//
///**
// * Generates a unique username.
// */
//function hs_unique_username($first_name, $last_name) {
//
//  $initial = substr($last_name, 0, 1);
//  $default_username = $first_name . $initial;
//  $iteration_username = $default_username;
//  $i = 1;
//  while(user_load_by_name($iteration_username)) {
//   // \Drupal::logger('hogscan')->notice("iteration $i");
//    $i++;
//    $iteration_username = $default_username . "-" . $i;
//  }
//  return $iteration_username;
//
//}
//
///*
//  Load a user by HOG ID
//*/
//function hs_load_user_by_hogid($hogid) {
//
//  $query = \Drupal::entityQuery('user')
//    ->condition('field_hog_id', $hogid, '=');
//  $result = $query->execute();
//
//  if(empty($result)) {
//    return false;
//  } else {
//    return User::load(array_pop($result));
//  }
//
//}
