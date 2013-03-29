<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Class Users
 * @author Vyacheslav Dolya <vyacheslav.dolya@gmail.com>
 * @package QR-BAR-CODE
 * @subpackage Models
 */
class Users extends CI_Model
{

    var $tableName = 'users';

    /**
     * callback function for checking Email Existings in database
     * @param string $email user email address
     * @return array result from database
     */
    public function checkEmailExistings($email)
    {
        return $this->db->get_where($this->tableName, array(
                            'user_email' => $email,
                                )
                        )
                        ->row_array();
    }

    /**
     * callback function for checking Facebook User Id Existings in database
     * @param string $facebook_user_id facebook user id
     * @return array result from database
     */
    public function checkFacebookUserIdExistings($facebook_user_id)
    {
        return $this->db->get_where($this->tableName, array(
                            'facebook_user_id' => $facebook_user_id,
                                )
                        )
                        ->row_array();
    }

    /**
     * Register a new user
     * @param array $data user data
     * @return array user data or empty array
     */
    public function register($data)
    {
        if ($this->db->insert($this->tableName, $data))
        {
            $data['user_id'] = $this->db->insert_id();
            return $data;
        }
        else
        {
            return array();
        }
    }

    /**
     * Find Record By Attributes
     * @param array $attributes attributes
     * @return array
     */
    public function findByAttributes($attributes)
    {
        return $this->db->get_where($this->tableName, $attributes)->row_array();
    }

    /**
     * Update User Data
     * @param array $data user data
     * @return boolean
     */
    public function updateUser($data)
    {
        return $this->db->update($this->tableName, $data);
    }

}