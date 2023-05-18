<?php

declare(strict_types=1);

namespace App\Views\Share;

use App\Interfaces\ViewInterface;
use App\Model\Form;
use App\Views\Common\View;

class ShareView extends View implements ViewInterface
{
    private string $cssFile = 'share.css';
    private string $infoText;
    private bool $recipesShared;

    public function __construct(private array $ownedRecipes, private array $friendsList, ?Form $form)
    {
        
        if(isset($form->resultData['recipesShared']))
            $this->infoText = 'Recipes Shared';
        else
            $this->infoText = $form->resultData['errorMsg'] ?? '';
        
        $this->recipesShared = $form->resultData['recipesShared'] ?? false;
        $this->pageName = 'Share';
    }

    public function display():string
    {
        ob_start();
        
        include __DIR__ . '/../Common/Components/Header.php';
        include 'Components/Share.php';
        include __DIR__ . '/../Common/Components/Footer.php';
        
        return (string)ob_get_clean();

    }
}