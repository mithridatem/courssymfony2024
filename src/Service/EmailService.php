<?php

namespace App\Service;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class EmailService{
    private string $smtp_server;
    private string $smtp_account;
    private string $smtp_password;
    private int $smtp_port;
    public function __construct(string $smtp_server, string $smtp_account, string $smtp_password, int $smtp_port)
    {
        $this->smtp_server = $smtp_server;
        $this->smtp_account = $smtp_account;
        $this->smtp_password = $smtp_password;
        $this->smtp_port = $smtp_port;
    }
    public function sendEmail(string $recevier, string $subject, string $content):string 
    {
        $mail = new PHPMailer(true);
        try {
            //Server settings
            $mail->SMTPDebug = SMTP::DEBUG_SERVER;
            $mail->isSMTP();
            $mail->Host       = $this->smtp_server;
            $mail->SMTPAuth   = true;
            $mail->Username   = $this->smtp_account;
            $mail->Password   = $this->smtp_password;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port       = $this->smtp_port;
            //To load the French version
            $mail->setLanguage('fr', '/optional/path/to/language/directory/');
            //Recipients
            $mail->setFrom($this->smtp_account, 'Admin Blog');
            $mail->addAddress($recevier);

            //Content
            $mail->isHTML(true);                                  
            $mail->Subject = $subject;
            $mail->Body    = $content;
            
            $mail->send();
            return 'Message has been sent';
        } catch (Exception $e) {
            return "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }
    public function testConfig(): string {
        return "Config : ".$this->smtp_server. " : ".$this->smtp_account;
    }
}
