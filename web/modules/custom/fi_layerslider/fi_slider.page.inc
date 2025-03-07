<?php

/**
 * @file
 * Contains fi_slider.page.inc..
 *
 * Page callback for Slider entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Slider templates.
 *
 * Default template: fi_slider.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_fi_slider(array &$variables) {
  // Fetch Slider Entity Object.
  $fi_slider = $variables['elements']['#fi_slider'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
