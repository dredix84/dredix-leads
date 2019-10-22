<?php

namespace App\Model\MongoDB;

use App\Services\MongoDbHandler;
use MongoDB\BSON\ObjectId;

class Businesses extends MongoDbHandler
{
    protected $collectionName = 'business';


    public function findById($id)
    {
        return $this->findOne([
            '_id' => new ObjectId($id)
        ]);
    }
}
