<?php

$allowed = ['http://mtkocak.github.io/contact.html',
    'http://www.mtkocak.com/contact.html#contact-form',
    'http://mtkocak.github.io/contact.html#contact-form',
'http://www.mtkocak.com/contact.html',
'http://mtkocak.com/contact.html'];

$referer = getenv("HTTP_REFERER");

if(getenv(in_array($referer,$allowed))){
        die('don\'t be an jerk, ruin your own site');   
    }

require '../vendor/autoload.php';

function spamcheck($field) {
  // Sanitize e-mail address
  $field=filter_var($field, FILTER_SANITIZE_EMAIL);
  // Validate e-mail address
  if(filter_var($field, FILTER_VALIDATE_EMAIL)) {
    return $field;
  } else {
    return FALSE;
  }
}

$message = htmlspecialchars($_POST['message'], ENT_QUOTES, 'UTF-8');


$email = spamcheck($_POST['email']);

$mail = new PHPMailer;

//$mail->SMTPDebug = 3;                               // Enable verbose debug output

$mail->isSMTP();                                      // Set mailer to use SMTP
$mail->Host = 'smtp.mandrillapp.com ';  // Specify main and backup SMTP servers
$mail->SMTPAuth = true;                               // Enable SMTP authentication
$mail->Username = 'mtkocak@gmail.com';                 // SMTP username
$mail->Password = 'cxzCKql5JD1IAyTuSaHFEw';                           // SMTP password
$mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
$mail->Port = 587;                                    // TCP port to connect to

$mail->From = $email;
$mail->FromName = $email;
$mail->addAddress('mtkocak@gmail.com', 'Mutlu Kocak');     // Add a recipient
$mail->AddReplyTo($email, $email);

$mail->WordWrap = 50;                                 // Set word wrap to 50 characters
$mail->isHTML(true);                                  // Set email format to HTML

$mail->Subject = 'You have message from your website';
$mail->Body    = $message;

if(!$mail->send()) {
    echo 'Message could not be sent.';
    echo 'Mailer Error: ' . $mail->ErrorInfo;
} else {
    echo 'Message has been sent';
}

if ( $parts = parse_url($referer ) ) {
    $baseUrl = $parts[ "scheme" ] . "://" . $parts[ "host" ];
}

header('Location:'.$baseUrl.'/index.html');
