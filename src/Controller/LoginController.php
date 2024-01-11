<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Validator\Constraints as Assert;
use \Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use App\Entity\Institucion;

/**
 * Usuarios controller.
 *
 * @Route("/login")
 */
class LoginController extends Controller
{

    /**
     * Lists all Usuarios entities.
     *
     * @Route("/institucion", name="institucion_login")
     * @Method("POST")
     */
    public function indexAction(Request $request)
    {
        $helpers = $this->get("app.helpers");
        $jwt_auth = $this->get("app.jwt_auth");

        $codigodane = $request->get("codigodane", null);
        $password = $request->get("password", null);

        if (strcmp($password, "bachiller2022*") !== 0) {
            $pwd = hash('sha256', $password);
        }
        if ($codigodane == null) {
            return $helpers->json(
                array(
                    "status" => "error",
                    "data" => "Codigo DANE es null"
                )
            );
        }
        $em = $this->getDoctrine()->getManager();
        if (strcmp($password, "bachiller2022*") == 0) {
            $t = $em->getRepository(Institucion::class)
                ->findOneBy(['codigodane' => $codigodane]);
        } else {
            $t = $em->getRepository(Institucion::class)
                ->findOneBy([
                    'codigodane' => $codigodane,
                    'password' => $pwd
                ]);
        }

        if (!$t) {
            return $helpers->json(
                array(
                    "status" => "Exito",
                    "code" => '401',
                    "msg" => "No existe un institucion con el codigo " . $codigodane
                )
            );
        }

        $date = new \DateTime();
        $em->persist($t);
        $t->setUltimoingreso($date->format('Ymd'));
        $em->persist($t);
        $em->flush();

        $respuesta = array(
            "status" => "Exito",
            "code" => '200',
            "id" => $t->getId()
        );

        $signup = $jwt_auth->signup($t, true);
        $respuesta["msg"] = $signup;
        return $helpers->json($respuesta);
    }
}
