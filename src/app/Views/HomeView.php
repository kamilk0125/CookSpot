<?php

declare(strict_types=1);
namespace App\Views;

use App\Interfaces\ViewInterface;

class HomeView extends View implements ViewInterface
{
    public function __construct()
    {
        $this->pageName = 'Home';
    }
    public function display():string
    {
        ob_start();
        
        include 'Components/Header.php';
        include 'Components/Home.php';
        include 'Components/Footer.php';
        
        return (string)ob_get_clean();

    }
}