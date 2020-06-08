<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

    public function __construct(){

        parent::__construct();
        // is_log_in();

        $this->load->library('form_validation');
        
    }

    public function index(){

        if($this->session->userdata('email')) {
            redirect ('user');
        }

        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
        $this->form_validation->set_rules('password', 'Password', 'trim|required');
        
        if($this->form_validation->run() == false){

            $data['title'] = 'Login Page';
            $this->load->view('templates/auth_header', $data);
            $this->load->view('auth/login');
            $this->load->view('templates/auth_footer');
        } else{
            // validasinya success
            $this->_login();
        }
    }

    private function _login(){
        $email = $this->input->post('email');
        $password = $this->input->post('password');

        $user = $this->db->get_where('user', ['email' => $email])->row_array();
        
        // usernya ada
        if($user) {
            // jika usernya aktif
            if($user['is_active'] == 1){

            
            if(password_verify($password, $user['password'])) {
                $data = [
                    'email' => $user['email'],
                    'role_id' => $user['role_id']
                ];
                $this->session->set_userdata($data);
                if ($user['role_id'] == 1){
                    redirect('admin');
                } else {
                    redirect('user');
                }
            }else{
                $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">
                Wrong password!</div>');
                redirect('auth'); 
            }
        }else {
                $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">
                this email has not been activated!</div>');
                redirect('auth'); 
            }
        }else{
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">
            Email is not registered!</div>');
            redirect('auth'); 
        }
    }   

    public function registration(){

        if($this->session->userdata('email')) {
            redirect ('user');
        }

        $this->form_validation->set_rules('name', 'Name', 'trim|required');
        $this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email|is_unique[user.email]', [
            'is_unique' => 'This email has already registered!'
        ]);
        $this->form_validation->set_rules('password1', 'Password', 'trim|required|min_length[3]|matches[password2]', [
            'matches' =>'password dont match!',
            'min_length' => 'Password too short!' 
        ]);
        $this->form_validation->set_rules('password2', 'R Password', 'trim|required|min_length[3]|matches[password1]');
        
        if( $this->form_validation->run() == false ){

            $data['title'] = 'WPU User Registration';
            $this->load->view('templates/auth_header', $data);
            $this->load->view('auth/registration');
            $this->load->view('templates/auth_footer');
        } else {
            $email = $this->input->post('email', true);
            $data = [
                'name' => htmlspecialchars($this->input->post('name', true)),
                'email' => htmlspecialchars($email),
                'image' => 'default.jpg',
                'password' => password_hash($this->input->post('password1'), PASSWORD_DEFAULT),
                'role_id' => 2,
                'is_active' => 0,
                'date_created' =>time()
            ];

            $token = base64_encode(random_bytes(32));
            $user_token = [
                'email' => $email,
                'token' => $token,
                'date_created' => time()

            ];

            $this->db->insert('user', $data);
            $this->db->insert('user_token', $user_token);

            $this->_sendEmail($token, 'verify');

            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
            Congratulation! your account has been created. Please Login!
            </div>');
            redirect('auth'); 
        }
    }

    private function _sendEmail($token, $type){
        $config = [
            'protocol'  => 'smtp',
            'smtp_host' => 'ssl://smtp.googlemail.com',
            'smtp_user' => 'lintangsemesta01@gmail.com',
            'smtp_pass' => 'lintangs01',
            'smtp_port' => 465,
            'mailtype'  => 'html',
            'charset'   => 'utf-8',
            'newline'   => "\r\n"
        ];

        $this->email->initialize($config);
        $this->load->library('email', $config);

        $this->email->from('lintangsemesta01@gmail.com');
        $this->email->to($this->input->post('email'));

        if ($type == 'verify') {

            $this->email->subject('Account verification');
            $this->email->message('click this link to verify your account : <a href="'. base_url() . 'auth/verify?email=' . $this->input->post('email') . '&token=' . $token . urlendcode($token) . '">Activate</a>');
        }

        if($this->email->send()) {
            return true;
        } else {
            echo $this->email->print_debugger();
            die;
        }
    }

    public function logout(){
        $this->session->unset_userdata('email');
        $this->session->unset_userdata('role_id');

        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
        Congratulation! your account has been logouted!
        </div>');
        redirect('auth'); 
    }

    public function blocked(){
        $this->load->view('auth/blocked');
    }

}