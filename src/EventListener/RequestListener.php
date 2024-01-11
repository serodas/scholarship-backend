<?php

namespace App\EventListener;

use Symfony\Component\HttpFoundation\Response;
use \Symfony\Component\HttpKernel\Event\GetResponseEvent;

class RequestListener {
  public $helpers;
  
  function __construct($helper) {
    $this->helpers = $helper;
  }

  public function onKernelRequest(GetResponseEvent $event) {
    //echo ($event->getRequest()->getMethod().'<br>');
//    echo 'Json en requestListener:'.$event->getRequest()->request->get('json');
//    die();
    //return;
    $partes = explode("/", $event->getRequest()->getPathInfo());
    
    $token = $partes[count($partes) - 1];

    if ($event->getRequest()->getMethod() == 'GET'){
//      var_dump($partes);
//    die();
      switch ($partes[1]){
	case "tbestado":
	  return;
	
	default:
	  if ($token == ''){
	    //$event->setResponse(new Response("Token no definido.", 401));
	  }
	  else{
	    $authCheck = $this->helpers->authCheck($token);

	    if ($authCheck === True) {

	    }
	    else{
	      //$event->setResponse(new Response("Token no válido. ", 401));
	      //return;
	    }	
	  }
	  
      }
    }
    else{
      switch ($partes[1]){

	default:
	   $token = $event->getRequest()->request->get('token');
      }
    }
    
    $uri = $event->getRequest()->getUri();
    //var_dump($partes);

    $parte = substr($uri, strlen($uri) - 7, strlen($uri));
    if ($parte == '/login/'){
      // La ruta de login no valida token
    }
    else{
//      echo 'Aqui voy.'.$token;
//      die();
      if ($token == ''){
	//$event->setResponse(new Response("Token no definido.", 401));
	return;
      }
      else{
	$authCheck = $this->helpers->authCheck($token);

	if ($authCheck === True) {

	}
	else{
	  //$event->setResponse(new Response("Token no valido. ", 401));
	}	
      }
    }
  }
}
