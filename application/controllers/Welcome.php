<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Welcome extends Front_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->config('form-builder');
        $this->load->config('app-config');
        $this->load->library('pagination');
        $this->load->library(array('mailer', 'form_builder'));
        $this->load->model(array('frontcms_setting_model', 'complaint_Model', 'Visitors_model', 'onlinestudent_model', 'filetype_model'));
        $this->blood_group = $this->config->item('bloodgroup');
        $this->load->library('Ajax_pagination');
        $this->load->library('module_lib');
        $this->load->library('captchalib');
        $this->banner_content         = $this->config->item('ci_front_banner_content');
        $this->perPage                = 12;
        $ban_notice_type              = $this->config->item('ci_front_notice_content');
        $this->sch_setting_detail     = $this->setting_model->getSetting();

        $this->data['banner_notices'] = $this->cms_program_model->getByCategory($ban_notice_type, array('start' => 0, 'limit' => 5));
        $this->load->model(array('batchsubject_model', 'examgroup_model', 'exam_model', 'customfield_model', 'feecategory_model', 'subjecttimetable_model', 'staff_model', 'examsubject_model', 'examgroupstudent_model', 'feereminder_model', 'filetype_model', 'session_model', 'marksheet_model'));
        $this->exam_type = $this->config->item('exam_type');
    }

    public function show_404()
    {
        $this->load->view('errors/error_message');
    }

    public function index()
    {
        $setting                     = $this->frontcms_setting_model->get();
        $this->data['active_menu']   = 'home';
        $this->data['page_side_bar'] = $setting->is_active_sidebar;
        $home_page                   = $this->config->item('ci_front_home_page_slug');
        $result                      = $this->cms_program_model->getByCategory($this->banner_content);
        $this->data['page']          = $this->cms_page_model->getBySlug($home_page);
        if (!empty($result)) {
            $this->data['banner_images'] = $this->cms_program_model->front_cms_program_photos($result[0]['id']);
        }
        $this->load_theme('home');
    }

    public function page($slug)
    {
        $page = $this->cms_page_model->getBySlug(urldecode($slug));
        if (!$page) {
            $this->data['page'] = $this->cms_page_model->getBySlug('404-page');
        } else {

            $this->data['page'] = $page;
        }

        if ($page['is_homepage']) {
            redirect('frontend');
        }
        $this->data['active_menu']       = $slug;
        $this->data['page_side_bar']     = $this->data['page']['sidebar'];
        $this->data['page_content_type'] = "";
        if (!empty($this->data['page']['category_content'])) {
            $content_array = $this->data['page']['category_content'];
            reset($content_array);
            $first_key            = key($content_array);
            $totalRec             = count($this->cms_program_model->getByCategory($content_array[$first_key]));
            $config['target']     = '#postList';
            $config['base_url']   = base_url() . 'welcome/ajaxPaginationData';
            $config['total_rows'] = $totalRec;
            $config['per_page']   = $this->perPage;
            $config['link_func']  = 'searchFilter';
            $this->ajax_pagination->initialize($config);
            //get the posts data
            $this->data['page']['category_content'][$first_key] = $this->cms_program_model->getByCategory($content_array[$first_key], array('limit' => $this->perPage));

            $this->data['page_content_type'] = $content_array[$first_key];
            //load the view
        }
        $this->data['page_form'] = false;

        if (strpos($page['description'], '[form-builder:') !== false) {
            $this->data['page_form'] = true;
            $start                   = '[form-builder:';
            $end                     = ']';

            $form_name = $this->customlib->getFormString($page['description'], $start, $end);

            $form = $this->config->item($form_name);

            $this->data['form_name'] = $form_name;
            $this->data['form']      = $form;

            if (!empty($form)) {
                foreach ($form as $form_key => $form_value) {

                    if (isset($form_value['validation'])) {
                        $display_string = ucfirst(preg_replace('/[^A-Za-z0-9\-]/', ' ', $form_value['id']));
                        if ($form_value['id'] == "captcha") {
                            $this->form_validation->set_rules($form_value['id'], $display_string, $form_value['validation']);
                        } else {
                            $this->form_validation->set_rules($form_value['id'], $display_string, $form_value['validation']);
                        }
                    }
                }

                if ($this->form_validation->run() == false) {
                } else {
                    $setting = $this->frontcms_setting_model->get();

                    $response_message = $form['email_title']['mail_response'];
                    $record           = $this->input->post();

                    if ($record['form_name'] == 'contact_us') {
                        $email     = $this->input->post('email');
                        $name      = $this->input->post('name');
                        $cont_data = array(
                            'name'    => $name . " <a href='mailto:$email'>(" . $email . ")</a>",
                            'source'  => 'Online',
                            'email'   => $this->input->post('email'),
                            'purpose' => $this->input->post('subject'),
                            'date'    => date('Y-m-d'),
                            'note'    => $this->input->post('description') . " (Sent from online front site)",
                        );
                        $visitor_id = $this->Visitors_model->add($cont_data);
                    }

                    if ($record['form_name'] == 'complain') {
                        $complaint_data = array(
                            'complaint_type' => 'General',
                            'source'         => 'Online',
                            'name'           => $this->input->post('name'),
                            'email'          => $this->input->post('email'),
                            'contact'        => $this->input->post('contact_no'),
                            'date'           => date('Y-m-d'),
                            'description'    => $this->input->post('description'),
                        );
                        $complaint_id = $this->complaint_Model->add($complaint_data);
                    }

                    $email_subject = $record['email_title'];
                    $mail_body     = "";
                    unset($record['email_title']);
                    unset($record['submit']);
                    foreach ($record as $fetch_k_record => $fetch_v_record) {
                        $mail_body .= ucwords($fetch_k_record) . ": " . $fetch_v_record;
                        $mail_body .= "<br/>";
                    }
                    if (!empty($setting) && $setting->contact_us_email != "") {

                        $this->mailer->send_mail($setting->contact_us_email, $email_subject, $mail_body);
                    }

                    $this->session->set_flashdata('msg', $response_message);
                    redirect('page/' . $slug, 'refresh');
                }
            }
        }

        $this->load_theme('pages/page');
    }

    public function ajaxPaginationData()
    {
        $page              = $this->input->post('page');
        $page_content_type = $this->input->post('page_content_type');
        if (!$page) {
            $offset = 0;
        } else {
            $offset = $page;
        }
        $data['page_content_type'] = $page_content_type;
        //total rows count
        $totalRec = count($this->cms_program_model->getByCategory($page_content_type));
        //pagination configuration
        $config['target']     = '#postList';
        $config['base_url']   = base_url() . 'welcome/ajaxPaginationData';
        $config['total_rows'] = $totalRec;
        $config['per_page']   = $this->perPage;
        $config['link_func']  = 'searchFilter';
        $this->ajax_pagination->initialize($config);
        //get the posts data
        $data['category_content'] = $this->cms_program_model->getByCategory($page_content_type, array('start' => $offset, 'limit' => $this->perPage));
        //load the view
        $this->load->view('themes/default/pages/ajax-pagination-data', $data, false);
    }

    public function read($slug)
    {

        $this->data['active_menu'] = 'home';
        $page                      = $this->cms_program_model->getBySlug(urldecode($slug));

        $this->data['page_side_bar']  = $page['sidebar'];
        $this->data['featured_image'] = $page['feature_image'];
        $this->data['page']           = $page;
        $this->load_theme('pages/read');
    }

    public function getSections()
    {

        $class_id = $this->input->post('class_id');
        $data     = $this->section_model->getClassBySectionAll($class_id);
        echo json_encode($data);
    }

    public function admission()
    {

        if ($this->module_lib->hasActive('online_admission')) {

            $this->data['active_menu'] = 'online-admission';
            $page                      = array('title' => 'Online Admission Form', 'meta_title' => 'online admission form', 'meta_keyword' => 'online admission form', 'meta_description' => 'online admission form');

            $this->data['page_side_bar']  = false;
            $this->data['featured_image'] = false;
            $this->data['page']           = $page;
            ///============
            $this->data['form_admission'] = $this->setting_model->getOnlineAdmissionStatus();

            ///////===
            $genderList               = $this->customlib->getGender();
            $this->data['genderList'] = $genderList;
            $this->data['title']      = 'Add Student';
            $this->data['title_list'] = 'Recently Added Student';

            $data["student_categorize"] = 'class';
            $session                    = $this->setting_model->getCurrentSession();

            $class                     = $this->class_model->getAll();
            $this->data['classlist']   = $class;
            $userdata                  = $this->customlib->getUserData();
            $this->data['sch_setting'] = $this->sch_setting_detail;

            $category                   = $this->category_model->get();
            $this->data['categorylist'] = $category;
            if ($this->captchalib->is_captcha('admission')) {
                $this->form_validation->set_rules('captcha', 'Captcha', 'trim|required|callback_check_captcha');
            }
            $this->form_validation->set_rules(
                'email',
                $this->lang->line('email'),
                array(
                    'valid_email', 'required',
                    array('check_student_email_exists', array($this->student_model, 'check_student_email_exists')),
                )
            );
            $this->form_validation->set_rules('firstname', $this->lang->line('first_name'), 'trim|required|xss_clean');
            $this->form_validation->set_rules('guardian_is', $this->lang->line('guardian'), 'trim|required|xss_clean');
            $this->form_validation->set_rules('gender', $this->lang->line('gender'), 'trim|required|xss_clean');
            $this->form_validation->set_rules('dob', $this->lang->line('date_of_birth'), 'trim|required|xss_clean');
            $this->form_validation->set_rules('class_id', $this->lang->line('class'), 'trim|required|xss_clean');
            $this->form_validation->set_rules('section_id', $this->lang->line('section'), 'trim|required|xss_clean');
            $this->form_validation->set_rules('guardian_name', $this->lang->line('guardian_name'), 'trim|required|xss_clean');
            $this->form_validation->set_rules('guardian_phone', $this->lang->line('guardian_phone'), 'trim|required|xss_clean');

            if ($this->form_validation->run() == false) {

                $this->load_theme('pages/admission');
            } else {
                //==============
                $document_validate = true;
                $image_validate    = $this->config->item('file_validate');
                $result            = $this->filetype_model->get();
                if (isset($_FILES["document"]) && !empty($_FILES['document']['name'])) {

                    $file_type = $_FILES["document"]['type'];
                    $file_size = $_FILES["document"]["size"];
                    $file_name = $_FILES["document"]["name"];

                    $allowed_extension = array_map('trim', array_map('strtolower', explode(',', $result->file_extension)));
                    $allowed_mime_type = array_map('trim', array_map('strtolower', explode(',', $result->file_mime)));
                    $ext               = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

                    if ($files = filesize($_FILES['document']['tmp_name'])) {

                        if (!in_array($file_type, $allowed_mime_type)) {
                            $this->data['error_message'] = 'File Type Not Allowed';
                            $document_validate           = false;
                        }

                        if (!in_array($ext, $allowed_extension) || !in_array($file_type, $allowed_mime_type)) {
                            $this->data['error_message'] = 'Extension Not Allowed';
                            $document_validate           = false;
                        }
                        if ($file_size > $result->file_size) {
                            $this->data['error_message'] = 'File should be less than' . number_format($image_validate['upload_size'] / 1048576, 2) . " MB";
                            $document_validate           = false;
                        }
                    }
                }
                //=====================
                if ($document_validate) {

                    $class_id   = $this->input->post('class_id');
                    $section_id = $this->input->post('section_id');

                    $data = array(
                        'roll_no'             => $this->input->post('roll_no'),
                        'mobileno'            => $this->input->post('mobileno'),
                        'email'               => $this->input->post('email'),
                        'firstname'           => $this->input->post('firstname'),
                        'lastname'            => $this->input->post('lastname'),
                        'mobileno'            => $this->input->post('mobileno'),
                        'class_section_id'    => $this->input->post('section_id'),
                        'guardian_is'         => $this->input->post('guardian_is'),
                        'dob'                 => date('Y-m-d', strtotime($this->input->post('dob'))),
                        'current_address'     => $this->input->post('current_address'),
                        'permanent_address'   => $this->input->post('permanent_address'),
                        'father_name'         => $this->input->post('father_name'),
                        'father_phone'        => $this->input->post('father_phone'),
                        'father_occupation'   => $this->input->post('father_occupation'),
                        'mother_name'         => $this->input->post('mother_name'),
                        'mother_phone'        => $this->input->post('mother_phone'),
                        'mother_occupation'   => $this->input->post('mother_occupation'),
                        'guardian_occupation' => $this->input->post('guardian_occupation'),
                        'guardian_email'      => $this->input->post('guardian_email'),
                        'gender'              => $this->input->post('gender'),
                        'guardian_name'       => $this->input->post('guardian_name'),
                        'guardian_relation'   => $this->input->post('guardian_relation'),
                        'guardian_phone'      => $this->input->post('guardian_phone'),
                        'guardian_address'    => $this->input->post('guardian_address'),
                        'admission_date'      => date('Y/m/d'),
                        'measurement_date'    => date('Y/m/d'),
                    );
                    if (isset($_FILES["document"]) && !empty($_FILES['document']['name'])) {
                        $time     = md5($_FILES["document"]['name'] . microtime());
                        $fileInfo = pathinfo($_FILES["document"]["name"]);
                        $doc_name = $time . '.' . $fileInfo['extension'];
                        move_uploaded_file($_FILES["document"]["tmp_name"], "./uploads/student_documents/online_admission_doc/" . $doc_name);

                        $data['document'] = $doc_name;
                    }

                    $insert_id = $this->onlinestudent_model->add($data);

                    $this->session->set_flashdata('msg', '<div class="alert alert-success">Thanks for registration. Please note your reference number ' . $insert_id . ' for further communication.</div>');

                    redirect($_SERVER['HTTP_REFERER'], 'refresh');
                }

                $this->load_theme('pages/admission');
            }
        }
    }

    public function check_captcha($captcha)
    {
        if ($captcha != $this->session->userdata('captchaCode')) :
            $this->form_validation->set_message('check_captcha', $this->lang->line('incorrect_captcha'));
            return false;
        else :
            return true;
        endif;
    }

    public function notice($id)
    {
        $data = $this->db->get_where('send_notification', ['id' => $id])->row_array();
        $this->data['data'] = $data;

        $this->data['page']['title'] =  $data['title'];
        $this->data['page']['meta_title'] =  $data['title'];
        $this->data['page']['meta_keyword'] =   $data['title'];
        $this->data['page']['meta_description'] =    $data['title'];
        $this->data['sch_setting'] = $this->sch_setting_detail;
        $this->data['active_menu']   = 'home';
        $this->data['page_side_bar']  = false;
        $this->load_theme('pages/noticeView');
    }

    public function allNotice()
    {
        $data = $this->db->get('send_notification')->result();

        $this->data['datas'] = $data;
        $this->data['page']['title'] =  'All Notices';
        $this->data['page']['meta_title'] = "All Notices";
        $this->data['page']['meta_keyword'] =  "All Notices";
        $this->data['page']['meta_description'] =  "All Notices";
        $this->data['sch_setting'] = $this->sch_setting_detail;
        $this->data['active_menu']   = 'home';
        $this->data['page_side_bar']  = false;

        $this->load_theme('pages/noticeList');
    }

    public function blogList($id)
    {
        $this->data['category'] = $this->db->get_where('blogCategory', ['id' => $id])->row();
        $this->data['posts'] = $this->db->get_where('blog', ['category_id' => $id])->result();

        $this->data['page']['title'] =  $this->data['category']->name;
        $this->data['page']['meta_title'] = $this->data['category']->name;
        $this->data['page']['meta_keyword'] =  $this->data['category']->name;
        $this->data['page']['meta_description'] =  $this->data['category']->name;
        $this->data['sch_setting'] = $this->sch_setting_detail;
        $this->data['active_menu']   = 'home';
        $this->data['page_side_bar']  = false;

        $this->load_theme('pages/blogList');
    }


    public function blog($id)
    {
        $this->db->select('blog.*, blogCategory.name as category_name');
        $this->db->join('blogCategory', 'blogCategory.id = blog.category_id', 'inner');
        $this->db->where('blog.id', $id);
        $this->data['posts']  = $this->db->get('blog')->row();

        $this->data['page']['title'] = $this->data['posts']->title;
        $this->data['page']['meta_title'] = $this->data['posts']->title;
        $this->data['page']['meta_keyword'] = $this->data['posts']->title;
        $this->data['page']['meta_description'] = $this->data['posts']->title;
        $this->data['sch_setting'] = $this->sch_setting_detail;
        $this->data['active_menu'] = 'home';
        $this->data['page_side_bar'] = false;

        $this->load_theme('pages/blog');
    }

    public function academicMessage($id)
    {
        $this->db->where('id', $id);
        $this->data['msg']  = $this->db->get('academic_messages')->row();

        $this->data['page']['title'] =  $this->data['msg']->title;
        $this->data['page']['meta_title'] = $this->data['msg']->title;
        $this->data['page']['meta_keyword'] =  $this->data['msg']->title;
        $this->data['page']['meta_description'] =  $this->data['msg']->title;
        $this->data['sch_setting'] = $this->sch_setting_detail;
        $this->data['active_menu']   = 'home';
        $this->data['page_side_bar']  = false;

        $this->load_theme('pages/academicMessage');
    }
    public function examResult()
    {

        $examgroup_result = $this->examgroup_model->get();
        $this->data['examgrouplist'] = $examgroup_result;

        $marksheet_result = $this->marksheet_model->get();
        $this->data['marksheetlist'] = $marksheet_result;

        $class = $this->class_model->get();
        $this->data['title'] = 'Add Batch';
        $this->data['title_list'] = 'Recent Batch';
        $this->data['examType'] = $this->exam_type;
        $this->data['classlist'] = $class;
        $session = $this->session_model->get();
        $this->data['sessionlist'] = $session;
        $this->form_validation->set_rules('class_id', $this->lang->line('class'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('section_id', $this->lang->line('section'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('session_id', $this->lang->line('session'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('exam_group_id', $this->lang->line('exam') . " " . $this->lang->line('group'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('exam_id', $this->lang->line('exam'), 'trim|required|xss_clean');

        if ($this->form_validation->run() == false) {
        } else {
            $exam_group_id = $this->input->post('exam_group_id');
            $exam_id = $this->input->post('exam_id');
            $session_id = $this->input->post('session_id');
            $class_id = $this->input->post('class_id');
            $section_id = $this->input->post('section_id');

            $marksheet_template = $this->input->post('marksheet');
            $this->data['marksheet_template'] = $marksheet_template;
            $exam_details = $this->examgroup_model->getExamByID($exam_id);

            $studentList = $this->examgroupstudent_model->searchExamStudents($exam_group_id, $exam_id, $class_id, $section_id, $session_id);

            $exam_subjects = $this->batchsubject_model->getExamSubjects($exam_id);
            $this->data['subjectList'] = $exam_subjects;

            if (!empty($studentList)) {
                foreach ($studentList as $student_key => $student_value) {
                    $studentList[$student_key]->subject_results = $this->examresult_model->getStudentResultByExam($exam_id, $student_value->exam_group_class_batch_exam_student_id);
                }
            }

            $this->data['studentList'] = $studentList;

            $exam_grades = $this->grade_model->getByExamType($exam_details->exam_group_type);
            $this->data['exam_grades'] = $exam_grades;
            $this->data['exam_details'] = $exam_details;
            $this->data['exam_id'] = $exam_id;
            $this->data['exam_group_id'] = $exam_group_id;
        }
        $this->data['sch_setting'] = $this->sch_setting_detail;

        $this->data['page']['title'] =  'online exam result';
        $this->data['page']['meta_title'] =  'online exam result';
        $this->data['page']['meta_keyword'] =  'online exam result';
        $this->data['page']['meta_description'] =  'online exam result';
        $this->data['active_menu']   = 'home';
        $this->data['page_side_bar']  = false;


        $this->load_theme('pages/exam-result');
    }

    public function examRoutine()
    {

        $examgroup_result = $this->examgroup_model->get();
        $this->data['examgrouplist'] = $examgroup_result;

        $this->form_validation->set_rules('exam_group_id', $this->lang->line('exam') . " " . $this->lang->line('group'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('exam_id', $this->lang->line('exam'), 'trim|required|xss_clean');
        if ($this->form_validation->run() == false) {
        } else {

            $id = $_POST['exam_id'];
            $this->data['examgroupDetail'] = $this->examgroup_model->getExamByID($id);

            $this->data['exam_subjects'] = $this->batchsubject_model->getExamSubjects($id);

            $class = $this->class_model->get();
            $this->data['classlist'] = $class;
            $session = $this->session_model->get();
            $this->data['sessionlist'] = $session;
            $this->data['current_session'] = $this->setting_model->getCurrentSession();
        }


        $this->data['page']['title'] =  'exam routine';
        $this->data['page']['meta_title'] =  'exam routine';
        $this->data['page']['meta_keyword'] =  'exam routine';
        $this->data['page']['meta_description'] =  'exam routine';
        $this->data['sch_setting'] = $this->sch_setting_detail;
        $this->data['active_menu']   = 'home';
        $this->data['page_side_bar']  = false;


        $this->load_theme('pages/exam-routine');
    }

    public function classRoutine()
    {
        $this->data['subject_id'] = "";
        $this->data['class_id']   = "";
        $this->data['section_id'] = "";
        $exam               = $this->exam_model->get();
        $class              = $this->class_model->get('', $classteacher = 'yes');
        $this->data['examlist']   = $exam;
        $this->data['classlist']  = $class;
        $staff                   = $this->staff_model->getStaffbyrole(2);
        $this->data['staff']           = $staff;
        $this->data['subject']         = array();
        $feecategory             = $this->feecategory_model->get();
        $this->data['feecategorylist'] = $feecategory;
        $this->form_validation->set_rules('class_id', $this->lang->line('class'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('section_id', $this->lang->line('section'), 'trim|required|xss_clean');

        if ($this->form_validation->run() == true) {
            if (isset($_POST['search'])) {

                $class_id    = $this->input->post('class_id');
                $section_id  = $this->input->post('section_id');
                $days        = $this->customlib->getDaysname();
                $days_record = array();
                foreach ($days as $day_key => $day_value) {
                    $class_id              = $this->input->post('class_id');
                    $section_id            = $this->input->post('section_id');
                    $days_record[$day_key] = $this->subjecttimetable_model->getSubjectByClassandSectionDay($class_id, $section_id, $day_key);
                }

                $this->data['timetable'] = $days_record;
            }
        }


        $this->data['page']['title'] =  'class routine';
        $this->data['page']['meta_title'] =  'class routine';
        $this->data['page']['meta_keyword'] =  'class routine';
        $this->data['page']['meta_description'] =  'class routine';
        $this->data['sch_setting'] = $this->sch_setting_detail;
        $this->data['active_menu']   = 'home';
        $this->data['page_side_bar']  = false;


        $this->load_theme('pages/class-routine');
    }

    public function library()
    {
        $listbook = $this->book_model->bookgetall();
        $this->data['listbook'] = $listbook;

        $this->data['page']['title'] =  'library';
        $this->data['page']['meta_title'] =  'library';
        $this->data['page']['meta_keyword'] =  'library';
        $this->data['page']['meta_description'] =  'library';
        $this->data['sch_setting'] = $this->sch_setting_detail;
        $this->data['active_menu']   = 'home';
        $this->data['page_side_bar']  = false;


        $this->load_theme('pages/library');
    }
    public function studentList()
    {
        $this->data['title']           = 'Student Search';
        $this->data['adm_auto_insert'] = $this->sch_setting_detail->adm_auto_insert;
        $this->data['sch_setting']     = $this->sch_setting_detail;
        $this->data['fields']          = $this->customfield_model->get_custom_fields('students', 1);
        $class                   = $this->class_model->get();
        $this->data['classlist']       = $class;

        $userdata = $this->customlib->getUserData();
        $carray   = array();

        if (!empty($this->data["classlist"])) {
            foreach ($this->data["classlist"] as $ckey => $cvalue) {

                $carray[] = $cvalue["id"];
            }
        }

        $button = $this->input->post('search');
        if ($this->input->server('REQUEST_METHOD') != "GET") {
            $class = $this->input->post('class_id');
            $section = $this->input->post('section_id');
            $search = $this->input->post('search');
            $search_text = $this->input->post('search_text');
            if (isset($search)) {
                if ($search == 'search_filter') {
                    $this->form_validation->set_rules('class_id', $this->lang->line('class'), 'trim|required|xss_clean');
                    if ($this->form_validation->run() == false) {
                    } else {
                        $this->data['searchby'] = "filter";
                        $this->data['class_id'] = $this->input->post('class_id');
                        $this->data['section_id'] = $this->input->post('section_id');
                        $this->data['search_text'] = $this->input->post('search_text');
                        $resultlist = $this->student_model->searchByClassSection($class, $section);
                        $this->data['resultlist'] = $resultlist;
                        $title = $this->classsection_model->getDetailbyClassSection($this->data['class_id'], $this->data['section_id']);
                        $this->data['title'] = 'Student Details for ' . $title['class'] . "(" . $title['section'] . ")";
                    }
                } else if ($search == 'search_full') {
                    $this->data['searchby'] = "text";

                    $this->data['search_text'] = trim($this->input->post('search_text'));
                    $resultlist = $this->student_model->searchFullText($search_text, $carray);
                    $this->data['resultlist'] = $resultlist;
                    $this->data['title'] = 'Search Details: ' . $this->data['search_text'];
                }
            }
        }

        $this->data['page']['title'] =  'student list';
        $this->data['page']['meta_title'] =  'student list';
        $this->data['page']['meta_keyword'] =  'student list';
        $this->data['page']['meta_description'] =  'student list';
        $this->data['sch_setting'] = $this->sch_setting_detail;
        $this->data['active_menu']   = 'home';
        $this->data['page_side_bar']  = false;


        $this->load_theme('pages/student-list');
    }
    public function teacherList()
    {
        $data['title']  = 'Staff Search';
        $this->data['fields'] = $this->customfield_model->get_custom_fields('staff', 1);
        $this->session->set_userdata('top_menu', 'HR');
        $this->session->set_userdata('sub_menu', 'HR/staff');
        $search             = $this->input->post("search");
        $resultlist         = $this->staff_model->searchFullText("", 1);
        $this->data['resultlist'] = $resultlist;
        $staffRole          = $this->staff_model->getStaffRole();
        $this->data["role"]       = $staffRole;
        $this->data["role_id"]    = "";
        $search_text        = $this->input->post('search_text');
        if (isset($search)) {
            if ($search == 'search_filter') {
                $this->form_validation->set_rules('role', $this->lang->line('role'), 'trim|required|xss_clean');
                if ($this->form_validation->run() == false) {

                    $this->data["resultlist"] = array();
                } else {
                    $this->data['searchby']    = "filter";
                    $role                = $this->input->post('role');
                    $this->data['employee_id'] = $this->input->post('empid');
                    $this->data["role_id"]     = $role;
                    $this->data['search_text'] = $this->input->post('search_text');
                    $resultlist          = $this->staff_model->getEmployee($role, 1);
                    $this->data['resultlist']  = $resultlist;
                }
            } else if ($search == 'search_full') {
                $this->data['searchby']    = "text";
                $this->data['search_text'] = trim($this->input->post('search_text'));
                $resultlist          = $this->staff_model->searchFullText($search_text, 1);

                $this->data['resultlist'] = $resultlist;
                $this->data['title']      = 'Search Details: ' . $data['search_text'];
            }
        }

        $this->data['page']['title'] =  'library';
        $this->data['page']['meta_title'] =  'library';
        $this->data['page']['meta_keyword'] =  'library';
        $this->data['page']['meta_description'] =  'library';
        $this->data['sch_setting'] = $this->sch_setting_detail;
        $this->data['active_menu']   = 'home';
        $this->data['page_side_bar']  = false;


        $this->load_theme('pages/teacher-list');
    }


    public function getByClass()
    {
        $class_id = $this->input->get('class_id');
        $data     = $this->section_model->getClassBySection($class_id);
        echo json_encode($data);
    }
}
