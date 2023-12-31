<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Onlineexam extends Admin_Controller
{
    public $sch_setting_detail = array();
    public function __construct()
    {
        parent::__construct();
        $this->auth->is_logged_in();
        $this->config->load('app-config');
        $this->sch_setting_detail = $this->setting_model->getSetting();
        $this->load->library('mailsmsconf');
    }

    public function index()
    {
        if (!$this->rbac->hasPrivilege('online_examination', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Online_Examinations');
        $this->session->set_userdata('sub_menu', 'Online_Examinations/Onlineexam');
        $questionList           = $this->onlineexam_model->get();
        $data['questionList']   = $questionList;
        $subject_result         = $this->subject_model->get();
        $data['subjectlist']    = $subject_result;
        $questionOpt            = $this->customlib->getQuesOption();
        $data['questionOpt']    = $questionOpt;
        $data['question_type']  = $this->config->item('question_type');
        $data['question_level'] = $this->config->item('question_level');
        $data['classList']      = $this->class_model->get();
        $this->load->view('layout/header', $data);
        $this->load->view('admin/onlineexam/index', $data);
        $this->load->view('layout/footer', $data);
    }

    public function evalution($id)
    {

        $this->session->set_userdata('top_menu', 'Online_Examinations');
        $this->session->set_userdata('sub_menu', 'Online_Examinations/Onlineexam');
        $data['id']         = $id;
        $data['title']      = 'student fees';
        $class              = $this->class_model->get();
        $data['classlist']  = $class;
        $onlineexam         = $this->onlineexam_model->get($id);
        $data['onlineexam'] = $onlineexam;

        $onlineexam_questions         = $this->onlineexamquestion_model->getByExamNoLimit($id, 'descriptive');
        $data['onlineexam_questions'] = $onlineexam_questions;

        $data['sch_setting'] = $this->sch_setting_detail;
        if ($this->input->server('REQUEST_METHOD') == 'POST') {
            $data['class_id']      = $this->input->post('class_id');
            $data['section_id']    = $this->input->post('section_id');
            $data['onlineexam_id'] = $this->input->post('onlineexam_id');
            $resultlist            = $this->onlineexam_model->searchOnlineExamStudents($data['class_id'], $data['section_id'], $data['onlineexam_id']);

            $data['resultlist'] = $resultlist;
        }
        $data['sch_setting'] = $this->sch_setting_detail;

        $this->load->view('layout/header', $data);
        $this->load->view('admin/onlineexam/evalution', $data);
        $this->load->view('layout/footer', $data);
    }

    public function getDescQues()
    {

        $pag_content    = '';
        $pag_navigation = '';

        if (isset($_POST['data']['page'])) {

            $page = $_POST['data']['page']; /* The page we are currently at */

            $max      = $_POST['data']['post_max']; /* Number of items to display per page */
            $cur_page = $page;
            $page -= 1;
            $per_page     = $max ? $max : 40;
            $previous_btn = true;
            $next_btn     = true;
            $first_btn    = true;
            $last_btn     = true;
            $start        = $page * $per_page;
            $count        = 0;

            $where_search = array();

            /* Check if there is a string inputted on the search box */
            if (!empty($_POST['data']['class_id'])) {
                $where_search['class_id'] = $_POST['data']['class_id'];
            }
            if (!empty($_POST['data']['section_id'])) {
                $where_search['section_id'] = $_POST['data']['section_id'];
            }
            if (!empty($_POST['data']['question_id'])) {
                $where_search['question_id'] = $_POST['data']['question_id'];
            }

            /* Retrieve all the posts */
            $all_items = $this->onlineexamresult_model->getDescriptionRecord($per_page, $start, $where_search, $_POST['data']['onlineexam_id']);

            /* Check if our query returns anything. */
            if ($all_items) {
                $result              = json_decode($all_items);
                $data['result']      = $result;
                $data['total_row']   = $result->total_row;
                $data['start']       = ($cur_page * $per_page) - $per_page + 1;
                $data['upto']        = ($result->total_row < ($cur_page * $per_page)) ? $result->total_row : ($cur_page * $per_page);
                $data['sch_setting'] = $this->sch_setting_detail;
                $pag_content         = $this->load->view('admin/onlineexam/_getDescQues', $data, true);

                /* If the query returns nothing, we throw an error message */
            } else {
                $pag_content = '';
            }
            $no_of_paginations = ceil($result->total_row / $per_page);

            if ($cur_page >= 7) {
                $start_loop = $cur_page - 3;
                if ($no_of_paginations > $cur_page + 3) {
                    $end_loop = $cur_page + 3;
                } else if ($cur_page <= $no_of_paginations && $cur_page > $no_of_paginations - 6) {
                    $start_loop = $no_of_paginations - 6;
                    $end_loop   = $no_of_paginations;
                } else {
                    $end_loop = $no_of_paginations;
                }
            } else {
                $start_loop = 1;
                if ($no_of_paginations > 7) {
                    $end_loop = 7;
                } else {
                    $end_loop = $no_of_paginations;
                }
            }

            $pag_navigation .= "<ul class='pagination'>";

            if ($first_btn && $cur_page > 1) {
                $pag_navigation .= "<li p='1' class='active_v'><a>" . $this->lang->line('first') . "</a></li>";
            } else if ($first_btn) {

                $pag_navigation .= "<li p='1' class='disabled'><a>" . $this->lang->line('first') . "</a></li>";
            }

            if ($previous_btn && $cur_page > 1) {
                $pre = $cur_page - 1;
                $pag_navigation .= "<li p='$pre' class='active_v'><a>" . $this->lang->line('previous') . "</a></li>";
            } else if ($previous_btn) {
                $pag_navigation .= "<li class='disabled'><a>" . $this->lang->line('previous') . "</a></li>";
            }
            for ($i = $start_loop; $i <= $end_loop; $i++) {

                if ($cur_page == $i) {
                    $pag_navigation .= "<li p='$i' class = 'active' ><a href='#'>{$i}</a></li>";
                } else {
                    $pag_navigation .= "<li p='$i' class='active_v'><a>{$i}</a></li>";
                }
            }

            if ($next_btn && $cur_page < $no_of_paginations) {
                $nex = $cur_page + 1;
                $pag_navigation .= "<li p='$nex' class='active_v'><a>" . $this->lang->line('next') . "</a></li>";
            } else if ($next_btn) {
                $pag_navigation .= "<li class='disabled'><a>" . $this->lang->line('next') . "</a></li>";
            }

            if ($last_btn && $cur_page < $no_of_paginations) {
                $pag_navigation .= "<li p='$no_of_paginations' class='active_v'><a>" . $this->lang->line('last') . "</a></li>";
            } else if ($last_btn) {
                $pag_navigation .= "<li p='$no_of_paginations' class='disabled'><a>" . $this->lang->line('last') . "</a></li>";
            }

            $pag_navigation = $pag_navigation . "</ul>";
        }

        $response = array(
            'content'    => $pag_content,
            'navigation' => $pag_navigation,
        );

        echo json_encode($response);

        exit();
    }

    public function assign($id)
    {
        if (!$this->rbac->hasPrivilege('online_assign_view_student', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Online_Examinations');
        $this->session->set_userdata('sub_menu', 'Online_Examinations/Onlineexam');
        $data['id']          = $id;
        $data['title']       = 'student fees';
        $class               = $this->class_model->get();
        $data['classlist']   = $class;
        $onlineexam          = $this->onlineexam_model->get($id);
        $data['onlineexam']  = $onlineexam;
        $data['sch_setting'] = $this->sch_setting_detail;

        if ($this->input->server('REQUEST_METHOD') == "GET") {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/onlineexam/assign', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $this->form_validation->set_rules('class_id', $this->lang->line('class'), 'trim|required|xss_clean');
            if ($this->form_validation->run() == false) {
                $this->load->view('layout/header', $data);
                $this->load->view('admin/onlineexam/assign', $data);
                $this->load->view('layout/footer', $data);
            } else {
                $data['class_id']      = $this->input->post('class_id');
                $data['section_id']    = $this->input->post('section_id');
                $data['onlineexam_id'] = $this->input->post('onlineexam_id');
                $resultlist            = $this->onlineexam_model->searchOnlineExamStudents($data['class_id'], $data['section_id'], $data['onlineexam_id']);
                $data['resultlist']    = $resultlist;
                $data['sch_setting']   = $this->sch_setting_detail;
                $this->load->view('layout/header', $data);
                $this->load->view('admin/onlineexam/assign', $data);
                $this->load->view('layout/footer', $data);
            }
        }
    }

    public function addstudent()
    {
        $this->form_validation->set_rules('onlineexam_id', $this->lang->line('exam') . " " . $this->lang->line('id'), 'required|trim|xss_clean');

        if ($this->form_validation->run() == false) {
            $data = array(
                'onlineexam_id' => form_error('onlineexam_id'),
            );
            $array = array('status' => 'fail', 'error' => $data);
            echo json_encode($array);
        } else {

            $array_insert  = array();
            $array_delete  = array();
            $class_id      = $this->input->post('post_class_id');
            $section_id    = $this->input->post('post_section_id');
            $onlineexam_id = $this->input->post('onlineexam_id');
            $resultlist    = $this->onlineexam_model->searchOnlineExamStudents($class_id, $section_id, $onlineexam_id);
            $all_students  = array();
            if (!empty($resultlist)) {

                foreach ($resultlist as $each_student_key => $each_student_value) {
                    if ($each_student_value['onlineexam_student_session_id'] != 0) {
                        $all_students[] = $each_student_value['onlineexam_student_session_id'];
                    }
                }
            }

            $students_id = $this->input->post('students_id');
            $students    = array();
            if (!isset($students_id)) {
                $students_id = array();
            }
            if (!empty($all_students)) {
                $array_delete = array_diff($all_students, $students_id);
            }
            if (!empty($students_id)) {
                $student_session_array = array();
                foreach ($students_id as $student_key => $student_value) {
                    $student_session_array[] = $student_value;
                }

                $student_array = array_diff($student_session_array, $all_students);
                if (!empty($student_array)) {
                    foreach ($student_array as $insert_key => $insert_value) {
                        $array_insert[] = array(
                            'onlineexam_id'      => $onlineexam_id,
                            'student_session_id' => $insert_value,
                        );
                    }
                }
            }

            $this->onlineexam_model->addStudents($array_insert, $array_delete, $onlineexam_id);

            $array = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('success_message'));
            echo json_encode($array);
        }
    }

    public function getOnlineExamByID()
    {
        $id = $this->input->post('recordid');

        $question_result = $this->onlineexam_model->get($id);

        echo json_encode(array('status' => 1, 'result' => $question_result));
    }

    public function searchQuestionByExamID()
    {
        $recordsTotal_flter = "";
        $userdata           = $this->customlib->getUserData();
        $role_id            = $userdata["role_id"];
        $data               = array();
        $pag_content        = '';
        $pag_navigation     = '';
        $is_quiz            = $this->input->post('is_quiz');
        $page               = $this->input->post('page');
        $exam_id            = $this->input->post('exam_id');
        $keyword            = $this->input->post('keyword');
        $question_type      = $this->input->post('question_type');
        $question_level     = $this->input->post('question_level');
        $class_id           = $this->input->post('class_id');
        $section_id         = $this->input->post('section_id');

        if (isset($page)) {
            $max      = 100;
            $cur_page = $page;
            $page -= 1;
            $per_page      = $max ? $max : 40;
            $previous_btn  = true;
            $next_btn      = true;
            $first_btn     = true;
            $last_btn      = true;
            $start         = $page * $per_page;
            $where_search  = array();
            $show_from     = ($cur_page * $max) - ($max - 1);
            $show_to       = $cur_page * $max;
            $total_display = 0;

            /* Check if there is a string inputted on the search box */
            if (($_POST['search'] != "") ||
                ($_POST['keyword'] != "") ||
                ($_POST['question_level'] != "") ||
                ($_POST['question_type'] != "") ||
                ($_POST['class_id'] != "") ||
                ($_POST['section_id'] != "")
            ) {
                $search                         = $this->input->post('search');
                $where_search['subject']        = $search;
                $where_search['keyword']        = $keyword;
                $where_search['question_level'] = $question_level;
                $where_search['question_type']  = $question_type;
                $where_search['class_id']       = $class_id;
                $where_search['section_id']     = $section_id;
            }
            $where_search['is_quiz'] = $is_quiz;
            $data['question_type']   = $this->config->item('question_type');
            $data['question_level']  = $this->config->item('question_level');
            $questionList            = $this->onlineexamquestion_model->getByExamID($exam_id, $per_page, $start, $where_search);

            $dt_data = array();
            if ($role_id == 2) {
                foreach ($questionList as $questionList_key => $questionList_value) {

                    $my_section = array();

                    if ($this->sch_setting_detail->class_teacher == 'yes' && $this->sch_setting_detail->my_question == '1') {

                        $my_class = $this->class_model->get();
                        foreach ($my_class as $class_key => $class_value) {

                            $section_id = $this->teacher_model->get_teacherrestricted_modesections($this->customlib->getStaffID(), $class_value['id']);

                            foreach ($section_id as $section_idkey => $section_idvalue) {
                                $my_section[] = $section_idvalue['section_id'];
                            }

                            if (in_array($questionList_value->section_id, $my_section, true) && $class_value['id'] == $questionList_value->class_id) {

                                $dt_data[]          = $questionList_value;
                                $recordsTotal_flter = count($dt_data);
                            }
                        }
                    } elseif ($this->sch_setting_detail->class_teacher == 'yes' && $this->sch_setting_detail->my_question == '0') {

                        $my_class = $this->class_model->get();
                        foreach ($my_class as $class_key => $class_value) {

                            $section_id = $this->teacher_model->get_teacherrestricted_modesections($this->customlib->getStaffID(), $class_value['id']);
                            foreach ($section_id as $section_idkey => $section_idvalue) {
                                $my_section[] = $section_idvalue['section_id'];
                            }

                            if (in_array($questionList_value->section_id, $my_section, true) && $class_value['id'] == $questionList_value->class_id) {
                                $dt_data[]          = $questionList_value;
                                $recordsTotal_flter = count($dt_data);
                            }
                        }
                    } elseif ($this->sch_setting_detail->class_teacher == 'no' && $this->sch_setting_detail->my_question == '1') {

                        if ($this->customlib->getStaffID() == $questionList_value->staff_id) {
                            $dt_data[]          = $questionList_value;
                            $recordsTotal_flter = count($dt_data);
                        }
                    } else {

                        $dt_data[]          = $questionList_value;
                        $recordsTotal_flter = count($dt_data);
                    }
                }
                $data['questionList'] = $dt_data;
            } else {
                $data['questionList'] = $questionList;
                $recordsTotal_flter   = count($questionList);
            }

            $count = $this->onlineexamquestion_model->getCountByExamID($exam_id, $where_search);

            $total_display = $recordsTotal_flter;
            /* Check if our query returns anything. */
            if ($count) {
                $pag_content = $this->load->view('admin/onlineexam/_searchQuestionByExamID', $data, true);
                /* If the query returns nothing, we throw an error message */
            }

            $no_of_paginations = ceil($count / $per_page);

            if ($cur_page >= 7) {
                $start_loop = $cur_page - 3;
                if ($no_of_paginations > $cur_page + 3) {
                    $end_loop = $cur_page + 3;
                } else if ($cur_page <= $no_of_paginations && $cur_page > $no_of_paginations - 6) {
                    $start_loop = $no_of_paginations - 6;
                    $end_loop   = $no_of_paginations;
                } else {
                    $end_loop = $no_of_paginations;
                }
            } else {
                $start_loop = 1;
                if ($no_of_paginations > 7) {
                    $end_loop = 7;
                } else {
                    $end_loop = $no_of_paginations;
                }
            }

            $pag_navigation .= "<ul class='pagination pull-right'>";

            if ($first_btn && $cur_page > 1) {
                $pag_navigation .= "<li p='1' class='activee'><a href='#'><i class='fa fa-angle-double-left'></i></a></li>";
            } else if ($first_btn) {

                $pag_navigation .= "<li p='1' class='disabled'><a href='#'><i class='fa fa-angle-double-left'></i></a></li>";
            }

            if ($previous_btn && $cur_page > 1) {
                $pre = $cur_page - 1;
                $pag_navigation .= "<li p='$pre' class='activee'><a href='#'><i class='fa fa-angle-left'></i></a></li>";
            } else if ($previous_btn) {

                $pag_navigation .= "<li  class='disabled'><a href='#'><i class='fa fa-angle-left'></i></a></li>";
            }
            for ($i = $start_loop; $i <= $end_loop; $i++) {

                if ($cur_page == $i) {

                    $pag_navigation .= "<li p='$i' class='active'><a href='#'>{$i}</a></li>";
                } else {

                    $pag_navigation .= "<li p='$i'  class='activee'><a href='#'>{$i}</a></li>";
                }
            }

            if ($next_btn && $cur_page < $no_of_paginations) {
                $nex = $cur_page + 1;

                $pag_navigation .= "<li p='$nex' class='activee'><a href='#'><i class='fa fa-angle-right'></i></a></li>";
            } else if ($next_btn) {
                $pag_navigation .= "<li class='disabled'><a href='#'><i class='fa fa-angle-right'></i></a></li>";
            }

            if ($last_btn && $cur_page < $no_of_paginations) {
                $pag_navigation .= "<li p='$no_of_paginations'  class='activee'><a href='#'><i class='fa fa-angle-double-right'></i></a></li>";
            } else if ($last_btn) {
                $pag_navigation .= "<li p='$no_of_paginations' class='disabled'><a href='#'><i class='fa fa-angle-double-right'></i></a></li>";
            }

            $pag_navigation = $pag_navigation . "</ul>";
        }

        $response = array(
            'content'       => $pag_content,
            'navigation'    => $pag_navigation,
            'show_from'     => ($total_display <= 0) ? 0 : $show_from,
            'show_to'       => ($show_to > $total_display) ? $total_display : $show_to,
            'total_display' => $total_display,
        );

        echo json_encode($response);
    }

    public function rankgenerate()
    {
        $examid = $this->input->post('examid');
        $student_data = $this->onlineexam_model->searchAllOnlineExamStudents($examid);
        $student_question_array = array();
        if (!empty($student_data)) {
            foreach ($student_data as $student_key => $student_value) {
                $student_question_array[$student_value['onlineexam_student_id']] = $this->onlineexamresult_model->getResultByStudent($student_value['onlineexam_student_id'], $examid);
            }
        }
        $data['onlineexam'] = $this->onlineexam_model->get($examid);
        $data['student_question_array'] = $student_question_array;
        $data['examid'] = $examid;
        $data['student_data'] = $student_data;
        $data['sch_setting'] = $this->sch_setting_detail;
        $page = $this->load->view('admin/onlineexam/_rankgenerate', $data, true);
        $array = array('status' => 1, 'page' => $page, 'examid' => $examid, 'message' => $this->lang->line('success_message'));
        echo json_encode($array);
    }

    public function add()
    {
        $this->form_validation->set_rules('exam', $this->lang->line('exam'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('attempt', $this->lang->line('attempt'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('exam_from', $this->lang->line('exam') . " " . $this->lang->line('from'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('exam_to', $this->lang->line('exam') . " " . $this->lang->line('to'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('duration', $this->lang->line('duration'), 'trim|required|callback_validate_duration');
        $this->form_validation->set_rules('description', $this->lang->line('description'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('passing_percentage', $this->lang->line('percentage'), 'trim|required|xss_clean');
        if ($this->form_validation->run() == false) {
            $msg = array(
                'exam'               => form_error('exam'),
                'attempt'            => form_error('attempt'),
                'exam_from'          => form_error('exam_from'),
                'duration'           => form_error('duration'),
                'exam_to'            => form_error('exam_to'),
                'description'        => form_error('description'),
                'passing_percentage' => form_error('passing_percentage'),
            );

            $array = array('status' => 0, 'error' => $msg, 'message' => '');
        } else {
            $is_active          = 0;
            $publish_result     = 0;
            $is_marks_display   = 0;
            $is_neg_marking     = 0;
            $is_random_question = 0;
            $is_quiz            = 0;
            $auto_publish_date  = "";
            if (isset($_POST['is_active'])) {
                $is_active = 1;
            }
            if (isset($_POST['publish_result'])) {
                $publish_result = 1;
            }
            if (isset($_POST['is_marks_display'])) {
                $is_marks_display = 1;
            }
            if (isset($_POST['is_neg_marking'])) {
                $is_neg_marking = 1;
            }
            if (isset($_POST['is_random_question'])) {
                $is_random_question = 1;
            }

            if (isset($_POST['auto_publish_date']) && $_POST['auto_publish_date'] != "") {

                $auto_publish_date = date('Y-m-d H:i:s', $this->customlib->dateTimeformatTwentyfourhour($this->input->post('auto_publish_date'), false));
            } else {
                $auto_publish_date = null;
            }

            $insert_data = array(
                'exam'               => $this->input->post('exam'),
                'attempt'            => $this->input->post('attempt'),
                'exam_from'          => date('Y-m-d H:i:s', $this->customlib->dateTimeformatTwentyfourhour($this->input->post('exam_from'), false)),
                'exam_to'            => date('Y-m-d H:i:s', $this->customlib->dateTimeformatTwentyfourhour($this->input->post('exam_to'), false)),
                'auto_publish_date'  => $auto_publish_date,
                'duration'           => $this->input->post('duration'),
                'description'        => $this->input->post('description'),
                'session_id'         => $this->setting_model->getCurrentSession(),
                'is_active'          => $is_active,
                'publish_result'     => $publish_result,
                'is_marks_display'   => $is_marks_display,
                'is_neg_marking'     => $is_neg_marking,
                'is_random_question' => $is_random_question,
                'passing_percentage' => $this->input->post('passing_percentage'),
            );
            if (isset($_POST['is_quiz']) && $_POST['is_quiz'] != "") {
                $insert_data['publish_result']    = 0;
                $insert_data['auto_publish_date'] = null;
                $insert_data['is_quiz']           = 1;
            } else {
                $insert_data['is_quiz'] = $is_quiz;
            }

            $id = $this->input->post('recordid');
            if ($id != 0) {
                $insert_data['id'] = $id;
            }

            $this->onlineexam_model->add($insert_data);
            if ($id != 0) {
                $exam_notification = $this->onlineexam_model->get_msnstatusByexam_id($id);


                if ($is_active == 1 && $exam_notification['publish_exam_notification'] == 0) {

                    $sender_details = array(
                        'exam_id'               => $id,
                        'exam_title'               => $this->input->post('exam'),
                        'attempt'            => $this->input->post('attempt'),
                        'time_duration' => $this->input->post('duration'),
                        'passing_percentage' => $this->input->post('passing_percentage'),
                        'exam_from'          => date('Y-m-d H:i:s', $this->customlib->dateTimeformatTwentyfourhour($this->input->post('exam_from'), false)),
                        'exam_to'            => date('Y-m-d H:i:s', $this->customlib->dateTimeformatTwentyfourhour($this->input->post('exam_to'), false)),

                    );

                    $notification_status = $this->mailsmsconf->mailsms('online_examination_publish_exam', $sender_details);

                    $publish_exam_notification['id'] = $id;
                    $publish_exam_notification['publish_exam_notification'] = '1';
                    $this->onlineexam_model->add($publish_exam_notification);
                }

                if ($publish_result == 1 && $exam_notification['publish_result_notification'] == 0) {

                    $sender_details = array(
                        'exam_id'               => $id,
                        'exam_title'               => $this->input->post('exam'),
                        'attempt'            => $this->input->post('attempt'),
                        'time_duration' => $this->input->post('duration'),
                        'passing_percentage' => $this->input->post('passing_percentage'),
                        'exam_from'          => date('Y-m-d H:i:s', $this->customlib->dateTimeformatTwentyfourhour($this->input->post('exam_from'), false)),
                        'exam_to'            => date('Y-m-d H:i:s', $this->customlib->dateTimeformatTwentyfourhour($this->input->post('exam_to'), false)),

                    );

                    $this->mailsmsconf->mailsms('online_examination_publish_result', $sender_details);
                    $publish_result_notification['id'] = $id;
                    $publish_result_notification['publish_result_notification'] = '1';
                    $this->onlineexam_model->add($publish_result_notification);
                }
            }

            $array = array('status' => 1, 'error' => '', 'message' => $this->lang->line('success_message'));
        }

        echo json_encode($array);
    }

    public function getRecord($id)
    {

        $result            = $this->onlineexam_model->get_result($id);
        $result['options'] = $this->onlineexam_model->get_option($id);
        $result['ans']     = $this->onlineexam_model->get_answer($id);
        echo json_encode($result);
    }

    public function delete($id)
    {

        $this->onlineexam_model->remove($id);
        redirect('admin/onlineexam', 'refresh');
    }


    public function saverank()
    {

        $this->form_validation->set_rules('row[]', 'row', 'trim|required|xss_clean');


        if ($this->form_validation->run() == false) {

            $msg = array(
                'row'    => form_error('row[]'),

            );

            $array = array('status' => 0, 'error' => $msg, 'message' => '');
        } else {
            $row = $this->input->post('row');
            $exam_id = $this->input->post('exam_id');

            if (!empty($row)) {
                $students = array();
                foreach ($row as $row_key => $row_value) {
                    $students[] = array(
                        'id' => $row_value,
                        'rank' => $this->input->post('onlineexam_student_id_' . $row_value)
                    );
                }

                $this->onlineexam_model->updateStudentRank($students, $exam_id);
            }

            $array = array('status' => 1, 'error' => '', 'message' => $this->lang->line('success_message'));
        }

        echo json_encode($array);
    }

    public function fillmarks()
    {

        $this->form_validation->set_rules('onlineexam_student_result_id', $this->lang->line('exam'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('fill_mark', $this->lang->line('marks'), 'trim|xss_clean|callback_validate_marks');
        $this->form_validation->set_rules('question_marks', 'question_marks--r', 'trim|required|xss_clean');

        if ($this->form_validation->run() == false) {

            $msg = array(
                'question_id'    => form_error('onlineexam_student_result_id'),
                'fill_mark'      => form_error('fill_mark'),
                'question_marks' => form_error('question_marks'),
            );

            $array = array('status' => 0, 'error' => $msg, 'message' => '');
        } else {
            $insert_data = array(
                'id'     => $this->input->post('onlineexam_student_result_id'),
                'marks'  => $this->input->post('fill_mark'),
                'remark' => $this->input->post('remark'),

            );
            $this->onlineexamresult_model->update($insert_data);
            $array = array('status' => 1, 'error' => '', 'message' => $this->lang->line('success_message'));
        }

        echo json_encode($array);
    }

    public function validate_duration($str)
    {
        if ($this->input->post('duration') != "") {
            if ($this->input->post('duration') != "00:00:00") {
                if (!preg_match('/^(0[0-9]|1[0-9]|2[0-3]):[0-5][0-9]:[0-5][0-9]$/', $str)) {

                    $this->form_validation->set_message('validate_duration', 'The %s field must be HH:mm:ss');
                    return false;
                }
            } else {
                $this->form_validation->set_message('validate_duration', 'The %s field can not be 00:00:00 ');
                return false;
            }
            return true;
        }
        return true;
    }

    public function validate_marks($str)
    {
        if (($this->input->post('fill_mark') != "") && ($this->input->post('question_marks') != "")) {
            if (preg_match('/^[+-]?([0-9]*[.])?[0-9]+$/', $str)) {
                if ($this->input->post('question_marks') < $this->input->post('fill_mark')) {
                    $this->form_validation->set_message('validate_marks', 'The %s field must be between 0 and ' . $this->input->post('question_marks'));
                    return false;
                }
                return true;
            } else {
                $this->form_validation->set_message('validate_marks', 'The %s field can only contain numbers');
                return false;
            }
        } elseif ($this->input->post('fill_mark') != "") {
            $this->form_validation->set_message('validate_marks', 'The %s field is requiredss');
            return false;
        }
    }

    public function questionAdd()
    {

        $this->form_validation->set_rules('question_id', $this->lang->line('exam'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('onlineexam_id', $this->lang->line('attempt'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('ques_mark', 'marks --r', 'trim|required|xss_clean');
        $this->form_validation->set_rules('ques_neg_mark', 'neg marks --r', 'trim|required|xss_clean');

        if ($this->form_validation->run() == false) {

            $msg = array(
                'question_id'   => form_error('question_id'),
                'onlineexam_id' => form_error('onlineexam_id'),
                'ques_mark'     => form_error('ques_mark'),
                'ques_neg_mark' => form_error('ques_neg_mark'),
            );

            $array = array('status' => 0, 'error' => $msg, 'message' => '');
        } else {
            $insert_data = array(
                'question_id'   => $this->input->post('question_id'),
                'onlineexam_id' => $this->input->post('onlineexam_id'),
                'marks'         => $this->input->post('ques_mark'),
                'neg_marks'     => $this->input->post('ques_neg_mark'),
            );
            $this->onlineexam_model->insertExamQuestion($insert_data);
            $array = array('status' => 1, 'error' => '', 'message' => $this->lang->line('success_message'));
        }

        echo json_encode($array);
    }

    public function deleteExamQuestions()
    {

        $this->form_validation->set_rules('question_id', 'question', 'trim|required|xss_clean');

        if ($this->form_validation->run() == false) {

            $msg = array(
                'question_id' => form_error('question_id'),
            );

            $array = array('status' => 0, 'error' => $msg, 'message' => '');
        } else {

            $this->onlineexamquestion_model->remove($this->input->post('question_id'));
            $array = array('status' => 1, 'error' => '', 'message' => $this->lang->line('delete_message'));
        }

        echo json_encode($array);
    }

    public function report()
    {

        $this->session->set_userdata('top_menu', 'Reports');
        $this->session->set_userdata('sub_menu', 'Reports/online_examinations');
        $this->session->set_userdata('subsub_menu', 'Reports/online_examinations/online_exam_report');
        $examList            = $this->onlineexam_model->get();
        $data['examList']    = $examList;
        $class               = $this->class_model->get();
        $data['classlist']   = $class;
        $data['sch_setting'] = $this->sch_setting_detail;
        $this->form_validation->set_rules('exam_id', $this->lang->line('exam'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('class_id', $this->lang->line('class'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('section_id', $this->lang->line('section'), 'trim|required|xss_clean');

        if ($this->form_validation->run() == false) {

            $this->load->view('layout/header', $data);
            $this->load->view('admin/onlineexam/report', $data);
            $this->load->view('layout/footer', $data);
        } else {

            if ($this->input->server('REQUEST_METHOD') == "POST") {

                $exam_id         = $this->input->post('exam_id');
                $class_id        = $this->input->post('class_id');
                $section_id      = $this->input->post('section_id');
                $results         = $this->onlineexamresult_model->getStudentByExam($exam_id, $class_id, $section_id);
                $data['results'] = $results;
            }

            $this->load->view('layout/header', $data);
            $this->load->view('admin/onlineexam/report', $data);
            $this->load->view('layout/footer', $data);
        }
    }

    public function getstudentresult()
    {
        $onlineexam_student_id      = $this->input->post('recordid');
        $examid                     = $this->input->post('examid');
        $student_session_id         = $this->input->post('student_session_id');
        $data['student_session_id'] = $this->input->post('student_session_id');
        $exam                       = $this->onlineexam_model->get($examid);
        $data['exam']               = $exam;
        $online_exam_validate       = $this->onlineexam_model->examstudentsID($student_session_id, $examid);

        $data['question_result'] = $this->onlineexamresult_model->getResultByStudent($onlineexam_student_id, $examid);
        $data['result_prepare']        = $this->onlineexamresult_model->checkResultPrepare($onlineexam_student_id);
        $data['online_exam_validate']  = $online_exam_validate;
        $questionOpt                   = $this->customlib->getQuesOption();
        $data['questionOpt']           = $questionOpt;
        $data['onlineexam_student_id'] = $onlineexam_student_id;
        $data['question_true_false']   = $this->config->item('question_true_false');

        $print = $this->input->post('print');
        if (isset($print)) {

            $question_result = $this->load->view('admin/onlineexam/_print', $data, true);
        } else {
            $question_result = $this->load->view('admin/onlineexam/_getstudentresult', $data, true);
        }

        echo json_encode(array('status' => 1, 'result' => $question_result));
    }

    public function getExamQuestions()
    {
        $exam_id                  = $this->input->post('recordid');
        $exam                     = $this->onlineexam_model->get($exam_id);
        $data['exam']             = $exam;
        $data['questions']        = $this->onlineexamquestion_model->getExamQuestions($exam_id);
        $data['questionSubjects'] = $this->onlineexamquestion_model->getExamQuestionSubjects($exam_id);
        $data['question_type']    = $this->config->item('question_type');
        $data['question_level']   = $this->config->item('question_level');

        $questionList = $this->load->view('admin/onlineexam/_getexamquestions', $data, true);
        echo json_encode(array('status' => 1, 'result' => $questionList, 'exam' => $exam));
    }
}
