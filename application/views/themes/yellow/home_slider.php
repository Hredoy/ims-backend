<?php
if (isset($banner_images) && !empty($banner_images)) {
?>
    <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="true">
        <div class="carousel-indicators">
            <?php
            $banner_firstn = TRUE;
            $i = 0;
            foreach ($banner_images as $banner_img_key => $banner_img_value) {
            ?>
                <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="<?= $i ?>" class="<?php if ($i == 0) echo 'active'; ?>" aria-current="true" aria-label="Slide <?= $i ?>"></button>

            <?php
                $banner_firstn = false;
                $i++;
            } ?>
        </div>
        <div class="carousel-inner">
            <?php
            $banner_first = TRUE;
            $j = 0;
            foreach ($banner_images as $banner_img_key => $banner_img_value) {
            ?>
                <div class="carousel-item <?php if ($j == 0) echo 'active'; ?>">
                    <img src="<?php echo base_url($banner_img_value->dir_path . $banner_img_value->img_name); ?>" class="d-block w-100" alt="...">
                </div>

            <?php $j++;
            } ?>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>

<?php
}
?>