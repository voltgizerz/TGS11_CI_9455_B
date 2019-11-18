<?php

use Restserver\Libraries\REST_Controller;

class Auth extends REST_Controller
{
    public function __construct()
    {
        header("Access-Control-Allow-Origin: *");         
        header("Access-Control-Allow-Methods: GET, OPTIONS, POST, DELETE");         
        header("Access-Control-Allow-Headers: Authorization, Content-Type, Content-Length, Accept-Encoding");  
        parent::__construct();
        $this->load->model('UserModel');
        $this->load->library('form_validation');
        $this->load->helper(['jwt', 'authorization']);
    }
    public function Rules()
    {
        return [
            [
                'field' => 'password',
                'label' => 'password',
                'rules' => 'required'
            ],
            [
                'field' => 'email',
                'label' => 'email',
                'rules' => 'required|valid_email'
            ]
        ];
    }

    public function index_post()
    {
        $validation = $this->form_validation;
        $rule = $this->Rules();
        $validation->set_rules($rule);
        if (!$validation->run()) {
            return $this->response($this->form_validation->error_array());
        }

        $user = new UserData();
        $user->password = $this->post('password');
        $user->email = $this->post('email');

        if ($result = $this->UserModel->verifyUser($user)) {
            // Create a token
            $token = AUTHORIZATION::generateToken(['id' => $result['id'], 'name' => $result['name']]);
            // Set HTTP status code
            $status = parent::HTTP_OK;
            $response = ['status' => $status, 'token' => $token];
            $this->response($response, $status);
        } else {
            return  $this->response(['msg' => 'Invalid username or password!'], parent::HTTP_NOT_FOUND);
        }
    }
}
class UserData
{
    public $name;
    public $password;
    public $email;
}
