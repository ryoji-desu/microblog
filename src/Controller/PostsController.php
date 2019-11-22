<?php

// /posts/index
// /posts
// /(controller)/(action)/(options)

namespace App\Controller;

class PostsController extends AppController
{
    public function index()
    {
        if (!$this->request->session()->check('userinfo')){
            // return $this->redirect(['controller' => 'Users','action' => 'login']);
        }
        if (!isset($_GET['page_id'])){
            $start = 0;
            $now = 1;
        } else{
          $n = $_GET['page_id']-1;
          $start = 10*$n-$n;
          $now = $_GET['page_id'];
        }
        $follow_id = $this->knowFollow();
        $mylikes_id = $this->knowMylikes();
        $this->set('mylikes_id',$mylikes_id);
        $myretweets_id = $this->knowMyretweets();
        $this->set('myretweets_id',$myretweets_id);

        $original = $this->Posts->find()
            ->select(['Posts.id','Posts.content','picture'=>'Posts.image_path','Posts.user_id','username'=>'u.username','profile'=>'u.profile_image',
            'modified'=>'Posts.modified','retweet'=>'Posts.parent_id','rname'=>'Posts.retweetby','Posts.deleted','orginal_content'=>'pr.content','original_image'=>'pr.image_path','orgiginal_user'=>'ur.username','original_profile'=>'ur.profile_image','original_delted'=>'pr.deleted','original_id'=>'pr.id'])
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
            ])
            ->where(['Posts.deleted is' => null,'Posts.user_id In'=>$follow_id]);
        $retweet = $this->Posts->find()
            ->select(['Posts.id','Posts.content','picture'=>'Posts.image_path','Posts.user_id','username'=>'u.username'
            ,'profile'=>'u.profile_image','modified'=>'r.modified','r.status','rname'=>'r.retweeter_name','Posts.deleted','orginal_content'=>'pr.content','original_image'=>'pr.image_path','orgiginal_user'=>'ur.username','original_profile'=>'ur.profile_image','original_delted'=>'pr.deleted','original_id'=>'pr.id'])
            ->join([
                'table' => 'retweets',
                'alias' => 'r',
                'type' =>'INNER',
                'conditions' => 'r.post_id = Posts.id'
            ])
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
            ])
            ->where(['Posts.deleted is' => null,'r.status'=>10,'r.user_id In'=>$follow_id]);
        //pagination
        $total = $retweet->count()+$original->count();
        $pages = ceil($total / 10);
        $this->set('pages',$pages);
        $this->set('now',$now);
        if ($total == 0){
            $this->set('posts',0);
            return;
        } else {
            $posts = $original->unionAll($retweet)->epilog("order by modified desc limit $start,10");


            //counting number of retweet
            $post_id = array();
            foreach($posts as $post) {
                array_push($post_id,$post->id);
            }
            $post_id = array_unique($post_id);
            $retweet_count = $this->count_retweet($post_id);
            $this->set('retweet_count',$retweet_count);
            $favorite_count = $this->count_favorite($post_id);
            $this->set('favorite_count',$favorite_count);
            $this->set('posts',$posts);
        }
    }
    public $paginate = [
        'limit' => 3,
        'order' => [
                'modified' => 'desc'
        ]
    ];
    public function count_retweet($post_id)
    {
        $this->loadModel('Retweets');
        $count = $this->Retweets->find()
                        ->where(['post_id In'=>$post_id,'status'=>10]);
                        $count->select(['count' => $count->func()->count('post_id'),'post_id' => 'post_id'])
                              ->group(['post_id']);
        $count = $count->toList();
        $array_key = array();
        $array_val = array();
        // dump($count);
        foreach ($count as $count){
                $array_key[]= $count->post_id;
                $array_val[] = $count->count;
        }
        if (!empty($array_key)){
            $retweet_count = array_combine($array_key,$array_val);
            return $retweet_count;
        }
    }
    public function count_favorite($post_id)
    {
        $this->loadModel('Likes');
        $count = $this->Likes->find()
                        ->where(['post_id In'=>$post_id,'status'=>1]);
                    $count->select(['count' => $count->func()->count('post_id'),'post_id' => 'post_id'])
                          ->group(['post_id']);
        $count = $count->toList();
        foreach ($count as $count){
            $array_key[]= $count->post_id;
            $array_val[] = $count->count;
        }
        if (!empty($array_key)){
            $favorite_count = array_combine($array_key,$array_val);
            return $favorite_count;
        }
    }
    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('Paginator');
    }
    public function add()
    {
        $post = $this->Posts->newEntity();
        if ($this->request->is('post')) {
            if(isset($this->request->data['post_id'])){
                $parent_id = $this->request->data['post_id'];
                $post->parent_id = $parent_id;
            }
            $post->user_id = $this->Auth->user('user_id');
            $edit_path =  "/var/www/html/";
            if($this->request->data['image']['name'] !== ""){
                $imagepath = $this->upload_image($this->request->data['image']);
                $imagepath = str_replace($edit_path, '', $imagepath);
                $post->image_path = $imagepath;
                if (isset($image_path) || strpos($imagepath,'error') !== false) {
                    $this->Flash->error('your picture is invalid,try another one');
                    return $this->redirect(['action' => 'index']);
                }
            }
            $content = $this->request->data['content'];
            if (mb_strlen($content)>=140) {
                $this->Flash->error('message should be below 140');
                return $this->redirect(['action' => 'index']);
            }
            if (trim($content) == "") {
                $this->Flash->error('empty cant be accepted');
                return $this->redirect(['action' => 'index']);
            }

            $post = $this->Posts->patchEntity($post,$this->request->data);

            if ($this->Posts->save($post)) {
                $this->Flash->success('Add Success');
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error('add error');
                $this->log(print_r($post->errors(),true),LOG_DEBUG);
                //error
            }

        }
        $this->set('post',$post);
    }
    public function edit($id = null)
    {
        if (!$this->Posts->exists(['id' => $id])) {
            $this->Flash->error('Error happend, try again');
            return $this->redirect(['controller'=>'Posts','action'=>'index']);
        }
        $post = $this->Posts->get($id);
        $myid = $this->Auth->user('user_id');
        if ($post->user_id !== $myid) {
            $this->Flash->error('Error happend, try again');
            return $this->redirect(['controller'=>'Posts','action'=>'index']);
        }
        if ($this->request->is(['post', 'patch', 'put'])) {
            $post = $this->Posts->patchEntity($post,$this->request->data);
            if ($this->Posts->save($post)) {
                $this->Flash->success('Edit Success');
                return $this->redirect(['action' => 'index']);
            } else {
                //error
                $this->Flash->error('Edit Error');
            }
        }
        $this->set(compact('post'));

    }
    public function delete($id = null)
    {
        $post = $this->Posts->get($id);
        $myid = $this->Auth->user('user_id');
        if ($post->user_id !== $myid) {
            $this->Flash->error('Delete Error');
            return $this->redirect(['action' => 'index']);
        }
        $post->deleted = 1;
        if ($this->Posts->save($post)) {
            $this->Flash->success('Delete Success');
        } else {
            $this->Flash->error('Delete Error');
        }
        return $this->redirect(['action' => 'index']);
    }
    public function knowFollow()
    {
        $this->loadModel('Follows');
        $myid = $this->Auth->user('user_id');
        $follows_data = $this->Follows->find()
                        ->where(['user_id'=>$myid,'status'=>1]);
        $follow = array(0=>$myid);
        foreach($follows_data as $follow_data){
            array_push($follow,$follow_data->follow_id);
        }
        return $follow;
    }
    public function knowMylikes()
    {
        $this->loadModel('Likes');
        $myid = $this->Auth->user('user_id');
        $mylikes = $this->Likes->find()
                     ->where(['user_id' => $myid,'status' => 1]);
        $mylike_id = array();
        foreach ($mylikes as $mylike) {
            array_push($mylike_id,$mylike->post_id);
        }
        return $mylike_id;
    }
    public function knowMyretweets()
    {
        $this->loadModel('Retweets');
        $myid = $this->Auth->user('user_id');
        $myretweets = $this->Retweets->find()
                     ->where(['user_id' => $myid,'status' => 10]);
        $myretweet_id = array();
        $myretweets_comment = $this->Posts->find()
                            ->where(['user_id'=>$myid,'deleted is'=>null,'parent_id is not'=>null]);
        foreach ($myretweets as $myretweet) {
            array_push($myretweet_id,$myretweet->post_id);
        }
        foreach ($myretweets_comment as $myretweets_comment) {
            array_push($myretweet_id,$myretweets_comment->parent_id);
        }
        return $myretweet_id;
    }
}
