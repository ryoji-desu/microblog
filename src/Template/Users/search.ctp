<?php
$this->assign('title','search');
?>
<div class = "container">
    <h2>search</h2>
    <?= $this->Form->create('User',['action' =>'search']); ?>
    <div class="form-group">
    <?= $this->Form->input('search',['class'=>"form-control w-50"]); ?>
    </div>
    <?= $this->Form->button('search',["class"=>"btn btn-primary"]); ?>
    <?= $this->Form->end(); ?>
<div class="mt-3"></div>
<?php if(isset($users)) :?>
    <h2>result of <?= h($data) ;?></h2>
    <section class="bg-light text-center py-5 mt-2">
        <h2 class="mb-5">Users(<?php echo h($countUser) ?>)</h2>
          <div class="container">
            <table class="table">
              <thead>
                <tr><th>profile_image</th><th>username</th></tr>
              </thead>
              <tbody>
                  <?php foreach($users as $user) :?>
                  <tr><td><?php if($user['image'] !==null) :?><img src="/../../../<?= h($user['image']) ;?>"><?php else :?><?= h('no image') ?><?php endif;?></div></td>
                      <td><?= $this->Html->link($user['username'],['action' => 'profile', $user->id]); ?></td></tr>
                  <?php endforeach ;?>
              </tbody>
            </table>
          </div>
    </section>
<?php endif ;?>
<?php if(isset($posts)) :?>
    <h3>Posts(<?php echo h($countPosts) ?>)</h3>
    <?php foreach($posts as $post) :?>
        <div class="card m-auto shadow" style="width: 50rem;">
            <div class="align-self-start  d-flex">
                <?php if($post->profile !== null) :?>
                    <div class=""><img src="/../../../<?= h($post->profile) ?>" id ="profile"></div>
                <?php else :?>
                    <div class=""><img src="<?= h(url) ?>webroot/img/noprofile.jpg" style="height:75px;" id ="profile"></div>
                <?php endif ; ?>
                <div class=""><h6><?= h($post->username); ?></h6></div>
            </div>
            <div class="justify-content-center text-center">
                <div class="pb-3"><?= h($post->content); ?></div>
                <?php if($post->image_path !== null) :?>
                    <div class="in-pict mb-5">
                        <img src="/../../../<?= h($post->image_path) ;?>">
                    </div>
                <?php endif;?>
                <?php if($post->orgiginal_user !==null) :?>
                    <div class="card m-auto shadow" style="width: 35rem;">
                        <div class="align-self-start  d-flex">
                            <?php if($post->original_profile !== null) :?>
                                <div class=""><img src="/../../../<?= h($post->original_profile) ?>" id ="profile"></div>
                            <?php else :?>
                                <div class=""><img src="<?= h(url) ?>webroot/img/noprofile.jpg" style="height:75px;" id ="profile"></div>
                            <?php endif ; ?>
                            <div class=""><h6><?= h($post->orgiginal_user); ?></h6></div>
                        </div>
                        <div class="justify-content-center text-center">
                            <div class="pb-3"><?= h($post->orginal_content); ?></div>
                            <?php if($post->original_image !== null) :?>
                                <div class="in-pict mb-5">
                                    <img src="/../../../<?= h($post->original_image) ;?>">
                                </div>
                            <?php endif ;?>
                        </div>
                    </div>
                <?php endif ;?>
            </div>
        </div>
    <?php endforeach ;?>
<?php endif ;?>
