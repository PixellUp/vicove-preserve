<?php

namespace App\Models;


use Aws\DynamoDb\DynamoDbClient;
use Aws\DynamoDb\Marshaler;

abstract class Model
{
    protected string $table;

    private DynamoDbClient $dynamodb;
    /**
     * @var Marshaler
     */
    private Marshaler $marshaler;

    public function __construct()
    {
        $this->dynamodb = new DynamoDbClient([
            'version' => '2012-08-10',
            'region'  => env('AWS_DEFAULT_REGION')
        ]);

        $this->marshaler = new Marshaler();

        if (!isset($this->table)) {
            throw new \RuntimeException('Model must have a table name');
        }
    }

    public function all(): array
    {
        $args = [
            'TableName' => $this->table,
            'Limit'     => (int)env('DYNAMODB_LIMIT', 10)
        ];

        if ($lastEvaluatedKey = $_REQUEST['cursor'] ?? null) {
            $args[] = [
                'ExclusiveStartKey' => [
                    'id' => [
                        'S' => $lastEvaluatedKey,
                    ],
                ]
            ];
        }

        $result = $this->dynamodb->scan($args);

        $items = $result->get('Items');
        $lastEvaluatedKey = $result->get('LastEvaluatedKey');

        foreach ($items as &$item) {
            $item = $this->marshaler->unmarshalItem($item);
        }

        return [
            'items'  => $items,
            'cursor' => $this->marshaler->unmarshalItem($lastEvaluatedKey)['id'] ?? null
        ];
    }

    public function findByCategory(string $category): array
    {
        // LocalSecondaryIndexes is title_bg_index
        $args = [
            'TableName'                 => $this->table,
            'IndexName'                 => 'title_bg_index',
            'KeyConditionExpression'    => 'title_bg = :title_bg',
            'ExpressionAttributeValues' => [
                ':title_bg' => [
                    'S' => $category,
                ],
            ],
            'Limit'                     => (int)env('DYNAMODB_LIMIT', 10)
        ];

        if ($lastEvaluatedKey = $_REQUEST['cursor'] ?? null) {
            $args['ExclusiveStartKey'] = [
                'id' => [
                    'S' => $lastEvaluatedKey,
                ],
            ];
        }

        $result = $this->dynamodb->query($args);

        $items = $result->get('Items');
        $lastEvaluatedKey = $result->get('LastEvaluatedKey');

        foreach ($items as &$item) {
            $item = $this->marshaler->unmarshalItem($item);
        }

        return [
            'items'  => $items,
            'cursor' => $this->marshaler->unmarshalItem($lastEvaluatedKey ?? [])['id'] ?? null
        ];
    }

    /**
     * @param string $id
     *
     * @return Model
     * @throws \RuntimeException
     */
    public function find(string $id)
    {
        $this->printClassAction('Finding item with id: ' . $id);

        $result = $this->dynamodb->getItem([
            'TableName' => $this->table,
            'Key'       => $this->marshaler->marshalItem([
                'id' => $id
            ])
        ])->get('Item');

        if (!$result) {
            throw new \RuntimeException('Item not found');
        }

        $class = "App\Models\\" . class_basename($this);
        $model = new $class;

        foreach ((object)$this->marshaler->unmarshalItem($result) as $key => $value) {
            $model->$key = $value;
        }

        return $model;
    }

    private function printClassAction(string $action): void
    {
        echo sprintf('[%s model] %s', class_basename($this), $action) . PHP_EOL;
    }

    public function delete(string $id): void
    {
        $this->printClassAction('Deleting item with id: ' . $id);

        $this->dynamodb->deleteItem([
            'TableName'                   => $this->table,
            'Key'                         => $this->marshaler->marshalItem([
                'id' => $id
            ]),
            'ReturnItemCollectionMetrics' => 'SIZE'
        ]);

    }

    public function save(): void
    {
        $this->printClassAction('Saving');

        $reflection = new \ReflectionClass(__CLASS__);

        $variables = $reflection->getProperties();

        $internal_variables = array_map(function ($item) {
            return $item->name;
        }, $variables);


        $model_data = [];

        foreach ($this as $key => $value) {
            if (in_array($key, $internal_variables, true)) {
                continue;
            }

            $model_data[$key] = $value;
        }

        if (!isset($model_data['id'])) {
            throw new \RuntimeException('Model id is not set');
        }

        if (!isset($model_data['created_at'])) {
            $model_data['created_at'] = now();
        }

        $this->dynamodb->putItem([
            'TableName' => $this->table,
            'Item'      => $this->marshaler->marshalItem($model_data)
        ]);

        $this->printClassAction('Saved');
    }

}