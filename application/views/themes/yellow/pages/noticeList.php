<div class="row">
    <div class="col-sm-12">
        <h2>
            সকল নোটিশ-
        </h2>
    </div>
    <?php foreach ($datas as $data) : ?>
        <div class="col-sm-12">
            <a href="<?= base_url('notice/' . $data->id) ?>">
                <div class="card">
                    <div class="card-body">
                        <?= $data->title ?>
                    </div>
                </div>
            </a>
        </div>
    <?php endforeach; ?>
</div>