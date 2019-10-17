<?php

namespace App\Controller;

use App\Controller\AppController;
use MongoDB\BSON\Regex;
use MongoDB\Client;
use MongoDB\Collection;
use MongoDB\Driver\Cursor;
use MongoDB\Model\BSONDocument;

/**
 * Test Controller
 *
 *
 * @method \App\Model\Entity\Test[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class TestController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null
     */
    public function index()
    {
//        $UsersTable = $this->loadModel('Users');
//        $users      = $UsersTable->find('all', ['conditions' => ['name' => 'teste']])->first();

//        $test = $this->paginate($this->Test);

//        $this->set(compact('users'));

        $client = new Client(
            'mongodb+srv://andre:S2xN2cBWhPJSNZr@cluster0-axu7y.mongodb.net/test?retryWrites=true&w=majority'
        );

        /** @var Collection $collection */
        $collection = ($client)->leads_helper->business;
        var_dump($collection);

        /** @var Cursor */
        $businesses = $collection->find([
            'city' => new Regex('^Petersburg', 'i'),
        ]);
        var_dump($businesses->toArray());

//        $collection = ($client)->test->users;
//
//        $insertOneResult = $collection->insertOne([
//            'username' => 'admin',
//            'email' => 'admin@example.com',
//            'name' => 'Admin User',
//        ]);
//
//        printf("Inserted %d document(s)\n", $insertOneResult->getInsertedCount());
//
//        var_dump($insertOneResult->getInsertedId());
    }

    /**
     * View method
     *
     * @param  string|null  $id  Test id.
     *
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $test = $this->Test->get($id, [
            'contain' => []
        ]);

        $this->set('test', $test);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $test = $this->Test->newEntity();
        if ($this->request->is('post')) {
            $test = $this->Test->patchEntity($test, $this->request->getData());
            if ($this->Test->save($test)) {
                $this->Flash->success(__('The test has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The test could not be saved. Please, try again.'));
        }
        $this->set(compact('test'));
    }

    /**
     * Edit method
     *
     * @param  string|null  $id  Test id.
     *
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $test = $this->Test->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $test = $this->Test->patchEntity($test, $this->request->getData());
            if ($this->Test->save($test)) {
                $this->Flash->success(__('The test has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The test could not be saved. Please, try again.'));
        }
        $this->set(compact('test'));
    }

    /**
     * Delete method
     *
     * @param  string|null  $id  Test id.
     *
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $test = $this->Test->get($id);
        if ($this->Test->delete($test)) {
            $this->Flash->success(__('The test has been deleted.'));
        } else {
            $this->Flash->error(__('The test could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
