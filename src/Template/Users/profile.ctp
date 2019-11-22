<?php
$this->assign('title','profile');
foreach($hisInfo as $hisInfo){
    $hisName = $hisInfo->username;
    $hisPicture = $hisInfo->profile_image;
    $hisId = $hisInfo->user_id;
    break;
}
?>
<div class="pt-3"></div>
<div class="card m-auto shadow" style="width: 40rem; height:15rem;">
    <div class="justify-content-center text-center">
        <div class="pt-5"></div>
        <?php if($hisPicture !== null) :?>
            <img src="/../../../<?= h($hisPicture) ?>" id ="profile_big"/>
        <?php else :?>
            <img src="<?= h(url) ?>webroot/img/noprofile.jpg" style="height:75px;" id ="profile_big">
        <?php endif ; ?>
    </div>
    <div class="text-right">
        <?php if($follow == 'unfollow') :?>
            <p><?= $this->Form->button('unfollow',["class"=>"btn btn-success",'name'=>'unfollow','type'=>'button','id'=>$hisId,'value'=>$hisId]); ?></p>
        <?php elseif($follow == 'me') :?>
            <p>me</p>
        <?php else :?>
            <p><?= $this->Form->button('follow',["class"=>"btn btn-primary",'id'=>$hisId,'name'=>'follow','type'=>'button','value'=>$hisId]); ?></p>
        <?php endif ; ?>
    </div>
</div>
<h1 class="pb-3"><?= h($hisInfo->username) ?>'s posts(<?= h($count) ?>)</h1>
    <?php foreach($hisPosts as $hisPost) :?>
        <div class="card m-auto shadow" style="width: 50rem;">
            <div class="align-self-start  d-flex">
                <?php if($hisPicture !== null) :?>
                    <div class=""><img src="/../../../<?= h($hisPicture) ?>" id ="profile"></div>
                <?php else :?>
                    <div class=""><img src="<?= h(url) ?>webroot/img/noprofile.jpg" style="height:75px;" id ="profile"></div>
                <?php endif ; ?>
                <div class=""><h6><?= h($hisName); ?></h6></div>
            </div>
            <div class="justify-content-center text-center">
                <div class="pb-3"><?= h($hisPost->content); ?></div>
                <?php if($hisPost->image_path !== null) :?>
                    <div class="in-pict mb-5">
                        <img src="/../../../<?= h($hisPost->image_path) ;?>">
                    </div>
                <?php endif;?>
            </div>
    <?php endforeach ;?>
<?= $this->Form->end(); ?>
<script>

    $('button').click(function() {
        var user_id = $(this).val();
        var name = $(this).attr('name');
        var array = {[name] : user_id};
        $.ajax({
           type:'post',
           url:'/exercise/microblog1/follows/check',
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
