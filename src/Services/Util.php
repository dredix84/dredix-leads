<?php


namespace App\Services;


use Cake\I18n\Time;
use MongoDB\BSON\ObjectId;

class Util
{
    public static function getNowMongoDb()
    {
        $date = new Time();

        return $date->toIso8601String();
    }

    public static function getIdFromMongoId($value)
    {
        if (is_string($value)) {
            return $value;
        } elseif (is_array($value) && isset($value['$oid'])) {
            return $value['$oid'];
        } elseif ($value instanceof ObjectId) {
            return (string)$value;
        }

        return $value;
    }


    public static function getMongoIdFromData($data)
    {
        $idIdentifier = null;

        if (is_array($data)) {
            if (isset($data['_id']) && isset($data['_id']['$oid'])) {
                return new ObjectId($data['_id']['$oid']);
            } elseif (isset($data['$oid'])) {
                return new ObjectId($data['$oid']);
            }
        } elseif (is_string($data['_id'])) {
            return new ObjectId($data['_id']);
        }

        return null;
    }
}
