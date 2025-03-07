<?php

namespace Drupal\hs_chapter\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\hs\Controller\HsBaseController;


/**
* Parent class for HOG[SCAN].
*
*/
class HsChapterController {

  public function config_welcome_email() {
    $build = [
      '#markup' => 'configure welcome email',
    ];
    return $build;
  }

  public function chapter_dashboard() {
    $current_members = HsBaseController::getCurrentMembers();
    $expired_members = HsBaseController::getExpiredMembers();
    $blocked_members = HsBaseController::getBlockedMembers();
    $current_count = count($current_members);
    $expired_count = count($expired_members);
    $blocked_count = count($blocked_members);

    $markup = "<p>Current Member Count: $current_count<br>";
    $markup .= "Expired Member Count: $expired_count</br>";
    $markup .= "Blocked Member Count: $blocked_count</p>";

    $build = [
      '#markup' => $markup,
    ];
    return $build;
  }

  public function chapter_content() {
    $build = [
      '#markup' => 'Chapter Content Menu',
    ];
    return $build;
  }

  public function chapter_members() {
    $build = [
      '#markup' => 'Chapter Member Menu',
    ];
    return $build;
  }

  public function chapter_config() {
    $build = [
      '#markup' => 'Chapter Congiguration Menu',
    ];
    return $build;
  }

  public function logo_builder() {
    $build = [
      '#markup' => 'Chapter Logo Builder',
    ];
    return $build;
  }

  public function chapter_reports() {
    $build = [
      '#markup' => 'Chapter Reports Menu',
    ];
    return $build;
  }

  public function chapter_utilities() {
    $build = [
      '#markup' => 'Chapter Utilities Menu',
    ];
    return $build;
  }

  public function import_csv() {
    $build = [
      '#markup' => 'import csv',
    ];
    return $build;
  }

  public function export_csv() {
    $build = [
      '#markup' => 'export csv',
    ];
    return $build;
  }

  public function clear_server_cache() {
    $build = [
      '#markup' => 'Flush cache.',
    ];
    return $build;
  }

  public function cpanel() {
    $build = [
      '#markup' => 'Open cpanel in a new tab.',
    ];
    return $build;
  }

  public function webmail() {
    $build = [
      '#markup' => 'Open webmail in a new tab.',
    ];
    return $build;
  }



}
