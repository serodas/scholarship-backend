<?php

namespace App\Service;

class Helpers {
  public $jwt_auth;
  
  public function __construct($jwt_auth) {
    $this->jwt_auth = $jwt_auth;
  }

  public function authCheck($hash, $getIdentity = false) {
    $jwt_auth = $this->jwt_auth;
    
    $auth = false;
    if ($hash != null){
      if ($getIdentity == false){
	$check_token = $jwt_auth->checkToken($hash);
	if ($check_token == true){
	  $auth = true;
	}
      }
      else{
	$check_token = $jwt_auth->checkToken($hash, true);
	if (is_object($check_token) === true){
	  $auth = True;
	}
      }
    }
    return $auth;
  }

  public function json($data) {

    $normalizers = array(new \Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer());
    $encoders = array("json" => new \Symfony\Component\Serializer\Encoder\JsonEncoder());

    $serializer = new \Symfony\Component\Serializer\Serializer($normalizers, $encoders);
    $json = $serializer->serialize($data, 'json');

    $response = new \Symfony\Component\HttpFoundation\Response();
    $response->setContent($json);

    // $response->headers->set('Access-Control-Allow-Headers', 'origin, content-type, accept');
    // $response->headers->set('Access-Control-Allow-Origin', '*');
    // $response->headers->set('Access-Control-Allow-Methods', 'POST, GET, PUT, DELETE, PATCH, OPTIONS');

    // $response->headers->set("Content-Type", "application/json");

    return $response;
  }
}
