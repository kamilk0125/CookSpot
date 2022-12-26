<?php

declare(strict_types=1);

namespace App\Interfaces;

use App\Main\Routing\Request;

interface ControllerInterface{
    
    public function processRequest(Request $request);
}