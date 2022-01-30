<?php

namespace App\Parsers;

use App\Http\Http;

class OnPageVicoveParser
{
    public function parse(string $page_url): array
    {
        $page = Http::get($page_url);

        return $page->filter('p.category-article')->each(function ($item) {
            return $item->html();
        });

    }

}