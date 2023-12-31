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
    <!--print table-->
    <link href="<?php echo base_url(); ?>backend/dist/datatables/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>backend/dist/datatables/css/buttons.dataTables.min.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>backend/dist/datatables/css/dataTables.bootstrap.min.css" rel="stylesheet">
    <!--print table mobile support-->
    <link href="<?php echo base_url(); ?>backend/dist/datatables/css/responsive.dataTables.min.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>backend/dist/datatables/css/rowReorder.dataTables.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <link rel="stylesheet" href="<?php echo base_url() . 'frontend/'; ?>css/lightbox.css">
    <link href="<?php echo base_url() . 'frontend/'; ?>css/style.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo base_url() . 'frontend/'; ?>css/horizontal-menu.css">
    <link rel="stylesheet" href="<?php echo base_url() . 'frontend/'; ?>css/color6.css">
    <link rel="stylesheet" href="<?php echo base_url() . 'frontend/'; ?>css/skin-modes.css">
    <link rel="stylesheet" href="<?php echo $base_assets_url; ?>datepicker/bootstrap-datepicker3.css" />
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <style>
        @import url('https://fonts.maateen.me/kalpurush/font.css');

        body {
            margin: 0;
            font-size: 18px;
            font-weight: 400;
            line-height: 1.5;
            color: #000;
            text-align: left;
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

<body class="headerstyle1 container" style="background: url(' <?= base_url('uploads/system/bg_main.gif') ?>') repeat-y scroll center top #fff !important;">

    <div class="horizontalMenucontainer" style="padding: 0;">
        <div class="card" style="margin: 0;">
            <div class="card-body" style="padding: 0;">
                <header class="header-main">
                    <div class="top-bar">
                        <div class="container">
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
                                                <a target="_blank" href="<?php echo site_url('site/userlogin') ?>" class="text-dark"><i class="fa fa-university" aria-hidden="true"></i><span class="mx-1">User Login</span></a>
                                            </li>
                                            <li>
                                                <a target="_blank" href="<?php echo site_url('site/login') ?>" class="text-dark"><i class="fa fa-university" aria-hidden="true"></i><span class="mx-1">Admin / Teacher Login</span></a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <header class="header-search header-logosec p-2 pt-5">
                        <div class="container">
                            <div class="row">
                                <div class="col-lg-12 col-md-12">
                                    <div class="header-search-logo">
                                        <div class="row">
                                            <?php if ($school_setting->headerBanner) :  ?>
                                                <div class="col-sm-12">
                                                    <img src="<?= base_url('images/' . $school_setting->headerBanner) ?>" alt="<?= $school_setting->name ?>">
                                                </div>
                                            <?php else : ?>
                                                <div class="col-sm-2">
                                                    <a class="header-logo" style="margin-top: -20px;" href="#">
                                                        <img src="<?= base_url($front_setting->logo) ?>" style="height:136px;" alt="<?= $school_setting->name ?>">
                                                    </a>
                                                </div>
                                                <div class="col-sm-7">
                                                    <h2 style="width:100%;font-size: 23px;color: #096cf7; font-weight:bold"><?= $school_setting->name ?>
                                                        <br>
                                                    </h2>
                                                    <h2 style="width:100%;font-size: 21px;color: #096cf7; font-weight:bold">
                                                        <?= $school_setting->eng_name  ?>
                                                    </h2>
                                                    <h3 style="font-size:20px;"><?= $school_setting->address ?>
                                                    </h3>
                                                </div>
                                                <div class="col-sm-3 text-right">
                                                    <img src="<?= base_url('images/govt.png') ?>" style="height:120px;" alt="">
                                                    <img src="<?= base_url('images/probortok.jpg') ?>" style="height:120px;" alt="">
                                                </div>
                                            <?php endif; ?>

                                        </div>
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
                                            $this->db->where('category_id', 11);
                                            $notices = $this->db->get('blog')->result();
                                            foreach ($notices as $notice) :
                                            ?>
                                                <li> <a href="<?= base_url('blog/' . $notice->id) ?>">» <?= $notice->title ?></a> </li>
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
                    <div class="container">
                        <div class="row bg-white">

                            <div class="col-sm-8">
                                <?php if (empty($content) || current_url() == base_url()) { ?>
                                    <?php echo $slider; ?>
                                    <div class="col-lg-12">
                                        <section class="sptb bg-white">
                                            <div class="container">
                                                <div class="section-title center-block text-center">
                                                    <h2>সকল ই-সেবা</h2>
                                                    <span class="sectiontitle-design"><span class="icons"></span></span>
                                                </div>
                                                <div class="item-all-cat center-block text-center education-categories">
                                                    <div class="row">
                                                        <div class="col-lg-3 col-md-4 col-sm-6">
                                                            <div class="item-all-card text-dark text-center" style="height:160px!important;">
                                                                <a href="<?= base_url('/online_admission') ?>"></a>
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
                                                                <a href="<?= base_url('page/admission-rules') ?>"></a>
                                                                <div class="iteam-all-icon">
                                                                    <img src="images/rules.png" alt="" width="57px" height="57px">
                                                                </div>
                                                                <div class="item-all-text mt-3">
                                                                    <h5 class="mb-0">ভর্তির নিয়মাবলি</h5>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-3 col-md-4 col-sm-6">
                                                            <div class="item-all-card text-dark text-center" style="height:160px!important;">
                                                                <a href="<?= base_url('page/teaching-permission-and-recognition') ?>"></a>
                                                                <div class="iteam-all-icon">
                                                                    <img src="images/permission.png" alt="" width="57px" height="57px">
                                                                </div>
                                                                <div class="item-all-text mt-3">
                                                                    <h5 class="mb-0">পাঠদানের অনুমতি ও স্বীকৃতি</h5>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-3 col-md-4 col-sm-6">
                                                            <div class="item-all-card text-dark text-center" style="height:160px!important;">
                                                                <a href="<?= base_url('exam-results') ?>"></a>
                                                                <div class="iteam-all-icon">
                                                                    <img src="images/exam-results.png" alt="" width="57px" height="57px">
                                                                </div>
                                                                <div class="item-all-text mt-3">
                                                                    <h5 class="mb-0">ফলাফল</h5>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-3 col-md-4 col-sm-6">
                                                            <div class="item-all-card text-dark text-center" style="height:160px!important;">
                                                                <a href="<?= base_url('student-list') ?>"></a>
                                                                <div class="iteam-all-icon">
                                                                    <img src="images/personal-information.png" alt="" width="57px" height="57px">
                                                                </div>
                                                                <div class="item-all-text mt-3">
                                                                    <h5 class="mb-0">শিক্ষার্থীর তথ্য</h5>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-3 col-md-4 col-sm-6">
                                                            <div class="item-all-card text-dark text-center" style="height:160px!important;">
                                                                <a href="<?= base_url('teacher-stuff-list') ?>"></a>
                                                                <div class="iteam-all-icon">
                                                                    <img src="images/information.png" alt="" width="57px" height="57px">
                                                                </div>
                                                                <div class="item-all-text mt-3">
                                                                    <h5 class="mb-0">শিক্ষক-কর্মচারীর তথ্য</h5>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-3 col-md-4 col-sm-6">
                                                            <div class="item-all-card text-dark text-center" style="height:160px!important;">
                                                                <a href="<?= base_url('library') ?>"></a>
                                                                <div class="iteam-all-icon">
                                                                    <img src="images/library.png" alt="" width="57px" height="57px">
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
                                                                    <img src="images/group.png" alt="" width="57px" height="57px">
                                                                </div>
                                                                <div class="item-all-text mt-3">
                                                                    <h5 class="mb-0">পরিচালনা কমিটির তথ্য</h5>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-3 col-md-4 col-sm-6">
                                                            <div class="item-all-card text-dark text-center" style="height:160px!important;">
                                                                <a href="<?= base_url('page/academic-information') ?>"></a>
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
                                                                <a href="<?= base_url('class-routine') ?>"></a>
                                                                <div class="iteam-all-icon">
                                                                    <img src="images/calendar.png" alt="" width="57px" height="57px">
                                                                </div>
                                                                <div class="item-all-text mt-3">
                                                                    <h5 class="mb-0">ক্লাস রুটিন</h5>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-3 col-md-4 col-sm-6">
                                                            <div class="item-all-card text-dark text-center" style="height:160px!important;">
                                                                <a href="<?= base_url('exam-routine') ?>"></a>
                                                                <div class="iteam-all-icon">
                                                                    <img src="images/work-schedule.png" alt="" width="57px" height="57px">
                                                                </div>
                                                                <div class="item-all-text mt-3">
                                                                    <h5 class="mb-0">পরীক্ষার রুটিন</h5>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-3 col-md-4 col-sm-6">
                                                            <div class="item-all-card text-dark text-center" style="height:160px!important;">
                                                                <a href="<?= base_url('page/picture') ?>"></a>
                                                                <div class="iteam-all-icon">
                                                                    <img src="images/image-gallery.png" alt="" width="57px" height="57px">
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
                                                <div class="card-header" style="background-color: #609513 !important;color: #fff;">
                                                    <h2>
                                                        বিদ্যালয় পরিচিতি

                                                    </h2>
                                                </div>
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-sm-4">
                                                            <img src="<?= base_url() . $school_setting->institute_image ?> " style="margin-top:60px" class="img-fluid">
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
                                        $blogCategory = $this->db->where(['is_active' => 1, 'status' => 1])->get('blogCategory')->result();
                                        foreach ($blogCategory as $category) :
                                            $blogs = $this->db->where('category_id', $category->id)->order_by('id', 'ASC')->limit(5)->get('blog')->result();
                                        ?>
                                            <div class="col-lg-6">
                                                <div class="card">
                                                    <div class="card-header" style="background-color: #609513 !important;color: #fff;">
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
                                    <div class="card-header" style="background-color: #609513 !important;color: #fff;">
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
                                        <img src="<?= $msg->image ?>" class="card-img-top" alt="...">
                                        <div class="card-body text-center p-0">
                                            <h2 style="background-color: #609513 !important;color: #fff; padding: 10px"><?= $msg->title ?></h2>
                                            <h3 style="padding:15px"><?= $msg->name ?></h3>
                                        </div>
                                        <div class="card-footer text-center">
                                            <a href="<?= base_url('academic-message/' . $msg->id) ?>">বিস্তারিত</a>
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