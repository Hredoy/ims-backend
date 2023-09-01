<div class="row">
    <div class="col-sm-12">
        <h2>
            <?= $category->name ?>
        </h2>
    </div>
    <?php foreach ($posts as $post) :
    ?>
        <div class="col-sm-12">
            <a href="<?= base_url('blog/' . $post->id) ?>">
                <div class="card">
                    <div class="card-body">
                        <?= $post->title ?>
                    </div>
                </div>
            </a>
        </div>
    <?php endforeach; ?>
</div>