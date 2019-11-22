<?php

$this->assign('title', 'Comment View') ;
$me = $this->request->getSession()->read('Auth.User');
?>
<h1 class="font-weight-normal">
    Post
</h1>
<?php foreach ($originalPost as $post) :?>
<h3 class="font-italic"><?php echo h($post->content); ?></h3>
<?php endforeach ;?>

<h2 class="font-weight-normal">Comments <span>(<?= h($number); ?>)</span></h2>

<table class="table">
    <thead>
        <tr><th>commenter</th><th>body</th></tr>
    </thead>
    <tbody>
        <?php foreach ($comments as $comment) :?>
                <tr><td><?= h($comment->username) ;?></td>
                <td><?php echo h($comment->body); ?>
                    <?php if($me['username'] == $comment->username) :?>
                        <?=
                            $this->Form->postLink(
                                '[x]',
                                ['action'=>'delete', $comment->id],
                                ['confirm' => 'Are you sure?']
                            );
                        ?>
                    <?php endif;?>
                </td></tr>
        <?php endforeach;?>
    </tbody>
</table>
<div>
    <ul class="pagination">
        <li>
            <?php
            if ($this->Paginator->hasPrev()) {
                echo $this->Paginator->prev('< previous');
            }
            ?>
        </li>
        <?php echo $this->Paginator->numbers(); ?>
        <li>
            <?php
            if ($this->Paginator->hasNext()) {
                echo $this->Paginator->next('next >');
            }
            ?>
        </li>
    </ul>
</div>
<h2 class="font-weight-normal">New Comment</h2>
    <?= $this->Form->create(null, [
        'url' =>['controller' => 'Comments', 'action' => 'add']
    ]); ?>
<div class="form-group">
    <?= $this->Form->input('body',['class'=>"form-control w-50",'rows'=>'2']); ?>
        <?= $this->Form->hidden('post_id',['value'=>$post->id]); ?>
        <?= $this->Form->button('Add',["class"=>"mt-3 btn btn-primary"]); ?>
    <?= $this->Form->end(); ?>
</div>
