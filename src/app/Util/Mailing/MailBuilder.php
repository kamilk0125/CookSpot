<?php

declare(strict_types=1);

namespace App\Util\Mailing;

use App\Util\Mailing\Mailer;
use App\Util\Resource\Handlers\ResourceHandler;

class MailBuilder
{
    private array $embededImages;

    public function __construct()
    {
        $this->embededImages = [['path' => (new ResourceHandler)->getResource('img', 'general/logo.png')->path, 'cid' => 'logo']];
    }

    public function sendTemplateEmail(string $template, string $subject, array $recipiants, array $args = [], array $embededImages = []):bool
    {
        $embededImages = array_merge($this->embededImages, $embededImages);
        $emailMessage =  $this->generateMessageFromTemplate($template, $args);
        return (new Mailer())->sendEmail($recipiants, $subject, $emailMessage, true, $embededImages);
    }

    public function generateMessageFromTemplate(string $template, array $args=[]){

        ob_start();
        include 'EmailTemplates/EmailHeader.php';
        include 'EmailTemplates/' . $template;
        include 'EmailTemplates/EmailFooter.php';
        
        return (string)ob_get_clean();

    }

}