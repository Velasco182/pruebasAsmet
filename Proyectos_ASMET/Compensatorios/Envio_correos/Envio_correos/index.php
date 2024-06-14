<?php
include('PHPMailer.php');
include('SMTP.php');
include('Exception.php');

$mail = new PHPMailer(true);


try {
    //Server settings
    $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
    $mail->isSMTP();                                            //Send using SMTP
    $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
    $mail->Username   = 'noreply@asmetsalud.com';                     //SMTP username
    $mail->Password   = '4sm3t+2019';                               //SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
    $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

    //Recipients
    $mail->setFrom('noreply@asmetsalud.com', 'Asmet Salud SAS');
          //Add a recipient
    $mail->addAddress('anderson.orozco@asmetsalud.com');               //Name is optional
    //$mail->addReplyTo('info@example.com', 'Information');
    //$mail->addCC('cc@example.com');
    //$mail->addBCC('bcc@example.com');

    //Attachments
    //$mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
    //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

    //Content
    $mail->isHTML(true);                                  //Set email format to HTML
    $mail->Subject = 'USUARIO: '.'DAVID.PRADO'.' creado dentro de la plataforma de recepcion de informacion';
    $mail->Body    = '
    <html>
    <h3> Cordial saludo, le informamos que se la designado un usuario dentro de la plataforma de recepción de información</h3> 
    <br>
    
    <div>
    USUARIO:<strong>david.prado</strong>
    </div>
    <div>
    <span>Para crear o cambiar su contraseña click en el siguiente enlace: </span><a href="www.google.com.co">Click aquí</a>
    </div>
    </html>
    ';

    $mail->AltBody = 'Contacte al administrador para crear su contraseña';

    $mail->send();
    echo 'Message has been sent';
} catch (Exception2 $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}

?>