<?php declare(strict_types=1);

use Aws\Sqs\SqsClient;

require __DIR__ . '/vendor/autoload.php';

return static function ($event) {

    $client = new SqsClient([
        'version' => '2012-11-05',
        'region'  => env('AWS_DEFAULT_REGION'),
    ]);

    $categories_path = __DIR__ . '/data/categories.json';

    if (!file_exists($categories_path)) {
        throw new \RuntimeException('Categories file not found');
    }

    $categories = json_decode(file_get_contents($categories_path), flags: JSON_THROW_ON_ERROR);

    foreach ($categories as $category){
        $client->sendMessage([
            'MessageBody' => serialize($category),
            'QueueUrl' => env('CATEGORISES_PAGINATION_QUEUE_URL'),
        ]);
    }

    // indicate that the job is done in the queue
    return true;
};
