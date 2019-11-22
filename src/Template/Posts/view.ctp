<?php
$this->assign('title', 'Blog Detail') ;
?>

<h1>
    <?= h($post->title); ?>
</h1>

<p><?= nl2br(h($post->body)); ?></p>

<?php if(count($post->comments)) :?>
<h2>Comments <span>(<?= count($post->comments); ?>)</span></h2>
<ul>
<?php foreach ($post->comments as $comment) :?>
    <li>
        <?= h($comment->body); ?>
    </li>
<?php endforeach;?>
</ul>
<?php endif; ?>
<h2>New Comment</h2>
<?= $this->Form->create(null, [
    'url' =>['controller' => 'Comments', 'action' => 'add']
]); ?>
<?= $this->Form->input('body'); ?>
<?= $this->Form->hidden('post_id',['value'=>$post->id]); ?>
<?= $this->Form->button('Add',["class"=>"btn btn-primary"]); ?>
<?= $this->Form->end(); ?>
