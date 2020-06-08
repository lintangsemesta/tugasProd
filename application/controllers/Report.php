<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Report extends CI_Controller 
{
    public function __construct()
    {
        parent::__construct();
        is_log_in();
        }
        public function index(){


            $data['title'] = 'Data User';
            $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
            $data['userr'] = $this->db->get('user')->result_array();

            $this->load->view('templates/header', $data);
            $this->load->view('templates/sidebar', $data);
            $this->load->view('templates/topbar', $data);
            $this->load->view('report/index', $data);
            $this->load->view('templates/footer');
    
            }

            
    }