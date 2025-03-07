<?php

namespace Drupal\hs_admin\Batch;

use Drupal;
use Drupal\node\Entity\Node;

use Drupal\hs\Controller\HsBaseController;

/**
 * Demo Data Batch
 */
class DemoDataBatch extends HsBaseController {

  /**
   * @param $payload
   * @param $context
   *
   * @return void
   */
  public static function batchGenerateUserData($payload, &$context) {

    $new_user =  parent::createUser($payload);

    $context['message'] = 'Generating user # ' . count($context['results']);
    $context['results'][] = $new_user;

  }

  /**
   * @param $success
   * @param $results
   * @param $operations
   *
   * @return void
   */
  public static function batchFinishedUserData($success, $results, $operations) {
    if ($success) {
      $count = count($results);
      $message = $count . ' users processed.';
    } else {
      $message = t('Finished with an error.');
    }

    Drupal::messenger()->addStatus($message);

  }

  /**
   * @param $payload
   * @param $context
   *
   * @return void
   * @throws \Drupal\Core\Entity\EntityStorageException
   */
  public static function batchGenerateEventData($payload, &$context) {

      $node = Node::create(['type' => 'event']);

      $node->title = $payload['title'];

      $body_content = file_get_contents(Drupal::service('extension.list.module')->getPath('hs_admin') . "/misc/html/" . $payload['body']);
      $node->body = $body_content;
      $node->body->format = "full_html";

      $node->field_event_date_range->value = $payload['event_start_date'];
      $node->field_event_date_range->end_value = $payload['event_end_date'];

      $node->field_event_points = $payload['field_event_points'];
      $node->field_event_mileage = $payload['field_event_mileage'];

      $node->field_event_location_start->organization = $payload['field_event_location_start']['organization'];
      $node->field_event_location_start->address_line1 = $payload['field_event_location_start']['address_line1'];
      $node->field_event_location_start->locality = $payload['field_event_location_start']['locality'];
      $node->field_event_location_start->administrative_area = $payload['field_event_location_start']['administrative_area'];
      $node->field_event_location_start->postal_code = $payload['field_event_location_start']['postal_code'];

      $node->field_event_location_end->organization = $payload['field_event_location_end']['organization'];
      $node->field_event_location_end->address_line1 = $payload['field_event_location_end']['address_line1'];
      $node->field_event_location_end->locality = $payload['field_event_location_end']['locality'];
      $node->field_event_location_end->administrative_area = $payload['field_event_location_end']['administrative_area'];
      $node->field_event_location_end->postal_code = $payload['field_event_location_end']['postal_code'];

      // team
      $node->event_ref_team_coordinator = $payload['event_ref_team_coordinator'];
      $node->field_event_ref_team_lead_rc = $payload['field_event_ref_team_lead_rc'];
      $node->field_event_ref_team_mid_rc = $payload['field_event_ref_team_mid_rc'];
      $node->field_event_ref_team_sweep_rc = $payload['field_event_ref_team_sweep_rc'];

      // terms
      $node->field_event_ref_term_event_type = $payload['field_event_ref_term_event_type'];

      // files
      // $node->set('field_event_images', $payload['field_event_images']);
      // $node->set('field_event_gpx_files', $payload['field_event_gpx_files']);


      // force a new node
      $node->enforceIsNew();

      // save node
      $node->save();

      $context['message'] = 'Generating event # ' . count($context['results']);
      $context['results'][] = $node;


    }

  /**
   * @param $success
   * @param $results
   * @param $operations
   *
   * @return void
   */
    public static function batchFinishedEventData($success, $results, $operations) {
      if ($success) {
        $count = count($results);
        $message = $count . ' events processed.';
      } else {
        $message = t('Finished with an error.');
      }

      Drupal::messenger()->addStatus($message);

    }


}
