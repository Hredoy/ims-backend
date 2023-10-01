<div class="row dark-blue">
    <div>
        <svg id="" preserveAspectRatio="xMidYMax meet" class="svg-separator sep3" viewBox="0 0 1600 100" style="display: block;" data-height="100">
            <path class="" style="opacity: 1;fill: #170944 !important;" d="M-40,71.627C20.307,71.627,20.058,32,80,32s60.003,40,120,40s59.948-40,120-40s60.313,40,120,40s60.258-40,120-40s60.202,40,120,40s60.147-40,120-40s60.513,40,120,40s60.036-40,120-40c59.964,0,60.402,40,120,40s59.925-40,120-40s60.291,40,120,40s60.235-40,120-40s60.18,40,120,40s59.82,0,59.82,0l0.18,26H-60V72L-40,71.627z"></path>
            <path class="" style="opacity: 1;fill: #332072 !important;" d="M-40,83.627C20.307,83.627,20.058,44,80,44s60.003,40,120,40s59.948-40,120-40s60.313,40,120,40s60.258-40,120-40s60.202,40,120,40s60.147-40,120-40s60.513,40,120,40s60.036-40,120-40c59.964,0,60.402,40,120,40s59.925-40,120-40s60.291,40,120,40s60.235-40,120-40s60.18,40,120,40s59.82,0,59.82,0l0.18,14H-60V84L-40,83.627z"></path>
            <path class="" style="fill: #170944 !important;" d="M-40,95.627C20.307,95.627,20.058,56,80,56s60.003,40,120,40s59.948-40,120-40s60.313,40,120,40s60.258-40,120-40s60.202,40,120,40s60.147-40,120-40s60.513,40,120,40s60.036-40,120-40c59.964,0,60.402,40,120,40s59.925-40,120-40s60.291,40,120,40s60.235-40,120-40s60.18,40,120,40s59.82,0,59.82,0l0.18,138H-60V96L-40,95.627z"></path>
        </svg>
    </div>
</div>
<footer>
    <div>
        <footer class="bg-dark text-white">
            <div class="footer-main" style="border-top: 0px;">
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
                            <p style="padding: 0;margin: 0px;font-size: 14px;text-align: center;color: #fff;font-weight: 600;border-bottom: 1px solid white;width: 30%;margin: auto;">
                                প্রযুক্তিগত সহায়তায়</p>
                            <a target="_blank" href="https://vortexbytz.com/" style="display:inline-block;">
                                <img src="<?= base_url() . 'images/146x44-dark.png' ?>" style="width:150px; height:auto;">
                            </a>
                            <span style="font-size: 27px;font-weight: bold;">&</span>
                            <a target="_blank" href="https://vortexbytz.com/" style="display:inline-block;">
                                <img src="<?= base_url() . 'images/footerlogo.png' ?>" style="width:150px; height:auto;">
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
    </div>
</footer>