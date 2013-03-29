<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
require_once 'authentificate.php';

/**
 * Class Signin
 * @author Vyacheslav Dolya <vyacheslav.dolya@gmail.com>
 * @package QR-BAR-CODE
 * @subpackage Controller
 * @link signin
 */
class Signin extends Authentificate
{

    public $user_first_name = '';
    public $user_last_name = '';
    public $activity = 'sign';

    public function __construct()
    {
        parent::__construct();
        $this->validateUser();
    }

    /**
     * Basic function for Signin User
     */
    public function index()
    {
        $this->load->model('users');
        $this->lang->load('validation');

        if ($this->is_facebook_user)
        {
            $facebook_new_token = $this->input->post('facebook_new_token');
            if (!empty($facebook_new_token))
            {
                if ($this->users->findByAttributes(array(
                            'facebook_user_token' => $facebook_new_token,
                        )))
                {
                    $this->setError(501, $this->lang->line('new_token_exist'));
                }
                else
                {
                    if ($this->users->updateUser(array(
                                'facebook_user_id' => $this->facebook_user_id,
                                'facebook_user_token' => $facebook_new_token,
                            )))
                    {
                        $this->setError(501, $this->lang->line('new_token_update_failed'));
                    }
                    else
                    {
                        $this->user['facebook_user_token'] = $facebook_new_token;
                        $this->setParam('user', $this->user);
                    }
                }
            }
            else
            {
                $this->setParam('user', $this->user);
            }
        }
        else
        {
            $this->setParam('user', $this->user);
        }
        $this->output($this->activity);
    }

}