<?php


namespace App\Services;


use Cake\I18n\Time;
use Exception;
use MongoDB\Client;
use MongoDB\DeleteResult;
use MongoDB\Driver\Cursor;
use MongoDB\Driver\Exception\RuntimeException as DriverRuntimeException;
use MongoDB\Exception\InvalidArgumentException;
use MongoDB\Exception\UnsupportedException;
use MongoDB\InsertOneResult;
use MongoDB\UpdateResult;

class MongoDbHandler
{

    /** @var string */
    private $dns;

    /** @var Client */
    private $client;

    /** @var string */
    private $databaseName;

    protected $collectionName = null;

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
     * @param  string|null  $collectionName
     * @param  string|null  $databaseName
     *
     * @return \MongoDB\Collection
     * @throws Exception
     */
    public function getCollection($collectionName = null, $databaseName = null)
    {
        $collectionName = $collectionName ?: $this->collectionName;
        if (empty($collectionName)) {
            throw new Exception('No mongodb database name provided');
        }

        return $this->getDb($databaseName)->{$collectionName};
    }

    /**
     * Finds documents matching the query.
     *
     * @param  array|object  $filter  Query by which to filter documents
     * @param  array  $options  Additional options
     *
     * @return Cursor
     * @throws Exception
     * @see Find::__construct() for supported options
     * @see http://docs.mongodb.org/manual/core/read-operations-introduction/
     */
    public function find($filter = [], array $options = [])
    {
        return $this->getCollection()->find($filter, $options);
    }

    /**
     * Finds a single document matching the query.
     *
     * @param  array|object  $filter  Query by which to filter documents
     * @param  array  $options  Additional options
     *
     * @return array|object|null
     * @throws Exception
     * @see FindOne::__construct() for supported options
     * @see http://docs.mongodb.org/manual/core/read-operations-introduction/
     */
    public function findOne($filter = [], array $options = [])
    {
        return $this->getCollection()->findOne($filter, $options);
    }

    /**
     * Inserts one document.
     *
     * @param  array|object  $document  The document to insert
     * @param  array  $options  Command options
     *
     * @return InsertOneResult
     * @throws Exception
     * @see http://docs.mongodb.org/manual/reference/command/insert/
     * @see InsertOne::__construct() for supported options
     */
    public function insertOne($document, array $options = [])
    {
        return $this->getCollection()->insertOne($document, $options);
    }

    /**
     * Updates at most one document matching the filter.
     *
     * @param  array|object  $filter  Query by which to filter documents
     * @param  array|object  $update  Update to apply to the matched document
     * @param  array  $options  Command options
     *
     * @return UpdateResult
     * @throws Exception
     * @see http://docs.mongodb.org/manual/reference/command/update/
     * @see UpdateOne::__construct() for supported options
     */
    public function updateOne($filter, $update, array $options = [])
    {
        return $this->getCollection()->updateOne($filter, $update, $options);
    }

    /**
     * Deletes at most one document matching the filter.
     *
     * @param  array|object  $filter  Query by which to delete documents
     * @param  array  $options  Command options
     *
     * @return DeleteResult
     * @throws Exception
     * @see DeleteOne::__construct() for supported options
     * @see http://docs.mongodb.org/manual/reference/command/delete/
     */
    public function deleteOne($filter, array $options = [])
    {
        return $this->getCollection()->deleteOne($filter, $options);
    }

    /**
     * Deletes all documents matching the filter.
     *
     * @param  array|object  $filter  Query by which to delete documents
     * @param  array  $options  Command options
     *
     * @return DeleteResult
     * @throws Exception
     * @see DeleteMany::__construct() for supported options
     * @see http://docs.mongodb.org/manual/reference/command/delete/
     */
    public function deleteMany($filter, array $options = [])
    {
        return $this->getCollection()->deleteMany($filter, $options);
    }

    public function getNowIsoFormat()
    {
        $time = new Time();

        return $time->toIso8601String();
    }

    /******************** Getters and Setters ***************************************?
     * /**
     * @return string
     */
    public function getDns()
    {
        return $this->dns;
    }

    /**
     * @return Client
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * @return string
     */
    public function getDatabaseName()
    {
        return $this->databaseName;
    }

    /**
     * @return null
     */
    public function getCollectionName()
    {
        return $this->collectionName;
    }

}
