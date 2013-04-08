<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Class Authentificate User
 * @author Vyacheslav Dolya <vyacheslav.dolya@gmail.com>
 * @package QR-BAR-CODE
 * @subpackage Controller
 */
class Authentificate extends CI_Controller
{

    protected $error = array();
    protected $user = array();
    protected $meta = array();
    protected $deleted = array();
    protected $record = array();
    protected $history = array();
    protected $types = array();
    public $activity = 'sign';
    public $is_facebook_user;
    public $facebook_user_id;
    public $facebook_user_token;
    public $user_email;
    public $user_password;

    public function __construct()
    {
        $_POST = array(
            'is_facebook_user' => '0',
            'user_email' => 'ebalda@gmail.com',
            'user_password' => '1234567',
            'user_first_name' => 'Test',
            'user_last_name' => 'Test',
            
        );
        parent::__construct();
        $this->_preValidate();
    }

    /**
     * Pre Validate function for validation required params
     */
    private function _preValidate()
    {
        $this->load->library('validation');
        $this->validation->set_rules('is_facebook_user', 'is Facebook User', 'required|boolean');
        if ($this->validation->run() == FALSE)
        {
            $this->setError(500, $this->validation->error_string());
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
            $this->validation->set_rules('facebook_user_id', 'Facebook User Id', 'required|integer|callback_facebook_user_id_does_not_exist');
            $this->validation->set_rules('facebook_user_token', 'Facebook User Token', 'required');
        }
        else
        {
            $this->validation->set_rules('user_email', 'User Email', 'required|valid_email|callback_user_email_does_not_exist');
            $this->validation->set_rules('user_password', 'User Passwordl', 'required');
        }

        if ($this->validation->run() == FALSE)
        {
            $this->setError(500, $this->validation->error_string());
            $this->output($this->activity);
        }
        else
        {
            if ($this->is_facebook_user)
            {
                $this->facebook_user_id = $this->input->post('facebook_user_id');
                $this->facebook_user_token = $this->input->post('facebook_user_token');
                $attributes = array(
                    'is_facebook_user' => 1,
                    'facebook_user_id' => $this->facebook_user_id,
                    'facebook_user_token' => $this->facebook_user_token,
                );
            }
            else
            {
                $this->user_email = $this->input->post('user_email');
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
                $this->setError(501, $this->lang->line('no_such_user'));
                $this->output($this->activity);
            }
        }
    }

    /**
     * basic function for server outpur request
     * @param string $activity type of recent activity
     */
    public function output($activity)
    {
        header('Content-type: application/json');
        $output = array();
        if(!empty($this->error))
        {
            $output['error'] = $this->error;
        }
        else
        {
            switch ($activity)
            {
                case 'sign':
                    $output = array(
                        'user' => $this->user,
                    );
                    break;
                case 'add':
                    $output = array(
                        'record' => $this->record,
                    );
                    break;
                case 'delete':
                    $output = array(
                    );
                    break;
                case 'history':
                    $output = array(
                        'history' => $this->history,
                        'meta' => $this->meta,
                        'deleted' => $this->deleted,
                    );
                    break;
                case 'types':
                    $output = array(
                        'types' => $this->types,
                    );
                    break;
            }
        }
        echo json_encode($output);
        die();
    }

    /**
     * function for setting protected variable error in a correct format
     * @param int $code error code
     * @param string $description error description
     */
    public function setError($code, $description)
    {
        $this->error = array(
            'error_code' => $code,
            'error_description' => $description
        );
    }

    /**
     * function for setting protected variables
     * @param string $variable name of protected variable
     * @param mixed $value value of protected variable
     */
    public function setParam($variable, $value)
    {
        switch ($variable)
        {
            case 'user':
                $this->user = $value;
                break;
            case 'record':
                $this->record = $value;
                break;
            case 'history':
                $this->history = $value;
                break;
            case 'meta':
                $this->meta = $value;
                break;
            case 'deleted':
                $this->deleted = $value;
                break;
            case 'types':
                $this->types = $value;
                break;
        }
    }

    /**
     * callback function for checking email in database
     * @param string $email user email address
     * @return boolean
     */
    public function user_email_does_not_exist($email)
    {
        $this->lang->load('validation');
        $this->load->model('users');
        $find = $this->users->checkEmailExistings($email);
        if (empty($find))
        {
            $this->validation->set_message('user_email_does_not_exist', $this->lang->line('user_email_does_not_exist'));
            return false;
        }
        return true;
    }

    /**
     * callback function for checking facebook user id in database
     * @param string $facebook_user_id facebook user id
     * @return boolean
     */
    public function facebook_user_id_does_not_exist($facebook_user_id)
    {
        $this->lang->load('validation');
        $this->load->model('users');
        $find = $this->users->checkFacebookUserIdExistings($facebook_user_id);
        if (empty($find))
        {
            $this->validation->set_message('facebook_user_id_does_not_exist', $this->lang->line('facebook_user_id_does_not_exist'));
            return false;
        }
        return true;
    }

}