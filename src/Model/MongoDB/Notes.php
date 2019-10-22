<?php


namespace App\Model\MongoDB;


use App\Services\MongoDbHandler;
use App\Services\Util;

class Notes extends MongoDbHandler
{
    protected $collectionName = 'business_notes';


    public function saveNotes($postData, $user)
    {
        $result = null;
        if (isset($postData['id']) && ! empty($postData['id'])) {
            //Do something
        } else {
            $result = $this->insertOne([
                'user_id'     => Util::getIdFromMongoId($user->_id),
                'business_id' => Util::getIdFromMongoId($postData['business_id']),
                'note_text'   => $postData['note_text'],
                'created'     => Util::getNowMongoDb(),
                'modified'    => Util::getNowMongoDb()
            ]);

            return $result;
        }
    }
}
