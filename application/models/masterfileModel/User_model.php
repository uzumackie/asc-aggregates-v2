<?php
defined('BASEPATH') or exit('No direct script access allowed');

class User_model extends CI_Model
{

    function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
    }

    public function getUsers()
    {
        $result = $this->db->SELECT('*')
            ->FROM('users')
            ->ORDER_BY('user_id', 'DESC')
            ->GET()
            ->RESULT_ARRAY();
        return $result;
    }

    #region duplicate
    public function duplicate($username, $user_id = null)
    {
        $this->db->select('*')
            ->from('users')
            ->where('username', $username);

        if ($user_id !== null) {
            // Exclude current user from duplicate check
            $this->db->where('user_id !=', $user_id);
        }

        $result = $this->db->get()->row();
        return $result;
    }

    public function getUserById($userid)
    {
        $query = $this->db->get_where('users', array('user_id' => $userid));
        return $query->row();
    }

    public function update($table, $data, $where)
    {
        $this->db->where($where)
            ->update($table, $data);
        return $this->db->affected_rows();
    }

    #region searchEmployee
    public function searchEmployee($str)
    {
        $api_url = 'http://172.16.161.34/api/farms/filter/employee/name?q=' . urlencode($str);
        $response = @file_get_contents($api_url); // Suppress warnings from file_get_contents using @

        if ($response === FALSE) {
            return ['status' => 'error', 'message' => 'Unable to reach the API'];
        }

        $data = json_decode($response, true);

        if (isset($data['data']['employee']) && !empty($data['data']['employee'])) {
            return ['status' => 'success', 'data' => $data['data']['employee']];
        } else {
            return ['status' => 'empty', 'message' => 'No results found'];
        }
    }

    public function checkUserExistence($empId)
    {
        $query = $this->db->get_where('users', array('emp_id' => $empId));
        return $query->num_rows() > 0;
    }

    #region clearDateLimitsIfNotToday
    public function clearDateLimitsIfNotToday()
    {
        // Get today's date
        $today = date('Y-m-d');

        // Get all records where created_at is not today's date
        $this->db->where('DATE(updated_at) !=', $today);
        $this->db->where('(back_date_limit > 0 OR advance_date_limit > 0)');

        $data = array(
            'back_date_limit' => 0,
            'advance_date_limit' => 0
        );

        $this->db->update('user_roles_default', $data);

        return $this->db->affected_rows() > 0;
    }

    #region getBuAndRoles
    public function getBuAndRoles()
    {
        $userId = $this->session->userdata('user_id');

        $this->db->select('users.business_unit, user_roles.role_code, user_roles.user_id, user_roles.role_description, user_roles.allow_view, user_roles.allow_add, user_roles.allow_update, user_roles.allow_delete, user_roles.allow_reprint, user_roles.mod_type');
        $this->db->from('users');
        $this->db->join('user_roles', 'users.user_id = user_roles.user_id', 'left');
        $this->db->where('users.user_id', $userId);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            $userData = $query->result_array();
            // $this->session->set_userdata('business_unit', $userData[0]['business_unit']);
            return $userData;
        }

        return [];
    }

    // public function getBuAndRoles()
    // {
    //     $userId = $this->session->userdata('user_id');

    //     $this->db->select('users.business_unit, user_roles.role_code, users.user_id, user_roles.role_description, user_roles.allow_view, user_roles.allow_add, user_roles.allow_update, user_roles.allow_delete, user_roles.allow_reprint, user_roles.mod_type');
    //     $this->db->from('users');
    //     $this->db->join('user_roles', 'users.user_id = user_roles.user_id', 'left');
    //     $this->db->where('users.user_id', $userId);
    //     $query = $this->db->get();

    //     return $query->result_array(); // business_unit is already in result
    // }

    public function getUserRolesData($userId)
    {
        $this->db->select('*');
        $this->db->where('user_id', $userId);
        $query = $this->db->get('user_roles');
        return $query->result_array();
    }

    public function getUserRolesDefaultData($userId, $userType)
    {
        $this->db->select('*');
        $this->db->where('user_id', $userId);
        $this->db->where('user_type', $userType);
        $query = $this->db->get('user_roles_default');
        return $query->result_array();
    }

    #region resetUserRolesToDefault
    // public function resetUserRolesToDefault($user_id, $user_type)
    // {
    //     // Get default roles
    //     $default_roles = $this->db->get_where('user_roles_default', ['user_type' => $user_type])->result_array();

    //     // ✅ Validation: no default roles found
    //     if (empty($default_roles)) {
    //         JSONResponse([
    //             'message' => 'No default roles found for user type "' . $user_type . '". Please set up defaults first.',
    //             'info' => 'Warning'
    //         ]);
    //         return;
    //     }

    //     $this->db->trans_start();

    //     foreach ($default_roles as $default) {
    //         $existing = $this->db->get_where('user_roles', [
    //             'user_id' => $user_id,
    //             'role_code' => $default['role_code']
    //         ])->row_array();

    //         $update_data = [
    //             'role_description' => $default['role_description'],
    //             'allow_view' => $default['allow_view'],
    //             'allow_add' => $default['allow_add'],
    //             'allow_update' => $default['allow_update'],
    //             'allow_delete' => $default['allow_delete'],
    //             'allow_reprint' => $default['allow_reprint'],
    //             'group_type' => $default['group_type'],
    //             'mod_type' => $default['mod_type'],
    //         ];

    //         if ($existing) {
    //             $this->db->where('user_id', $user_id);
    //             $this->db->where('role_code', $default['role_code']);
    //             $this->db->update('user_roles', $update_data);
    //         } else {
    //             $update_data['user_id'] = $user_id;
    //             $update_data['role_code'] = $default['role_code'];
    //             $this->db->insert('user_roles', $update_data);
    //         }
    //     }

    //     $this->db->trans_complete();

    //     if ($this->db->trans_status() === FALSE) {
    //         $this->db->trans_rollback();
    //         JSONResponse([
    //             'message' => 'Failed to reset roles.',
    //             'info' => 'Error'
    //         ]);
    //     } else {
    //         JSONResponse([
    //             'message' => 'User roles successfully updated with default values.',
    //             'info' => 'Success'
    //         ]);
    //     }
    // }
    public function resetUserRolesToDefault($user_id, $user_type)
    {
        // Get default roles
        $default_roles = $this->db->get_where('user_roles_default', ['user_type' => $user_type])->result_array();

        if (empty($default_roles)) {
            return [
                'message' => 'No default roles found for user type "' . $user_type . '". Please set up defaults first.',
                'info' => 'Warning'
            ];
        }

        $this->db->trans_start();

        // Delete all existing roles for this user
        $this->db->delete('user_roles', ['user_id' => $user_id]);

        // Insert default roles
        foreach ($default_roles as $default) {
            $insert_data = [
                'user_id' => $user_id,
                'role_code' => $default['role_code'],
                'role_description' => $default['role_description'],
                'allow_view' => $default['allow_view'],
                'allow_add' => $default['allow_add'],
                'allow_update' => $default['allow_update'],
                'allow_delete' => $default['allow_delete'],
                'allow_reprint' => $default['allow_reprint'],
                'group_type' => $default['group_type'],
                'mod_type' => $default['mod_type'],
            ];

            $this->db->insert('user_roles', $insert_data);
        }

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return [
                'message' => 'Failed to reset roles.',
                'info' => 'Error'
            ];
        } else {
            return [
                'message' => 'User roles successfully reset to default.',
                'info' => 'Success'
            ];
        }
    }

    #region getUserData
    public function getUserData($user_id)
    {
        $this->db->where('user_id', $user_id);
        $query = $this->db->get('users');
        return $query->row_array();
    }


}
