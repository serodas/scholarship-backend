<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use App\Entity\Requerimiento;

/**
 *
 * @Route("/requerimiento")
 */

class RequerimientoController extends Controller implements TokenAuthenticatedController
{
    /**
     * @Route("/findall", name="requerimiento_findall")
     */
  public function findall()
  {
    $helpers = $this->get("app.helpers");    
    $em = $this->getDoctrine()->getManager();
      
    $l = $em->getRepository(Requerimiento::class)
      ->findBy(array(), array('orden' => 'ASC'));
      
    return $helpers->json($l);
  }
}
