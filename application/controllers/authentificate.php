<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
require_once 'controller.php';
/**
 * Class Authentificate User
 * @author Vyacheslav Dolya <vyacheslav.dolya@gmail.com>
 * @package QR-BAR-CODE
 * @subpackage Controller
 */
class Authentificate extends Controller
{
    public $activity = 'sign';
    public $is_facebook_user;
    public $facebook_user_id;
    public $user_email;
    public $user_password;  

    public function __construct()
    {
        parent::__construct();
        $this->_preValidate();
    }

    /**
     * Pre Validate function for validation required params
     */
    private function _preValidate()
    {
//        $_POST = array(
//        'is_facebook_user' => 1,
//        'facebook_user_id' => '12345678',
//        'facebook_username' => 'james',
//        'user_email' => 'james@co.uk',
//        'user_password' => '123456',
//        'user_first_name' => 'bobby',
//        'user_last_name' => 'statem',
//        );
        $this->load->library('validation');
        $this->validation->set_rules('is_facebook_user', 'is Facebook User', 'required|boolean');
        if ($this->validation->run() == FALSE)
        {
            $this->setError($this->code, $this->validation->error_string());
            $this->output($this->activity);
        }
        else
        {
            $this->is_facebook_user = $this->input->post('is_facebook_user');
        }
    }

    /**
     * function of user validation
     * check user existings in db and allow or deny his request
     * and set public user variable
     */
    public function validateUser()
    {
        $this->load->library('validation');
        if ($this->is_facebook_user)
        {
            $this->validation->set_rules('facebook_user_id', 'Facebook User Id', 'required||max_length[16]|callback_facebook_user_id_does_not_exist');
        }
        else
        {
            $this->validation->set_rules('user_password', 'User Password', 'required');
        }

        $this->validation->set_rules('user_email', 'User Email', 'required|valid_email|callback_user_email_does_not_exist');
        if ($this->validation->run() == FALSE)
        {
            $this->setError($this->code, $this->validation->error_string());
            $this->output($this->activity);
        }
        else
        {
            $this->user_email = $this->input->post('user_email');
            if ($this->is_facebook_user)
            {
                $this->facebook_user_id = $this->input->post('facebook_user_id');
                $attributes = array(
                    'is_facebook_user' => 1,
                    'facebook_user_id' => $this->facebook_user_id,
                    'user_email' => $this->user_email,
                );
            }
            else
            {
                $this->user_password = $this->input->post('user_password');
                $attributes = array(
                    'user_email' => $this->user_email,
                    'user_password' => $this->user_password,
                );
            }

            $this->load->model('users');
            $this->user = $this->users->findByAttributes($attributes);
            if (empty($this->user))
            {
                $this->lang->load('validation');
                $this->code = 506;
                $this->setError($this->code, $this->lang->line('no_such_user'));
                $this->output($this->activity);
            }
        }
    }

}