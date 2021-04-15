<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \PHPMailer\PHPMailer\PHPMailer;

class MailController extends Controller
{

	private static $request;

    public static function send($request)
    {
    	self::$request = (object) $request;
	    $mail = new PHPMailer();
	 	try{
		 	$mail->isSMTP();
		 	$mail->CharSet = 'utf-8';
		 	$mail->SMTPAuth = true;
		 	$mail->SMTPSecure = 'ssl';
		 	$mail->Host = env('MAIL_HOST', ''); #gmail has host  smtp.gmail.com
		 	$mail->Port = env('MAIL_PORT', ''); #gmail has port  587 . without double quotes
		 	$mail->Username = env('MAIL_USERNAME', ''); #your username. actually your email
		 	$mail->Password = env('MAIL_PASSWORD', ''); # your password. your mail password
		 	$mail->setFrom('info@lan-host.net', 'Lan-Host.net'); 
		 	$mail->Subject = self::$request->subject;
		 	$mail->MsgHTML(self::$request->text);
		 	$mail->addAddress(self::$request->receiver_email, self::$request->receiver_name); 
		 	$mail->send();

		//dd($mail);
		}catch(phpmailerException $e){
	 		dd($e);
	 	}catch(Exception $e){
	 		dd($e);
	 	} 
    }

}
