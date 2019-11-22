<?php
$this->assign('title', 'Edit Post') ;
?>

<h1>
    Edit Post
</h1>

<?= $this->Form->create($post); ?>
<div class="form-group">
<?= $this->Form->input('content',['rows'=>'5','class'=>"form-control"]); ?>
<?= $this->Form->button('Update',["class"=>"btn btn-primary"]); ?>
<?= $this->Form->end(); ?>
