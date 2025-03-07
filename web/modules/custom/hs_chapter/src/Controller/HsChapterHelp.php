<?php

namespace Drupal\hs_chapter\Controller;

use Drupal\Core\Controller\ControllerBase;


/**
*   HOG[SCAN] Help.
*
*/
class HsChapterHelp {
  
  public function help_menu() {
    $build = [
      '#markup' => 'Help main menu.',
    ];
    return $build;
  }
  
  
  
  
}