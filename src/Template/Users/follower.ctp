<?php
$this->assign('title','view');
$me = $this->request->getSession()->read('Auth.User');
?>
    <div class="pt-4"><h1>Followers</h1></div>
    <?php foreach($myfollower as $user) :?>
        <div class="card m-auto shadow" style="width: 50rem;">
            <div class="align-self-start  d-flex">
                <?php if($user->image !== null) :?>
                    <div class=""><img src="<?= h(url) ?><?= h($me['profile_image']) ?>" id ="profile"></div>
                <?php else :?>
                    <div class=""><img src="/../../../microblog/microblog1/webroot/img/noprofile.jpg" style="height:75px;" id ="profile"></div>
                <?php endif ; ?>
                <div class=""><h6><?=
                                  $this->Form->postLink(
                                      "$user->username",
                                      ['controller'=>'Users','action'=>'profile', $user->id]
                                  );
                                  ?></h6></div>
            </div>
    <?php endforeach ;?>

<script>

    $('button').click(function() {
        var user_id = $(this).val();
        var name = $(this).attr('name');
        var array = {[name] : user_id};
        $.ajax({
           type:'post',
           url:'/microblog/microblog1/follows/check',
           headers: {
               'X-CSRF-Token': '<?= h($this->request->getParam('_csrfToken')); ?>'
           },
           dataType: 'json',
           contentType: 'application/json',
           data: JSON.stringify(array),
           success:
               function (result) {
                   // $(this).attr('name', result);
                   if (name == 'follow') {
                       $('#'+user_id).text('unfollow');
                       $('#'+user_id).attr('name', 'unfollow');
                       $('#'+user_id).removeClass("btn btn-primary");
                       $('#'+user_id).addClass("btn btn-success");
                   } else {
                       $('#'+user_id).text('follow');
                       $('#'+user_id).attr('name', 'follow');
                       $('#'+user_id).removeClass("btn btn-success");
                       $('#'+user_id).addClass("btn btn-primary");
                   }
               }
       });
        // ,
        // function (data) {
        //     alert("読み込み失敗");
        // }
    // );
   })
</script>
