<footer>
    <div>
        <footer class="bg-dark text-white">
            <div class="footer-main">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-4 col-sm-6">
                            <h3 class="fo-title">গুরুত্বপুর্ণ ওয়েবসাইটের লিঙ্ক</h3>
                            <ul class="f1-list">
                                <?php
                                foreach ($footer_menus as $footer_menu_key => $footer_menu_value) {

                                    $cls_menu_dropdown = "";
                                    if (!empty($footer_menu_value['submenus'])) {

                                        $cls_menu_dropdown = "dropdown";
                                    }
                                ?>
                                    <li class="<?php echo $cls_menu_dropdown; ?>">
                                        <?php
                                        $top_new_tab = '';
                                        $url = '#';
                                        if ($footer_menu_value['open_new_tab']) {
                                            $top_new_tab = "target='_blank'";
                                        }
                                        if ($footer_menu_value['ext_url']) {
                                            $url = $footer_menu_value['ext_url_link'];
                                        } else {
                                            $url = site_url($footer_menu_value['page_url']);
                                        }
                                        ?>

                                        <a href="<?php echo $url; ?>" <?php echo $top_new_tab; ?>><?php echo $footer_menu_value['menu']; ?></a>

                                        <?php
                                        ?>


                                    </li>
                                <?php
                                }
                                ?>
                            </ul>
                        </div><!--./col-md-3-->

                        <div class="col-md-4 col-sm-6">
                            <h3 class="fo-title">আমাদের অনুসরণ করুন</h3>
                            <ul class="social">
                                <?php $this->view('/themes/default/social_media'); ?>
                            </ul>
                        </div><!--./col-md-3-->
                        <div class="col-md-4 col-sm-6">
                            <h3 class="fo-title">যোগাযোগ ও পরামর্শ</h3>
                            <div class="row">
                                <div class="col-sm-12">
                                    <p><strong><i class="fa fa-map-marker"></i> ক্যাম্পাস :<br>
                                        </strong><?php echo $school_setting->address; ?></p>
                                </div>
                                <div class="col-sm-12">
                                    <p><strong><i class="fa fa-phone"></i> Phone:</strong>
                                        <a href="mailto:<?php echo $school_setting->email; ?>"> <?= $school_setting->phone ?> </a>
                                    </p>
                                </div>
                                <div class="col-sm-12">
                                    <p><strong><i class="fa fa-chrome"></i> Web :</strong> <a href="<?= base_url(); ?>"><?= site_url() ?></a><br>
                                    </p>
                                </div>
                                <div class="col-sm-12">
                                    <strong><i class="fa fa-envelope"></i> E-mail :</strong> <a href="mailto:<?= $school_setting->email ?>"><?= $school_setting->email ?></a>

                                </div>
                            </div>
                        </div><!--./col-md-3-->
                        <div class="col-md-3 col-sm-6">
                            <a class="twitter-timeline" data-tweet-limit="1" href="#"></a>
                        </div><!--./col-md-3-->
                    </div><!--./row-->
                    <div class="row">

                        <div class="col-md-12 text-center">
                            <a target="_blank" href="https://edufics.com/" style="display:inline-block;">
                                <p style="padding: 0;margin: 0px;font-size: 14px;text-align: center;color: #fff;font-weight: 600;border-bottom: 1px solid white;">
                                    প্রযুক্তিগত সহায়তায়</p>
                                <img src="<?= base_url() . 'images/footerlogo.png' ?>" style="width:150px; height:auto;">
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
    </div>
</footer>