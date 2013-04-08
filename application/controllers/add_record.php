<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
require_once 'authentificate.php';

/**
 * Class Add Record
 * @author Vyacheslav Dolya <vyacheslav.dolya@gmail.com>
 * @package QR-BAR-CODE
 * @subpackage Controller
 * @link add_record
 */
class Add_record extends Authentificate
{

    public $activity = 'add';
    public $recordType = '';

    public function __construct()
    {
        parent::__construct();
        $this->validateUser();
    }

    /**
     * Basic function for Adding New Record
     */
    public function index()
    {
        $_POST += array(
            'date' => '234356576',
            'latitude' => '3244432432',
            'longitude' => '34534543',
            'code' => 'sdfdbdfg43try',
            'type' => '1',
        );
        $this->lang->load('validation');
        $this->load->library('validation');
        $this->validation->set_rules('date', 'Date', 'required|numeric');
        $this->validation->set_rules('latitude', 'Latitude', 'required|numeric');
        $this->validation->set_rules('longitude', 'Longitude', 'required|numeric');
        $this->validation->set_rules('code', 'Code', 'required|max_length[1024]');
        $this->validation->set_rules('type', 'Type', 'required|callback_check_record_type');

        if ($this->validation->run() == FALSE)
        {
            $this->setError(500, $this->validation->error_string());
            $this->output($this->activity);
            die();
        }
        else
        {
            $inputType = $this->input->post('type');
            $insert = array(
                'date' => $this->input->post('date'),
                'latitude' => $this->input->post('latitude'),
                'longitude' => $this->input->post('longitude'),
                'code' => $this->input->post('code'),
                'type_id' => $this->recordType,
                'user_id' => $this->user['user_id'],
            );

            $this->load->model('history','history_model');
            $result = $this->history_model->addRecord($insert);
            if (!empty($result))
            {
                unset($insert['user_id']);
                unset($insert['type_id']);
                $insert['type'] = array(
                    'type_id' => $this->recordType,
                );
                $insert['record_id'] = $result['record_id'];
                $insert['user'] = $this->user;
                $this->setParam('record', $insert);
            }
            else
            {
                $this->setError(501, $this->lang->line('add_record_failed'));
            }
        }

        $this->output($this->activity);
    }

    /**
     * Function for chaking correct record type
     * @param string $type record type
     * @return boolean
     */
    public function check_record_type($type)
    {
        $this->load->model('type');
        $find = $this->type->getType($type);
        if (empty($find))
        {
            $this->validation->set_message('check_record_type', $this->lang->line('type_does_not_exist'));
            return false;
        }
        else
        {
            $this->recordType = $find['type_id'];
        }
        return true;
    }

}