<?php

// /posts/index
// /posts
// /(controller)/(action)/(options)

namespace App\Controller;

class RetweetsController extends AppController
{
    public function check($id = null)
    {
        $myid = $this->Auth->user('user_id');
        $myname = $this->Auth->user('username');
        $data = $this->request->data;
        $retweetOrUnRetweet = array_keys($data);
        $retweet = $retweetOrUnRetweet[0];
        $retweet_id = $data[$retweet];
        if ($this->Retweets->exists(['user_id' => $myid,'post_id' =>$retweet_id])){
            if ($retweet == 'retweet') {
                $retweet = $this->Retweets->find()
                             ->where(['user_id' => $myid,'post_id' =>$retweet_id]);
                foreach($retweet as $retweet){
                    $id = $retweet->id;
                    break;
                }
                $retweet = $this->Retweets->get($id);
                $retweet = $this->Retweets->patchEntity($retweet,['status' => 10]);
                if ($this->Retweets->save($retweet)) {
                    $this->Flash->success('retweet Success');
                    return $this->redirect(['controller' => 'Posts','action' => 'index']);
                } else {
                    $this->Flash->error('retweet Error');
                    $this->log(print_r($post->errors(),true),LOG_DEBUG);
                    return $this->redirect(['controller' => 'Posts','action' => 'index']);
                }
            } else {
                $retweet = $this->Retweets->find()
                             ->where(['user_id' => $myid,'post_id' =>$retweet_id]);
                foreach($retweet as $retweet){
                    $id = $retweet->id;
                    break;
                }
                $retweet = $this->Retweets->get($id);
                $retweet = $this->Retweets->patchEntity($retweet,['status' => 0]);
                if ($this->Retweets->save($retweet)) {
                    $this->Flash->success('unretweet Success');
                    return $this->redirect(['controller' => 'Posts','action' => 'index']);
                } else {
                    $this->Flash->error('retweet Error');
                    $this->log(print_r($post->errors(),true),LOG_DEBUG);
                    return $this->redirect(['controller' => 'Posts','action' => 'index']);
                }
            }
        } else {
            $retweet = $this->Retweets->newEntity();
            $retweet->post_id = $retweet_id;
            $retweet->user_id = $myid;
            $retweet->retweeter_name = $myname;
            if ($this->Retweets->save($retweet)) {
                $this->Flash->success('Retweet Success');
                return $this->redirect(['controller' => 'Posts','action' => 'index']);
            } else {
                $this->Flash->error('Retweet Error');
                $this->log(print_r($post->errors(),true),LOG_DEBUG);
            }
        }
    }

}
