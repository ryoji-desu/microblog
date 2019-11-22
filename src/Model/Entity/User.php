<?php


namespace App\Model\Entity;

use Cake\ORM\Entity;
use Cake\Auth\DefaultPasswordHasher;

class User extends Entity
{
    // protected $_accessible = [
    //     'email' => true,
    //     'password' => true,
    //     'status' => true,
    //     'created' => true,
    // ];
    protected function _setPassword($password)
      {
          if (strlen($password) > 0) {
            return (new DefaultPasswordHasher)->hash($password);
          }
      }
}
