<?php


namespace App\Controller;


use App\Form\LoginForm;
use App\Form\RegisterForm;
use MongoDB\Model\BSONDocument;

class UsersController extends AppController
{

    public function initialize()
    {
        parent::initialize();
        $this->Auth->allow(['register', 'logout']);
    }

    public function login()
    {
        $this->setTitle('Login');
        if ($this->request->is('post')) {
            $loginForm = new LoginForm();
            $postData  = $this->request->getData();
            $isValid   = $loginForm->validate($postData);

            if ($isValid) {
                /** @var BSONDocument $user */
                $user = $loginForm->execute($postData);
                $this->Auth->setUser($user);
                $this->Flash->success('Welcome '.$user->full_name);

                return $this->redirect($this->Auth->redirectUrl());
            } else {
                $this->Flash->error(__('Username or password is incorrect'));
            }
        }
    }

    public function register()
    {
        if ($this->request->is('post')) {
            $registerData = $this->request->getData();

            $registerForm = new RegisterForm();
            $isValid      = $registerForm->validate($this->request->getData());

            if ($isValid) {
                $result = $registerForm->execute($registerData);
                $this->Flash->success('Your user account has been created.');

                return $this->redirect(['action' => 'login']);
            } else {
                $this->Flash->error('There was a problem while attempting to create your account.');
            }
        }
    }

    public function logout()
    {
        return $this->redirect($this->Auth->logout());
    }
}
