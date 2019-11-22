<?php

// /posts/index
// /posts
// /(controller)/(action)/(options)

namespace App\Controller;

class LikesController extends AppController
{
    public function check($id = null)
    {
        $this->autoRender = false;
        $myid = $this->Auth->user('user_id');
        $data = $this->request->data;
        $likeOrUnLike = array_keys($data);
        $like = $likeOrUnLike[0];
        $like_id = $data[$like];
        if ($this->Likes->exists(['user_id' => $myid,'post_id' =>$like_id])){
            if ($like == 'like') {
                $like = $this->Likes->find()
                             ->where(['user_id' => $myid,'post_id' =>$like_id]);
                foreach($like as $like){
                    $id = $like->id;
                    break;
                }
                $like = $this->Likes->get($id);
                $like = $this->Likes->patchEntity($like,['status' => 1]);
                if ($this->Likes->save($like)) {
                    return $this->response->withType('application/json')
                            ->withStringBody(json_encode("unlike"));
                    // $this->Flash->success('like Success');
                    // return $this->redirect(['controller' => 'Posts','action' => 'index']);
                } else {
                    // $this->Flash->error('like Error');
                    // $this->log(print_r($post->errors(),true),LOG_DEBUG);
                    // return $this->redirect(['controller' => 'Posts','action' => 'index']);
                }
            } else {
                $like = $this->Likes->find()
                             ->where(['user_id' => $myid,'post_id' =>$like_id]);
                foreach($like as $like){
                    $id = $like->id;
                    break;
                }
                $like = $this->Likes->get($id);
                $like = $this->Likes->patchEntity($like,['status' => 0]);
                if ($this->Likes->save($like)) {
                    return $this->response->withType('application/json')
                            ->withStringBody(json_encode("like"));
                    // $this->Flash->success('unlike Success');
                    // return $this->redirect(['controller' => 'Posts','action' => 'index']);
                } else {
                    // $this->Flash->error('unlike Error');
                    // $this->log(print_r($post->errors(),true),LOG_DEBUG);
                    // return $this->redirect(['controller' => 'Posts','action' => 'index']);
                }
            }
        } else {
            $like = $this->Likes->newEntity();
            $like->post_id = $like_id;
            $like->user_id = $myid;
            if ($this->Likes->save($like)) {
                return $this->response->withType('application/json')
                        ->withStringBody(json_encode("unlike"));
                // $this->Flash->success('Like Success');
                // return $this->redirect(['controller' => 'Posts','action' => 'index']);
            } else {
                // $this->Flash->error('Retweet Error');
                // $this->log(print_r($post->errors(),true),LOG_DEBUG);
            }
        }
    }

}
