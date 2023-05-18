<?php

declare(strict_types=1);

namespace App\Util;

use App\Addons\DataHandling\DataHandler;
use App\Attributes\FormHandler;
use App\Model\Form;

abstract class Manager{

    public function processForm(array $formData, ?array $formFiles):Form
    {   
        $handler = $formData['handler'];
        $action = $formData['action'];
        $data = array_merge($formData['args'], $formFiles ?? []);
        if(method_exists($this->{$handler}, $action)){
            $isFormHandler = DataHandler::hasAttribute($this->{$handler}, $action, FormHandler::class);
            if($isFormHandler){
                $args = DataHandler::mapMethodArgs($this->{$handler}, $action, $data);
                if(!is_null($args))
                    $form = $this->{$handler}->{$action}(...$args);
                    $form->inputData = $formData;
            }
        }

        if(!isset($form)){
            $form = new Form();
            $form->inputData = $formData;
        }

        return $form;
    }
}