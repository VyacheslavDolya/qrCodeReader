<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
require_once 'controller.php';
/**
 * Class Forgot Password
 * @author Vyacheslav Dolya <vyacheslav.dolya@gmail.com>
 * @package QR-BAR-CODE
 * @subpackage Controller
 */
class Forgot_Password extends Controller
{

    public function __construct()
    {
        parent::__construct();
//        $_POST = array(
//        'user_email' => 'james@co.uk',
//        );
    }

    public function index()
    {
        $this->load->library('validation');
        $this->validation->set_rules('user_email', 'User Email', 'required|valid_email|callback_user_email_does_not_exist');
        if ($this->validation->run() == FALSE)
        {
            $this->setError($this->code, $this->validation->error_string());
            $this->output();
        }
        else
        {
            $this->load->model('users');
            $email = $this->input->post('user_email',true);
            $find = $this->users->checkEmailExistings($email);
            $message = "<p>You're receiving this e-mail because you requested a password for your user account at QRCodeReader app..</p>";
            $message .= "<p>Your login is:".$email."</p>";
            $message .= "<p>Your password is:".$find['user_password']."</p>";
            $message .= "<p>Thanks for using our app.</p>";
            $message .= "<center>The Spire team</center>";
            if($this->_sendNotification($email,$message))
            {
                $this->output();
            }
            else
            {
                $this->lang->load('validation');
                $this->setError($this->code, $this->lang->line('oops'));
            }
        }
    }

    /**
     * Send Notification
     */
    private function _sendNotification($emailAddress,$message = '')
    {
        $brandInfo = $this->config->item('brandInfo');
        $this->load->library('email');
        $this->email->from($brandInfo['email'], $brandInfo['name']);
        $this->email->to($emailAddress);
        $this->email->subject('Email Test');
        $this->email->message($message);
        //$this->email->print_debugger();
        return $this->email->send();
    }
}