<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Class History
 * @author Vyacheslav Dolya <vyacheslav.dolya@gmail.com>
 * @package QR-BAR-CODE
 * @subpackage Models
 */
class History extends CI_Model
{

    var $tableName = 'history';

    /**
     * Add Record
     * @param array $data record data
     * @return array record data or empty array
     */
    public function addRecord($data)
    {
        if ($this->db->insert($this->tableName, $data))
        {
            $data['record_id'] = $this->db->insert_id();
            return $data;
        }
        else
        {
            return array();
        }
    }

    /**
     * Get
     * @param integer $userId
     * @param integer $count count of selected rows
     * @param integer $offset offset
     * @param integer $updated_gt date in unix timestamp
     * @return array
     */
    public function get($userId, $count, $offset, $updated_gt)
    {
        $conditions = array('user_id' => $userId);
        if (!empty($updated_gt))
        {
            $conditions['date > '] = $updated_gt;
        }
        return $this->db->get_where($this->tableName, $conditions, $count, $offset)->result_array();
    }

    /**
     * Get Count
     * @param integer $userId
     * @param integer $offset offset
     * @param integer $updated_gt date in unix timestamp
     * @return integer num rows
     */
    public function getCount($userId, $offset, $updated_gt)
    {
        $conditions = array('user_id' => $userId);
        if (!empty($updated_gt))
        {
            $conditions['date > '] = $updated_gt;
        }
        $query = $this->db->get_where($this->tableName, $conditions, null, $offset);
        return $query->num_rows();
    }

}