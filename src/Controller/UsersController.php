<?php

namespace App\Controller;
use Cake\Mailer\Email;
use Cake\Utility\Security;
use Cake\Routing\Router;

class UsersController extends AppController
{
    public function login()
    {
        if ($this->request->session()->check('userinfo')){
            return $this->redirect(['controller' => 'Posts','action' => 'index']);
        }
        if ($this->request->is('post')) {
            //validation
            $check = array();
            $s = array('username',$this->request->data['username']);
            $check[] = $this->validation_empty($s);
            $n = array('password',$this->request->data['password']);
            $check[] = $this->validation_empty($n);
            //login or not
            if (in_array("error",$check)){
                return;
            } else {
                $user = $this->Auth->identify();
                if ($user['status'] === 0) {
                    $this->Flash->error('your account has not activated yet, please activate it');
                }else {
                    if ($user) {
                        $this->Auth->setUser($user);
                        $this->request->session()->write('userinfo', $user);
                        $this->Flash->success('Login Success');
                        return $this->redirect($this->Auth->redirectUrl());
                    }
                    $this->Flash->error('your username or password is not correct');
                }
            }
        }
    }

    public function logout()
    {
        $this->Flash->success('logout');
        $this->request->session()->destroy();
        return $this->redirect($this->Auth->logout());
    }

    public function add()
    {
        if ($this->request->session()->check('userinfo')){
            return $this->redirect(['controller' => 'Posts','action' => 'index']);
        }
        $user = $this->Users->newEntity();
        if ($this->request->is('post')) {
            //store data
            $username = $this->request->data['username'];
            $email = $this->request->data['email'];
            $password = $this->request->data['password'];
            $password_confirm = $this->request->data['password_confirm'];
            //validation
            $check = array();
            $s = array('username',$username);
            $check[] = $this->validation_empty($s);
            $n = array('email',$email);
            $check[] = $this->validation_empty($n);
            $s = array('password',$password);
            $check[] = $this->validation_empty($s);
            $n = array('password_confirm',$password_confirm);
            $check[] = $this->validation_empty($n);
            if ($this->Users->exists(['username' => $username])) {
                $check[] = $this->set('username','Your username is being used, please try other name');
            };
            if ($this->Users->exists(['email' => $email])) {
                $check[] = $this->set('email','Your email is being used, please try other email');
            }
            if ($password !== $password_confirm) {
                $check[] = $this->set('password_confirm','your password and password_confirm dont match, try it again');
            }
            //save or not
            if (in_array("error",$check)){
                return;
            } else {
                $email = $this->request->data['email'];
                $hash = Security::hash($email, 'sha1', true);
                $user = $this->Users->patchEntity($user,$this->request->data);
                $user->email_hash = $hash;
                if ($this->Users->save($user)) {
                    $this->activation($email,$hash);
                    $this->Flash->success('User Registration Success,please activate your eamil adress');
                    return $this->redirect(['action' => 'login']);
                } else {
                    $this->Flash->error('User Registration Error');
                }
            }
        }
        $this->set('user',$user);
    }

    public function activation($email,$hash)
    {
        $emailadress = $email;
        $email = new Email('default');
        $email->from(['actionryoji@gmail.com' => 'Microblog'])
              ->to($emailadress)
              ->emailFormat('html')
              ->setTemplate("activation")
              ->viewVars(['value' => $hash])
              ->subject('Email verification')
              ->send();

    }
    public function search()
    {
        $myid = $this->Auth->user('user_id');
        if ($this->request->is('post')) {
            $data = $this->request->data['search'];
            $users = $this->Users->find()
            ->leftJoin(
                ['f' => 'follows'],
                ['f.follow_id = Users.user_id',"f.user_id = $myid"]
                )->select([
                    'id' => 'Users.user_id',
                    'username' => 'Users.username',
                    'image' => 'Users.profile_image',
                    'status' => 'f.status'
                ])->where(['Users.username LIKE' =>"%$data%",'Users.status'=>1]);
            $countUser = $users->count();
            $this->set('countUser',$countUser);
            $this->set('users',$users);
            $posts = $this->searchPosts($data);
            $this->set('posts',$posts[0]);
            $this->set('countPosts',$posts[1]);
            $this->set('data',$data);
        }
    }

    public function edit()
    {
        $myid = $this->Auth->user('user_id');
        $myinfo_array = $this->Users->get($myid)->toArray();
        $myname = $myinfo_array['username'];
        $myemail = $myinfo_array['email'];
        $this->set('myname',$myname);
        $this->set('myemail',$myemail);
        $myinfo = $this->Users->get($myid);

        if ($this->request->is(['post', 'patch', 'put'])) {
            $info = $this->request->data;
            $username = $info['username'];
            $email = $info['email'];
            $check = array();
            //validation
            $s = array('username',$username);
            $check[] = $this->validation_empty($s);
            $n = array('email',$email);
            $check[] = $this->validation_empty($n);
            $check_username = $this->Users->find()
                                          ->where(['username'=>$username,'user_id is not'=>$myid])
                                          ->count();
            if ($check_username !== 0) {
                $check[] = "error";
                $this->set('username','your username is being used please try the other');
            }
            if (in_array('error',$check)){
                return;
            } else {
                if ($info['image']['tmp_name'] === "") {
                    $myinfo->username = $info['username'];
                    $myinfo->email = $info['email'];
                    if ($this->Users->save($myinfo)) {
                        $this->Flash->success('Edit Success');
                        return $this->redirect(['action' => 'edit']);
                    } else {
                        //error
                        $this->Flash->error('Edit Error');
                    }
                }else{
                    $edit_path =  "/var/www/html/";
                    $myinfo->username = $info['username'];
                    $myinfo->email = $info['email'];
                    $imagepath = $this->upload_image($this->request->data['image']);
                    $imagepath = str_replace($edit_path, '', $imagepath);
                    $myinfo->profile_image = $imagepath;
                    if (isset($image_path) || strpos($imagepath,'error') !== false) {
                        $this->Flash->error('your picture is invalid,try another one');
                        return $this->redirect(['action' => 'edit']);
                    }
                    if ($this->Users->save($myinfo)) {
                        $this->Flash->success('Edit Success');
                        return $this->redirect(['action' => 'edit']);
                    } else {
                        //error
                        $this->Flash->error('Edit Error');
                    }
                }
            }
        }
    }
    public function searchPosts($data)
    {
        $this->loadModel('Posts');
        $searchedPosts = $this->Posts->find()
                        ->select(['username'=>'u.username','profile'=>'u.profile_image','Posts.content','Posts.image_path','orginal_content'=>'pr.content','original_image'=>'pr.image_path','orgiginal_user'=>'ur.username','original_profile'=>'ur.profile_image','original_delted'=>'pr.deleted','original_id'=>'pr.id'])
                        ->where(['Posts.content LIKE' => "%$data%",'Posts.deleted is'=>null])
                        ->join([
                            'table' => 'users',
                            'alias' => 'u',
                            'type' =>'INNER',
                            'conditions' => 'u.user_id = Posts.user_id'
                        ])
                        ->join([
                            'table' => 'posts',
                            'alias' => 'pr',
                            'type' => 'LEFT',
                            'conditions' => 'Posts.parent_id = pr.id'
                        ])
                        ->join([
                            'table' =>'users',
                            'alias' => 'ur',
                            'type' => 'LEFT',
                            'conditions' => 'pr.user_id = ur.user_id'
                        ]);
        $countPosts = $searchedPosts->count();
        $array = array($searchedPosts,$countPosts);
        return $array;

    }
    public function view()
    {
        $myid = $this->Auth->user('user_id');
            $users = $this->Users->find()
            ->leftJoin(
                ['f' => 'follows'],
                ['f.follow_id = Users.user_id',"f.user_id = $myid"]
            )->select([
                'id' => 'Users.user_id',
                'username' => 'Users.username',
                'image' => 'Users.profile_image',
                'status' => 'f.status'
            ])
            ->where(['Users.status'=>1]);
        $this->set('users',$users);
    }
    public function profile($id = null)
    {
        $hisId = $id;
        $myid = $this->Auth->user('user_id');
        if (!$this->Users->exists(['user_id' => $hisId])) {
            $this->Flash->error('Error happend, try again');
            return $this->redirect(['controller'=>'Posts']);
        }
        $this->loadModel('Posts');
        $hisPosts = $this->Posts->find()
                ->where(['user_id'=>$hisId,'deleted is'=>null])
                ->order(['modified']);
        if ($hisPosts !== null) {
            $count = $hisPosts->count();
        } else {
            $count = 0;
        }
        $hisInfo = $this->Users->find()
                ->where(['user_id'=>$hisId]);
        $this->loadModel('Follows');
        if ($this->Follows->exists(['user_id' => $myid,'follow_id' =>$hisId,'status'=>1])) {
            $this->set('follow','unfollow');
        } elseif ($myid == $hisId) {
            $this->set('follow','me');
        } else {
            $this->set('follow','follow');
        }
        $this->set('hisInfo',$hisInfo);
        $this->set('hisPosts',$hisPosts);
        $this->set('count',$count);
    }

    public function mypage()
    {
        $myinfo = $this->Auth->user();
        $myid = $myinfo['user_id'];
        $this->loadModel('Posts');
        $myPosts = $this->Posts->find()
                ->where(['user_id'=>$myid,'deleted is'=>null])
                ->order(['modified']);
        $myfollow = $this->countMyFollow($myid);
        $this->set('myfollow',$myfollow);
        $this->set('myPosts',$myPosts);
    }

    public function countMyFollow($id = null)
    {
        $this->loadModel('Follows');
        $myfollow = $this->Follows->find()
                    ->where(['user_id' => $id,"status" => 1])
                    ->count();
        $myfollower_id = $this->Follows->find()
                    ->where(['follow_id' => $id,"status" => 1])
                    ->select('user_id');
        $myfollower = $myfollower_id ->count();
        return array($myfollow,$myfollower,$myfollower_id);
    }
    public function activate()
    {
        $url = Router::url();
        $keys = parse_url($url);
        $path = explode("/", $keys['path']);
        $last = end($path);
        if ($last == 'activate'){
            return $this->redirect(['action' => 'login']);
        }
        $user = $this->Users->find()
                ->where(['email_hash'=>$last])
                ->select(['user_id'])
                ->toArray();
        if ($user == false) {
            $this->Flash->success('Your url has something wrong');
            return $this->redirect(['action' => 'login']);
        }
        foreach ($user as $user) {
            $id = $user->user_id;
        }
        $myinfo = $this->Users->get($id);
        if ($myinfo->status === 1) {
            $this->Flash->success('Your Account has already activated');
            return $this->redirect(['action' => 'login']);
        }
        $myinfo->status = 1;
        if ($this->Users->save($myinfo)) {
            $this->Flash->success('Activate Success');
            return $this->redirect(['action' => 'login']);
        } else {
            $this->Flash->error('Activate Error');
        }


    }
    public function follower()
    {
        $id = $this->Auth->user('user_id');
        $this->loadModel('Follows');
        $myfollower = $this->Follows->find()
                    ->where(['follow_id' => $id,"Follows.status" => 1])
                    ->select(['profile'=>'u.profile_image','username'=>'u.username','id'=>'u.user_id'])
                    ->join([
                        'table' => 'users',
                        'alias' => 'u',
                        'type' =>'INNER',
                        'conditions' => 'u.user_id = Follows.user_id'
                    ]);
        $this->set('myfollower',$myfollower);
    }
    public function initizalize()
    {
        parent::initizalize();
        $this->Auth->allow(['logout','add','activate','login']);
    }


}
