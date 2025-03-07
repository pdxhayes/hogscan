<?php

namespace Drupal\hs\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;

/**
 *   Ajax functions to pre-validate registration and user edit forms.
 *
 */
class HsAjaxController extends HsBaseController {

  /**
   *  Used by the user_register form as an ajax call to see if the hogid
   * already exists in the system.
   *
   * @return \Symfony\Component\HttpFoundation\JsonResponse
   */
  public function unique_hogid(): JsonResponse {

    $result = [];

    if ($user = parent::loadUserByHogId($_GET['hogid'])) {
      $result['uid'] = $user->id();
    }

    return new JsonResponse($result);
  }


  /**
   * Used by the user_register form as an ajax call to see if the email address
   * already exists in the system.
   *
   * @return \Symfony\Component\HttpFoundation\JsonResponse
   */
  public function unique_email(): JsonResponse {

    $result['email'] = $_GET['email'];
    if ($user = user_load_by_mail($_GET['email'])) {
      $result['uid'] = $user->id();
      $result['username'] = $user->getDisplayName();
    }

    return new JsonResponse($result);
  }

}
