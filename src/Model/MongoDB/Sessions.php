<?php


namespace App\Model\MongoDB;


use App\Services\MongoDbHandler;

class Sessions extends MongoDbHandler
{
    protected $collectionName = 'sessions';

}
