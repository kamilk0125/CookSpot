<?php

declare(strict_types=1);

namespace App\Views\Friends;

use App\Interfaces\ViewInterface;
use App\Models\Friends\FriendsManager;
use App\Views\Common\View;

class FriendsView extends View implements ViewInterface
{
    private string $cssFile = 'friends.css';
    private array $friendsList;
    private array $receivedInvitations;

    public function __construct(array $modelData)
    {
        $this->friendsList = $modelData['friendsData']['friendsList'];
        $this->receivedInvitations = $modelData['friendsData']['receivedInvitations'];
        $this->pageName = 'Friends';
    }

    public function display():string
    {
        ob_start();
        
        include __DIR__ . '/../Common/Components/Header.php';
        include 'Components/Friends.php';
        include __DIR__ . '/../Common/Components/Footer.php';
        
        return (string)ob_get_clean();

    }
}