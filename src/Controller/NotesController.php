<?php

namespace App\Controller;

use App\Controller\AppController;
use App\Model\MongoDB\Notes;
use Cake\Http\Exception\NotAcceptableException;
use Cake\Http\Response;
use Cake\Http\ServerRequest;

class NotesController extends AppController
{
    /** @var Notes */
    private $businessNotesModel;

    public function initialize()
    {
        parent::initialize();
        $this->businessNotesModel = new Notes();
    }

    public function saveBusinessNote()
    {
        if ($this->request->is('post')) {
            $postData = $this->getRequest()->getData();
            $result   = $this->businessNotesModel->saveNotes($postData, $this->getUser());

            return $this->jsonSuccessResponse(['save_id' => $result->getInsertedId()]);
        }

        return $this->jsonErrorResponse(new NotAcceptableException('No data sent'));
    }

    public function getBusinessNotes($id)
    {
        try {
            $notes = $this->businessNotesModel->find(['business_id' => $id], ['sort' => ['created' => -1],]);

            return $this->jsonSuccessResponse($notes->toArray());
        } catch (\Exception $e) {
            return $this->jsonErrorResponse($e);
        }
    }
}
