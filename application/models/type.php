<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Class Type
 * @author Vyacheslav Dolya <vyacheslav.dolya@gmail.com>
 * @package QR-BAR-CODE
 * @subpackage Models
 */
class Type extends CI_Model
{

    var $tableName = 'type';

    /**
     * Get Type By Description
     * @param string $typeId description of type
     * @return array result from database
     */
    public function getType($typeId)
    {
        return $this->db->get_where($this->tableName, array('type_id' => $typeId,))->row_array();
    }

    /**
     * Get Types
     * @return array result from database
     */
    public function get()
    {
        return $this->db->get($this->tableName)->result_array();
    }
}