<?php

/**
 * Class Forgot Password
 * @author Vyacheslav Dolya <vyacheslav.dolya@gmail.com>
 * @package QR-BAR-CODE
 * @subpackage Controller
 */
class Controller extends CI_Controller
{
    protected $error = array();
    protected $user = array();
    protected $meta = array();
    protected $deleted = array();
    protected $record = array();
    protected $history = array();
    protected $types = array();
    public $code = 500;

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
            $this->code = 505;
            $this->validation->set_message('facebook_user_id_does_not_exist', $this->lang->line('facebook_user_id_does_not_exist'));
            return false;
        }
        return true;
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
            $this->code = 503;
            $this->validation->set_message('user_email_does_not_exist', $this->lang->line('user_email_does_not_exist'));
            return false;
        }
        return true;
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
     * basic function for server outpur request
     * @param string $activity type of recent activity
     */
    public function output($activity = '')
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
                        'deleted' => $this->deleted,
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
}