<?php

declare(strict_types=1);

namespace App\Views\Profile;

use App\Interfaces\ViewInterface;
use App\Models\Profile\ProfileManager;
use App\Views\Common\View;

class ProfileView extends View implements ViewInterface
{
    private string $cssFile = 'profile.css';
    private array $profileData;
    private array $friendsForm;

    public function __construct(array $modelData)
    {
        $this->profileData = $modelData['profileData'];
        $this->pageName = $this->profileData['profileInfo']['displayName'];

        if($this->profileData['publicProfile']){
            $this->friendsForm['btnType'] = 'submit';
            $relation = $this->profileData['relation'];
            switch($relation['status']){
                case 'friend':
                    $this->friendsForm['btnText'] = '✓ Friends';
                    $this->friendsForm['btnClass'] = 'disabled';
                    break;
                case 'invitationReceived':
                    $this->friendsForm['action'] = 'answerInvitation';
                    $this->friendsForm['btnText'] = 'Accept Invitation';
                    $this->friendsForm['btnName'] = 'args[response]';
                    $this->friendsForm['btnValue'] = '1';
                    $this->friendsForm['args'] = ['args[invitationId]' => $relation['invitationId']];
                    break;
                case 'invitationSent':
                    $this->friendsForm['btnText'] = '✓ Invitation Sent';
                    $this->friendsForm['btnClass']  = 'disabled';
                    break;
                default:
                    $this->friendsForm['action'] = 'newInvitation';
                    $this->friendsForm['btnText'] = 'Add to friends';
                    $this->friendsForm['btnName'] = 'args[friendId]';
                    $this->friendsForm['btnValue'] = $this->profileData['profileInfo']['id'];
            }
        }
        else
            $this->friendsForm['btnText'] = 'Friends';
    }

    public function display():string
    {
        ob_start();
        
        include __DIR__ . '/../Common/Components/Header.php';
        include 'Components/Profile.php';
        include __DIR__ . '/../Common/Components/Footer.php';
        
        return (string)ob_get_clean();

    }
}