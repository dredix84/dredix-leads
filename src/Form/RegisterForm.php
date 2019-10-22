<?php


namespace App\Form;

use App\Model\MongoDB\Users;
use Cake\Auth\DefaultPasswordHasher;
use Cake\Form\Form;
use Cake\Form\Schema;
use Cake\I18n\Time;
use Cake\Validation\Validator;

class RegisterForm extends Form
{

    protected function _buildSchema(Schema $schema)
    {
        return $schema->addField('full_name', 'string')
                      ->addField('email', ['type' => 'string'])
                      ->addField('password', ['type' => 'text']);
    }

    public function validationDefault(Validator $validator)
    {
        $validator
            ->add('full_name', 'length', [
                'rule'    => ['minLength', 5],
                'message' => 'A name is required'
            ])->add('email', 'format', [
                'rule'    => 'email',
                'message' => 'A valid email address is required',
            ])
            ->add('password', 'length', [
                'rule'    => ['minLength', 5],
                'message' => 'password is required with a minimum length of 5'
            ])
            ->requirePresence('confirm_password')
            ->requirePresence('i_agree_to_terms')
            ->add(
                'i_agree_to_terms',
                'comparison', [
                'rule'    => function ($value, $context) {
                    return intval($value) === intval($context['data']['i_agree_to_terms']);
                },
                'message' => 'Small size cannot be bigger than Big size.'
            ]);

        return $validator;
    }

    protected function _execute(array $data)
    {
        $userModel                = new Users();
        $created                  = new Time();
        $data['password']         = (new DefaultPasswordHasher())->hash($data['password']);
        $data['i_agree_to_terms'] = true;
        $data['is_active']        = false;
        $data['created']          = $created->toIso8601String();
        $data['modified']         = $created->toIso8601String();
        unset($data['confirm_password']);

        return $userModel->insertOne($data);
    }
}
