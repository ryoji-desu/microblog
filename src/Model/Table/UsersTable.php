<?php

namespace App\Model\Table;

use Cake\ORM\Table;
// use Cake\ORM\RulesChecker;
use Cake\Validation\Validator;

class UsersTable extends Table
{
    public function initialize(array $config)
    {
        $this->addBehavior('Timestamp');
    }
    public function validationDefault(Validator $validator)
    {
        // $validator
        //   ->notEmpty('username')
        //   ->requirePresence('username')
        //   ->lengthBetween('username', [4, 8])
        //   ->notEmpty('email')
        //   ->requirePresence('email')
        //   ->email('email')
        //   ->notEmpty('password')
        //   ->requirePresence('password')
        //   ->lengthBetween('password', [4, 8])
        //   ->add('password',[
        //           'comWith' => [
        //               'rule' => ['compareWith','password_confirm'],
        //               'message' => 'confirm password doesnt match'
        //           ]
        //   ]);
        return $validator;
    }
    // public function buildRules(RulusChecker $rules)
    // {
    //     $rules->add($rules->isUnique(['username']));
    //     $rules->add($rules->isUnique(['email']));
    //
    //     return $rules;
    // }

}
