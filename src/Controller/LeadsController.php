<?php


namespace App\Controller;


use App\Model\MongoDB\Businesses;
use App\Model\MongoDB\Cities;
use App\Services\MongoDbHandler;
use App\Services\Util;
use Cake\Http\Exception\NotAcceptableException;
use MongoDB\BSON\ObjectId;
use MongoDB\BSON\Regex;

class LeadsController extends AppController
{
    private $mongo;

    public function initialize()
    {
        parent::initialize();
        $this->mongo = new MongoDbHandler();
    }

    public function index()
    {
        $this->setTitle('Business Search');
        $citiesModel = new Cities();
        $cities      = $citiesModel->getList();
        $this->set(compact('cities'));
    }


    public function business($id)
    {

        $businessModel = new Businesses();
        $business      = $businessModel->findById($id);

        $this->setTitle('Business Details', $business->company_name);
        $this->set(compact('business'));
        $this->set(['businessId' => $id]);
    }


    public function getBusinesses()
    {
        $searchData = $this->request->getQueryParams();
        $conditions = [];
        if (isset($searchData['city']) && ! empty($searchData['city'])) {
            $conditions['city'] = new Regex('^'.$searchData['city'], 'i');
        }
        if (isset($searchData['company_name']) && ! empty($searchData['company_name'])) {
            $conditions['company_name'] = new Regex($searchData['company_name'], 'i');
        }

        if (count($conditions)) {
            $businessModel = new Businesses();
            $businesses    = $businessModel->find($conditions, ['limit' => 200]);

            return $this->jsonSuccessResponse($businesses->toArray(), ['search' => $conditions]);
        }

        return $this->jsonErrorResponse((new NotAcceptableException('No query parameters provided')));
    }

    public function toggleStarred($id)
    {
        if ($this->request->is('post')) {
            $starred = $this->request->getData('starred');

            if ($starred !== null) {
                $businessTodosModel = new Businesses();
                $result             = $businessTodosModel->updateOne(
                    ['_id' => new ObjectId($id)],
                    [
                        '$set' => compact('starred')
                    ]
                );

                return $this->jsonSuccessResponse([], ['modified' => $result->getModifiedCount()]);
            }

        }

        return $this->jsonErrorResponse(new NotAcceptableException('No data sent'));
    }
}
