<?php

/**
 * @file
 * Contains \Drupal\fi_layerslider\SliderInterface.
 */

namespace Drupal\fi_layerslider;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Slider entities.
 *
 * @ingroup fi_layerslider
 */
interface SliderInterface extends ContentEntityInterface, EntityChangedInterface, EntityOwnerInterface {
  // Add get/set methods for your configuration properties here.

}
