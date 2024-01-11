<?php

namespace App\Service;

class Mail {
  public function enviarcorreo(){
/*
    $message = (new \Swift_Message('Hello Email'))
	    ->setFrom('jhenao@comfamiliar.com')
	    ->setTo('jhenao@comfamiliar.com')
	    ->setBody(
		    'Mi primeer correo', 'text/html'
	    );

    $mailer->send($message);
*/
    $transport = (new \Swift_SmtpTransport('smtp.comfamiliar.com', 25))
	    ->setUsername('jhenao@comfamiliar.com')
	    ->setPassword('sailor1972')
    ;

    $mailer = new \Swift_Mailer($transport);

    $message = (new \Swift_Message('Wonderful Subject'))
	    ->setFrom(['jhenao@comfamiliar.com' => 'jhenao'])
	    ->setTo(['jhenao@comfamiliar.com' => 'Jorge henao'])
	    ->setBody('Here is the message itself')
    ;

    $result = $mailer->send($message);
    
    return $result;
  }
}
