<?php

namespace App\Http\Controllers;

use App\Models\Vicove;

class ApiController
{
    public function index(): void
    {
        // set HTTP header to JSON
        header('Content-Type: application/json');

        echo json_encode((new Vicove())->all(), JSON_UNESCAPED_UNICODE);
    }

    public function categories(): void
    {
        $categories_path = __DIR__ . '/../../../data/categories.json';

        if (!file_exists($categories_path)) {
            throw new \RuntimeException('Categories file not found');
        }

        $categories = json_decode(file_get_contents($categories_path), flags: JSON_UNESCAPED_UNICODE);

        // set HTTP header to JSON
        header('Content-Type: application/json');

        echo json_encode($categories, JSON_UNESCAPED_UNICODE);
    }

    public function category(string $category)
    {
        $vicove = new Vicove();
        $jokes = $vicove->findByCategory(mb_ucfirst($category));

        // set HTTP header to JSON
        header('Content-Type: application/json');
        echo json_encode($jokes, JSON_UNESCAPED_UNICODE);
    }
}
