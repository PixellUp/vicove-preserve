<?php

require __DIR__ . '/vendor/autoload.php';


use App\Models\Vicove;
use App\Parsers\OnPageVicoveParser;

return static function ($event) {


    foreach ($event['Records'] as $record) {
        $body = $record['body'];

        $category = unserialize($body);

        $parser = new OnPageVicoveParser();

        $jokes = $parser->parse($category->url);

        foreach ($jokes as $joke) {

            $vicove = new Vicove();

            $vicove->id = md5($joke);
            $vicove->joke = $joke;
            $vicove->url = str_before($category->url,'?');
            $vicove->title_bg = $category->title_bg;
            $vicove->title_en = $category->title_en;

            $vicove->save();
        }
    }
};
