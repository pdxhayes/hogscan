<?php

namespace Drupal\hs_admin\Controller;

/**
 *
 */
class HsAdminController {

  /**
   * @return string[]
   */
  public function main_menu() {
    $build = [
      '#markup' => 'HOG[SCAN] Administration Main Menu',
    ];
    return $build;
  }

  /**
   * @return string[]
   */
  public function utilities_menu() {
    $build = [
      '#markup' => 'HOG[SCAN] Administration Utilities Menu',
    ];
    return $build;
  }



}
