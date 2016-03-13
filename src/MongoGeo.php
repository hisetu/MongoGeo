<?php
namespace hisetu\MongoGeo;

use Exception;
use MongoClient;

class MongoGeo
{
    /**
     * @var MongoClient
     */
    private $client;
    private $collection;

    public function __construct(MongoClient $client, $db, $collection)
    {
        $this->client = $client;
        $this->collection = $client->selectCollection($db, $collection);
        $this->collection->createIndex(['loc' => '2dsphere']);
    }

    public function createPolygon($name, $category, array $coordinates)
    {
        if (!$this->isPolygonValid($coordinates))
            throw new Exception('Polygon format invalid.');

        $this->collection->update(
            ['name' => $name],
            [
                'loc' => [
                    'type' => 'Polygon',
                    'coordinates' => $coordinates
                ],
                'name' => $name,
                'category' => $category
            ],
            ['upsert' => true]);
    }

    public function near($lng, $lat, $maxDistance = 0)
    {
        $mongoCursor = $this->collection->find([
            'loc' => [
                '$near' => [
                    '$geometry' => [
                        'type' => 'Point',
                        'coordinates' => [(double)$lng, (double)$lat]
                    ],
                    '$maxDistance' => $maxDistance
                ]
            ]
        ]);
        return iterator_to_array($mongoCursor, false);
    }

    /**
     * @param array $coordinates
     * @return bool
     */
    private function isPolygonValid(array $coordinates)
    {
        $startPoint = $coordinates[0];
        $endPoint = $coordinates[count($coordinates) - 1];
        if ($startPoint[0] == $endPoint[0] && $startPoint[1] == $endPoint[1])
            return true;
        return false;
    }
}