<?php

declare(strict_types=1);

namespace App\Views\Profile;

use App\Interfaces\ViewInterface;
use App\Model\Profile;
use App\Model\Relation;
use App\Views\Common\View;

class ProfileView extends View implements ViewInterface
{
    private string $cssFile = 'profile.css';
    private bool $publicProfile;
    private array $friendsForm;

    public function __construct(private Profile $profile, private ?Relation $relation = null)
    {
        $this->publicProfile = !is_null($relation);
        $this->pageName = $this->profile->user->getUserData('displayName');

        if($this->publicProfile){
            $this->friendsForm['btnType'] = 'submit';
            switch($this->relation->status){
                case 'friend':
                    $this->friendsForm['friendsBtnText'] = '✓ Friends';
                    $this->friendsForm['friendsBtnClass'] = 'css-disabled';
                    $this->friendsForm['friendsBtnDisabled'] = true;
                    $this->friendsForm['deleteBtnValue'] = $this->profile->user->getUserData('id');
                    $this->friendsForm['deleteBtnVisible'] = true;
                    break;
                case 'invitationReceived':
                    $this->friendsForm['action'] = 'answerInvitation';
                    $this->friendsForm['friendsBtnText'] = 'Accept Invitation';
                    $this->friendsForm['friendsBtnName'] = 'args[response]';
                    $this->friendsForm['friendsBtnValue'] = '1';
                    $this->friendsForm['args'] = ['args[invitationId]' => $relation->invitationId];
                    $this->friendsForm['friendsBtnDisabled'] = false;
                    $this->friendsForm['deleteBtnVisible'] = false;
                    break;
                case 'invitationSent':
                    $this->friendsForm['friendsBtnText'] = '✓ Invitation Sent';
                    $this->friendsForm['friendsBtnClass']  = 'css-disabled';
                    $this->friendsForm['friendsBtnDisabled'] = true;
                    $this->friendsForm['deleteBtnVisible'] = false;
                    break;
                default:
                    $this->friendsForm['action'] = 'newInvitation';
                    $this->friendsForm['friendsBtnText'] = 'Add to friends';
                    $this->friendsForm['friendsBtnName'] = 'args[friendId]';
                    $this->friendsForm['friendsBtnValue'] = $this->profile->user->getUserData('id');
                    $this->friendsForm['friendsBtnDisabled'] = false;
                    $this->friendsForm['deleteBtnVisible'] = false;
            }
        }
        
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