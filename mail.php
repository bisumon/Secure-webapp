<?php 
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;
    use PHPMailer\PHPMailer\SMTP;
    require 'vendor/autoload.php';
    echo "start"; //testing
    $mail = new PHPMailer(true);
    echo "start 2"; //testing
    $email = "7eec576565c8a4@crankymonkey.info";
    $sub = "testing";
    $msg = "testing mail service";
    try{
        echo "send_email start"; //testing
        echo "php mailer created"; //testing
        $mail->SMTPDebug = 2;                   // Enable verbose debug output
        $mail->isSMTP();                        // Set mailer to use SMTP
        $mail->Host       = 'smtp.gmail.com;';    // Specify main SMTP server
        $mail->SMTPAuth   = true;               // Enable SMTP authentication
        $mail->Username   = 'clive27755@gmail.com';     // SMTP username
        $mail->Password   = 'gdwnnudahonzxkci';         // SMTP password
        $mail->SMTPSecure = 'tls';              // Enable TLS encryption
        $mail->Port       = 587;                // TCP port to connect to
        $mail->setFrom('clive27755@gmail.com', 'Lovejoy');           // Set sender of the mail
        $mail->addAddress($email);           // Add a recipient
        $mail->isHTML(true);                                  
        $mail->Subject = $sub;
        $mail->Body    = $msg;
    //    return mail($email,$sub,$msg,$header);
        if($mail->send()){
            echo "sent"; //testing
            return true;
        } else {
            echo "not send";//testing
            return false;
        }
        echo "end"; //testing
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }

?>