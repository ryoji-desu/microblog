<?php
$this->assign('title','Mainpage');
$me = $this->request->getSession()->read('Auth.User');
$check_retweet = 0;
$check_like = 0;
?>


<h1>
    Mainpage
</h1>
<?= $this->Form->create("Post", ['type' => 'file','action' =>'add']); ?>
<div class="form-group">
    <?= $this->Form->input('content',['rows'=>'5','class'=>"form-control"]); ?>
</div>
<div class="form-group">
    <?= $this->Form->input('image',['type' => "file",'class'=>"form-control"]); ?>
</div>
    <?= $this->Form->button('Add',["class"=>"btn btn-primary mb-5"]); ?>
    <?php if(isset($content)) :?>
        <div class="mb-3 text-danger"><?= h($content) ;?></div>
    <?php endif ;?>
    <?= $this->Form->end(); ?>
    <?php if(!is_Object($posts) && $posts == 0) :?>
        <h5>No Post<a href="<?= h(url) ?>users/view" class="pl-3">you can follow from here</a></h5>
    <?php else :?>
        <?php foreach ($posts as $post) :?>
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
                    <?php if($post->original_delted == 1) :?>
                        <p>this original post has alredy deleted<p>
                        <?php continue;?>
                    <?php else :?>
                        <div class="pb-3"><?= h($post->content); ?></div>
                        <?php if($post->picture !== null) :?>
                            <div class="in-pict mb-5">
                                <img src="/../../../<?= h($post->picture) ;?>">
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
                    <?php endif ;?>
                </div>
                <div class="d-flex align-items-end">
                    <div class="container">
                    <div class="row pl-5">
                    <div class="col">
                        <?=
                        $this->Form->postLink(
                            'comment',
                            ['controller'=>'Comments','action'=>'view', $post->id]
                        );
                        ?>
                    </div>
                    <div class="col">
                        <div>
                            <?php if(empty($retweet_count)) :?>
                                <p>0</p>
                            <?php else :?>
                                <?php foreach ($retweet_count as $key => $value) :?>
                                    <?php if ($post->id == $key):?>
                                        <p><?= h($value) ;?></p>
                                        <?php $check_retweet = 1;?>
                                    <?php endif ;?>
                                <?php endforeach ;?>
                                <?php if ($check_retweet === 0) :?>
                                    <p>0</p>
                                <?php else :?>
                                    <?php $check_retweet = 0;?>
                                <?php endif ;?>
                            <?php endif ;?>
                        </div>
                        <?= $this->Form->create(null, ['url' =>['controller'=>'Retweets','action'=>'check']]); ?>
                            <?php if($post->retweet == 10 && $post->rname == $me['username']) :?>
                                <?= $this->Form->button('unretweet',['name'=>'unretweet','value'=>$post->id,"class"=>"btn btn-sm btn-success"]); ?>
                            <?php elseif($post->retweet == 10 && $post->rname !== $me['username'] && in_array($post->id,$myretweets_id)) :?>
                                <p>this post is retweeted by <?= h($post->rname) ;?></p><p>already retweet</p>
                            <?php elseif($post->retweet == 10 && $post->rname !== $me['username'] && !in_array($post->id,$myretweets_id)) :?>
                                <p>this post is retweeted by <?= h($post->rname) ;?></p>
                                <?= $this->Form->button('retweet',['name'=>'retweet','value'=>$post->id,"class"=>"btn btn-sm btn-primary"]); ?>
                                <?php if ($post->orgiginal_user !== null) :?>
                                    <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#myModal" data-username="<?= h($post->orgiginal_user) ?>" data-id="<?= h($post->original_id) ?>" data-content="<?= h($post->orginal_content) ?>">retweet with comment</button>
                                <?php else :?>
                                    <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#myModal" data-username="<?= h($post->username) ?>" data-id="<?= h($post->id) ?>" data-content="<?= h($post->content) ?>">retweet with comment</button>
                                <?php endif ;?>
                            <?php elseif($post->retweet !== 10 && in_array($post->id,$myretweets_id)  ) :?>
                                <p>already retweet</p>
                            <?php elseif($post->orgiginal_user !== null &&  $post->user_id == $me['user_id']) :?>
                                <p>already retweet</p>
                            <?php else :?>
                                <?= $this->Form->button('retweet',['name'=>'retweet','value'=>$post->id,"class"=>"btn btn-sm btn-primary"]); ?>
                                <?php if ($post->orgiginal_user !== null) :?>
                                    <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#myModal" data-username="<?= h($post->orgiginal_user) ?>" data-id="<?= h($post->original_id) ?>" data-content="<?= h($post->orginal_content) ?>">retweet with comment</button>
                                <?php else :?>
                                    <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#myModal" data-username="<?= h($post->username) ?>" data-id="<?= h($post->id) ?>" data-content="<?= h($post->content) ?>">retweet with comment</button>
                                <?php endif ;?>
                            <?php endif ;?>
                        <?= $this->Form->end(); ?>
                    </div>
                    <div class="col">
                        <div>
                            <?php if(empty($favorite_count)) :?>
                                <p id= "<?= h($post->id) ?>" class= "count<?= h($post->id) ;?>">0</p>
                            <?php else :?>
                                <?php foreach ($favorite_count as $key => $value) :?>
                                    <?php if ($post->id == $key):?>
                                        <p id= "<?= h($post->id) ?>" class= "count<?= h($post->id) ;?>"><?= h($value) ;?></p>
                                        <?php $check_like = 1;?>
                                    <?php endif ;?>
                                <?php endforeach ;?>
                                <?php if ($check_like === 0) :?>
                                    <p id= "<?= h($post->id) ?>" class= "count<?= h($post->id) ;?>">0</p>
                                <?php else :?>
                                    <?php $check_like = 0;?>
                                <?php endif ;?>
                            <?php endif ;?>
                        </div>
                            <?php if(in_array($post->id,$mylikes_id)) :?>
                                <?= $this->Form->button('unlike',['name'=>'unlike','value'=>$post->id,'type'=>'button','id'=>"btn$post->id","class"=>"btn$post->id btn btn-sm btn-success"]); ?>
                            <?php else :?>
                                <?= $this->Form->button('like',['name'=>'like','value'=>$post->id,'type'=>'button','id'=>"btn$post->id","class"=>"btn$post->id btn btn-sm btn-primary"]); ?>
                            <?php endif ;?>
                    </div>
                        <?php if($post->user_id == $me['user_id'] && $post->retweet !== 10 ) :?>
                            <div class="col"><?= $this->Html->link('[Edit]',['action' => 'edit', $post->id]); ?></div>
                            <div class="col"><?=
                                $this->Form->postLink(
                                    '[x]',
                                    ['action'=>'delete', $post->id],
                                    ['confirm' => 'Are you sure?']
                                );
                                ?>
                            </div>
                        <?php else :?><div class="col"></div>
                        <?php endif ;?>
                    <div class="col"><p><?= $post->modified->format('g:i A, F d') ;?></p></div>
                    </div>
                    </div>
                </div>
            </div>
        <?php endforeach ;?>
    <?php endif ;?>

<nav aria-label="Page Navigation">
    <ul class="pagination">
        <?php for($o = 1; $o <= $pages; $o++) :?>
                <?php echo '<li class="page-item">'.'<a class="page-link" href=\'http://localhost:8080/posts/?page_id='. $o. '\')>'. $o. '</a>'. '　'.'</li>'; ;?>
        <?php endfor ;?>
    </ul>
</nav>
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg" role="document" >
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Retweet</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="閉じる">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
          <?= $this->Form->create("Post", ['type' => 'file','action' =>'add']); ?>
          <div class="form-group">
              <?= $this->Form->input('content',['rows'=>'15','class'=>"form-control"]); ?>
                  <div class="card m-auto shadow w-75 ">
                      <div class="align-self-start  d-flex">
                          <div class=""><img src="<?= h(url) ?>webroot/img/noprofile.jpg" style="height:50px;" id ="profile"></div>
                          <div class="modal-username"><h6></h6></div>
                      </div>
                      <div class="justify-content-center text-center">
                          <div class="pb-3 modal-postcontent"></div>
                      </div>
                  </div>
              </div>
          <div class="form-group">
              <?= $this->Form->input('post_id',['type'=>'hidden','class'=>'modal-postid']); ?>
              <?= $this->Form->input('image',['type' => "file",'class'=>"form-control"]); ?>
          </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">close</button>
        <?= $this->Form->button('Retweet',["class"=>"btn btn-primary"]); ?>
        <?= $this->Form->end(); ?>
      </div>
    </div>
  </div>
</div>
<script>
    $('button[id^="btn"]').click(function() {
        var post_id = $(this).val();
        var name = $(this).attr('name');
        var count =$('#'+post_id).text();

        console.log(count);
        var array = {[name] : post_id};
        $.ajax({
           type:'post',
           url:'http://localhost:8080/likes/check',
           headers: {
               'X-CSRF-Token': '<?= h($this->request->getParam('_csrfToken')); ?>'
           },
           dataType: 'json',
           contentType: 'application/json',
           data: JSON.stringify(array),
           success:
               function (result) {
                   // $(this).attr('name', result);
                   var id = '.'+'btn'+post_id
                   if (name == 'like') {
                       $(id).text('unlike');
                       $(id).attr('name', 'unlike');
                       $(id).removeClass("btn btn-primary");
                       $(id).addClass("btn btn-success");
                       $('.'+'count'+post_id).text(Number(count)+1);
                   } else {
                       $(id).text('like');
                       $(id).attr('name', 'like');
                       $(id).removeClass("btn btn-success");
                       $(id).addClass("btn btn-primary");
                       $('.'+'count'+post_id).text(Number(count)-1);
                   }
               }
       });
   })
    $('#myModal').on('shown.bs.modal', function (event) {
         var button = $(event.relatedTarget);
         var username = button.data("username");
         var content = button.data("content");
         var post_id = button.data("id");
         var modal = $(this);
         modal.find(".modal-username").text(username);
         modal.find(".modal-postcontent").text(content);
         modal.find(".modal-postid").val(post_id);
        $('#myInput').trigger('focus')
    })
</script>
