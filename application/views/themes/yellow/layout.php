<!DOCTYPE html>
<html dir="<?php echo ($front_setting->is_active_rtl) ? "rtl" : "ltr"; ?>" lang="<?php echo ($front_setting->is_active_rtl) ? "ar" : "en"; ?>">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?php echo $page['title']; ?></title>
    <meta name="title" content="<?php echo $page['meta_title']; ?>">
    <meta name="keywords" content="<?php echo $page['meta_keyword']; ?>">
    <meta name="description" content="<?php echo $page['meta_description']; ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="<?php echo base_url($front_setting->fav_icon); ?>" type="image/x-icon">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <link rel="stylesheet" href="<?php echo base_url() . 'frontend/'; ?>css/lightbox.css">
    <link href="<?php echo base_url() . 'frontend/'; ?>css/style.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo base_url() . 'frontend/'; ?>css/horizontal-menu.css">
    <link rel="stylesheet" href="<?php echo base_url() . 'frontend/'; ?>css/color6.css">
    <link rel="stylesheet" href="<?php echo base_url() . 'frontend/'; ?>css/skin-modes.css">
    <link rel="stylesheet" href="<?php echo $base_assets_url; ?>datepicker/bootstrap-datepicker3.css" />
    <style>
        @import url('https://fonts.maateen.me/kalpurush/font.css');

        body {
            margin: 0;
            font-size: 18px;
            font-weight: 400;
            line-height: 1.5;
            color: #000;
            text-align: left;
            background-color: #f2f5f9;
        }

        *:not(i) {
            font-family: 'Kalpurush', sans-serif !important;

        }

        marquee ul li {
            display: inline-block;
        }

        blockquote:before {
            color: #2e87e7;
            content: open-quote;
            font-size: 4em;
            line-height: 0.1em;
            margin-right: 0.25em;
            vertical-align: -0.4em;
        }

        blockquote,
        q {
            quotes: "\201C" "\201D" "\2018" "\2019";
        }
    </style>
    <script type="text/javascript">
        var base_url = "<?php echo base_url() ?>";
    </script>
    <?php
    //$this->load->view('layout/theme');

    if ($front_setting->is_active_rtl) {
    ?>
        <link href="<?php echo $base_assets_url; ?>rtl/bootstrap-rtl.min.css" rel="stylesheet">
        <link href="<?php echo $base_assets_url; ?>rtl/style-rtl.css" rel="stylesheet">
    <?php
    }
    ?>
    <?php echo $front_setting->google_analytics; ?>

</head>

<body class="headerstyle1 container-fluid">

    <div class="horizontalMenucontainer" style="padding: 0;">
        <div class="card" style="margin: 0;">
            <div class="card-body" style="padding: 0;">
                <header class="header-main">
                    <div class="top-bar">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-xl-8 col-lg-8 col-sm-4 col-7">
                                    <div class="top-bar-left d-flex">
                                        <div class="clearfix">
                                            <div id="banglaDateTime"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-4 col-lg-4 col-sm-8 col-5">
                                    <div class="top-bar-right">
                                        <ul class="custom mx-2">
                                            <li>
                                                <a target="_blank" href="<?php echo site_url('site/userlogin') ?>" class="text-dark"><i class="fa fa-university" aria-hidden="true"></i><span class="mx-1">User</span></a>
                                            </li>
                                            <li>
                                                <a target="_blank" href="<?php echo site_url('site/login') ?>" class="text-dark"><i class="fa fa-university" aria-hidden="true"></i><span class="mx-1">Admin</span></a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <header class="header-search header-logosec p-2 pt-5">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-lg-12 col-md-12">
                                    <div class="header-search-logo">
                                        <a class="header-logo" style="margin-top: -20px;" href="#">
                                            <img src="<?= base_url($front_setting->logo) ?>" style="height:136px; float:left" alt="">
                                        </a>
                                        <h2 style="width:100%;"><?= $school_setting->name ?>
                                        </h2>
                                        <h3><?= $school_setting->address ?>
                                        </h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </header>
                    <div class="row">
                        <div class="col-sm-12 mb-2">
                            <div class="newsTrick row">
                                <div class="col-sm-2"><span class="ticker-title">শেষ আপডেট</span></div>
                                <div class="col-sm-10">
                                    <marquee scrollamount="5" scrolldelay="5" dir="ltr" direction="left" behavior="alternate" onmouseover="if (!window.__cfRLUnblockHandlers) return false; this.stop();" onmouseout="if (!window.__cfRLUnblockHandlers) return false; this.start();">
                                        <ul>
                                            <?php
                                            $currentDate = date('Y-m-d');
                                            $this->db->where('publish_date =', $currentDate);
                                            $notices = $this->db->get('send_notification')->result();
                                            foreach ($notices as $notice) :
                                            ?>
                                                <li> <a href="<?= base_url('notice/' . $notice->id) ?>">» <?= $notice->title ?></a> </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </marquee>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php echo $header; ?>
                </header>
                <main class="mt-3">
                    <div class="container-fluid">
                        <div class="row bg-white">

                            <div class="col-sm-8">
                                <?php if (empty($content) || current_url() == base_url()) { ?>
                                    <?php echo $slider; ?>
                                    <div class="col-lg-12">
                                        <section class="sptb bg-white">
                                            <div class="container-fluid">
                                                <div class="section-title center-block text-center">
                                                    <h2>সকল ই-সেবা</h2>
                                                    <span class="sectiontitle-design"><span class="icons"></span></span>
                                                </div>
                                                <div class="item-all-cat center-block text-center education-categories">
                                                    <div class="row">
                                                        <div class="col-lg-3 col-md-4 col-sm-6">
                                                            <div class="item-all-card text-dark text-center" style="height:160px!important;">
                                                                <a href="#"></a>
                                                                <div class="iteam-all-icon">
                                                                    <img src="images/documents.png" alt="" width="57px" height="57px">
                                                                </div>
                                                                <div class="item-all-text mt-3">
                                                                    <h5 class="mb-0">ভর্তি ফরম</h5>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-3 col-md-4 col-sm-6">
                                                            <div class="item-all-card text-dark text-center" style="height:160px!important;">
                                                                <a href="<?= base_url('online_admission') ?>"></a>
                                                                <div class="iteam-all-icon">
                                                                    <img src="images/school.png" alt="" width="57px" height="57px">
                                                                </div>
                                                                <div class="item-all-text mt-3">
                                                                    <h5 class="mb-0">ফলাফল</h5>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-3 col-md-4 col-sm-6">
                                                            <div class="item-all-card text-dark text-center" style="height:160px!important;">
                                                                <a href="#"></a>
                                                                <div class="iteam-all-icon">
                                                                    <img src="images/lecture.png" alt="" width="57px" height="57px">
                                                                </div>
                                                                <div class="item-all-text mt-3">
                                                                    <h5 class="mb-0">শিক্ষার্তীর তথ্য</h5>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-3 col-md-4 col-sm-6">
                                                            <div class="item-all-card text-dark text-center" style="height:160px!important;">
                                                                <a href="#"></a>
                                                                <div class="iteam-all-icon">
                                                                    <img src="images/scholarship.png" alt="" width="57px" height="57px">
                                                                </div>
                                                                <div class="item-all-text mt-3">
                                                                    <h5 class="mb-0">শিক্ষক কর্মচারীর তথ্য</h5>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-3 col-md-4 col-sm-6">
                                                            <div class="item-all-card text-dark text-center" style="height:160px!important;">
                                                                <a href="#"></a>
                                                                <div class="iteam-all-icon">
                                                                    <img src="images/higher-education.png" alt="" width="57px" height="57px">
                                                                </div>
                                                                <div class="item-all-text mt-3">
                                                                    <h5 class="mb-0">পাঠাগার</h5>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-3 col-md-4 col-sm-6">
                                                            <div class="item-all-card text-dark text-center" style="height:160px!important;">
                                                                <a href="#"></a>
                                                                <div class="iteam-all-icon">
                                                                    <img src="images/company.png" alt="" width="57px" height="57px">
                                                                </div>
                                                                <div class="item-all-text mt-3">
                                                                    <h5 class="mb-0">পরিচালনা কমিঠির তথ্য</h5>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-3 col-md-4 col-sm-6">
                                                            <div class="item-all-card text-dark text-center" style="height:160px!important;">
                                                                <a href="#"></a>
                                                                <div class="iteam-all-icon">
                                                                    <img src="images/company.png" alt="" width="57px" height="57px">
                                                                </div>
                                                                <div class="item-all-text mt-3">
                                                                    <h5 class="mb-0">একাডেমিক তথ্য</h5>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-3 col-md-4 col-sm-6">
                                                            <div class="item-all-card text-dark text-center" style="height:160px!important;">
                                                                <a href="#"></a>
                                                                <div class="iteam-all-icon">
                                                                    <img src="images/company.png" alt="" width="57px" height="57px">
                                                                </div>
                                                                <div class="item-all-text mt-3">
                                                                    <h5 class="mb-0">ক্লাস রুটিন</h5>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-3 col-md-4 col-sm-6">
                                                            <div class="item-all-card text-dark text-center" style="height:160px!important;">
                                                                <a href="#"></a>
                                                                <div class="iteam-all-icon">
                                                                    <img src="images/company.png" alt="" width="57px" height="57px">
                                                                </div>
                                                                <div class="item-all-text mt-3">
                                                                    <h5 class="mb-0">পরীক্ষার রুটিন</h5>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-3 col-md-4 col-sm-6">
                                                            <div class="item-all-card text-dark text-center" style="height:160px!important;">
                                                                <a href="#"></a>
                                                                <div class="iteam-all-icon">
                                                                    <img src="images/company.png" alt="" width="57px" height="57px">
                                                                </div>
                                                                <div class="item-all-text mt-3">
                                                                    <h5 class="mb-0">ফটো গ্যালারী</h5>
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </section>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h2>
                                                        বিদ্যালয় পরিচিতি

                                                    </h2>
                                                </div>
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-sm-4">
                                                            <img src="<?= $school_setting->institute_image ?> " style="margin-top:60px" class="img-fluid">
                                                        </div>
                                                        <div class="col-sm-8">
                                                            <h3><?= $school_setting->name ?> আপনাকে স্বাগতম</h3>
                                                            <blockquote>
                                                                <?= $school_setting->inst_details ?>
                                                            </blockquote>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <?php
                                        $blogCategory = $this->db->get('blogCategory')->result();
                                        foreach ($blogCategory as $category) :
                                            $blogs = $this->db->where('category_id', $category->id)->order_by('id', 'ASC')->limit(5)->get('blog')->result();
                                        ?>
                                            <div class="col-lg-6">
                                                <div class="card">
                                                    <div class="card-header">
                                                        <h3 class="card-title"><?= $category->name ?></h3>
                                                    </div>
                                                    <div class="card-body pb-4">
                                                        <ul class="vertical-scroll" style="overflow-y: hidden; height: 448px;">
                                                            <?php foreach ($blogs as $blog) :
                                                                $words = explode(' ', $blog->title);
                                                                $shortened_title = implode(' ', array_slice($words, 0, 4));
                                                                if (count($words) > 4) {
                                                                    $shortened_title .= '...';
                                                                }
                                                            ?>
                                                                <li class="item">
                                                                    <div class="p-3">
                                                                        <div class="mb-2"><a href="<?= base_url('blog/' . $blog->id) ?>"><span class="fs-16"><?= $shortened_title; ?>
                                                                                </span></a></div>
                                                                        <span class="badge badge-blue  f-12"><i class="fa fa-calender"></i> <?= date('Y-m-d', strtotime($blog->created_at)) ?></span>
                                                                    </div>
                                                                </li>
                                                            <?php endforeach; ?>

                                                        </ul>
                                                    </div>
                                                    <div class="card-footer">
                                                        <a href="<?= base_url('blog-list/' . $category->id) ?>">আরো দেখুন &gt;&gt;</a>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php } else { ?>
                                    <?php echo $content; ?>
                                <?php } ?>
                            </div>
                            <div class="col-sm-4">
                                <div class="card" style="height:430px;">
                                    <div class="card-header">
                                        <h3 class="card-title">নোটিশ বোর্ড</h3>
                                    </div>
                                    <div class="card-body noticeboard" style="padding: 0 10px; height: 300px;">
                                        <marquee scrollamount="1" scrolldelay="1" dir="ltl" direction="up" behavior="scroll" onmouseover="if (!window.__cfRLUnblockHandlers) return false; this.stop();" onmouseout="if (!window.__cfRLUnblockHandlers) return false; this.start();" style="width:100%; height:100%;">
                                            <ul>
                                                <?php
                                                $currentDate = date('Y-m-d');
                                                $this->db->where('publish_date <=', $currentDate);
                                                $notices = $this->db->get('send_notification')->result();
                                                foreach ($notices as $notice) :
                                                ?>
                                                    <li>
                                                        <a href="<?= base_url('/notice/' . $notice->id) ?>"><?= $notice->title ?></a>
                                                    </li>
                                                <?php endforeach; ?>
                                            </ul>
                                        </marquee>
                                    </div>
                                    <div class="card-footer"><a href="<?= base_url('/all-notice') ?>">সকল নোটিশ</a></div>
                                </div>
                                <?php
                                $academic_message = $this->db->get('academic_messages')->result();
                                foreach ($academic_message as $msg) :
                                ?>
                                    <div class="card">
                                        <div class="card-header">
                                            <h3><?= $msg->title ?></h3>
                                        </div>
                                        <div class="card-body">
                                            <img src="<?= $msg->image ?>" style="width:35%; height:auto; float:left; margin-right:5px; border:1px solid #999">
                                            <p><?= $msg->name ?></p>
                                            <p>ফোন <?= $msg->phone ?></p>
                                            <p>ইমেইল <?= $msg->email ?></p>

                                        </div>
                                        <div class="card-footer">
                                            <a href="<?= base_url('academic-message/' . $msg->id) ?>"><?= $msg->title ?> এর বাণী</a>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                                <div class="card">
                                    <div class="card-body">
                                        <iframe src="<?= $front_setting->google_map ?>" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </main>
                <?php echo $footer; ?>

            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>
    <script src="<?= base_url() . 'frontend/' ?>js/lightbox.js"></script>

    <script>
        function updateBanglaDateTime() {
            const gregorianDate = new Date();

            const options = {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
                timeZoneName: 'short'
            };

            const formattedBanglaDateTime = gregorianDate.toLocaleDateString('bn-BD', options);

            document.getElementById('banglaDateTime').textContent = formattedBanglaDateTime;
        }

        // Update the Bangla date and time every second
        setInterval(updateBanglaDateTime, 1000);

        // Initial update
        updateBanglaDateTime();
    </script>
</body>

</html>