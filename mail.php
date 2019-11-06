<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require '.\vendor\autoload.php';
function enviarMail($toMail, $numeroPagare){
    $mail = new PHPMailer(TRUE);
    $mail->isSMTP();
    $mail->Host = 'smtp.office365.com';
    $mail->Port       = 587;
    $mail->SMTPSecure = 'tls';
    $mail->SMTPAuth   = true;
    $mail->Username = 'rpower@servicobranza.cl';
    $mail->Password = 'randolph.2019p';
    $mail->SetFrom('rpower@servicobranza.cl', 'FromEmail');
    $mail->addAddress($toMail, 'ToEmail');
    $mail->IsHTML(true);
    
    $mail->Subject = 'Asignacion de Pagare '.$numeroPagare;
    $mail->Body    = 'El siguiente es para indicarle que se le han asignado el siguiente pagare: '.$numeroPagare;
    
    if(!$mail->send()) {
        //echo 'Message could not be sent.';
        //echo 'Mailer Error: ' . $mail->ErrorInfo;
    } else {
        //echo 'Message has been sent';
    }
}

?>