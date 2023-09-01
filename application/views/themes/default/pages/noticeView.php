<div class="row">
    <div class="col-sm-12  text-center">
        <h2><?= $data['title'] ?></h2>
        <p>Publish On: <?= date('d/m/Y', strtotime($data['publish_date'])) ?> - Notice Date: <?= date('d/m/Y', strtotime($data['date'])) ?> </p>
    </div>

    <div class="col-sm-12">
        <?= $data['message'] ?>
    </div>
</div>