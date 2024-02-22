<?php

namespace App\Service;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class EmailService
{
    private string $smtp_server;
    private string $smtp_account;
    private string $smtp_password;
    private int $smtp_port;
    private PHPMailer $mail;

    //injection des dépendances
    public function __construct(string $smtp_server, string $smtp_account, string $smtp_password, int $smtp_port)
    {
        $this->smtp_server = $smtp_server;
        $this->smtp_account = $smtp_account;
        $this->smtp_password = $smtp_password;
        $this->smtp_port = $smtp_port;
        $this->mail = new PHPMailer(true);
    }

    /**
     * Méthode pour envoyer un email
     * @param string $receiver Mail du destinataire
     * @param string $subject Objet du mail
     * @param string $body Corp du mail au format HTML
     * @return string Retourne la confirmation d'envoi ou une exception
     */
    public function sendEmail(string $receiver, string $subject, string $content): string
    {
        try {
            //Server settings
            $this->setConfig();

            //Recipients
            $this->mail->setFrom($this->smtp_account, 'Admin Blog');
            $this->mail->addAddress($receiver);

            //Content
            $this->mail->isHTML(true);
            $this->mail->Subject = $subject;
            $this->mail->Body    = $content;

            //Send mail
            $this->mail->send();

            return 'Message has been sent';
        } catch (Exception $e) {
            return "Message could not be sent. Mailer Error: {$this->mail->ErrorInfo}";
        }
    }

    /**
     * Méthode pour tester la configuration du service (.env-> service.yaml)
     * @return string retourne l'url du serveur SMTP et du mail du compte
     */
    public function testConfig(): string
    {
        return "Config : " . $this->smtp_server . " : " . $this->smtp_account;
    }

    /**
     * Méthode pour configurer le serveur SMTP
     * @return void
     */
    private function setConfig(): void
    {
        //Server settings
        $this->mail->SMTPDebug = SMTP::DEBUG_OFF;
        $this->mail->isSMTP();
        $this->mail->Host       = $this->smtp_server;
        $this->mail->SMTPAuth   = true;
        $this->mail->Username   = $this->smtp_account;
        $this->mail->Password   = $this->smtp_password;
        $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $this->mail->Port       = $this->smtp_port;
        //To load the French version
        $this->mail->setLanguage('fr', '/optional/path/to/language/directory/');
    }
}
