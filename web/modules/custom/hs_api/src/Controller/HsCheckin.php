<?php

namespace Drupal\hs_api\Controller;

// For creating checkin and rsvp nodes
use Drupal\node\Entity\Node;

// For user stuff...
use Drupal\user\Entity\User;

// For JSON output
use Symfony\Component\HttpFoundation\JsonResponse;

/**
* User API class for HOG[SCAN].
*
*/
class HsCheckin extends HsApi {
  
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
  public function checkin_crud() {
    return new JsonResponse($this->crudHandler());
  }
  
  /**
  * Redirect requests by request method
  *
  * @return array
  *   example data 
  */
  private function crudHandler() {
    
    // this function supports multiple request methods.
    // route the request to the appropriate handler.
    switch($_SERVER['REQUEST_METHOD']) {
      case "POST":
        return $this->postRequest();
        break;
      case "GET":
        return $this->getRequest();
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
  * POST method requests create examples.
  * 
  * @return array
  *   new example record data
  */
  private function postRequest() {
  
    // get the input json if any.
    $payload = json_decode(file_get_contents('php://input'), true);
    
    // prepare a result
    $result = array();
    $result['method'] = $_SERVER['REQUEST_METHOD'];
    $result['function'] = 'examplePostRequest';
    $result['payload'] = $payload;
  
    // return the result
    return $result;
    
  }
  
  /**
  * GET method requests retrieve examples
  *
  * @return array
  *   requested example record data
  */
  private function getRequest() {
  
    // get the input data if any.
    $payload = $_GET;
    
    // prepare a result
    $result = array();
    $result['method'] = $_SERVER['REQUEST_METHOD'];
    $result['function'] = 'exampleGetRequest';
    $result['payload'] = $payload;
    $result['test'] = $this->escApiTestFunction();
    
    // return the result
    return $result;
  
  }
  
  /**
  * PATCH method requests update examples
  *
  * @return array
  *   updated example record data
  */
  private function patchRequest() {
    
    // get the input json if any.
    $payload = json_decode(file_get_contents('php://input'), true);
    
    // prepare a result
    $result = array();
    $result['method'] = $_SERVER['REQUEST_METHOD'];
    $result['function'] = 'examplePatchRequest';
    $result['payload'] = $payload;
    
    // return the result
    return $result;
    
  }
  
  /**
  * DELETE method requests delete examples
  *
  * @return array
  *   deleted example nid
  */
  private function deleteRequest() {
    
    // get the input json if any.
    $payload = json_decode(file_get_contents('php://input'), true);
    
    // prepare a result
    $result = array();
    $result['method'] = $_SERVER['REQUEST_METHOD'];
    $result['function'] = 'exampleDeleteRequest';
    $result['payload'] = $payload;
    
    // return the result
    return $result;
    
  }
  
  
}