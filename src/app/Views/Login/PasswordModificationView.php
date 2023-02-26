<?php

declare(strict_types=1);

namespace App\Views\Login;

use App\Interfaces\ViewInterface;
use App\Views\Common\View;

class PasswordModificationView extends View implements ViewInterface
{
    private string $cssFile = 'passwordModification.css';
    private bool $valid;
    private string $errorMsg;
    private array $formData;
    private int $userId; 
    private bool $requestType;
    private string $verificationHash;

    public function __construct(array $modelData)
    {
        $this->valid = $modelData['passwordResetData']['valid'] ?? true; 
        $this->formData = $modelData['formData'] ?? [];
        $this->errorMsg = $modelData['formResult']['errorMsg'] ?? '';
        $this->userId = $modelData['id']; 
        $this->requestType = $modelData['requestType'];
        $this->verificationHash = $modelData['hash'] ?? '';
        $this->pageName = 'Password modification';

    }
    public function display():string
    {
        ob_start();
        
        include __DIR__ . '/../Common/Components/Header.php';
        include 'Components/PasswordModification.php';
        include __DIR__ . '/../Common/Components/Footer.php';
        
        return (string)ob_get_clean();

    }
}