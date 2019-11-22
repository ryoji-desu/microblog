<?php

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

class PostsTable extends Table
{
    public function initialize(array $config)
    {
        $this->addBehavior('Timestamp');
    }
    public function validationDefault(Validator $validator)
    {
        // $validator
        //   ->add('content',[
        //       'length' =>[
        //           'rule' =>['maxLength',140],
        //           'message'  => 'body must be below 140'
        //     ]
        // ]);
        // $validator
        //   ->notEmpty('username')
        //   ->requirePresence('username');
        return $validator;
    }

}
