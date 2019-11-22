
<?php
namespace App\Controller;

use Cake\Controller\Controller;
use Cake\Event\Event;

// ソーシャルログイン用コントローラー
class AuthController extends AppController
{

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        // レイアウトなし
        $this->autoRender = FALSE;
    }

    // /auth/yahoojp
    public function yahoojp()
    {
        $this->authFunction();
    }

    // /auth/facebook
    public function facebook()
    {
        $this->authFunction();
    }

    //　/auth/google
    public function google()
    {
        $this->authFunction();
    }

    // /auth/twitter
    public function twitter()
    {
        $this->authFunction();
    }

    // 共通function
    private function authFunction()
    {
        // Opauth require_once
        $opauth_path = '/var/www/html//exercise/microblog1/plugins/Opauth/';
        require_once $opauth_path.'config.php';
        require_once $opauth_path.'Opauth.php';

        // ソーシャルログイン処理
        new \Opauth($config);
    }

    // ソーシャルログイン完了後のaction
    public function complete()
    {
        // session.auto_startオンやAuthなどでセッションスタート済みの場合不要
        if (!isset($_SESSION['opauth'])) {
            session_start();
        }

        // 取得データ表示
        if (isset($_SESSION['opauth']['auth'])) {
            // 成功

            // CakePHP ~3.4
            $session = $this->request->session();
            // CakePHP 3.5~
            // $session = $this->request->getSession();

            $session->write('opauth', $_SESSION['opauth']['auth']);
            var_dump($session->read('opauth'));
        } elseif (isset($_SESSION['opauth']['error'])) {
            // 失敗
            var_dump($_SESSION['opauth']['error']);
        } else {
            // その他失敗
            echo 'Opauth ERROR!';
        }
    }
}
