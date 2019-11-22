<?php
$this->assign('title','login');
?>
<div class = "container">
    <h1>Login</h1>
        <?= $this->Form->create('User',['action' =>'/login']); ?>
    <div class="form-group">
        <?= $this->Form->input('username',['placeholder'=>'username','class'=>"form-control w-50"]); ?>
    </div>
        <?php if(isset($username)) :?>
            <div class="mb-3 text-danger"><?= h($username) ;?></div>
        <?php endif ;?>
    <div class="form-group">
        <?= $this->Form->input('password',['placeholder'=>'password','class'=>"form-control w-50"]); ?>
        <?php if(isset($password)) :?>
            <div class="mb-3 text-danger"><?= h($password) ;?></div>
        <?php endif ;?>
    </div>
    <div class= "mt-3">
        <?= $this->Form->button('login',["class"=>"btn btn-primary w-25"]); ?>
    </div>
        <?= $this->Form->end(); ?>
        <?php echo $this->Html->link('signup',[
        'controller' => 'Users',
        'action' => 'add'
        ]) ?>
    <br>


<fb:login-button
  scope="public_profile,email"
  onlogin="checkLoginState();">
</fb:login-button>
</div>
