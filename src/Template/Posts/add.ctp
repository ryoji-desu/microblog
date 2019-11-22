<?php
$this->assign('title', 'Post Creation') ;
?>
<div class = "container">
<h1>Post</h1>

<?= $this->Form->create($post, ['type' => 'file']); ?>
<div class="form-group">
    <?= $this->Form->input('content',['rows'=>'5','class'=>"form-control"]); ?>
</div>
<div class="form-group">
    <?= $this->Form->input('image',['type' => "file",'class'=>"form-control"]); ?>
</div>
    <?= $this->Form->button('Add',["class"=>"btn btn-primary"]); ?>
    <?= $this->Form->end(); ?>
