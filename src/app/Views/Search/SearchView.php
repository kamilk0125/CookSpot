<?php

declare(strict_types=1);

namespace App\Views\Search;

use App\Interfaces\ViewInterface;
use App\Views\Common\View;

class SearchView extends View implements ViewInterface
{
    private string $cssFile = 'search.css';

    public function __construct(private string $searchKeyword, private array $searchResults)
    {
        $this->pageName = 'Search';
    }

    public function display():string
    {
        ob_start();
        
        include __DIR__ . '/../Common/Components/Header.php';
        include 'Components/Search.php';
        include __DIR__ . '/../Common/Components/Footer.php';
        
        return (string)ob_get_clean();

    }
}