<?php

declare(strict_types=1);

namespace App\Controllers;
use App\Interfaces\ControllerInterface;
use App\Main\Routing\Request;

class HomeController implements ControllerInterface
{
    public function processRequest(Request $request)
    {
        return $this->redirect('profile');
    }

    private function redirect(string $location){
        return "<script>location.href='/{$location}';</script>";
    }


}