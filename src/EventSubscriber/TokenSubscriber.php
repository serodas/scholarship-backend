<?php

namespace App\EventSubscriber;

use \Psr\Log\LoggerInterface;
use App\Controller\TokenAuthenticatedController;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use \Symfony\Component\HttpKernel\Event\GetResponseEvent;

class TokenSubscriber implements EventSubscriberInterface
{
  /*
    private $tokens;

    public function __construct($tokens)
    {
        $this->tokens = $tokens;
    }
*/

    private $logger;
    
    public function __construct(LoggerInterface $logger) {
      $this->logger = $logger;
    }

    public function onKernelRequest(GetResponseEvent $event){
      $this->logger->info("Aqui voy");
      $request = $event->getRequest();
      
//      die("It work en request");
    }
    
    /*
    public function onKernelController(FilterControllerEvent $event){
      $this->logger->info("Aqui voy");
      die("It work");
    }
     * 
     */
    /*
    public function onKernelController(FilterControllerEvent $event)
    {
        $controller = $event->getController();


        if (!is_array($controller)) {
            return;
        }

        if ($controller[0] instanceof TokenAuthenticatedController) {
            $token = $event->getRequest()->query->get('token');
//            if (!in_array($token, $this->tokens)) {
//                throw new AccessDeniedHttpException('This action needs a valid token!');
//            }
  
	  $event->getRequest()->attributes->set('auth_token', $token);
        }
    }
    */
    /*
    public function onKernelResponse(FilterResponseEvent $event)
    {
	if (!$token = $event->getRequest()->attributes->get('auth_token')) {
	    return;
	}

	$response = $event->getResponse();

	$hash = sha1($response->getContent().$token);
	$response->headers->set('X-CONTENT-HASH', $hash);
    }
*/
    public static function getSubscribedEvents()
    {
        return array(
//            KernelEvents::CONTROLLER => 'onKernelController',
	    KernelEvents::REQUEST => 'onKernelRequest',
//	    KernelEvents::RESPONSE => 'onKernelResponse',
        );
    }
}