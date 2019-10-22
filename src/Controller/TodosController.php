<?php

namespace App\Controller;

use App\Controller\AppController;
use App\Model\MongoDB\Businesses;
use App\Model\MongoDB\BusinessTodos;
use App\Services\Util;
use Cake\Http\Exception\NotAcceptableException;
use MongoDB\BSON\ObjectId;

class TodosController extends AppController
{
    /** @var BusinessTodos */
    private $businessTodosModel;

    public function initialize()
    {
        parent::initialize();
        $this->businessTodosModel = new BusinessTodos();
    }


    public function saveBusinessTask()
    {
        if ($this->request->is('post')) {
            $postData = $this->getRequest()->getData();
            $result   = $this->businessTodosModel->saveTodo($postData, $this->getUser());

            return $this->jsonSuccessResponse([], ['save_id' => $result->getInsertedId()]);
        }

        return $this->jsonErrorResponse(new NotAcceptableException('No data sent'));
    }

    public function getBusinessTodos($id)
    {
        try {
            $todos = $this->businessTodosModel->find(['business_id' => $id], ['sort' => ['created' => -1],]);

            return $this->jsonSuccessResponse($todos->toArray());
        } catch (\Exception $e) {
            return $this->jsonErrorResponse($e);
        }
    }

    public function saveBusinessUpdate()
    {
        if ($this->request->is('post')) {
            $postData = $this->getRequest()->getData();
            $result   = $this->businessTodosModel->saveTodo($postData, $this->getUser());

            return $this->jsonSuccessResponse([], ['modified' => $result->getModifiedCount()]);
        }

        return $this->jsonErrorResponse(new NotAcceptableException('No data sent'));
    }

    public function deleteBusinessTodo($id)
    {
        if ($this->request->is(['post', 'delete'])) {
            $businessTodosModel = new Businesses();

            $result = $this->businessTodosModel->deleteOne([
                'business_id' => $id,
                '_id' => Util::getMongoIdFromData($this->request->getData('_id'))
            ]);

            return $this->jsonSuccessResponse([], ['delete_count' => $result->getDeletedCount()]);
        }

        return $this->jsonErrorResponse(new NotAcceptableException('No data sent'));
    }
}
