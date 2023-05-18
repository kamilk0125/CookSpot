<?php

declare(strict_types=1);

namespace App\Model;

class Invitation
{
    public int $id;
    public int $senderId;
    public int $receiverId;
    public string $picturePath;
    public string $displayName;
}