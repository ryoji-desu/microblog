<?php

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

class RetweetsTable extends Table
{
    public function initialize(array $config)
    {
        $this->addBehavior('Timestamp');
    }


}
