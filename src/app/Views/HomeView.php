<?php

declare(strict_types=1);
namespace App\Views;

use App\Views\ViewInterface;

class HomeView extends View implements ViewInterface 
{
    public function display():string
    {
        $this->pageName = 'Home';

        ob_start();
        include 'Components/Header.php';
        include 'Components/Home.php';
        include 'Components/Footer.php';
        
        return (string)ob_get_clean();

    }
}