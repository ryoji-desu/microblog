<?php

// /posts/index
// /posts
// /(controller)/(action)/(options)

namespace App\Controller;

class FollowsController extends AppController
{
    public function check()
    {
        $this->autoRender = false;
        $data = $this->request->data;
        $myid = $this->Auth->user('user_id');
        $followOrUnfollow = array_keys($data);
        $follow = $followOrUnfollow[0];
        $follow_id = $data[$follow];
        if ($this->Follows->exists(['user_id' => $myid,'follow_id' =>$data[$follow]])) {
            if ($follow == 'follow') {
                $follow = $this->Follows->find()
                             ->where(['user_id' => $myid,'follow_id' =>$data[$follow]]);
                foreach($follow as $follow){
                    $id = $follow->id;
                    break;
                }
                $follow = $this->Follows->get($id);
                $follow = $this->Follows->patchEntity($follow,['status' => 1]);
                if ($this->Follows->save($follow)) {
                    return $this->response->withType('application/json')
                            ->withStringBody(json_encode("unfollow"));
                    // $this->Flash->success('Follow Success');
                    // return $this->redirect(['controller' => 'Users','action' => 'view']);
                } else {
                    return $this->response->withType('application/json')
                            ->withStringBody(json_encode("error"));
                    // $this->Flash->error('Add Error');
                    // $this->log(print_r($post->errors(),true),LOG_DEBUG);
                }
                return $this->redirect(['controller' => 'Users','action' => 'view']);
            } else {

                $follow = $this->Follows->find()
                             ->where(['user_id' => $myid,'follow_id' =>$data[$follow]]);
                foreach($follow as $follow){
                    $id = $follow->id;
                    break;
                }
                $follow = $this->Follows->get($id);
                $follow = $this->Follows->patchEntity($follow,['status' => 0]);
                if ($this->Follows->save($follow)) {
                    return $this->response->withType('application/json')
                            ->withStringBody(json_encode("follow"));
                    // $this->Flash->success('UnFollow Success');
                    // return $this->redirect(['controller' => 'Users','action' => 'view']);
                } else {
                    return $this->response->withType('application/json')
                            ->withStringBody(json_encode("error"));
                    // $this->Flash->error('Add Error');
                    // $this->log(print_r($post->errors(),true),LOG_DEBUG);
                }
            }
        } else {
            $follow = $this->Follows->newEntity();
            $follow->follow_id = $follow_id;
            $follow->user_id = $myid;
            if ($this->Follows->save($follow)) {
                return $this->response->withType('application/json')
                        ->withStringBody(json_encode("unfollow"));
                // $this->Flash->success('Follow Success');
                // return $this->redirect(['controller' => 'Users','action' => 'view']);
            } else {
                return $this->response->withType('application/json')
                        ->withStringBody(json_encode("error"));
                // $this->Flash->error('Add Error');
                // $this->log(print_r($post->errors(),true),LOG_DEBUG);
            }
        }
    }

}
