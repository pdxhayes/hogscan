<?php

namespace Drupal\fi_layerslider\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\fi_layerslider\Entity\Slider;

/**
 * Provides a 'LayerSlider' Block
 *
 * @Block(
 *   id = "fi_layerslider",
 *   admin_label = @Translation("Fi LayerSlider"),
 *   category = @Translation("Fi LayerSlider"),
 *   deriver = "Drupal\fi_layerslider\Plugin\Derivative\FiLayerSliderBlock",
 * )
 */
class FiLayerSliderBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $slider_id = $this->getDerivativeId();
    $slider = $slider = Slider::load($slider_id);
    $html_id = \Drupal\Component\Utility\Html::getUniqueId('fi_layerslider_' . REQUEST_TIME);
    $render = [
      '#theme' => 'fi_layerslider_slider',
      '#slider' => $slider,
      '#html_id' => $html_id,
      '#cache' => [
        'max-age' => 0,
      ],
      '#contextual_links' => [
        'fi_layerslider' => [
          'route_parameters' => ['fi_slider' => $slider_id]
        ],
      ]
    ];
	$render['#attached']['library'][] = 'fi_layerslider/frontend';
	$render['#attached']['drupalSettings'] = ['fi_layerslider_settings' => [$html_id => $slider->getSettings()],];

    return $render;
  }

}