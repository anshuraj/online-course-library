<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Report extends CI_Controller {

	public function __construct(){

		parent::__construct();

        $this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
        $this->output->set_header("Pragma: no-cache");   
        $this->output->set_content_type('application_json');   

        $this->load->model('test_model');
		$this->load->model('course_model');

	}

	public function index($id){

		$this->data['custom_css'] = array();
		$this->data['report'] = $this->test_model->getTestScores($id, $this->session->userdata('user_id'));
		$this->data['course'] = $this->course_model->getCourseData($id);

		$this->load->view('header', $this->data);
		$this->load->view('report');
	}

}