<nav aria-label="Page navigation">
    <ul class="pagination justify-content-center">
        <?php if ($previous_link) : ?>
            <li class="page-item">
                <a class="page-link" href="<?php echo $previous_link; ?>" aria-label="Previous">
                    <span aria-hidden="true">&laquo;</span>
                </a>
            </li>
        <?php endif; ?>

        <?php foreach ($links as $link) : ?>
            <li class="page-item <?php echo $link['active']; ?>">
                <a class="page-link" href="<?php echo $link['href']; ?>"><?php echo $link['title']; ?></a>
            </li>
        <?php endforeach; ?>

        <?php if ($next_link) : ?>
            <li class="page-item">
                <a class="page-link" href="<?php echo $next_link; ?>" aria-label="Next">
                    <span aria-hidden="true">&raquo;</span>
                </a>
            </li>
        <?php endif; ?>
    </ul>
</nav>