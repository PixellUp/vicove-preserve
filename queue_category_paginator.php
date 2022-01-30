<?php

require __DIR__ . '/vendor/autoload.php';


use App\Parsers\CategoryPaginationParser;
use Aws\Sqs\SqsClient;

return static function ($event) {
    $client = new SqsClient([
        'version' => '2012-11-05',
        'region'  => env('AWS_DEFAULT_REGION'),
    ]);

    foreach ($event['Records'] as $record) {
        $body = $record['body'];

        $category = unserialize($body);


        $parser = new CategoryPaginationParser();

        $parser->check($category->url);


        foreach ($parser->pages() as $page) {
            $category->url = $page;
            $client->sendMessage([
                'MessageBody' => serialize($category),
                'QueueUrl'    => env('VICOVE_QUEUE_URL'),
            ]);
        }
    }
};
