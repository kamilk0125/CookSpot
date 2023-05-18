<?php

declare(strict_types=1);

namespace App\Model;

class SharedItem
{
    public int $id;
    public int $ownerId;
    public string $ownerName;
    public $content;
}