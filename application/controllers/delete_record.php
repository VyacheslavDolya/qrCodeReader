<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
require_once 'authentificate.php';

/**
 * Class Delete Record
 * @author Vyacheslav Dolya <vyacheslav.dolya@gmail.com>
 * @package QR-BAR-CODE
 * @subpackage Controller
 * @link delete_record
 */
class Delete_record extends Authentificate
{

    public $activity = 'delete';

    public function __construct()
    {
        parent::__construct();
        $this->validateUser();
    }

    /**
     * Basic function for Deleting Records
     */
    public function index()
    {
        $this->lang->load('validation');
        $delete = $this->input->post('delete');
        if (empty($delete))
        {
            $this->setError(501, $this->lang->line('delete_is_required'));
        }
        else
        {
            $this->load->library('validation');
            if (is_array($delete))
            {
                foreach ($delete as $del)
                {
                    if ($this->validation->is_natural($del))
                    {
                        $remove[] = $del;
                    }
                }
            }
            else
            {
                if ($this->validation->is_natural($del))
                {
                    $remove[] = $delete;
                }
            }
            if (empty($remove))
            {
                $this->setError(501, $this->lang->line('delete_values_is_not_valid'));
            }
            else
            {
                $this->load->model('deleted', 'deleted_model');
                $this->deleted_model->remove($this->user['user_id'], $remove);
            }
        }
        $this->output($this->activity);
    }

}