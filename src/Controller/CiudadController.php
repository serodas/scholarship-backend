<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use App\Entity\Ciudad;
/**
 *
 * @Route("/ciudad")
 */

class CiudadController extends Controller implements TokenAuthenticatedController
{
    /**
     * @Route("/findall", name="ciudad_findall")
     */
  public function findall()
  {      
    $helpers = $this->get("app.helpers");
    $em = $this->getDoctrine()->getManager();
      
    $l = $em->getRepository(Ciudad::class)
	    ->findAll();    

    return $helpers->json($l);
  }
  
}
