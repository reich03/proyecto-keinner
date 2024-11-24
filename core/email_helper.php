<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

function enviarCorreo($user, $resumenDetalles)
{
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'redjhojan0319@gmail.com';
        $mail->Password = 'ieogoqiyjlxvklrn';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port = 465;

        $mail->setFrom('redjhojan0319@gmail.com', 'JhojanGrisales');
        $mail->addAddress($user['correo'], $user['nombre'] . ' ' . $user['apellido']);

        $mail->isHTML(true);
        $mail->Subject = 'Resumen de Compra';
        $mail->Body = $resumenDetalles;
        $mail->AltBody = strip_tags($resumenDetalles);

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Error al enviar correo: {$mail->ErrorInfo}");
        return false;
    }
}
