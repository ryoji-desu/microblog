<?php

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

class CommentsTable extends Table
{
    public function initialize(array $config)
    {
        $this->addBehavior('Timestamp');
    }
    public function validationDefault(Validator $validator)
    {
        $validator
          ->notEmpty('body')
          ->requirePresence('body')
          ->add('body',[
              'length' =>[
                  'rule' =>['maxLength',50],
                  'message'  => 'body must be below 50'
            ]
          ]);
        return $validator;
    }

}
