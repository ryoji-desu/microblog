<?php $this->assign('title','Mypage');
$me = $this->request->getSession()->read('Auth.User');
?>
<div class="pt-3"></div>
<div class="card m-auto shadow" style="width: 40rem; height:15rem;">
    <div class="justify-content-center text-center">
        <div class="text-right"><h4><a href="<?= h(url) ?>users/edit">Edit profile</a></h4></div>
        <?php if($me['profile_image'] !== null) :?>
            <img src="/../../../<?= h($me['profile_image']) ?>" id ="profile_big"/>
        <?php else :?>
            <img src="<?= h(url) ?>webroot/img/noprofile.jpg" style="height:75px;" id ="profile_big">
        <?php endif ; ?>
    </div>
    <div class="text-right">
        <ul class="list-inline">
        <h4><li class="list-inline-item"><a href="<?= h(url) ?>users/view">Following(<?= h($myfollow[0]) ?>)</a></li>
            <li class="list-inline-item"><a href="<?= h(url) ?>users/follower">Follower(<?= h($myfollow[1]) ?>)</a></li>
        </h4>
        </ul>
    </div>
</div>
<h1>My Posts</h1>

    <?php foreach ($myPosts as $post) :?>
        <div class="card m-auto shadow" style="width: 50rem;">
            <div class="align-self-start  d-flex">
            <?php if($me['profile_image'] !== null) :?>
                <div class=""><img src="/../../../<?= h($me['profile_image']) ?>" id ="profile"/></div>
            <?php else :?>
                <div class=""><img src="<?= h(url) ?>webroot/img/noprofile.jpg" style="height:75px;" id ="profile"></div>
            <?php endif ; ?>
            <div class=""><h6><?= h($me['username']); ?></h6></div>
        </div>
        <div class="justify-content-center text-center">
            <div class="pb-3"><?= h($post->content); ?></div>
            <?php if($post->image_path !== null) :?>
                <div class="in-pict mb-5">
                    <img src="/../../../<?= h($post->image_path) ;?>">
                </div>
            <?php endif;?>
        </div>
    </div>
    <?php endforeach ;?>
</div>
