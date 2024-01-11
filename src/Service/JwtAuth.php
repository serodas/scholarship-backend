<?php

namespace App\Service;

use \Firebase\JWT\JWT;

class JwtAuth
{
  public $manager;
  public $key;

  public function __construct($manager)
  {
    $this->maneger = $manager;
    $this->key = "clave-secreta";
  }

  public function signup($institucion, $getHash = NULL)
  {
    $token = array(
      "id" => $institucion->getId(),
      "iat" => time(),
      "exp" => time() + (7 * 24 * 60 * 60),
    );

    $jwt = JWT::encode($token, $this->key, 'HS256');
    $decoded = JWT::decode($jwt, $this->key, array('HS256'));

    if ($getHash != null) {
      return $jwt;
    } else {
      return $decoded;
    }
  }

  public function checkToken($jwt, $getIdentity = false)
  {
    $auth = false;

    try {
      $decode = JWT::decode($jwt, $this->key, array('HS256'));
    } catch (\UnexpectedValueException $ex) {
      $auth = false;
    } catch (\DomainException $ex) {
      $auth = false;
    }

    if (isset($decode->id)) {
      $auth = true;
    } else {
      $auth = false;
    }

    if ($getIdentity == true) {
      return $decode;
    } else {
      return $auth;
    }
  }
}
