<?php
$this->assign('title','edit profile');
?>

<h1>Edit Profile</h1>
<?= $this->Form->create(null, [
    'url' =>['controller' => 'Users', 'action' => 'edit'],'type' => 'file'
]); ?>
<div class="form-group">
<?= $this->Form->input('username',['value'=>$myname,'class'=>"form-control w-50"]); ?>
<?php if(isset($username)) :?><div class="mb-3 text-danger"><?= h($username) ;?></div><?php endif ;?>
</div>
<div class="form-group">
<?= $this->Form->input('email',['value'=>$myemail,'class'=>"form-control w-50"]); ?>
<?php if(isset($email)) :?><div class="mb-3 text-danger"><?= h($email) ;?></div><?php endif ;?>
</div>
<div class="form-group">
<?= $this->Form->input('image',['type' => "file",'class'=>"form-control w-50"]); ?>
<?php if(isset($image)) :?><div class="mb-3 text-danger"><?= h($image) ;?></div><?php endif ;?>
</div>
<?= $this->Form->button('Edit',["class"=>"btn btn-primary"]); ?>
<?= $this->Form->end(); ?>
