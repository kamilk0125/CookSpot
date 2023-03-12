<?php

declare(strict_types=1);

namespace App\Interfaces;

interface ManagerInterface{
    public function processForm(array $form, ?array $files);
}