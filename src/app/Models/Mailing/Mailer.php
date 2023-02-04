<?php

declare(strict_types=1);

namespace App\Models\Mailing;

use App\Models\Resource\ResourceManager;
use Exception;
use PHPMailer\PHPMailer\PHPMailer;

class Mailer{
    private PHPMailer $mail;

    public function __construct()
    {
        $this->mail = new PHPMailer();
    }

    public function sendEmail(array $recipients, string $subject, string $message, bool $isHTML = false, array $embededImages = [], array $attachments=[], array $config = null){
        if(is_null($config)){
            $config = $_ENV;
        }
        $this->mail  = new PHPMailer();
        $this->mail->isSMTP();
        $this->mail->Host = $config['MAILER_HOST'];
        $this->mail->Port = $config['MAILER_PORT'];
        $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $this->mail->SMTPAuth = true;
        $this->mail->Username = $config['MAILER_USERNAME'];
        $this->mail->Password = $config['MAILER_PASSWORD'];
        $this->mail->CharSet = 'UTF-8';
        $this->mail->setFrom($config['MAILER_SENDER_EMAIL'], $config['MAILER_SENDER_NAME']);
        
        foreach($recipients as $recipient){
            $this->mail->addAddress($recipient);
        }

        foreach($attachments as $attachment){
            $this->mail->addAttachment($attachment);
        }

        $this->mail->isHTML($isHTML);
        $this->mail->Subject = $subject;
        $this->mail->Body = $message;
        
        foreach($embededImages as $image){
            $this->mail->addEmbeddedImage($image['path'], $image['cid']);
        }
        
        try{
            return $this->mail->send();
        }
        catch(Exception){
            return false;
        }
    }

    public function generateMessageFromTemplate(string $template, array $args=[]){
        // $logoFilePath = (new ResourceManager())->getResource('img', 'general/logo.png')->path;
        // var_dump($logoFilePath);
        // $this->mail->addAttachment('logo.png', 'logo.png');
        // $this->mail->addEmbeddedImage('logo.png', 'logo');

        ob_start();
        include 'EmailTemplates/EmailHeader.php';
        include 'EmailTemplates/' . $template;
        include 'EmailTemplates/EmailFooter.php';
        
        return (string)ob_get_clean();

    }
}