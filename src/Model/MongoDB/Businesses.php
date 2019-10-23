<?php

namespace App\Model\MongoDB;

use App\Services\MongoDbHandler;

class Businesses extends MongoDbHandler
{
    protected $collectionName = 'business';
}
