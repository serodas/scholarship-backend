<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use App\Entity\Becado;
use App\Entity\Institucion;
use App\Entity\Formatocorreos;
use App\Entity\Sede;

use App\Utils\Parametros;

/**
 *
 * @Route("/institucion")
 */

class InstitucionController extends Controller implements TokenAuthenticatedController
{

    public $logoComfamiliar;

    public $imagenInvitacionpostulado;

    public $imagenInvitacionrector;

    public $contador;

    public function __constructor()
    {
    }

    function inicializarImages()
    {
        $this->contador = 0;
        $this->logoComfamiliar = "<img alt='Embedded Image' height='118' width='460' src='https://wssigec.comfamiliar.com/resources/imagealojamiento/logo_comfamiliar.png' />";

        $this->imagenInvitacionpostulado = "<img alt='Embedded Image' height='1200' width='800' src='https://wssigec.comfamiliar.com/resources/imagebachiller/invitacion_estudiante.jpg' />";
        $this->imagenInvitacionrector = "<img alt='Embedded Image' height='1200' width='800' src='https://wssigec.comfamiliar.com/resources/imagebachiller/invitacion_institucion.jpg' />";
    }

    public function validarCorreos()
    {
    }
    /**
     * @Route("/findall", name="institucion_findall")
     */
    public function findall()
    {
        $helpers = $this->get("app.helpers");
        $em = $this->getDoctrine()->getManager();

        $l = $em->getRepository(Institucion::class)
            ->findAll();

        return $helpers->json($l);
    }

    /**
     * @Route("/findbyid/{id}/{token}", name="institucion_findbyid")
     */
    public function findByIdAction($id, $token)
    {
        $helpers = $this->get("app.helpers");

        $check = $helpers->authCheck($token, true);

        if ($check == FALSE) {
            return new JsonResponse(array(
                "status" => "Error",
                "code" => "202",
                "msg" => "Token no es valido"
            ));
        }

        $em = $this->getDoctrine()->getManager();

        $t = $em->getRepository(Institucion::class)
            ->find($id);

        if (is_object($t)) {
            $t->setSedes([]);
            $institucionessedes = $em->getRepository(Institucion::class)
                ->findBy(['idprincipal' => $t->getId()]);
            $sedes = [];
            foreach ($institucionessedes as $s) {
                $sede = new Sede();
                $sede->setId($s->getId());
                $sede->setNombreinstitucion($s->getNombreinstitucion());
                $sede->setNucleo($s->getNucleo());

                array_push($sedes, $sede);
            }
            $t->setSedes($sedes);
        }

        return $helpers->json($t);
    }

    public function inicializarEntidadDesdeJSON($json, $t)
    {
        $params = json_decode($json);

        foreach ($params as $k => $valor) {
            switch (strtolower($k)) {
                case "id":
                    //No se actualiza este campo
                    break;
                case "nucleo":
                    $t->setNucleo($valor);
                    break;
                case "jerarquia":
                    $t->setJerarquia($valor);
                    break;
                case "nombreinstitucion":
                    $t->setNombreinstitucion($valor);
                    break;
                case "comuna":
                    $t->setComuna($valor);
                    break;
                case "publico":
                    $t->setPublico($valor);
                    break;
                case "direccion":
                    $t->setDireccion($valor);
                    break;
                case "telfijo";
                    $t->setTelfijo($valor);
                    break;
                case "movil":
                    $t->setmovil($valor);
                    break;
                case "cargo":
                    $t->setCargo($valor);
                    break;
                case "nombreresponsable":
                    $t->setNombreresponsable($valor);
                    break;
                case "correoelectronico":
                    $t->setCorreoelectronico($valor);
                    break;
                case "zona":
                    $t->setZona($valor);
                    break;
                case "codigodane":
                    $t->setCodigodane($valor);
                    break;
                case "password":
                    $t->setPassword($valor);
                    break;
                case "fechanotificacion": //No se actualiza desde registro
                    break;
                case "ciudad":
                    break;
                case "idprincipal":
                    $t->setIdprincipal($valor);
                    break;
                case "sedes":
                    break;
                case "ultimoingreso":
                    break;
                default:
                    throw new \Exception("El campo " . $k . " no esta definido en institucion");
            }
        }

        return $t;
    }

    public function findbyId($em, $id)
    {
        $t = $em->getRepository(Institucion::class)
            ->find($id);

        return $t;
    }

    /**
     * Registra institucion
     *
     * @Route("/registrarService", name="institucion_registrarservice")
     */
    public function registrarServiceAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $json = $request->get('json', NULL);
        $hash = $request->get('token', NULL);

        $helpers = $this->get("app.helpers");

        $check = $helpers->authCheck($hash, true);

        if ($json == null) {
            return new JsonResponse(array(
                "status" => "Error",
                "code" => "202",
                "msg" => "El JSON es null"
            ));
        }

        $params = json_decode($json);

        if (isset($params->id)) {
            if (strlen($params->id) > 0) {
                $esnuevo = FALSE;
                $t = $this->findbyId($em, $params->id);
            } else {
                $esnuevo = TRUE;
                $t = new Institucion();
            }
        } else {
            $esnuevo = TRUE;
            $t = new Institucion();
        }

        try {
            $t = $this->inicializarEntidadDesdeJSON($json, $t);
        } catch (\Exception $ex) {
            return new JsonResponse(array(
                "status" => "Error",
                "code" => "202",
                "msg" => "Error inicializando: " . $ex->getMessage()
            ));
        }

        try {
            $em->persist($t);
            $em->flush();

            return new JsonResponse(array(
                "status" => "Error",
                "code" => "200",
                "msg" => $esnuevo ? "Evaluacion registrada" : "Evaluacion actualizada"
            ));
        } catch (\Exception $ex) {
            return new JsonResponse(array(
                "status" => "Error",
                "code" => "202",
                "msg" => "Error creado evaluacion: " . $ex->getMessage()
            ));
        }
    }

    /**
     * Registra institucion
     *
     * @Route("/generarpasswordService", name="institucion_genepasservice")
     */
    public function generarpasswordAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $id = $request->get('id');
        //    $hash = $request->get('token');

        $helpers = $this->get("app.helpers");

        //    $check = $helpers->authCheck($hash, true);

        if ($id == null) {
            return $helpers->json(array(
                "status" => "Error",
                "code" => "202",
                "msg" => "El Id es null"
            ));
        }

        $t = $this->findbyId($em, $id);
        if (!$t) {
            return $helpers->json(array(
                "status" => "Error",
                "code" => "202",
                "msg" => "El Id es null"
            ));
        }
        $t->setPassword($this->generarPassword()["cifrado"]);

        try {
            $em->persist($t);
            $em->flush();

            return $helpers->json(array(
                "status" => "Ok",
                "code" => "200",
                "msg" => "Actualización de password exitosa: "
            ));
        } catch (\Exception $ex) {
            return $helpers->json(array(
                "status" => "Error",
                "code" => "202",
                "msg" => "Error actualizando el password de la entidad password: " . $ex->getMessage()
            ));
        }
    }


    public function validarDatos($institucion)
    {
    }

    /**
     * Registra un proceso judicial
     *
     * @Route("/registrareditService", name="institucion_editarservice")
     */
    public function registrareditServiceAction(Request $request)
    {
        $helpers = $this->get("app.helpers");

        $em = $this->getDoctrine()->getManager();
        $json = $request->get('json');

        if ($json == null) {
            return $helpers->json(array(
                "status" => "Error",
                "code" => "200",
                "msg" => "El json es nulo."
            ));
        }

        $params = json_decode($json);

        if (!isset($params->id)) {
            return $helpers->json(array(
                "status" => "Error",
                "code" => "202",
                "msg" => "No existe ID en params "
            ));
        }

        $institucion = $this->findById($em, $params->id);

        if (!$institucion) {
            return new JsonResponse(array(
                "status" => "Error",
                "code" => "202",
                "msg" => "Institucion no existe"
            ));
        }

        try {
            $institucion = $this->inicializarEntidadDesdeJSON($json, $institucion);
        } catch (\Exception $exc) {
            return new JsonResponse(array(
                "status" => "Error",
                "code" => "202",
                "msg" => $exc->getMessage()
            ));
        }

        try {
            $this->validarDatos($institucion);
        } catch (\Exception $exc) {
            return new JsonResponse(array(
                "status" => "Error",
                "code" => "202",
                "msg" => $exc->getMessage()
            ));
        }

        try {
            $em->persist($institucion);
            $em->flush();

            return $helpers->json(array(
                "status" => "Error",
                "code" => "200",
                "msg" => "Registro exitoso"
            ));
        } catch (\Exception $ex) {
            return $helpers->json(array(
                "status" => "Error",
                "code" => "200",
                "msg" => "Error actualizando el proceso judicial. " . $ex->getMessage()
            ));
        }
    }

    public function generarPassword()
    {
        $str = "abcdefghijklmnopqrstuvwxyz1234567890";
        $password = "";
        //Reconstruimos la contraseña segun la longitud que se quiera
        $pwd = [];
        for ($i = 0; $i < 6; $i++) {
            //obtenemos un caracter aleatorio escogido de la cadena de caracteres
            $password .= substr($str, rand(0, 35), 1);
        }
        $pas["sincifrar"] = $password;
        $pas["cifrado"] = hash('sha256', $password);

        return $pas;
    }

    /**
     * @Route("/asignarcontrasenas", name="institucion_asignarcontrasenas")
     */
    public function asignarContrasenas()
    {
        $helpers = $this->get("app.helpers");
        $em = $this->getDoctrine()->getManager();

        $l = $em->getRepository(Institucion::class)
            ->findAll();

        foreach ($l as $t) {
            $t->setPassword($this->generarPassword()["cifrado"]);

            $em->persist($t);
        }
        $em->flush();

        return $helpers->json(array(
            "status" => "Ok",
            "code" => "200",
            "msg" => "Creacion correcta de password"
        ));
    }

    public function enviarNotificacion($to, $texto, $asunto)
    {
        if (strlen($texto) < 4) {
            return null;
        }
        try {
            $transport = (new \Swift_SmtpTransport('smtp.sendgrid.net', 587))
                ->setUsername('apikey')
                ->setPassword('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');

            $mailer = new \Swift_Mailer($transport);

            $attachment =  \Swift_Attachment::fromPath('poster.png');
            $attachment2 =  \Swift_Attachment::fromPath('reglamento.pdf');
            $attachment3 =  \Swift_Attachment::fromPath('ficha_postulacion_bachiller_comfamiliar_2022.pdf');

            $message = (new \Swift_Message($asunto))
                ->setFrom(['premiobachiller@comfamiliar.com' => 'Subdirección Servicios Comfamiliar Risaralda'])
                ->setTo([$to => $to])
                ->setBody($texto)
                //   ->setReplyTo("premiobachiller@comfamiliar.com")
                ->setContentType("text/html;charset=UTF-8")
                ->attach($attachment)
                ->attach($attachment2)
                ->attach($attachment3);
          
            $headers = $message->getHeaders();
            // Con acuse de recibo
            $headers->addTextHeader('Disposition-Notification-to', "premiobachiller@comfamiliar.com");

            $result = $mailer->send($message);

            $this->contador++;
            return $result;
        } catch (\Throwable $ex) {
        }
    }

    public function enviarCorreoConvocatoriaInstitucion($em, $t, $f, $prueba)
    {
        $pas = $this->generarPassword();

        $t->setPassword($pas["cifrado"]);
        $date = new \DateTime();

        $texto = $f->getHead() .
            $f->getBodybefore() .
            "<img data-darkreader-inline-border-bottom=''"
            . " data-darkreader-inline-border-left=''"
            . " data-darkreader-inline-border-right=''"
            . " data-darkreader-inline-border-top=''"
            . " data-file-id='543769' src='https://wssigec.comfamiliar.com/resources/imagealojamiento/logo_comfamiliar.png' "
            . " style='border: 0px;width: 300px;height: 56px;margin: 0px;--darkreader-inline-border-top: initial;--darkreader-inline-border-right: initial;--darkreader-inline-border-bottom: initial;--darkreader-inline-border-left: initial;outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;' "
            . "width='100'><br /><br />" .
            "100-D-18852<br>" .
            "Respuesta a oficio número: Ninguno<br>" .
            "Pereira, 19 de octubre de 2022<br/><br/><br/>" .
            "Señor (a) <br>" .
            "{$t->getNombreresponsable()}<br>" .
            "<b>INSTITUCIÓN EDUCATIVA " . $t->getNombreinstitucion() . "</b><br />" .
            $t->getCiudad()->getNombre() . "<br />" .
            "<br />" .
            "<br /><br />" .
            "ASUNTO:    " . Parametros::ASUNTO_INVITACION_PREMIACION . "<br/>" .
            "<br />" .
            "<p>" .
            "La educación, como pilar fundamental de Comfamiliar Risaralda, se ve representada en
            desarrollo social y la posibilidad de nuevos escenarios para el departamento, es por esto, que
            la Caja de Compensación, resalta y premia el esfuerzo de los bachilleres del departamento de
            las Instituciones Educativas públicas y privadas, que se destacan por su formación integral,
            rendimiento académico, responsabilidad social y liderazgo en la comunidad estudiantil." .
            "</p>" .
            "<p>" .
            "Teniendo en cuenta estos planteamientos, Comfamiliar Risaralda, le invita para que la
            Institución Educativa que usted dirige, con el aval del Consejo Académico, postule al estudiante
            que por sus méritos consideran debe participar en el Premio al Bachiller Comfamiliar 2022;
            dicho premio corresponde a un valor de $ 35.103.600, que serán distribuidos en pagos anuales
            de $ 7.020.720, para que el bachiller ganador adelante una carrera reconocida por el ICFES, en
            cualquier ciudad del país, con una duración de hasta cinco años." .
            "</p>" .
            "<p>" .
            "Las postulaciones sólo se recibirán de forma digital, haciendo click <a href='https://bachiller.comfamiliar.com/#/" . $t->getCodigodane() . "/" . $pas["sincifrar"] . "'>AQUÍ</a>" .
            " o ingresando a la plataforma <a href='https://bachiller.comfamiliar.com/#/" . $t->getCodigodane() . "/" . $pas["sincifrar"] . "'>https://bachiller.comfamiliar.com</a> con el usuario <b>" . $t->getCodigodane() . "</b> y contraseña <b>" . $pas["sincifrar"] . "</b>,  registrando la postulación y adjuntando en formato PDF los siguientes documentos escaneados: " .
            "</p>" .
            "<ul>" .
            "<li>Certificado de Calificación de la Institución Educativa, de mínimo en Alto o Superior de acuerdo al Decreto 1290/2009 de Mineducación</li>" .
            "<li>Ficha de postulación al premio, firmada por el rector</li>" .
            "<li>Fotocopia del documento de identidad del estudiante a postular vigente</li>" .
            "<li>Acta de elección del postulado firmada por todos los miembros del Consejo Académico, donde conste el cumplimiento de los requisitos por parte del estudiante.</li>" .
            "<li>Las instituciones educativas que tengan sedes, deben anexar acta administrativa expedida por el ente de control que acredite su creación.</li>" .
            "</ul>" .
            "<p>" .
            "La plataforma para las postulaciones estará habilitada hasta el <strong>viernes 18 de noviembre</strong> y el reconocimiento se entregará de manera presencial el <strong>viernes 25 de noviembre de 2022 a las 2:00 p.m. de en el Hotel Movich - Salón Sapan.</strong>" .
            "</p>" .
            "<p>" .
            "Esta convocatoria se constituye en una valiosa oportunidad de acceso a la Educación Superior para los Bachilleres destacados del Departamento de Risaralda." .
            "</p>" .
            "<br />" .

            "Cordialmente, <br />" .
            "<br />" .
            "LUIS FERNANDO ACOSTA SANZ<br />" .
            "Director Administrativo<br /><br /><br />" .

            "Informes:<br />" .

            "<b>premiobachiller@comfamiliar.com - PBX 606 3135600 ext. 2210</b>" .
            "<br />" .

            $f->getBodyafter();

        if ($prueba) {
            $correo = 'srodas@comfamiliar.com';
        } else {
            $correo = $t->getCorreoelectronico();
        }
        $this->enviarNotificacion(
            $correo,
            $texto,
            Parametros::ASUNTO_INVITACION_PREMIACION
        );

        if ($prueba == false) {
            $t->setFechanotificacion($date->format('Ymd'));
            $em->persist($t);
        }

        return true;
    }

    public function enviarCorreoRecordatorioInstitucion($em, $t, $f, $prueba)
    {
        $date = new \DateTime();

        // "<html><body>".
        $texto = $f->getHead() .
            $f->getBodybefore() .
            "<img data-darkreader-inline-border-bottom=''"
            . " data-darkreader-inline-border-left=''"
            . " data-darkreader-inline-border-right=''"
            . " data-darkreader-inline-border-top=''"
            . " data-file-id='543769' src='https://wssigec.comfamiliar.com/resources/imagealojamiento/logo_comfamiliar.png' "
            . " style='border: 0px;width: 300px;height: 56px;margin: 0px;--darkreader-inline-border-top: initial;--darkreader-inline-border-right: initial;--darkreader-inline-border-bottom: initial;--darkreader-inline-border-left: initial;outline: none;text-decoration: none;-ms-interpolation-mode: bicubic;' "
            . "width='100'><br /><br />" .
            "<br /><br />" .
            "Apreciado Rector," .
            "<br /><br />" .
            "De acuerdo a la comunicación enviada el pasado 26 de octubre, queremos recordarle la importancia de su participación  en la convocatoria del Premio al Bachiller Comfamiliar 2021, que busca reconocer el esfuerzo de los estudiantes de Grado 11 y Ciclo 6, de las Instituciones Educativas públicas y privadas del departamento de Risaralda, que se destacan por sus logros académicos, responsabilidad social y liderazgo en la comunidad estudiantil.<br /><br />" .
            "Tenga en cuenta que la Institución Educativa que usted dirige, con el aval del Consejo Académico, podrá postular al estudiante que por sus méritos consideran debe participar y estar opcionado a recibir este premio de $30´000.000, que serán distribuidos en pagos anuales de $ 6.000.000, para que el bachiller ganador adelante una carrera profesional universitaria. Las postulaciones se recibirán de forma digital hasta el viernes 13 de noviembre de 2021. Conozca más información ingresando al enlace  <a href='https://bachiller.comfamiliar.com'>https://bachiller.comfamiliar.com</a> <br /><br />" .
            "Esta convocatoria se constituye en una valiosa oportunidad de acceso a la Educación Superior para los Bachilleres destacados del Departamento de Risaralda. Cualquier inquietud al respecto por favor comuníquese a nuestro PBX: 3135600 Extensión 2183.<br /><br /><br />" .
            "<b>Viviana Guevara Gómez</b><br />" .
            "Comunicadora Corporativa<br />" .
            "Comfamiliar Risaralda<br /><br /><br />" .
            "Informes:<br />" .
            "<b>premiobachiller@comfamiliar.com - contáctenos a través del chat en línea - PBX: 3135600 Extensión 2183" .
            "<br />";

        $f->getBodyafter();

        if ($prueba) {
            $correo = 'jhenao@comfamiliar.com';
        } else {
            $correo = $t->getCorreoelectronico();
        }
        $this->enviarNotificacion(
            $correo,
            $texto,
            Parametros::ASUNTO
        );

        if ($prueba == false) {
            $t->setFechanotificacion($date->format('Ymd'));
            $em->persist($t);
        }

        return true;
    }

    /**
     * @Route("/relacioncorreoconvocatoria", name="relinstitucion_cormasconv")
     */
    public function relacionenviarcorreomasivoconvocatoria()
    {
        $helpers = $this->get("app.helpers");
        $em = $this->getDoctrine()->getManager();
        $mensaje = "De: premiobachiller@comfamiliar.com - (Subdireccion servicios) " .
            " Fecha: 08 de Noviembre de 2021" .
            " Para:";

        $l = $em->getRepository(Institucion::class)
            ->findBy(["jerarquia" => "IE"]);

        $destinatarios = array();

        foreach ($l as $t) {

            $mensaje .= 'Institucion: ' .  $t->getNombreinstitucion() . 'correo: ' . $t->getCorreoelectronico() . "  -  ";
        }

        return $helpers->json(array(
            "status" => "Ok",
            "code" => "200",
            "msg" => "Correos enviados " . $mensaje
        ));
    }

    /**
     * @Route("/enviarcorreomasivoconvocatoria", name="institucion_cormasconv")
     */
    public function enviarcorreomasivoconvocatoria()
    {
        $helpers = $this->get("app.helpers");
        $em = $this->getDoctrine()->getManager();
        $contador = 0;

        try {
            $formato = $em->getRepository(Formatocorreos::class)
                ->find(1);
        } catch (\Exception $ex) {
            return $helpers->json(array(
                "status" => "Ok",
                "code" => "500",
                "msg" => "Error consultando el formato de los correos. " . $ex->getMessage()
            ));
        }

        $l = $em->getRepository(Institucion::class)
            ->findBy(["jerarquia" => 'IE']);
        $errores = "x";
        foreach ($l as $t) {
            if (strlen($t->getCorreoelectronico()) > 0) {
                if (filter_var($t->getCorreoelectronico(), FILTER_VALIDATE_EMAIL)) {
                } else {
                    $errores .= "id: " . $t->getId() . "-" . $t->getCorreoelectronico() . "-<br />";
                    $contador++;
                }
            }
        }

        if ($contador > 0) {
            return $helpers->json(array(
                "status" => "Ok",
                "code" => "202",
                "msg" => "Existen $contador correos inválidos" . $errores
            ));
        }

        $contador = 0;
        $list = "";
        $errores = "";
        foreach ($l as $t) {
            if (strlen($t->getCorreoelectronico()) > 0) {
                try {
                    $this->enviarCorreoConvocatoriaInstitucion($em, $t, $formato, false);
                    $contador++;
                    $list .= $contador . "  Institucion: " . $t->getNombreinstitucion() . " -  Email: " . $t->getCorreoelectronico() . ".  <br>";
                } catch (\Exception $e) {
                    $errores .= $contador . "  Institucion: " . $t->getNombreinstitucion() . " -  Email: " . $t->getCorreoelectronico() . ".  <br>";
                }
            }
        }

        $em->flush();

        $resp = array(
            "subject" => Parametros::ASUNTO_INVITACION_PREMIACION,
            "list" => $list,
            "errores" => $errores,
            "from" => Parametros::CORREO_BACHILLER
        );

        return $helpers->json(array(
            "status" => "Ok",
            "code" => "200",
            "Correos enviados " . $contador,
            "msg" => $resp
        ));
    }

    /**
     * @Route("/enviarcorreomasivorecordatorio", name="institucion_masivo_recordatorio")
     */
    public function enviarcorreomasivorecordatorio()
    {
        $helpers = $this->get("app.helpers");
        $em = $this->getDoctrine()->getManager();
        $contador = 0;

        try {
            $formato = $em->getRepository(Formatocorreos::class)
                ->find(1);
        } catch (\Exception $ex) {
            return $helpers->json(array(
                "status" => "Ok",
                "code" => "500",
                "msg" => "Error consultando el formato de los correos. " . $ex->getMessage()
            ));
        }

        $l = $em->getRepository(Institucion::class)
            ->findAll();
        $errores = "x";
        foreach ($l as $t) {
            if (strlen($t->getCorreoelectronico()) > 0) {
                if (filter_var($t->getCorreoelectronico(), FILTER_VALIDATE_EMAIL)) {
                } else {
                    $errores .= "id: " . $t->getId() . "-" . $t->getCorreoelectronico() . "-<br />";
                    $contador++;
                }
            }
        }

        if ($contador > 0) {
            return $helpers->json(array(
                "status" => "Ok",
                "code" => "202",
                "msg" => "Existen $contador correos inválidos" . $errores
            ));
        }

        $contador = 0;
        $list = "";
        $errores = "";
        $prueba = false;
        foreach ($l as $t) {
            if (strlen($t->getCorreoelectronico()) > 0) {
                try {
                    $this->enviarCorreoRecordatorioInstitucion($em, $t, $formato, $prueba);
                    $contador++;
                    $list .= $contador . "  Institucion: " . $t->getNombreinstitucion() . " -  Email: " . $t->getCorreoelectronico() . ".  <br>";

                    //            if ($prueba) {
                    //              break;
                    //            }
                } catch (\Exception $e) {
                    $errores .= $contador . "  Institucion: " . $t->getNombreinstitucion() . " -  Email: " . $t->getCorreoelectronico() . ".  <br>";
                }
            }
        }

        $resp = array(
            "subject" => "Recordatorio 'Premio Bachiller COMFAMILIAR RISARALDA 2021'.  Enviados:" . $contador,
            "list" => $list,
            "errores" => $errores,
            "from" => Parametros::CORREO_BACHILLER
        );

        return $helpers->json(array(
            "status" => "Ok",
            "code" => "200",
            "Correos enviados " . $contador,
            "msg" => $resp
        ));
    }

    /**
     * @Route("/enviarcorreoconvocatoria/{id}", name="institucion_corconv")
     */
    public function enviarcorreoconvocatoria($id)
    {
        $helpers = $this->get("app.helpers");
        $em = $this->getDoctrine()->getManager();

        try {
            $formato = $em->getRepository(Formatocorreos::class)
                ->find(1);
            //      $formato = new Formatocorreos();
        } catch (\Exception $ex) {
            return $helpers->json(array(
                "status" => "Ok",
                "code" => "500",
                "msg" => "Error consultando el formato de los correos. " . $ex->getMessage()
            ));
        }

        $t = $em->getRepository(Institucion::class)
            ->find($id);

        $procesando = $this->enviarCorreoConvocatoriaInstitucion($em, $t, $formato, false);
        $em->flush();

        if ($procesando) {
            $msg = "Se envió (1) correo con éxito";
        } else {
            $msg = "Error enviando el correo";
        }

        return $helpers->json(array(
            "status" => "Ok",
            "code" => "200",
            "msg" => $msg
        ));
    }

    /**
     * @Route("/enviarcorreoconvocatoriadane/{codigodane}", name="institucion_corconvdanef")
     */
    public function enviarcorreoconvocatoriacodigodane($codigodane)
    {
        $helpers = $this->get("app.helpers");
        $em = $this->getDoctrine()->getManager();

        try {
            $formato = $em->getRepository(Formatocorreos::class)
                ->find(1);
        } catch (\Exception $ex) {
            return $helpers->json(array(
                "status" => "Ok",
                "code" => "500",
                "msg" => "Error consultando el formato de los correos. " . $ex->getMessage()
            ));
        }

        $t = $em->getRepository(Institucion::class)
            ->findOneBy([
                "codigodane" => $codigodane,
                "jerarquia" => "IE"
            ]);

        $this->enviarCorreoConvocatoriaInstitucion($em, $t, $formato, false);
        $em->flush();

        return $helpers->json(array(
            "status" => "Ok",
            "code" => "200",
            "msg" => "Se ha enviado la información al correo " . $t->getCorreoelectronico()
        ));
    }

    public function enviarCorreoInstitucionrevisiondocumentos($em, $t, $e)
    {
        //    $date = new \DateTime();

        //    $t->setFechanotificacion($date->format('Ymd'));
        //    $em->persist($t);    

        $texto = "<html><body>" .
            "Pereira, 08 de Noviembre de 2021<br><br><br>" .
            "Señor(a) <br>" .
            "Rector(a) <br>" .
            //	    "<b>".$t->getNombreresponsable()."</b><br />".
            "<b>INSTITUCIÓN EDUCATIVA " . $t->getNombreinstitucion() . "</b><br />" .
            $t->getCiudad()->getNombre() . "<br />" .
            "<br />" .
            "Cordial Saludo" .
            "<br /><br /><br />" .
            "ASUNTO:" . Parametros::ASUNTO_ESTADO_POSTULACION . "<br>" .
            "<br /><br /><br />" .
            "Se informa que la postulación del estudiante ";

        $texto .= $e->getPrimernombre() . " " . $e->getSegundonombre() . " " . $e->getPrimerapellido() . " " . $e->getSegundoapellido();

        switch ($e->getMocodmotiv()) {
            case "01":
                $texto .= ", para optar por el premio al Bachiller Comfamiliar 2021, se encuentra en estado de revisión.";
                break;
            case "02":
                $texto .= ", para optar por el premio al Bachiller Comfamiliar 2021, fue registrada exitosamente.";
                break;
            case "03":
                $texto .= ", no fue aprobada teniendo en cuenta que " . $e->getObservacion() .
                    ", por favor revise la observación y realice nuevamente el proceso de postulación.";
                break;
        }

        $texto .= "<br />" .
            "<br />" .
            "<br />" .
            "<br />" .
            "Informes:<br /><br /><br />" .

            "<b>Subdirección de Servicios Sociales<b><br />" .
            "Teléfono: 3162341480<br />" .
            "PBX: 3135600 Ext. 2183<br />" .
            "<br />" .
            $this->logoComfamiliar .
            "</body></html>";

        $this->enviarNotificacion(
            $t->getCorreoelectronico(),
            $texto,
            Parametros::ASUNTO_ESTADO_POSTULACION
        );

        return NULL;
    }

    public function enviarCorreoInstitucionrevisiondocumentossindocumentos($em, $t)
    {

        $texto = "<html><body>" .
            "Pereira, 1 de agosto de 2021<br><br><br>" .
            "Señor(a) <br>" .
            "Rector(a) <br>" .
            //	    "<b>".$t->getNombreresponsable()."</b><br />".
            "<b>INSTITUCIÓN EDUCATIVA " . $t->getNombreinstitucion() . "</b><br />" .
            $t->getCiudad()->getNombre() . "<br />" .
            "<br />" .
            "Cordial Saludo" .
            "<br /><br /><br />" .
            "ASUNTO:" . Parametros::ASUNTO_ESTADO_POSTULACION . "<br>" .
            "<br /><br /><br />" .
            "Se informa que no se recibío por parte de la entidad educativa ninguna postulacion al premio 'Bachiller comfamiliar'";

        $texto .= "<br />" .
            "<br />" .
            "<br />" .
            "<br />" .
            "Informes:<br /><br /><br />" .

            "<b>Subdirección de Servicios Sociales<b><br />" .
            "Teléfono: 3135616<br />" .
            "PBX: 3135600 Ext. 2183<br />" .
            "<br />" .
            $this->logoComfamiliar .
            "</body></html>";

        $this->enviarNotificacion(
            $t->getCorreoelectronico(),
            $texto,
            Parametros::ASUNTO_ESTADO_POSTULACION
        );

        return NULL;
    }

    public function enviarcorreorevisiondocumentosinstitucion($em, $t)
    {
        $qb = $em->createQueryBuilder();

        $qb->select('i')
            ->from('App\Entity\Becado', 'i')
            ->join('i.institucion', 'c')
            ->where('c.id = :id')
            ->setParameter('id', $t->getId());

        $is = $qb->getQuery()
            ->getResult();
        if (empty($is)) {
            $this->enviarCorreoInstitucionrevisiondocumentossindocumentos($em, $t);
        } else {
            foreach ($is as $becado) {
                $this->enviarCorreoInstitucionrevisiondocumentos($em, $t, $becado);
            }
        }
    }

    /**
     * @Route("/enviarcorreomasivorevisiondocumentos", name="institucion_cormasrev")
     */
    public function enviarcorreomasivorevisiondocumentos()
    {
        $helpers = $this->get("app.helpers");
        $em = $this->getDoctrine()->getManager();
        $contador = 0;
        $l = $em->getRepository(Institucion::class)
            ->findBy(["jerarquia" => "IE"]);

        if ($this->validarCorreos() == FALSE) {
        }
        foreach ($l as $t) {
            if (filter_var($t->getCorreoelectronico(), FILTER_VALIDATE_EMAIL)) {
            } else {
                return $helpers->json(array(
                    "status" => "Ok",
                    "code" => "202",
                    "msg" => "Existen correos inválidos" . $t->getCorreoelectronico()
                ));
            }
        }

        if ($contador > 0) {
            return $helpers->json(array(
                "status" => "Ok",
                "code" => "202",
                "msg" => "Existen correos inválidos" . $contador
            ));
        }

        $contador = 0;
        foreach ($l as $t) {
            $this->enviarcorreorevisiondocumentosinstitucion($em, $t);
            $contador++;
        }

        $em->flush();

        return $helpers->json(array(
            "status" => "Ok",
            "code" => "200",
            "msg" => "Correos enviados " . $contador
        ));
    }


    /**
     * @Route("/enviarcorreorevisiondocumentos/{id}", name="institucion_correvisiondocs")
     */
    public function enviarcorreorevisiondocumentos($id)
    {
        $helpers = $this->get("app.helpers");
        $em = $this->getDoctrine()->getManager();
        $cont = 0;

        $t = $em->getRepository(Institucion::class)
            ->find($id);

        if (!$t) {
            return $helpers->json(array(
                "status" => "error",
                "code" => "202",
                "msg" => "No se encontró la instititución"
            ));
        }

        $this->enviarcorreorevisiondocumentosinstitucion($em, $t);

        return $helpers->json(array(
            "status" => "Ok",
            "code" => "200",
            "msg" => "Correos enviados " . $t->getNombreinstitucion() . " " . $cont
        ));
    }

    /**
     * @Route("/enviarcorreorevisiondocumentosdane/{codigodane}", name="institucion_correvdanef")
     */
    public function enviarcorreorevisiondocumentoscodigodane($codigodane)
    {
        $helpers = $this->get("app.helpers");
        $em = $this->getDoctrine()->getManager();

        $t = $em->getRepository(Institucion::class)
            ->findOneBy([
                "codigodane" => $codigodane,
                "jerarquia" => "IE"
            ]);

        $this->enviarCorreoInstitucionrevisiondocumentos($em, $t);
        $em->flush();

        return $helpers->json(array(
            "status" => "Ok",
            "code" => "200",
            "msg" => "Se ha enviado la información al correo " . $t->getCorreoelectronico()
        ));
    }

    public function enviarCorreoInvitacionEstudiante($em, $e, $prueba)
    {
        $texto = "<html><body>" .
            "Pereira, 18 de noviembre de 2021<br><br><br>" .
            "Apreciado estudiante <br />" .
            "<b>" . $e->getPrimernombre() . " " . $e->getSegundonombre() . " " . $e->getPrimerapellido() . " " . $e->getSegundoapellido() . "</b><br/>" .
            "<b>INSTITUCIÓN EDUCATIVA " . $e->getinstitucion()->getNombreinstitucion() . "</b><br />" .
            $e->getinstitucion()->getCiudad()->getNombre() . "<br />" .
            "<br />" .
            "Cordial Saludo" .
            "<br /><br /><br />" .
            "ASUNTO:" . Parametros::ASUNTO_INVITACION_PREMIACION . "<br>" .
            "<br />" .
            //"¡Felicitaciones! Fuiste postulado al Premio Bachiller Comfamiliar 2021<br /><br />".
            "La Institución Educativa a la que perteneces te postuló para estar entre los opcionados para recibir el Premio Bachiller Comfamiliar 2021 debido a tus logros académicos, responsabilidad social y liderazgo en la comunidad estudiantil, por lo anterior te compartimos la invitación a la Ceremonia de Premiación que realizaremos de manera virtual, el próximo jueves 19 de noviembre iniciando a las 3:30 p.m. a través de la plataforma <a href='https://www.comfamiliar.com/megusta/'>www.comfamiliar.com/megusta</a><br /><br />" .
            "Esta invitación es personal e intransferible, válida para que tú y dos acompañantes se registren a través del siguiente enlace <a href='https://bit.ly/2Ujm2jI'>https://bit.ly/2Ujm2jI</a> para asistir al acto de premiación. Vale la pena aclararte que únicamente estarán opcionados para participar en el sorteo y recibir el premio que corresponde a una beca por $ 30´000.000 para adelantar una carrera profesional aprobada por el ICFES, los estudiantes postulados que se conecten al acto de premiación. que únicamente estarán opcionados para recibir el premio que corresponde a una beca por $ 30´000.000 para adelantar una carrera profesional aprobada por el ICFES, los estudiantes postulados que se conecten al acto de premiación.<br /><br />" .
            "¡Te esperamos!<br /><br />";

        $texto .= "<br />" .
            $this->imagenInvitacionpostulado .
            "<br />" .
            "<br />" .
            "<br />" .
            "Informes:<br /><br /><br />" .

            "<b>Comfamiliar Risaralda<b><br />" .
            "<b>Subdirección de Servicios Sociales<b><br />" .
            "Teléfono: 3162341480<br />" .
            "PBX: 3135600 Ext. 2183<br />" .
            "<br />" .
            $this->logoComfamiliar .
            "</body></html>";

        if ($prueba == true) {
            $correod = "jhenao@comfamiliar.com";
        } else {
            $correod = $e->getCorreoelectronico();
        }

        $this->enviarNotificacion(
            $correod,
            $texto,
            Parametros::ASUNTO_INVITACION_PREMIACION
        );
        return NULL;
    }

    /**
     * @Route("/enviarcorreomasivoinvitacionestudiantes", name="institu_invitacest")
     */
    public function enviarcorreomasivoinvitacionestudiantes()
    {
        $helpers = $this->get("app.helpers");
        $em = $this->getDoctrine()->getManager();
        $contador = 0;

        $this->inicializarImages();
        $becados = $em->getRepository(Becado::class)
            ->findBy(['vigencia' => 2021, "mocodmotiv" => "01"]);

        if (empty($becados)) {
            return $helpers->json(array(
                "status" => "Ok",
                "code" => "202",
                "msg" => "No existen becados con evaluacion aprobada"
            ));
        }

        if ($this->validarCorreos() == FALSE) {
        }
        $errores = false;
        $correoe = '';
        foreach ($becados as $becado) {
            if (filter_var($becado->getCorreoelectronico(), FILTER_VALIDATE_EMAIL)) {
            } else {
                $errores = true;
                $correoe .= $becado->getCorreoelectronico();
            }
        }

        if ($errores) {
            return $helpers->json(array(
                "status" => "Ok",
                "code" => "202",
                "msg" => "Existen correos inválidos en invitacion postulado: " . $correoe
            ));
        }

        $this->contador = 0;

        foreach ($becados as $becado) {
            $prueba = false;
            $this->enviarCorreoInvitacionEstudiante($em, $becado, $prueba);
        }

        return $helpers->json(array(
            "status" => "Ok",
            "code" => "200",
            "msg" => "Correos enviados " . $this->contador
        ));
    }

    /**
     * @Route("/listgueststudents", name="institu_listguesstudents")
     */

    public function listGuestStudents()
    {
        $helpers = $this->get("app.helpers");
        $em = $this->getDoctrine()->getManager();
        $contador = 0;

        $becados = $em->getRepository(Becado::class)
            ->findBy(['vigencia' => Parametros::YEAR, "mocodmotiv" => "01"]);

        if (empty($becados)) {
            return $helpers->json(array(
                "status" => "Ok",
                "code" => "202",
                "msg" => "No existen becados con evaluacion aprobada"
            ));
        }
        $list = array();

        $errores = false;
        $correoe = '';

        foreach ($becados as $becado) {
            if (filter_var($becado->getCorreoelectronico(), FILTER_VALIDATE_EMAIL)) {
            } else {
                $errores = true;
                $correoe .= $becado->getCorreoelectronico();
            }
        }

        if ($errores) {
            return $helpers->json(array(
                "status" => "Ok",
                "code" => "202",
                "msg" => "Existen correos inválidos en invitacion postulado: " . $correoe . "  -  "
            ));
        }

        $this->contador = 0;

        foreach ($becados as $becado) {
            switch ($becado->getConsecutivo()) {
                case 76:
                    break;
                case 95:
                    break;
                case 97:
                    break;
                case 98:
                    break;
                case 107:
                    break;
                case 128:
                    break;
                case 132:
                    break;
                case 143:
                    break;
                case 144:
                    break;
                case 147:
                    break;
                case 153:
                    break;
                case 154:
                    break;
                case 160:
                    break;
                case 162:
                    break;
                default:
                    array_push($list, $becado);
            }
        }

        $resp = array(
            "subject" => Parametros::ASUNTO_INVITACION_PREMIACION,
            "list" => $list,
            "from" => Parametros::CORREO_BACHILLER
        );

        return $helpers->json(array(
            "status" => "Ok",
            "code" => "200",
            "msg" => $resp
        ));
    }

    public function enviarCorreoInvitacionInstitucion($em, $i, $prueba)
    {
        $this->inicializarImages();

        $texto = "<html><body>" .
            "Pereira, 18 de noviembre de 2021<br><br><br>" .
            //      "Señor(a) <br>".
            "Rector(a) <br>" .
            "<b>INSTITUCIÓN EDUCATIVA " . $i->getNombreinstitucion() . "</b><br />" .
            $i->getCiudad()->getNombre() . "<br />" .
            "<br />" .
            "Apreciado Rector (a),<br /><br/>" .
            "Agradecemos la postulación realizada para la convocatoria del Premio al Bachiller Comfamiliar y tenemos el gusto de invitarle a la ceremonia de entrega del premio, que se realizará de manera virtual mañana jueves 19 de noviembre a las 4:00 p.m. a través de la plataforma <a href='https://www.comfamiliar.com/megusta'>www.comfamiliar.com/megusta</a><br /><br />" .
            "Regístrese a través del siguiente enlace <a href='https://bit.ly/2Ujm2jI'>https://bit.ly/2Ujm2jI</a> para asistir al acto de premiación. En caso de no poder acompañarnos, por favor delegue un miembro de su Institución Educativa para que se conecte a este importante acto, vale la pena aclararle que la persona a quien usted delegue se debe registrar a través del enlace mencionado.<br />" .
            "Cordialmente,<br /><br />" .
            "Comfamiliar Risaralda<br />";


        $texto .= "<br />" .
            $this->imagenInvitacionrector .
            "<br />" .
            "<br />" .
            "<br />" .
            "Informes:<br /><br /><br />" .

            "<b>Comfamiliar Risaralda<b><br />" .
            "<b>Subdirección de Servicios Sociales<b><br />" .
            // "Teléfono: 3135616<br />".
            "PBX: 3135600 Ext. 2183<br />" .
            "<br />" .
            $this->logoComfamiliar .
            "</body></html>";

        $transport = (new \Swift_SmtpTransport('smtp.comfamiliar.com', 25))
            ->setUsername('jhenao@comfamiliar.com')
            ->setPassword('sailor1972');

        if ($prueba) {
            $correo = 'jhenao@comfamiliar.com';
        } else {
            $correo = $t->getCorreoelectronico();
        }

        $this->enviarNotificacion(
            $correo,
            $texto,
            Parametros::ASUNTO_INVITACION_PREMIACION
        );

        return NULL;
    }

    public function enviarcorreomasivoinvitacionrectorconestudiantespostulados($em, $t, $prueba)
    {
        $qb = $em->createQueryBuilder();

        $qb->select('i')
            ->from('App\Entity\Becado', 'i')
            ->join('i.institucion', 'c')
            ->andWhere('c.id = :id and i.vigencia = :vigencia')
            ->setParameter('vigencia', Parametros::YEAR)
            ->setParameter('id', $t->getId());

        $is = $qb->getQuery()
            ->getResult();
        if (!empty($is)) {
            $this->enviarCorreoInvitacionInstitucion($em, $t, $prueba);
            return true;
        } else {
            return false;
        }
    }

    /**
     * @Route("/enviarcorreomasivoinvitacionrector", name="institucion_invitacionrec")
     */
    public function enviarcorreomasivoinvitacionrector()
    {
        $helpers = $this->get("app.helpers");
        $em = $this->getDoctrine()->getManager();
        $contador = 0;
        $l = $em->getRepository(Institucion::class)
            ->findBy(["jerarquia" => "IE"]);

        if ($this->validarCorreos() == FALSE) {
        }
        foreach ($l as $t) {
            if (filter_var($t->getCorreoelectronico(), FILTER_VALIDATE_EMAIL)) {
            } else {
                return $helpers->json(array(
                    "status" => "Ok",
                    "code" => "202",
                    "msg" => "Existen correos inválidos" . $t->getCorreoelectronico()
                ));
            }
        }

        if ($contador > 0) {
            return $helpers->json(array(
                "status" => "Ok",
                "code" => "202",
                "msg" => "Existen correos inválidos" . $contador
            ));
        }

        $contador = 0;
        $prueba = false;

        foreach ($l as $t) {
            if ($this->enviarcorreomasivoinvitacionrectorconestudiantespostulados($em, $t, $prueba)) {
                $contador++;
            }
        }

        $em->flush();

        return $helpers->json(array(
            "status" => "Ok",
            "code" => "200",
            "msg" => "Correos enviados invitacion rector: " . $contador
        ));
    }

    /**
     * @Route("/listguestrector", name="institu_listguestrector")
     */

    public function listGuestRector()
    {
        $helpers = $this->get("app.helpers");
        $em = $this->getDoctrine()->getManager();
        $contador = 0;
        $l = $em->getRepository(Institucion::class)
            ->findBy(["jerarquia" => "IE"]);

        if ($this->validarCorreos() == FALSE) {
        }
        foreach ($l as $t) {
            if (filter_var($t->getCorreoelectronico(), FILTER_VALIDATE_EMAIL)) {
            } else {
                return $helpers->json(array(
                    "status" => "Ok",
                    "code" => "202",
                    "msg" => "Existen correos inválidos" . $t->getCorreoelectronico()
                ));
            }
        }

        if ($contador > 0) {
            return $helpers->json(array(
                "status" => "Ok",
                "code" => "202",
                "msg" => "Existen correos inválidos" . $contador
            ));
        }
        $list = array();
        $contador = 0;
        foreach ($l as $t) {
            array_push($list, $t);
            $contador++;
        }

        $resp = array(
            "subject" => Parametros::ASUNTO_INVITACION_PREMIACION,
            "list" => $list,
            "from" => Parametros::CORREO_BACHILLER
        );

        return $helpers->json(array(
            "status" => "Ok",
            "code" => "200",
            "msg" => $resp
        ));
    }

    public function enviarCorreoInstitucionSinEstudiantespostuladosMail($em, $t)
    {
        $this->inicializarImages();

        $texto = "<html><body>" .
            "Pereira, 1 de noviembre de 2021<br><br><br>" .
            "Señor(a) <br>" .
            "Rector(a) <br>" .
            "<b>INSTITUCIÓN EDUCATIVA " . $t->getNombreinstitucion() . "</b><br />" .
            $t->getCiudad()->getNombre() . "<br />" .
            "<br />" .
            "Cordial Saludo" .
            "<br /><br /><br />" .
            "ASUNTO:" . Parametros::ASUNTO_INVITACION_PREMIACION . "<br>" .
            "<br />" .
            "Teniendo en cuenta  la logística interna de las instituciones educativas para el proceso de elección del  alumno  a postular al premio Bachiller Comfamiliar 2021, nos permitimos informar que la plataforma de inscripción bachiller.comfamiliar.com estará abierta hasta el miercoles 24 de Noviembre.<br><br />" .
            "Agradecemos realizar el registro de los estudiantes de su plantel educativo que cumplan con los requisitos establecidos para la postulación.<br><br>" .
            "<br />" .
            "<br />" .
            "<br />" .
            "Cordialmente, <br />" .
            "<br />" .
            "MAURIER VALENCIA HERNÁNDEZ<br />" .
            "Director Administrativo<br /><br />" .
            "Informes:<br /><br /><br />" .
            "<b>Subdirección de Servicios Sociales<b><br />" .
            "Teléfono: 3135616<br />" .
            "PBX: 3135600 Ext. 2183<br />" .
            "<br />" .
            $this->logoComfamiliar .
            "</body></html>";

        //    $mail = $this->get("app.mail");
        $this->enviarNotificacion(
            $t->getCorreoelectronico(),
            $texto,
            Parametros::ASUNTO_INVITACION_PREMIACION
        );

        return NULL;
    }

    public function enviarCorreoInstitucionSinEstudiantespostuladosValidaEstudiante($em, $t)
    {
        $qb = $em->createQueryBuilder();

        $qb->select('i')
            ->from('App\Entity\Becado', 'i')
            ->join('i.institucion', 'c')
            ->where('c.id = :id')
            ->setParameter('id', $t->getId());

        $is = $qb->getQuery()
            ->getResult();
        if (empty($is)) {
            $this->enviarCorreoInstitucionSinEstudiantespostuladosMail($em, $t);
        }
    }

    /**
     * @Route("/enviarCorreoInstitucionSinEstudiantespostulados", name="institucion_enviasinpostulados")
     */
    public function enviarCorreoInstitucionSinEstudiantespostulados()
    {
        $helpers = $this->get("app.helpers");
        $em = $this->getDoctrine()->getManager();
        $contador = 0;
        $l = $em->getRepository(Institucion::class)
            ->findBy(["jerarquia" => "IE"]);

        if ($this->validarCorreos() == FALSE) {
        }
        foreach ($l as $t) {
            if (filter_var($t->getCorreoelectronico(), FILTER_VALIDATE_EMAIL)) {
            } else {
                return $helpers->json(array(
                    "status" => "Ok",
                    "code" => "202",
                    "msg" => "Existen correos inválidos" . $t->getCorreoelectronico()
                ));
            }
        }

        if ($contador > 0) {
            return $helpers->json(array(
                "status" => "Ok",
                "code" => "202",
                "msg" => "Existen correos inválidos" . $contador
            ));
        }

        $contador = 0;
        foreach ($l as $t) {
            $this->enviarCorreoInstitucionSinEstudiantespostuladosValidaEstudiante($em, $t);
            $contador++;
        }

        return $helpers->json(array(
            "status" => "Ok",
            "code" => "200",
            "msg" => "Correos enviados " . $contador
        ));
    }
}
