<?php

namespace Drupal\hs_api\Controller;

use Drupal\node\Entity\Node; // For working with nodes
use Drupal\user\Entity\User; // For working with users
use Drupal\taxonomy\Entity\Term; // for working with taxonomy terms
use Symfony\Component\HttpFoundation\JsonResponse; // for JSON response output
use Drupal\hs\Services\HsHelpers; // for HS Helpers


/**
* Parent class for HOG[SCAN].
*
*/
class HsApi {

  public function test() {

    $module = 'system';
    $key = 'mail';
    $to = 'russ@hogscan.com,pdxrhayes@gmail.com';
    $params['context']['subject'] = 'This is a test to multiple addresses';
    $params['context']['message'] = 'This is a test sending email from Drupal to multiple recipients.';
    $langcode = 'en';
    $send = true;

    $mailManager = \Drupal::service('plugin.manager.mail');
    $result = $mailManager->mail($module, $key, $to, $langcode, $params, NULL, $send);

    return new JsonResponse(json_encode($result));

  }


  // DEBUG: Get list of permissions
  public function get_permission_list() {
    $user = \Drupal::currentUser();
    $user_roles = $user->getRoles();

    $roles_permissions = user_role_permissions($user_roles);

    $final_array = array();
    foreach ($roles_permissions as $role_key => $permissions) {
      foreach ($permissions as $permission) {
        $final_array[] = $permission;
      }
    }

    return new JsonResponse($final_array);
  }

  public function motd() {

    return new JsonResponse($this->getMotd());

  }

  private function getMotd() {

    $motd['lastUpdate'] = '2022-01-09T23:59:59';
    $motd['apiVersion'] = "3.0.b1";
    $motd['appVersion'] = "3.0.a1";
    $motd['messageOfTheDay']['message'] = "Something to show the user.";
    $motd['messageOfTheDay']['persistent'] = "TRUE";

    return $motd;

  }

  public function get_member_list() {
    $helper = new HsHelpers;
    return new JsonResponse($helper->getMemberList());

  }

  public function get_events() {

    $query = \Drupal::entityQuery('node');
    $query->condition('type', 'event');
    $query->condition('status', 1);

    $query_result = $query->execute();

    $result=[];
    foreach($query_result as $eid) {
      $node = node::load($eid);
      $result[$eid]['title'] = $node->get('title')->value ?? '';
      $result[$eid]['body'] = $node->get('body')->value ?? '';
      $result[$eid]['date_start'] = $node->get('field_event_date_range')->value ?? '';
      $result[$eid]['date_end'] = $node->get('field_event_date_range')->end_value ?? '';

      $location_start = $node->get('field_event_location_start')->getValue();
      $result[$eid]['location_start']['address_line_1'] = $location_start[0]['address_line1'];
      $result[$eid]['location_start']['address_line_2'] = $location_start[0]['address_line2'];
      $result[$eid]['location_start']['city'] = $location_start[0]['locality'];
      $result[$eid]['location_start']['state'] = $location_start[0]['administrative_area'];
      $result[$eid]['location_start']['postal_code'] = $location_start[0]['postal_code'];
      $result[$eid]['location_start']['country'] = $location_start[0]['country_code'] ?? '';

      $location_end = $node->get('field_event_location_end')->getValue();
      $result[$eid]['location_end']['address_line_1'] = $location_end[0]['address_line1'];
      $result[$eid]['location_end']['address_line_2'] = $location_end[0]['address_line2'];
      $result[$eid]['location_end']['city'] = $location_end[0]['locality'];
      $result[$eid]['location_end']['state'] = $location_end[0]['administrative_area'];
      $result[$eid]['location_end']['postal_code'] = $location_end[0]['postal_code'];
      $result[$eid]['location_end']['country'] = $location_end[0]['country_code'] ?? '';

      $result[$eid]['mileage'] = $node->get('field_event_mileage')->value ?? '';
      $result[$eid]['points'] = $node->get('field_event_points')->value ?? '';

      $result[$eid]['lead_rc'] = $node->get('field_event_ref_team_lead_rc')->getValue() ?? '';
    }

    return new JsonResponse($result);

  }


  public function getEventData($nid) {
    return array("event_data");
  }

  public function getCheckinData($nid) {
    return array("checkin_data");
  }

  public function getRsvpData($nid) {
    return array("rsvp_data");
  }

  public function getCheckinsForEvent($nid) {
    return array('event_checkin_data');
  }

  public function getCheckinsForUser($uid) {
    return array("user_checkin_data");
  }

  public function getRsvpsForEvent($nid) {
    return array("event_rsvp_data");
  }

  public function getRsvpsForUser($uid) {
    return array("user_rsvp_data");
  }



}
