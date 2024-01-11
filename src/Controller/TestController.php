<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 *
 * @Route("/test")
 */

class TestController extends Controller implements TokenAuthenticatedController
{
  /**
   * @Route("/", name="test")
   */
  public function test()
  {      
    $helpers = $this->get("app.helpers");
    return $helpers->json("test");
  }
  
}
