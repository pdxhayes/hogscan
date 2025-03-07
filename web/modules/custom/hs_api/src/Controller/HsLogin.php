<?php

namespace Drupal\hs_api\Controller;

use Drupal\user\Controller\UserAuthenticationController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

 use Drupal\hs\Services\HsHelpers; // for HS Helpers

/**
 * Returns responses for Hogscan JSON routes.
 */
class HsLogin extends UserAuthenticationController
{

  /**
   * Logs in a user.
   *
   * @param Request $request
   *   The request.
   *
   * @return Response
   *   A response which contains the ID and CSRF token.
   */
  public function login(Request $request)
  {
    $response_data = parent::login($request);

    // We need to fetch the uid and load the user.
    $content = $response_data->getContent();
    $decoded_data = $this->serializer->decode($content, 'json');
    $uid = $decoded_data['current_user']['uid'];
    $csrf_token = $decoded_data['csrf_token'];
    $logout_token = $decoded_data['logout_token'];


    // Fetching the custom data.
    $helper = new HsHelpers;
    $decoded_data = $helper->getUserData($uid);

    // set the tokens
    $decoded_data['csrf_token'] = $csrf_token;
    $decoded_data['logout_token'] = $logout_token;


    $encoded_custom_data = $this->serializer->encode($decoded_data, 'json');
    $response_data->setContent($encoded_custom_data);
    return $response_data;
  }


}
