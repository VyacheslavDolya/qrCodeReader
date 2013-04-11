<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
require_once 'authentificate.php';

/**
 * Class Retrieve Code Types
 * @author Vyacheslav Dolya <vyacheslav.dolya@gmail.com>
 * @package QR-BAR-CODE
 * @subpackage Controller
 * @link retrieve_codetypes
 */
class Retrieve_codetypes extends Authentificate
{

    public $activity = 'types';

    /**
     * Basic function for Adding New Record
     */
    public function index()
    {
        $this->load->model('type');
        $this->setParam($this->activity, $this->type->get());
        $this->output($this->activity);
    }
}