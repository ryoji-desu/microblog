<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Controller;

use Cake\Controller\Controller;
use Cake\Event\Event;

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @link https://book.cakephp.org/3.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller
{

    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading components.
     *
     * e.g. `$this->loadComponent('Security');`
     *
     * @return void
     */
    public function initialize()
    {
        parent::initialize();


        $this->viewBuilder()->layout('my_layout');

        $this->loadComponent('RequestHandler', [
            'enableBeforeRedirect' => false,
        ]);
        $this->loadComponent('Flash');
        $this->loadComponent('Auth',[
            'authenticate' => [
                'form' => [
                    'fields' => [
                        'username' => 'username',
                        'password' => 'password'
                    ]
                ]
            ],
            'loginAction' => [
                'controller' => 'Users',
                'action' => 'login'
            ],
            'loginRedirect' => [
                'controller' => 'Posts',
                'action' => 'index'
            ],
            'logoutRedirect' => [
                'controller' => 'Users',
                'action' => 'login'
            ],
        ]);
        /*
         * Enable the following component for recommended CakePHP security settings.
         * see https://book.cakephp.org/3.0/en/controllers/components/security.html
         */
        //$this->loadComponent('Security');
    }
    public function validation_empty($s = null)
    {
        $error = "error";
        if ($s[1] =="" || $s[1] == " " || empty($s[1]) ) {
            $this->set($s[0],'please enter');
            return $error;
        }
        if ($s[0] !== 'email' && !preg_match('/\A[a-zA-Z0-9 ]+\z/',$s[1])) {
            $this->set($s[0],'You are using wrong words,only number and alphabet can be accepted');
            return $error;
        }
        if ($s[0] == 'username'  && mb_strlen($s[1])>=8 ||mb_strlen($s[1])<=4) {
            $this->set($s[0],'it should be less than 8 and more than 4');
            return $error;
        }
        if ($s[0] == 'password' && mb_strlen($s[1])>=8 ||mb_strlen($s[1])<=4) {
            $this->set($s[0],'it should be less than 8 and more than 4');
            return $error;
        }
    }
    public function upload_image($image = array())
    {
        $image = $this->request->data['image'];
        $error = "error";
        try {
            $this -> _validateUpload($image);
        } catch (\Exception $e){
            $this->Flash->error('Invalid Picture');
            return $error;
        }
        try {
            $ext = $this -> _validateImageType($image);
        } catch (\Exception $e){
            $this->Flash->error('Invalid Imagetype');
            return $error;
        }
        try {
             $path = $this -> _save($ext,$image);
             return $path;
        } catch (\Exception $e){
            $this->Flash->error('cannot save');
            return $error;
        }

    }
    private function _validateUpload($image) {
        $error = "error";
        if ( !isset($image['error'])) {
            return $error = "error";
        }
        if (explode("/",$image['type'])[0] != "image") {
            return $error = "error";
        }
        switch ($image['error']) {
            case UPLOAD_ERR_OK:
            return true;
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
            return $error = "error";
            default:
            return $error = "error";
        }
    }

    private function _validateImageType($image)
    {
        $error = "error";
        $this->_imageType = exif_imagetype($image['tmp_name']);
        switch($this->_imageType){
            case IMAGETYPE_GIF:
            return 'gif';
            break;
            case IMAGETYPE_JPEG:
            return 'jpeg';
            break;
            case IMAGETYPE_PNG:
            return 'png';
            break;
            default:
            return $error = "error";
        }
    }
    private function _save($ext,$image)
    {
        $error = "error";
        $this->_imageFileName = sprintf(
            '%s_%s.%s',
            time(),
            sha1(uniqid(mt_rand(), true)),
            $ext
        );

        $uploaddir = realpath(WWW_ROOT . "img/");
        $savePath = $uploaddir . '/' .$this->_imageFileName;
        $res = move_uploaded_file($image['tmp_name'], $savePath);
        if ($res === false) {
            return $error = "error";
        }
        return $savePath;
    }
    public function beforeFilter(Event $event)
    {
      $this->Auth->allow(['add','activate','login']);
    }
}
