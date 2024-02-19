<?php

namespace App\Service;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class MailService
{
    private string $server;
    private int $port;
    private string $account;
    private string $pwd;
    private PHPMailer $mail;
    //initialisation des paramètres de connexion
    public function __construct($server, $port, $account, $pwd)
    {
        $this->server = $server;
        $this->port = $port;
        $this->account = $account;
        $this->pwd = $pwd;
        $this->mail = new PHPMailer(true);
    }
    public function testConfig(): string
    {
        return "server : " . $this->server . " port : " . $this->port . " account : " . $this->account . " pwd : " . $this->pwd;
    }
    public function sendEmail($receiver,$subject,$message): string
    {
        try {
            //Server settings
            $this->mail->SMTPDebug = SMTP::DEBUG_SERVER;
            $this->mail->isSMTP();
            $this->mail->Host       = $this->server;
            $this->mail->SMTPAuth   = true;
            $this->mail->Username   = $this->account;
            $this->mail->Password   = $this->pwd;
            $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $this->mail->Port       = $this->port;

            //Recipients
            $this->mail->setFrom($this->account, 'Mailer');
            $this->mail->addAddress($receiver);
            
            //Content
            $this->mail->isHTML(true);
            $this->mail->Subject = $subject;
            $this->mail->Body    = $message;
            $this->mail->send();
            return 'Message has been sent';
        } 
        catch (Exception $e) {
            return "Message could not be sent. Mailer Error: {$this->mail->ErrorInfo}";
        }
    }
}
