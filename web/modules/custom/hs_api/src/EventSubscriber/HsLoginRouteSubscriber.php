<?php

namespace Drupal\hs_api\EventSubscriber;

use Drupal\Core\Routing\RouteSubscriberBase;
use Symfony\Component\Routing\RouteCollection;

/**
 * Hogscan JSON route subscriber.
 */
class HsLoginRouteSubscriber extends RouteSubscriberBase {

  /**
   * {@inheritdoc}
   */
  public function alterRoutes(RouteCollection $collection) {
    $route_login = $collection->get('user.login.http');
    $route_login->setDefaults([
      '_controller' => '\Drupal\hs_api\Controller\HsLogin::login',
    ]);
  }
}
