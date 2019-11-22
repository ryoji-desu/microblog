<?php
$this->assign('title','register');
?>

<h1>Register</h1>
    <div class = "container">
        <?= $this->Form->create(null, [
            'url' =>['controller' => 'Users', 'action' => 'add']
        ]); ?>
        <div class="form-group">
            <?= $this->Form->input('username',['class'=>"form-control w-50",'placeholder'=>'username(more than 4 characters,less than 8)']); ?>
            <?php if(isset($username)) :?>
                <div class="mb-3 text-danger"><?= h($username) ;?></div>
            <?php endif ;?>
        </div>
        <div class="form-group">
            <?= $this->Form->input('email',['class'=>"form-control w-50",'placeholder'=>'email']); ?>
            <?php if(isset($email)) :?>
                <div class="mb-3 text-danger"><?= h($email) ;?></div>
            <?php endif ;?>
        </div>
        <div class="form-group">
            <?= $this->Form->input('password',['class'=>"form-control w-50",'placeholder'=>'password(more than 4 characters,less than 8)']); ?>
            <?php if(isset($password)) :?>
                <div class="mb-3 text-danger"><?= h($password) ;?></div>
            <?php endif ;?>
        </div>
        <div class="form-group">
            <?= $this->Form->input('password_confirm',array(
                'type' => 'password','class'=>"form-control w-50",'placeholder'=>'password-cofirm(more than 4 characters,less than 8)'
            )); ?>
            <?php if(isset($password_confirm)) :?>
                <div class="mb-3 text-danger"><?= h($password_confirm) ;?></div>
            <?php endif ;?>
        </div>
        <?= $this->Form->button('Add',["class"=>"btn btn-primary w-25"]); ?>
        <?= $this->Form->end(); ?>
        <?php echo $this->Html->link('login',[
            'controller' => 'Users',
            'action' => 'login'
            ]) ?>
    </div>
