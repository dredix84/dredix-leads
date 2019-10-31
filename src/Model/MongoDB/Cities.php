<?php


namespace App\Model\MongoDB;


use App\Services\MongoDbHandler;
use MongoDB\Model\BSONDocument;

class Cities extends MongoDbHandler
{
    protected $collectionName = 'business_cities';

    public function getList()
    {
        $outData = [];
        $cities  = $this->getCollection($this->collectionName)->find();
        foreach ($cities->toArray() as $city) {
            /** @var BSONDocument $city */
            if ( ! empty($city->name)) {
                $outData[$city->name] = $city->name;
            }
        }

        return $outData;
    }

    /**
     * Performs radius search for all cities close to the named city
     * @param $cityName
     * @param $radiusKm
     *
     * @return array|\MongoDB\Driver\Cursor
     * @throws \Exception
     */
    public function findCitiesNear($cityName, $radiusKm)
    {
        if ($dbCity = $this->findOne(['name' => $cityName])) {
            $citiesConditions                 = [];
            $citiesConditions['geo_location'] = [
                '$nearSphere' => [
                    '$geometry'    => [
                        'type'        => 'Point',
                        'coordinates' => (array)$dbCity->geo_location->coordinates
                    ],
                    '$maxDistance' => $radiusKm
                ]
            ];

            $results = $this->find($citiesConditions);

            return $results;
        }

        return [];
    }

}
