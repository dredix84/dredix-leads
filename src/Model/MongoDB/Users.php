<?php


namespace App\Model\MongoDB;


use App\Services\MongoDbHandler;

class Users extends MongoDbHandler
{
    protected $collectionName = 'users';

    public function getUserByEmail($email)
    {

        return $this->findOne([
            'email' => trim($email)
        ]);
    }
}
