<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use App\Entity\Parametros;


/**
 *
 * @Route("/parametros")
 */

class ParametrosController extends Controller implements TokenAuthenticatedController
{

  public function __constructor() {

  }

  /**
   * @Route("/find", name="parametros_find")
   */
  public function find()
  {
    $helpers = $this->get("app.helpers");
    $em = $this->getDoctrine()->getManager();
      
    $l = $em->getRepository(Parametros::class)
	    ->findAll();    

    return $helpers->json($l[0]);
  }
  
  public function inicializarParametrosesdeJSON($json, $t){
    $params = json_decode($json);

    foreach ($params as $k=>$valor){
      switch (strtolower($k)) {
	case "id":
	  //No se actualiza este campo
	  break;
	case "fechanotificacion":
	  $t->setFechanotificacion($valor);
	  break;
	case "textonotificacion":
	  $t->setTextonotificacion($valor);
	  break;
	default:
	  throw new \Exception("El campo ".$k." no esta definido en institucion");
      }
    }
    
    return $t;
  }
  
  
  public function findbyId($em, $id) {
    $t = $em->getRepository(Parametros::class)
	    ->find($id);
    
    return $t;
  }

  
  /**
   * Registra parametros
   *
   * @Route("/registrarService", name="parametros_registrarservice")
   */
  public function registrarServiceAction(Request $request) {
    $em = $this->getDoctrine()->getManager();
    $json = $request->get('json', NULL);
    $hash = $request->get('token', NULL);
    
    $helpers = $this->get("app.helpers");
    
    $check = $helpers->authCheck($hash, true);
	    return new JsonResponse(array("status" => "Error",
	  "code" => "202",
	  "msg" => "El JSON es null"));
    if ($json == null) {
      return new JsonResponse(array("status" => "Error",
	  "code" => "202",
	  "msg" => "El JSON es null"));
    }

    $params = json_decode($json);

    if (isset($params->id)){
      if (strlen($params->id) > 0){
	$esnuevo = FALSE;
	$t = $this->findbyId($em, $params->id);
      }
    } else {
      return new JsonResponse(array("status" => "Error",
	  "code" => "500",
	  "msg" => "Sin ID.  ".$json.' Params:'.$params));
    }
    
    try {
      $t = $this->inicializarParametrosesdeJSON($json, $t);
    } catch (\Exception $ex) {
      return new JsonResponse(array("status" => "Error",
	  "code" => "202",
	  "msg" => "Error inicializando: " . $ex->getMessage()));
    }

    try {
      $em->persist($t);
      $em->flush();

      return new JsonResponse(array("status" => "Error",
	  "code" => "200",
	  "msg" => $esnuevo ? "Evaluacion registrada" : "Evaluacion actualizada"));
    } catch (\Exception $ex) {
      return new JsonResponse(array("status" => "Error",
	  "code" => "202",
	  "msg" => "Error creado evaluacion: " . $ex->getMessage()));
    }
  }
}
