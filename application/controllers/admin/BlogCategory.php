<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class BlogCategory extends Admin_Controller
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
        $this->session->set_userdata('top_menu', 'Blog');
        $this->session->set_userdata('sub_menu', 'Blog/category');
        $class = $this->class_model->get();

        $data['result'] = $this->db->get('blogCategory')->result();
        $this->load->view('layout/header', $data);
        $this->load->view('admin/blog-category/category', $data);
        $this->load->view('layout/footer', $data);
    }

    public function create()
    {
        $data = array();
        $data['sessionlist'] = $this->session_model->get();
        $this->session->set_userdata('top_menu', 'Blog');
        $this->session->set_userdata('sub_menu', 'Blog/category');
        $class = $this->class_model->get();


        $this->form_validation->set_rules('name', $this->lang->line('name'), 'required');
        if ($this->form_validation->run() == FALSE) {


            $this->load->view('layout/header', $data);
            $this->load->view('admin/blog-category/category', $data);
            $this->load->view('layout/footer', $data);
        } else {

            $prepareData['name'] = $this->input->post('name');
            $this->db->insert('blogCategory', $prepareData);
        }
        redirect('admin/blogCategory/index');
    }

    public function delete($id)
    {
        $hasData = $this->db->get_where('blog', ['category_id' => $id])->num_rows();
        if ($hasData) {
            $this->session->set_flashdata('msg', '<div class="alert alert-danger text-left">Failed To Delete Category Please Remove Blogs First</div>');
            redirect('admin/blogCategory/index');
        }
        $this->db->where('id', $id)->delete('blogCategory');
        $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('success_message') . '</div>');
        redirect('admin/blogCategory/index');
    }
}
