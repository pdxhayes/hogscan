<?php

/**
 * @file
 * Contains \Drupal\fi_layerslider\Plugin\Derivative\FiLayerSliderBlock.
 */

namespace Drupal\fi_layerslider\Plugin\Derivative;

use Drupal\Component\Plugin\Derivative\DeriverBase;

/**
 * Provides blocks which belong to MD Slider.
 */
class FiLayerSliderBlock extends DeriverBase {

  /**
   * {@inheritdoc}
   */
  public function getDerivativeDefinitions($base_plugin_definition) {
    $select = \Drupal::service('database')->select('fi_slider', 'slider');
    $select->fields('slider');
    $sliders = $select->execute()->fetchAll();

    foreach ($sliders as $slide) {
      $this->derivatives[$slide->id] = $base_plugin_definition;
      $this->derivatives[$slide->id]['admin_label'] = $slide->name;
    }

    return $this->derivatives;
  }
}