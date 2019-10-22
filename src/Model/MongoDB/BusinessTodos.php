<?php


namespace App\Model\MongoDB;


use App\Services\MongoDbHandler;
use App\Services\Util;

class BusinessTodos extends MongoDbHandler
{
    protected $collectionName = 'business_todos';

    public function saveTodo($postData, $user)
    {
        $result = null;
        $id     = Util::getMongoIdFromData($postData);

        if ($id) {
            $result = $this->updateOne(
                [
                    '_id' => $id
                ],
                [
                    '$set' =>
                        [
                            'description'  => $postData['description'],
                            'completed'    => $postData['completed'],
                            'completed_on' => Util::getNowMongoDb(),
                            'modified'     => Util::getNowMongoDb()
                        ]
                ]
            );

            return $result;
        } else {
            $result = $this->insertOne([
                'user_id'      => Util::getIdFromMongoId($user->_id),
                'business_id'  => Util::getIdFromMongoId($postData['business_id']),
                'description'  => $postData['description'],
                'completed'    => false,
                'completed_on' => null,
                'created'      => Util::getNowMongoDb(),
                'modified'     => Util::getNowMongoDb()
            ]);

            return $result;
        }
    }
}
