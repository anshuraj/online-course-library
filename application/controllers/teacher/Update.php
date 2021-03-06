<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Update extends CI_Controller {

	public function __construct()
	{
		parent::__construct();

        $this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
        $this->output->set_header("Pragma: no-cache");   
        $this->output->set_content_type('application_json');     
       
		$this->load->model('course_model');
		$this->load->model('test_model');

	}

	public function index($id=Null){

		$this->data['course_id'] = $id;	
		$this->data['custom_css'] = array();
		$this->data['lesson'] = $this->course_model->getLessons($id);
		$this->data['tests'] = $this->test_model->getTests($id);
		$this->data['assignments'] = $this->test_model->getAssignments($id);

		if($this->session->userdata('user_id')){

			$this->load->view('header', $this->data);
			$this->load->view('teacher/update', $this->data);
		} else {
			header('Refresh:3;'. site_url('account'));
			echo 'Please Login to continue. Redirecting...';
			exit();
		}
	}

	public function upload(){
		$this->form_validation->set_rules('l_name', 'Name', 'trim|required');
        $this->form_validation->set_rules('desc', 'Description', 'trim|required');
		$this->form_validation->set_rules('course_id', 'Course id', 'trim|required');

		if($this->form_validation->run()===FALSE){
            $response = ['status'=>0, 'message'=> $this->form_validation->error_array() ];
			$this->output->set_output(json_encode($response));

            return;
        }

		$name =  $this->input->post('l_name');
		$desc =  $this->input->post('desc');
		$course_id = $this->input->post('course_id');

		if ( ! empty($_FILES))
		{
			$config['upload_path'] = "./uploads";
			$config['allowed_types'] = 'mp4|flv|mov';

			$this->load->library('upload', $config);
			if (! $this->upload->do_upload("file")) {

				$response = ['status'=>0, 'message'=> 'File cannot be uploaded']; 
			} else {
				$file_name = $this->upload->data('file_name');
				$data = ['name'=> $name, 'course_id'=>$course_id, 'description'=> $desc, 'content'=> $file_name];

				$res = $this->course_model->createLesson($data);

				if($res == TRUE){
						$response = ['status'=>1, 'message'=> 'success', 'result'=> $res]; 

					} else {
						$response = ['status'=> 0, 'message'=> 'Error saving data'];
				    }
			}
		}
		else{
			$response = ['status'=> 0, 'message'=> 'File not found' ];
		}

		$this->output->set_output(json_encode($response));
		
	}

	public function addTest(){

		$this->form_validation->set_rules('title', 'title', 'trim|required');
        $this->form_validation->set_rules('desc', 'Description', 'trim|required');
        $this->form_validation->set_rules('course_id', 'Course id', 'trim|required');

		if($this->form_validation->run()===FALSE){
            $response = ['status'=>0, 'message'=> $this->form_validation->error_string() ];
			$this->output->set_output(json_encode($response));

            return;
        }

		$title =  $this->input->post('title');
		$desc =  $this->input->post('desc');
		$course_id = $this->input->post('course_id');

		$data = ['title'=> $title, 'description'=> $desc, 'course_id'=> $course_id];

		$res = $this->test_model->addTest($data);

		if($res == TRUE){

				$this->output->set_output(json_encode([
		            'status'=>1,
		            'message'=> 'success',
		            'data'=> $res
		        ]));

			} else {
				$this->output->set_output(json_encode([
		            'status'=> 0,
		            'message'=> 'failed'
		        ]));
		    }
	}

	public function addAssign(){

		$this->form_validation->set_rules('assign', 'Assignment', 'trim|required');
        $this->form_validation->set_rules('descrip', 'Description', 'trim|required');
        $this->form_validation->set_rules('course_id', 'Course id', 'trim|required');

		if($this->form_validation->run()===FALSE){
            $response = ['status'=>0, 'message'=> $this->form_validation->error_string() ];
			$this->output->set_output(json_encode($response));

            return;
        }

		$assign =  $this->input->post('assign');
		$descrip =  $this->input->post('descrip');
		$course_id = $this->input->post('course_id');

		$data = ['name'=> $assign, 'description'=> $descrip, 'course_id'=> $course_id];

		$res = $this->test_model->addAssign($data);

		if($res == TRUE){

				$this->output->set_output(json_encode([
		            'status'=>1,
		            'message'=> 'success',
		            'data'=> $res
		        ]));

			} else {
				$this->output->set_output(json_encode([
		            'status'=> 0,
		            'message'=> 'failed'
		        ]));
		    }
	}
}
