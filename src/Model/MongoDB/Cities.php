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
}
