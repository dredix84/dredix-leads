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

    /**
     * Main business page
     * @throws \Exception
     */
    public function index()
    {
        $this->setTitle('Business Search');
        $citiesModel = new Cities();
        $cities      = $citiesModel->getList();
        $this->set(compact('cities'));
    }

    /**
     * Page for starred businesses
     * @throws \Exception
     */
    public function starred()
    {
        $this->setTitle('Starred Business Search');
        $citiesModel = new Cities();
        $cities      = $citiesModel->getList();
        $this->set(compact('cities') + ['starred' => true]);
        $this->render('index');
    }

    /**
     * Displays business details
     * @param $id
     *
     * @throws \Exception
     */
    public function business($id)
    {
        $businessModel = new Businesses();
        $business      = $businessModel->findById($id);

        $this->setTitle('Business Details', $business->company_name);
        $this->set(compact('business'));
        $this->set(['businessId' => $id]);
    }

    /**
     * Queries the database for businesses matching the search criteria
     * @return \Cake\Http\Response
     * @throws \Exception
     */
    public function getBusinesses()
    {
        $searchData = $this->request->getQueryParams();
        $businessModel = new Businesses();

        $conditions = $businessModel->getFilterFromPostData($searchData);

        if (count($conditions)) {
            $businesses    = $businessModel->find($conditions, ['limit' => 200]);

            return $this->jsonSuccessResponse($businesses->toArray(), ['search' => $conditions]);
        }

        return $this->jsonErrorResponse((new NotAcceptableException('No query parameters provided')));
    }

    /**
     * Used to toggle the Starred status of a business
     * @param $id
     *
     * @return \Cake\Http\Response
     * @throws \Exception
     */
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

    /**
     * Used to manage ajax inline edits
     * @return \Cake\Http\Response
     * @throws \Exception
     */
    public function inlineEdit()
    {
        if ($this->request->is('post')) {
            $postData = $this->request->getData();
            if (isset($postData['key']) && isset($postData['field']) && isset($postData['value'])) {
                $businessTodosModel = new Businesses();
                $result             = $businessTodosModel->updateField(
                    new ObjectId($postData['key']),
                    $postData['field'],
                    $postData['value']
                );

                return $this->jsonSuccessResponse([], ['modified' => $result->getModifiedCount()]);
            }
        }

        return $this->jsonErrorResponse(new NotAcceptableException('No data sent'));
    }
}
