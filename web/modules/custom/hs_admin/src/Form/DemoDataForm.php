<?php

namespace Drupal\hs_admin\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

use Drupal\hs\Controller\HsBaseController;

/**
* Demo Data Form
*/
class DemoDataForm extends FormBase {

  /**
   * @return string
   */
  public function getFormId(): string {
    return 'demo_data_form';
  }

  /**
   * @param array $form
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *
   * @return array
   */
  public function buildForm(array $form, FormStateInterface $form_state): array {

    $form['generate_member_quantity'] = array(
      '#type' => 'textfield',
      '#title' => t('Number of members to generate'),
      '#size' => 20,
      '#default_value' => 0,
    );

    $form['generate_events'] = array(
      '#type' => 'checkbox',
      '#title' =>	"Generate Events",
      '#options' => array(TRUE=>"Generate Events"),
      '#default_value' => FALSE,
      '#validated' => TRUE,
    );

    $form['months_past'] = array(
      '#type' => 'textfield',
      '#title' => t('Months Past'),
      '#size' => 20,
      '#default_value' => 6,
    );

    $form['months_future'] = array(
      '#type' => 'textfield',
      '#title' => t('Months Future'),
      '#size' => 20,
      '#default_value' => 6,
    );

    $form['generate_rsvps'] = array(
      '#type' => 'checkbox',
      '#title' =>	"Generate Rsvps",
      '#options' => array(TRUE=>"Generate Rsvps"),
      '#default_value' => FALSE,
      '#validated' => TRUE,
    );

    $form['generate_checkins'] = array(
      '#type' => 'checkbox',
      '#title' =>	"Generate Checkins",
      '#options' => array(TRUE=>"Generate Checkins"),
      '#default_value' => FALSE,
      '#validated' => TRUE,
    );

    $form['actions']['#type'] = 'actions';
    $form['actions']['submit'] = array(
      '#type' => 'submit',
      '#value' => 'Generate',
      '#button_type' => 'primary',
    );

    return $form;


  }

  // public function validateForm(array &$form, FormStateInterface $form_state) {
  //
  // }

  /**
   * @param array $form
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *
   * @return void
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

    // generate users?
    if($form_state->getValue('generate_member_quantity') > 0) {

      // how many?
      $qty = $form_state->getValue('generate_member_quantity');

      // define batch
      $user_batch = array(
        'title' => 'Generating demo data',
        'operations' => [],
        'init_message' => 'Preparing...',
        'progress_message' => 'Processed @current of @total.',
        'error_message' => 'An error occurred during data generation.',
        'finished' => '\Drupal\hs_admin\Batch\DemoDataBatch::batchFinishedUserData',
      );

      // loop $qty times, generate data and add to batch.
      for($i = 0; $i < $qty; $i++) {
        $user_payload = $this::getUserPayload();
        $user_batch['operations'][] = ['\Drupal\hs_admin\Batch\DemoDataBatch::batchGenerateUserData', [$user_payload]];
      }

      // set the batch
      batch_set($user_batch);

    }

    // generate events?
    if(1 == $form_state->getValue('generate_events')) {

      $events = json_decode(file_get_contents(Drupal::service('extension.list.module')->getPath('hs_admin') . '/misc/json/events.json'), TRUE);
      $count = count($events);

      $event_batch = array(
        'title' => 'Generating ' . $count . " event records.",
        'operations' => [],
        'init_message' => 'Preparing...',
        'progress_message' => 'Processed @current of @total.',
        'error_message' => 'An error occurred during data generation.',
        'finished' => '\Drupal\hs_admin\Batch\DemoDataBatch::batchFinishedEventData',
      );

      foreach($events as $event_payload) {
        // let's do some random dates.
        $year = date("Y");
        $long_month = date("F");
        $base_datestring = $event_payload['datestring'] . " " . $year; // i.e. "first Saturday of %month%
        $datestring = str_replace("%month%", $long_month, $base_datestring);


        $event_payload['event_start_date'] = date("Y-m-d\T14:00:00", strtotime($datestring));
        $event_payload['event_end_date'] = date("Y-m-d\T16:00:00", strtotime($datestring));

        $event_batch['operations'][] = ['\Drupal\hs_admin\Batch\DemoDataBatch::batchGenerateEventData', [$event_payload]];

      }


      batch_set($event_batch);
    }

  }

   public function getEventPayload() {

     $events = json_decode(file_get_contents(Drupal::service('extension.list.module')->getPath('hs_admin') . '/misc/json/events.json'), TRUE);

     $event = $events[rand(0,count($events)-1)];

     // let's do some random dates.
     $year = date("Y");
     $long_month = date("F");
     $base_datestring = $event['datestring'] . " " . $year; // "first Saturday of %month%
     $datestring = str_replace("%month", $long_month, $base_datestring);


     $event['event_start_date'] = date("Y-m-d\T14:00:00", strtotime($datestring));
     $event['event_end_date'] = date("Y-m-d\T16:00:00", strtotime($datestring));

     return $event;

   }

  /**
   * @return array
   */
  public function getUserPayload(): array {

    $names = json_decode(file_get_contents(Drupal::service('extension.list.module')->getPath('hs_admin') . '/misc/json/names.json'), TRUE);
    $addresses = json_decode(file_get_contents(Drupal::service('extension.list.module')->getPath('hs_admin') . '/misc/json/addresses.json'), TRUE);
    $words = json_decode(file_get_contents(Drupal::service('extension.list.module')->getPath('hs_admin') . '/misc/json/words.json'), TRUE);

    $firstnames = $names['first'];
    $lastnames = $names['last'];
    $nicknames = $names['nickname'];

    shuffle($firstnames);
    shuffle($lastnames);
    shuffle($nicknames);
    shuffle($addresses);
    shuffle($words);

    $first = array_pop($firstnames);
    $last = array_pop($lastnames);
    $nickname = array_pop($nicknames);

    // add some expired members.
    // Roll the dice. 7 and 11 are craps.
    $member_until = date("Y-m-t", strtotime('Dec 31'));
    $release_until = date("Y-m-t", strtotime('Dec 31'));
    $national_until = date("Y-m-t", strtotime(rand(2022,2024) . '-' . rand(1,12) . '-15'));

    $r = rand(7,11);
    if(7 == $r) {
      $d = rand(2019,2021) . "-12-31";
      $member_until = $d;
      $release_until = $d;
    } else if (11 == $r) {
      $national_until = date("Y-m-t", strtotime(rand(2019,2021) . '-' . rand(1,12) . '-15'));
    }

    $mail = urlencode(array_pop($words)) . rand(1,99) . "@" . urlencode(array_pop($words)) . ".com";

    $username = HsBaseController::uniqueUsername($first,$last);

    $hog_id = "HS" . rand(1000000, 9999999);
    $phone = "(" . rand(100,999) . ") " . rand(100,999) . "-" . rand(1000,9999);


    $payload['email'] = $mail;
    $payload['username'] = $username;
    $payload['password'] = "DemoData123";
    $payload['field_hogscan_migrate_uid'] = '';
    $payload['field_member_address']['address_line1'] = '18665 NW Tolovana St';
    $payload['field_member_address']['address_line2'] = '';
    $payload['field_member_address']['locality'] = 'Portland ';
    $payload['field_member_address']['administrative_area'] = 'OR';
    $payload['field_member_address']['postal_code'] = '97229';
    $payload['field_member_address']['country_code'] = 'US';
    $payload['field_member_admin_notes'] = 'Sample Admin Note';
    $payload['field_member_app_lat'] = '123.123';
    $payload['field_member_app_long'] = '-123.123';
    $payload['field_member_app_platform'] = 'App Platform';
    $payload['field_member_app_push_token'] = 'App Token';
    $payload['field_member_email_opt_in'] = '1';
    $payload['field_member_first_name'] = $first;
    $payload['field_member_gen_card'] = 1;
    $payload['field_member_hog_first_name'] = 'HOG_FIRST';
    $payload['field_member_hog_id'] = $hog_id;
    $payload['field_member_hog_last_name'] = 'HOG_LAST';
    $payload['field_member_hog_life_member'] = 1;
    $payload['field_member_hog_member_since'] = date('Y-m-d');
    $payload['field_member_hog_member_type'] = 'full';
    $payload['field_member_hog_member_until'] = $national_until;
    $payload['field_member_hog_middle_name'] = 'HOG_MIDDLE';
    $payload['field_member_hog_mileage_2018'] = rand(2500,25000);
    $payload['field_member_hog_mileage_2019'] = rand(2500,25000);
    $payload['field_member_hog_mileage_2020'] = rand(2500,25000);
    $payload['field_member_hog_mileage_2021'] = rand(2500,25000);
    $payload['field_member_hog_mileage_2022'] = rand(2500,25000);
    $payload['field_member_hog_mileage_life'] = rand(150000,300000);
    $payload['field_member_hog_status'] = 'Active';
    $payload['field_member_ice_name']= array_pop($firstnames) . " " . array_pop($lastnames);
    $payload['field_member_ice_phone'] = $phone;
    $payload['field_member_last_name'] = $last;
    $payload['field_member_nick_name'] = $nickname;
    $payload['field_member_notification_opt_in'] = '1';
    $payload['field_member_phone'] = $phone;
    $payload['field_member_points'] = 0;
    $payload['field_member_qr'] = null;
    $payload['field_member_release_until'] = $release_until;
    $payload['field_member_send_welcome_email'] = 0;
    $payload['field_member_since'] = date("Y-m-d");
    $payload['field_member_source'] = 'Import';
    $payload['field_member_until'] = $member_until;
    $payload['field_ref_notification_groups'] = '';
    $payload['user_picture'] = null;

    return $payload;

  }



}
