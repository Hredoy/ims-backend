<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Blog extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->sch_setting_detail = $this->setting_model->getSetting();
        $this->auth->is_logged_in();
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
        $this->session->set_userdata('sub_menu', 'Blog/blog');
        $class = $this->class_model->get();

        $this->db->select('blog.id, blog.title, blogCategory.name AS category_name');
        $this->db->join('blogCategory', 'blogCategory.id = blog.category_id', 'inner');
        $query = $this->db->get('blog');
        $data['resultData'] =  $query->result();
        $this->load->view('layout/header', $data);
        $this->load->view('admin/blog/list', $data);
        $this->load->view('layout/footer', $data);
    }

    public function create()
    {
        $data = array();
        $data['sessionlist'] = $this->session_model->get();
        $this->session->set_userdata('top_menu', 'Blog');
        $this->session->set_userdata('sub_menu', 'Blog/blog');
        $class = $this->class_model->get();

        $data['categories'] = $this->db->get('blogCategory')->result();

        $this->load->view('layout/header', $data);
        $this->load->view('admin/blog/create', $data);
        $this->load->view('layout/footer', $data);
    }

    public function store()
    {
        $data = array();
        $data['sessionlist'] = $this->session_model->get();
        $this->session->set_userdata('top_menu', 'Blog');
        $this->session->set_userdata('sub_menu', 'Blog/blog');
        $class = $this->class_model->get();


        $this->form_validation->set_rules('title', $this->lang->line('title'), 'required');
        $this->form_validation->set_rules('category_id', $this->lang->line('category'), 'required');
        $this->form_validation->set_rules('description', $this->lang->line('description'), 'required');

        if ($this->form_validation->run() == FALSE) {


            $this->load->view('layout/header', $data);
            $this->load->view('admin/blog/create', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $prepareData['title'] = $this->input->post('title');
            $prepareData['category_id'] = $this->input->post('category_id');
            $prepareData['description'] = $this->input->post('description');
            $prepareData['image'] = $this->input->post('image');
            $this->db->insert('blog', $prepareData);
        }
        redirect('admin/blog/index');
    }



    public function edit($id)
    {
        $data = array();
        $data['sessionlist'] = $this->session_model->get();
        $this->session->set_userdata('top_menu', 'Blog');
        $this->session->set_userdata('sub_menu', 'Blog/blog');
        $class = $this->class_model->get();

        $data['result'] = $this->db->get_where('blog', ['id' => $id])->row_array();
        $data['categories'] = $this->db->get('blogCategory')->result();

        $this->load->view('layout/header', $data);
        $this->load->view('admin/blog/edit', $data);
        $this->load->view('layout/footer', $data);
    }

    public function update($id)
    {
        $data = array();
        $data['sessionlist'] = $this->session_model->get();
        $this->session->set_userdata('top_menu', 'Blog');
        $this->session->set_userdata('sub_menu', 'Blog/blog');
        $class = $this->class_model->get();


        $this->form_validation->set_rules('title', $this->lang->line('title'), 'required');
        $this->form_validation->set_rules('category_id', $this->lang->line('category'), 'required');
        $this->form_validation->set_rules('description', $this->lang->line('description'), 'required');

        if ($this->form_validation->run() == FALSE) {


            $this->load->view('layout/header', $data);
            $this->load->view('admin/blog/create', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $prepareData['title'] = $this->input->post('title');
            $prepareData['category_id'] = $this->input->post('category_id');
            $prepareData['description'] = $this->input->post('description');
            $prepareData['image'] = $this->input->post('image');
            $this->db->where('id', $id);
            $this->db->update('blog', $prepareData);
        }
        redirect('admin/blog/index');
    }

    public function delete($id)
    {
        $this->db->where('id', $id)->delete('blog');
        $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('success_message') . '</div>');
        redirect('admin/blog/index');
    }
}
