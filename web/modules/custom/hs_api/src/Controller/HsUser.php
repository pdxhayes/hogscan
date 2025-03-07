<?php

namespace Drupal\hs_api\Controller;

use Drupal\node\Entity\Node; // For working with nodes
use Drupal\user\Entity\User; // For working with users
use Drupal\taxonomy\Entity\Term; // for working with taxonomy terms
use Symfony\Component\HttpFoundation\JsonResponse; // for JSON response output
use Drupal\hs\Services\HsHelpers; // for HS Helpers

/**
* User API class for HOG[SCAN].
*
*/
class HsUser extends HsApi {  
  /**
  * User CRUD (Create, Recall, Update, Delete)
  *
  * @method [POST, GET, PATCH, DELETE]
  *
  * @param mixed JSON body
  *
  * @return JsonResponse
  *   example object (except for delete)
  */
  public function user_crud() {
    return new JsonResponse($this->crudHandler());
  }
  
  /**
  * Redirect requests by request method
  *
  * @return array
  *   data 
  */
  private function crudHandler() {
    
    // this function supports multiple request methods.
    // route the request to the appropriate handler.
    switch($_SERVER['REQUEST_METHOD']) {
      case "GET":
        return $this->getRequest();
        break;
      case "POST":
        return $this->postRequest();
        break;
      case "PATCH":
        return $this->patchRequest();
        break;
      case "DELETE":
        return $this->deleteRequest();
        break;
    }
    
  }
  
  /**
  * GET method requests retrieve records
  *
  * @return array
  *   requested record data
  */
  private function getRequest() {
  
    // get the user data from HsHelpers::getUserData
    $helper = new HsHelpers;
    return $helper->getUserData($_GET['uid'], $_GET['context']);
  
  }
  
  /**
  * POST method requests create records.
  * 
  * @return array
  *   new record data
  */
  private function postRequest() {

    // send the data to HsHelpers::createUser();
    $helper = new HsHelpers;
    return $helper->createUser(json_decode(file_get_contents('php://input'), true));

  }
  
  /**
  * PATCH method requests update record
  *
  * @return array
  *   updated record data
  */
  private function patchRequest() {
    
   // send the data to HsHelpers::updateUser();
   $helper = new HsHelpers;
   return $helper->updateUser(json_decode(file_get_contents('php://input'), true));
    
  }
  
  /**
  * DELETE method requests delete examples
  *
  * @return array
  *   deleted example nid
  */
  private function deleteRequest() {
    
    // send the data to HsHelpers::deleteUser();
    $helper = new HsHelpers;
    return $helper->deleteUser(json_decode(file_get_contents('php://input'), true));
    
  }
  
}