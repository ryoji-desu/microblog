<?php

namespace App\Controller;

class CommentsController extends AppController
{

    public function view($id = null)
    {
        $post_id = $id;
        $originalPost = $this->getOriginalPost($id);
        $this->loadModel('Users');
        $comments = $this->Comments->find()
                        ->where(['post_id' => $post_id,"Comments.deleted is" =>null])
                        ->select(['Comments.body','username'=>'u.username','Comments.id'])
                        ->join([
                            'table' => 'users',
                            'alias' => 'u',
                            'type' =>'INNER',
                            'conditions' => 'u.user_id = Comments.user_id'
                        ]);
        $number = $comments->count();
        $comments = $this->paginate($comments);
        $this->set('number',$number);
        $this->set('originalPost',$originalPost);
        $this->set('comments',$comments);

    }
    public function add()
    {
        $comment = $this->Comments->newEntity();
        if ($this->request->is('post')) {
            $myid = $myid = $this->Auth->user('user_id');
             $comment->user_id = $myid;
             $post_id = $this->request->data['post_id'];
             $body = $this->request->data['body'];
             $comment->body = $body;
             $comment->post_id = $post_id;
             if (trim($body) == "") {
                 $this->Flash->error('empty cant be accepted');
                 return $this->redirect(['action' => 'view',$comment->post_id]);
             }
             // $comment = $this->Comments->patchEntity($comment,$this->request->data);
             if ($this->Comments->save($comment)) {
                 $this->Flash->success('Comment Add Success');
                 return $this->redirect(['action' => 'view',$comment->post_id]);
             } else {
                 $this->Flash->error('Comment Add Error');
                 return $this->redirect(['action' => 'view',$comment->post_id]);
             }
         }
         $this->set('comment',$comment);
    }
    public function delete($id = null)
    {
        $comment = $this->Comments->get($id);
        $myid = $this->Auth->user('user_id');
        if ($comment->user_id !== $myid) {
            $this->Flash->error('Delete Error');
            return $this->redirect(['action' => 'view',$comment->post_id]);
        }
        $comment->deleted = 1;
        if ($this->Comments->save($comment)) {
            $this->Flash->success('Delete Success');
        } else {
            $this->Flash->error('Delete Error');
        }
        return $this->redirect(['action' => 'view',$comment->post_id]);
    }
    public function getOriginalPost($id)
    {
        $this->loadModel('Posts');
        $post = $this->Posts->find()
                ->where(['id'=>$id]);
        if (!$this->Posts->exists(['id' => $id])) {
            $this->Flash->error('Error happend, try again');
            return $this->redirect(['controller'=>'Posts']);
        }
        return $post;
    }
    public $paginate = [
        'limit' => 3,
        'order' => [
                'modified' => 'desc'
        ]
    ];
    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('Paginator');
    }
}
