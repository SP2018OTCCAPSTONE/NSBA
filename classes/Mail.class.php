<?php
 use PHPMailer\PHPMailer\PHPMailer;
 use PHPMailer\PHPMailer\Exception;
 use PHPMailer\PHPMailer\SMTP;
/**
 * Mail class - a wrapper around PHPMailer
 * phpmailer/phpmailer suggests installing league/oauth2-google (Needed for Google XOAUTH2 authentication)
 * phpmailer/phpmailer suggests installing hayageek/oauth2-yahoo (Needed for Yahoo XOAUTH2 authentication)
 * phpmailer/phpmailer suggests installing stevenmaguire/oauth2-microsoft (Needed for Microsoft XOAUTH2 authentication)
 */

class Mail
{

  private function __construct() {}  // disallow creating a new object of the class with new Mail()

  private function __clone() {}  // disallow cloning the class

  /**
   * Send an email
   *
   * @param string $name     Name
   * @param string $email    Email address
   * @param string $subject  Subject
   * @param string $body     Body
   * @return boolean         true if the email was sent successfully, false otherwise
   */
  public static function send($name, $email, $subject, $body)
  {
      //// PHPmailerAutoLoad is no longer used in phpmailer 6.0.5 -- Composer takes care of the autoloader?
    //require dirname(dirname(__FILE__)) . '/Capstone_Seed/vendor/PHPMailer/PHPMailerAutoload.php';

    $mail = new PHPMailer(true);

    $mail->isSMTP();
    //$mail->Host = Config::SMTP_HOST;
    $mail->Host = getenv('SMTP_HOST');
    $mail->SMTPAuth = true;
    //$mail->Username = Config::SMTP_USER;
    $mail->Username = getenv('SMTP_USER');
    //$mail->Password = Config::SMTP_PASS;
    $mail->Password = getenv('SMTP_PASS');
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    $mail->SMTPDebug = 4; // PROVIDES HIGH LEVEL OF CONNECTION ERROR DEBUGGING

    $mail->From = 'justinkmangan@gmail.com';

    $mail->isHTML(true);

    $mail->addAddress($email, $name);
    $mail->Subject = $subject;
    $mail->Body = $body;

    if ( ! $mail->send()) {
      error_log($mail->ErrorInfo);
      return false;

    } else {
      return true;

    }
  }

}
