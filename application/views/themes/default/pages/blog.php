<div class="row">
    <div class="col-sm-12  text-center">
        <img src="<?= $posts->image ?>" alt="">
    </div>
    <div class="col-sm-12">
        <h2><?= $posts->title ?></h2>
        <p>Publish On: <?= date('d/m/Y', strtotime($posts->created_at)) ?> </p>
    </div>

    <div class="col-sm-12">
        <?= $posts->description; ?>
    </div>
</div>