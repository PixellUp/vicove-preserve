<?php

namespace App\Parsers;

use App\Http\Http;

class CategoryPaginationParser
{
    protected int $numberOfPages = 0;
    private string $category_url;

    public function check(string $category_url): void
    {
        $page = Http::get($this->category_url = $category_url);


        $pages = (int)$page->filter('p.description b')->getNode(1)->textContent;

        $this->setNumberOfPages($pages);
    }

    /**
     * @param int $numberOfPages
     */
    private function setNumberOfPages(int $numberOfPages): void
    {
        $this->numberOfPages = $numberOfPages;
    }

    public function pages(): array
    {
        $pages = [];

        for ($i = 1; $i <= $this->getNumberOfPages(); $i++) {
            $pages[] = $this->category_url . '?page=' . $i;
        }

        return $pages;
    }

    /**
     * @return int
     */
    public function getNumberOfPages(): int
    {
        return $this->numberOfPages;
    }

}