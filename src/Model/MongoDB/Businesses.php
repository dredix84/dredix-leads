<?php

namespace App\Model\MongoDB;

use App\Services\MongoDbHandler;
use MongoDB\BSON\Regex;

class Businesses extends MongoDbHandler
{
    protected $collectionName = 'business';


    public function getFilterFromPostData($postData)
    {
        $conditions = [];
        if (is_array($postData) && count($postData)) {
            if (isset($postData['city']) && ! empty($postData['city'])) {
                $conditions['city'] = new Regex('^'.$postData['city'], 'i');
            }
            if (isset($postData['type']) && ! empty($postData['type'])) {
                $conditions['naics_description'] = new Regex($postData['type'], 'i');
            }
            if (isset($postData['company_name']) && ! empty($postData['company_name'])) {
                $conditions['company_name'] = new Regex($postData['company_name'], 'i');
            }
            if (isset($postData['starred']) && ! empty($postData['starred'])) {
                $conditions['starred'] = true;
            }
            if (isset($postData['tags']) && ! empty($postData['tags'])) {
                $conditions['tags'] = [
                    '$in' => $postData['tags']
                ];
            }
            if (isset($postData['has_email']) && $postData['has_email'] !== null) {
                $conditions['email'] = [
                    '$exists' => true,
                    '$ne'     => null
                ];
            }
            if (isset($postData['has_website']) && $postData['has_website'] !== null) {
                $conditions['website'] = [
                    '$exists' => true,
                    '$ne'     => null
                ];
            }

            //Doing radius search
            if (isset($postData['radius_city']) && ! empty($postData['radius_city'])) {
                $city       = $postData['radius_city'];
                $kilometers = $postData['radius_kilometers'] ?: 20;

                $citiesModel = new Cities();
                $cities      = $citiesModel->findCitiesNear($city, $kilometers * 1000);
                $cityNames   = [];
                if ($cities) {
                    foreach ($cities as $city) {
                        $cityNames[] = new Regex('^'.$city->name, 'i');
                    }
                    $conditions['city'] = [
                        '$in' => $cityNames
                    ];
                }
            }
        }

        return $conditions;
    }
}
