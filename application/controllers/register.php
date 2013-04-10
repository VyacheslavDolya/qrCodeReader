<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

require_once 'authentificate.php';

/**
 * Class Register
 * @author Vyacheslav Dolya <vyacheslav.dolya@gmail.com>
 * @package QR-BAR-CODE
 * @subpackage Controller
 * @link register
 */
class Register extends Authentificate
{

    public $user_first_name = '';
    public $user_last_name = '';
    public $facebook_username = '';
    public $locale = '';
    public $birthday = '';
    public $gender = 0;
    public $activity = 'sign';

    public function __construct()
    {
        parent::__construct();
        $this->_validate();
    }

    /**
     * function of user validation,
     * validate user params
     * and set class public variables
     */
    private function _validate()
    {
        $this->load->library('validation');
        if ($this->is_facebook_user)
        {
            $this->validation->set_rules('facebook_user_id', 'Facebook User Id', 'required|callback_facebook_user_id_exist');
            $this->validation->set_rules('facebook_username', 'Facebook User Name', 'required');
        }

        $this->validation->set_rules('user_email', 'User Email', 'required|valid_email|callback_user_email_exist|max_length[256]');
        $this->validation->set_rules('user_password', 'User Passwordl', 'required|max_length[256]');
        $this->validation->set_rules('user_first_name', 'User First Name', 'required|max_length[256]');
        $this->validation->set_rules('user_last_name', 'User Last Name', 'required|max_length[256]');

        if($this->input->post('locale'))
        {
            $this->validation->set_rules('locale', 'User Last Name', 'required|max_length[256]');
        }

        if($this->input->post('birthday'))
        {
            $this->validation->set_rules('birthday', 'User Last Name', 'required|integer');
        }

        if($this->input->post('gender'))
        {
            $this->validation->set_rules('gender', 'User Last Name', 'required|boolean');
        }

        if ($this->validation->run() == FALSE)
        {
            $this->setError(500, $this->validation->error_string());
            $this->output($this->activity);
            die();
        }
        else
        {
            $this->facebook_user_id = $this->is_facebook_user ? $this->input->post('facebook_user_id') : 0;
            $this->facebook_username = $this->is_facebook_user ? $this->input->post('facebook_username') : '';
            $this->locale = $this->locale ? $this->input->post('locale') : '';
            $this->birthday = $this->birthday ? $this->input->post('birthday') : 0;
            $this->gender = $this->gender ? $this->input->post('gender') : 0;
            $this->user_email = $this->input->post('user_email');
            $this->user_password = $this->input->post('user_password');
            $this->user_first_name = $this->input->post('user_first_name');
            $this->user_last_name = $this->input->post('user_last_name');
        }
    }

    /**
     * Basic function for Regestering User
     */
    public function index()
    {
        $this->load->model('users');
        $user = $this->users->register(
                array(
                    'is_facebook_user' => $this->is_facebook_user,
                    'user_email' => $this->user_email,
                    'user_password' => $this->user_password,
                    'facebook_user_id' => $this->facebook_user_id,
                    'facebook_username' => $this->facebook_username,
                    'user_first_name' => $this->user_first_name,
                    'user_last_name' => $this->user_last_name,
                    'locale' => $this->locale,
                    'birthday' => $this->birthday,
                    'gender' => $this->gender,
                )
        );
        if (!empty($user))
        {
            $this->setParam('user', $user);
        }
        else
        {
            $this->lang->load('validation');
            $this->setError(501, $this->lang->line('save_failed'));
        }
        $this->output($this->activity);
    }

    /**
     * callback function for checking email in database
     * @param string $email user email address
     * @return boolean
     */
    public function user_email_exist($email)
    {
        $this->lang->load('validation');
        $this->load->model('users');
        $find = $this->users->checkEmailExistings($email);
        if (!empty($find))
        {
            $this->validation->set_message('user_email_exist', $this->lang->line('user_email_exist'));
            return false;
        }
        return true;
    }

    /**
     * callback function for checking facebook user id in database
     * @param string $facebook_user_id facebook user id
     * @return boolean
     */
    public function facebook_user_id_exist($facebook_user_id)
    {
        $this->lang->load('validation');
        $this->load->model('users');
        $find = $this->users->checkFacebookUserIdExistings($facebook_user_id);
        if (!empty($find))
        {
            $this->validation->set_message('facebook_user_id_exist', $this->lang->line('facebook_user_id_exist'));
            return false;
        }
        return true;
    }

}