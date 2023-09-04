<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Academic extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->auth->is_logged_in();
        $this->sch_setting_detail = $this->setting_model->getSetting();
        $this->config->load('app-config');
        $this->load->library('smsgateway');
        $this->load->library('mailsmsconf');
        $this->load->library('encoding_lib');
    }

    public function index()
    {
        $data = array();
        $data['sessionlist'] = $this->session_model->get();
        $this->session->set_userdata('top_menu', 'AcademicNotice');
        $this->session->set_userdata('sub_menu', 'AcademicNotice/index');
        $class = $this->class_model->get();


        $data['academic_messages'] = $this->db->get('academic_messages')->result();
        $this->load->view('layout/header', $data);
        $this->load->view('admin/academic-message/index', $data);
        $this->load->view('layout/footer', $data);
    }

    public function create()
    {
        $data = array();
        $data['sessionlist'] = $this->session_model->get();
        $this->session->set_userdata('top_menu', 'AcademicNotice');
        $this->session->set_userdata('sub_menu', 'AcademicNotice/index');
        $class = $this->class_model->get();

        $this->load->view('layout/header', $data);
        $this->load->view('admin/academic-message/create', $data);
        $this->load->view('layout/footer', $data);
    }

    public function store()
    {
        $data = array();
        $data['sessionlist'] = $this->session_model->get();
        $this->session->set_userdata('top_menu', 'AcademicNotice');
        $this->session->set_userdata('sub_menu', 'AcademicNotice/index');
        $class = $this->class_model->get();


        $this->form_validation->set_rules('title', $this->lang->line('title'), 'required');
        $this->form_validation->set_rules('name', $this->lang->line('name'), 'required');
        $this->form_validation->set_rules('email', $this->lang->line('email'), 'required');
        $this->form_validation->set_rules('phone', $this->lang->line('phone'), 'required');
        $this->form_validation->set_rules('image', $this->lang->line('image'), 'required');
        $this->form_validation->set_rules('description', $this->lang->line('description'), 'required');

        if ($this->form_validation->run() == FALSE) {


            $this->load->view('layout/header', $data);
            $this->load->view('admin/academic-message/create', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $prepareData['title'] = $this->input->post('title');
            $prepareData['name'] = $this->input->post('name');
            $prepareData['email'] = $this->input->post('email');
            $prepareData['phone'] = $this->input->post('phone');
            $prepareData['description'] = $this->input->post('description');
            $prepareData['image'] = $this->input->post('image');
            $this->db->insert('academic_messages', $prepareData);
        }
        redirect('admin/academic/index');
    }

    public function edit($id)
    {
        $data = array();
        $data['sessionlist'] = $this->session_model->get();
        $this->session->set_userdata('top_menu', 'AcademicNotice');
        $this->session->set_userdata('sub_menu', 'AcademicNotice/index');
        $class = $this->class_model->get();

        $data['result'] = $this->db->get_where('academic_messages', ['id' => $id])->row_array();

        $this->load->view('layout/header', $data);
        $this->load->view('admin/academic-message/edit', $data);
        $this->load->view('layout/footer', $data);
    }

    public function update($id)
    {
        $data = array();
        $data['sessionlist'] = $this->session_model->get();
        $this->session->set_userdata('top_menu', 'AcademicNotice');
        $this->session->set_userdata('sub_menu', 'AcademicNotice/index');
        $class = $this->class_model->get();


        $this->form_validation->set_rules('title', $this->lang->line('title'), 'required');
        $this->form_validation->set_rules('name', $this->lang->line('name'), 'required');
        $this->form_validation->set_rules('email', $this->lang->line('email'), 'required');
        $this->form_validation->set_rules('phone', $this->lang->line('phone'), 'required');
        $this->form_validation->set_rules('image', $this->lang->line('image'), 'required');
        $this->form_validation->set_rules('description', $this->lang->line('description'), 'required');

        if ($this->form_validation->run() == FALSE) {


            $this->load->view('layout/header', $data);
            $this->load->view('admin/academic-message/create', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $prepareData['title'] = $this->input->post('title');
            $prepareData['name'] = $this->input->post('name');
            $prepareData['email'] = $this->input->post('email');
            $prepareData['phone'] = $this->input->post('phone');
            $prepareData['description'] = $this->input->post('description');
            $prepareData['image'] = $this->input->post('image');
            $this->db->where('id', $id);
            $this->db->update('academic_messages', $prepareData);
        }
        redirect('admin/academic/index');
    }

    public function delete($id)
    {
        $this->db->where('id', $id)->delete('academic_messages');
        $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('success_message') . '</div>');
        redirect('admin/academic/index');
    }
}
