<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Class Deleted
 * @author Vyacheslav Dolya <vyacheslav.dolya@gmail.com>
 * @package QR-BAR-CODE
 * @subpackage Models
 */
class Deleted extends CI_Model
{

    var $tableName = 'deleted';
    var $tableNameRelated = 'history';

    /**
     * function for removing records from database
     * @param integer $userId
     * @param array $remove array with id's to remove
     */
    public function remove($userId, $remove)
    {
        foreach ($remove as $id)
        {
            $this->db->trans_start();
            $this->db->delete($this->tableNameRelated, array(
                'record_id' => $id,
                'user_id' => $userId,
                    )
            );
            if ($this->db->affected_rows())
            {
                $this->db->insert($this->tableName, array(
                    'record_id' => $id,
                    'user_id' => $userId,
                ));
            }
            $this->db->trans_complete();
        }
    }

    /**
     * Get Deleted Records for user
     * @param integer $userId
     * @return array deleted id's
     */
    public function getRecords($userId)
    {
        $deleted = $this->db->get_where($this->tableName, array(
                    'user_id' => $userId,
                ))->result_array();
        if (!empty($deleted))
        {
            foreach ($deleted as $row)
            {
                $flush[] = $row['record_id'];
            }
            return $flush;
        }
        else
        {
            return array();
        }
    }

}