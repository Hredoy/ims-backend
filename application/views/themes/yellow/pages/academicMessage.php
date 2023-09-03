<div class="row">
    <div class="col-sm-12 text-center">
        <img src="<?= $msg->image ?>" alt="">
    </div>
    <div class="col-sm-12">
        <h2><?= $msg->title ?></h2>
        <h3>নাম: <?= $msg->name ?></h3>
        <h3>ফোন: <?= $msg->phone ?></h3>
        <h3>ইমেইল: <?= $msg->email ?></h3>
    </div>

    <div class="col-sm-12">
        <?= $msg->description; ?>
    </div>
</div>