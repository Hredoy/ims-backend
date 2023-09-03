<div id="sticky-wrapper" class="sticky-wrapper is-sticky" style="height: 64.5833px;">
    <div class="header-style horizontal-main bg-dark-transparent clearfix" style="width: 1176px;  z-index: inherit;">
        <div class="horizontal-mainwrapper container clearfix">
            <nav class="horizontalMenu clearfix d-md-flex">
                <div class="horizontal-overlapbg"></div>
                <div class="horizontal-overlapbg active"></div>
                <ul class="horizontalMenu-list">
                    <?php
                    foreach ($main_menus as $menu_key => $menu_value) {
                        $submenus = false;
                        $cls_menu_dropdown = "";
                        $menu_selected = "";
                        if ($menu_value['page_slug'] == $active_menu) {
                            $menu_selected = "active";
                        }
                        if (!empty($menu_value['submenus'])) {
                            $submenus = true;
                            $cls_menu_dropdown = "dropdown";
                        }
                    ?>

                        <li aria-haspopup="true" class="<?php echo $menu_selected . " " . $cls_menu_dropdown; ?>">
                            <?php
                            if (!$submenus) {
                                $top_new_tab = '';
                                $url = '#';

                                if ($menu_value['open_new_tab']) {
                                    $top_new_tab = "target='_blank'";
                                }
                                if ($menu_value['ext_url']) {
                                    $url = $menu_value['ext_url_link'];
                                } else {
                                    $url = site_url($menu_value['page_url']);
                                }
                            ?>
                                <a href="<?php echo $url; ?>" <?php echo $top_new_tab; ?> class="<?php echo $menu_selected . " " . $cls_menu_dropdown; ?>"><?php echo $menu_value['menu']; ?></a>
                            <?php
                            } else {
                                $child_new_tab = '';
                                $url = '#';
                            ?>
                                <span class="horizontalMenu-click">
                                    <i class="horizontalMenu-arrow fa fa-angle-down"></i>
                                </span>
                                <a href="#" target=""><?php echo $menu_value['menu']; ?> <b class="caret"></b></a>
                                <ul class="sub-menu">
                                    <?php
                                    foreach ($menu_value['submenus'] as $submenu_key => $submenu_value) {
                                        if ($submenu_value['open_new_tab']) {
                                            $child_new_tab = "target='_blank'";
                                        }
                                        if ($submenu_value['ext_url']) {
                                            $url = $submenu_value['ext_url_link'];
                                        } else {
                                            $url = site_url($submenu_value['page_url']);
                                        }
                                    ?>
                                        <li aria-haspopup="true"><a href="<?php echo $url; ?>" <?php echo $child_new_tab; ?>><?php echo $submenu_value['menu'] ?></a></li>
                                    <?php
                                    }
                                    ?>

                                </ul>

                            <?php
                            }
                            ?>
                        </li>
                    <?php
                    }
                    ?>
                </ul>
            </nav>
        </div>
    </div>
</div>