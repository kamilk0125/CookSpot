<?php

declare(strict_types=1);
namespace App\Views\Home;

use App\Interfaces\ViewInterface;
use App\Views\Common\View;

class HomeView extends View implements ViewInterface
{
    public function __construct()
    {
        $this->pageName = 'Home';
    }
    public function display():string
    {
        ob_start();
        
        include __DIR__ . '/../Common/Components/Header.php';
        include 'Components/Home.php';
        include __DIR__ . '/../Common/Components/Footer.php';
        
        return (string)ob_get_clean();

    }
}