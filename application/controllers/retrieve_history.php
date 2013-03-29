<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
require_once 'authentificate.php';

/**
 * Class Retrieve History
 * @author Vyacheslav Dolya <vyacheslav.dolya@gmail.com>
 * @package QR-BAR-CODE
 * @subpackage Controller
 * @link retrieve_history
 */
class Retrieve_history extends Authentificate
{

    public $activity = 'history';
    public $count = 5;

    public function __construct()
    {
        parent::__construct();
        $this->validateUser();
    }

    /**
     * Basic function for Retrieving History
     */
    public function index()
    {
        $this->load->library('validation');
        $offset = $this->validation->is_natural($this->input->post('offset')) ? $this->input->post('offset') : 0;
        $count = $this->validation->is_natural($this->input->post('count')) ? $this->input->post('count') : 5;
        $updated_gt = $this->validation->is_natural($this->input->post('updated_gt')) ? $this->input->post('updated_gt') : 0;
        $this->load->model('history', 'history_model');
        $history = $this->history_model->get($this->user['user_id'], $count, $offset, $updated_gt);
        if (!empty($history))
        {
            $lastResult = end($history);
            $lastFounded_gt = $lastResult['date'];
            $this->_generateMeta($this->user['user_id'], $count, $offset, $updated_gt, $lastFounded_gt);
            $this->setParam('history', $history);
        }
        $this->_getDeleted();
        $this->output($this->activity);
    }

    /**
     * function for getting deleted records for current user
     */
    private function _getDeleted()
    {
        $this->load->model('deleted', 'deleted_model');
        $deleted = $this->deleted_model->getRecords($this->user['user_id']);
        if (!empty($deleted))
        {
            $this->setParam('deleted', $deleted);
        }
    }

    /**
     * Generate Meta
     * @param intereg $user_id
     * @param integer $limit
     * @param integer $offset
     * @param integer $updated_gt date merging
     * @param integer $lastFounded_gt last founded date for setting new merging checkpoint
     */
    private function _generateMeta($user_id, $limit, $offset, $updated_gt, $lastFounded_gt)
    {
        $this->load->model('history', 'history_model');
        $countRows = $this->history_model->getCount($user_id, $offset, $updated_gt);
        $left = $countRows - ($limit + $offset);
        if ($left > 0)
        {
            $this->setParam('meta', array(
                'offset' => $limit + $offset,
                'count' => $limit,
                'updated_gt' => $lastFounded_gt,
            ));
        }
    }

}