<?php


namespace App\Form;

use App\Model\MongoDB\Users;
use Cake\Auth\DefaultPasswordHasher;
use Cake\Event\EventManager;
use Cake\Form\Form;
use Cake\Form\Schema;
use Cake\Http\Session;
use Cake\I18n\Time;
use Cake\Validation\Validator;
use MongoDB\Model\BSONDocument;

class LoginForm extends Form
{

    private $userModel;

    public function __construct(EventManager $eventManager = null)
    {
        parent::__construct($eventManager);
        $this->userModel = new Users();
    }

    protected function _buildSchema(Schema $schema)
    {
        return $schema
            ->addField('email', ['type' => 'string'])
            ->addField('password', ['type' => 'text']);
    }


    public function validationDefault(Validator $validator)
    {
        $validator
            ->add('email', 'format', [
                'rule'    => 'email',
                'message' => 'A valid email address is required',
            ])
            ->add('password', 'length', [
                'rule'    => ['minLength', 5],
                'message' => 'password is required with a minimum length of 5'
            ])
            ->add('password', 'custom', [
                'rule'    => function ($value, $context) {
                    $user = $this->userModel->getUserByEmail($context['data']['email']);
                    if ($user) {
                        if ((new DefaultPasswordHasher)->check($value, $user->password)) {
                            return true;
                        }
                    }

                    return false;
                },
                'message' => 'The email and password is not valid.',
            ]);

        return $validator;
    }

    protected function _execute(array $data)
    {
        $userModel = new Users();
        $date      = new Time();

        /** @var BSONDocument $user */
        $user = $this->userModel->getUserByEmail($data['email']);


        if ($user) {
            $this->userModel->updateOne(
                ['_id' => $user->_id],
                ['$set' => ['last_login' => $date->toIso8601String()]]
            );

            return $user;
        }

        return null;
    }
}
