<?php


namespace App\Services;


use Exception;
use MongoDB\Client;

class MongoDbHandler
{

    /** @var string */
    private $dns;

    /** @var Client */
    private $client;

    /** @var string */
    private $databaseName;

    /**
     * MongoDbHandler constructor.
     *
     * @param  string|null  $databaseName
     * @param  string|null  $dns
     *
     * @throws Exception
     */
    public function __construct($databaseName = null, $dns = null)
    {
        $this->databaseName = $databaseName ? $databaseName : env('MONGODB_DATABASE');
        if ($this->databaseName === null) {
            throw new Exception('No mongodb database name provided');
        }

        $this->dns = $dns ? $dns : env('MONGODB_DNS');
        if ($this->dns === null) {
            throw new Exception('MongoDB Connection string is null');
        }

        $this->client = new Client($this->dns);
    }

    /**
     * @param  null  $databaseName
     *
     * @return \MongoDB\Database
     */
    public function getDb($databaseName = null)
    {
        $databaseName = $databaseName ?: $this->databaseName;

        return ($this->client)->{$databaseName};
    }

    /**
     * @param $collectionName
     * @param  null  $databaseName
     *
     * @return \MongoDB\Collection
     */
    public function getCollection($collectionName, $databaseName = null)
    {
        return $this->getDb($databaseName)->{$collectionName};
    }
}
