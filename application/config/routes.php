<?php

defined('BASEPATH') or exit('No direct script access allowed');

$route['default_controller'] = 'welcome/index';
$route['user/resetpassword/([a-z]+)/(:any)'] = 'site/resetpassword/$1/$2';
$route['admin/resetpassword/(:any)'] = 'site/admin_resetpassword/$1';
$route['admin/unauthorized'] = 'admin/admin/unauthorized';
$route['parent/unauthorized'] = 'parent/parents/unauthorized';
$route['student/unauthorized'] = 'user/user/unauthorized';
$route['teacher/unauthorized'] = 'teacher/teacher/unauthorized';
$route['accountant/unauthorized'] = 'accountant/accountant/unauthorized';
$route['librarian/unauthorized'] = 'librarian/librarian/unauthorized';
$route['404_override'] = 'welcome/show_404';
$route['translate_uri_dashes'] = FALSE;
$route['cron/(:any)'] = 'cron/index/$1';

//======= front url rewriting==========
$route['page/(:any)'] = 'welcome/page/$1';
$route['notice/(:any)'] = 'welcome/notice/$1';
$route['read/(:any)'] = 'welcome/read/$1';
$route['all-notice'] = 'welcome/allNotice';
$route['blog-list/(:any)'] = 'welcome/blogList/$1';
$route['blog/(:any)'] = 'welcome/blog/$1';
$route['academic-message/(:any)'] = 'welcome/academicMessage/$1';
$route['online_admission'] = 'welcome/admission';
$route['frontend'] = 'welcome';
