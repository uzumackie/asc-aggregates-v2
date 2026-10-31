<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Masterfile_model extends CI_Model
{


    function __construct()
    {
        parent::__construct();

    }

    #region fetchBuFromFarmsMasterfile
    public function fetchBuFromFarmsMasterfile()
    {
        // Fetch business_units from the external API using the helper function
        $business_units = api_url($this->config->item('fetchBusinessunits'));

        // Check for errors
        if (isset($business_units['error'])) {
            return ['error' => $business_units['error']];
        }

        // Track changes
        $changes = [];

        foreach ($business_units as $bu) {
            // Map API fields to local table fields
            $data = array(
                'bu_id' => $bu['id'], // Mapping 'id' from API to 'bu_id' in the database
                'bu_code' => $bu['bu_code'],
                'bu_name' => $bu['bu_name'],
                'prefix' => $bu['prefix'],
                'si_prefix' => $bu['si_prefix'],
                'pi_raw_prefix' => $bu['pi_raw_prefix'],
                'pi_sup_prefix' => $bu['pi_sup_prefix'],
            );

            // Check if the record already exists in the business_units table for the same bu_id
            $existingBu = $this->db->where('bu_id', $data['bu_id'])
                ->get('business_units')
                ->row_array();

            if ($existingBu) {
                // Check for changes and update only if there are changes
                $isChanged = false;
                $changesDetail = [];

                foreach ($data as $key => $value) {
                    if ($existingBu[$key] != $value) {
                        $isChanged = true;
                        $changesDetail[] = $key . ': ' . $existingBu[$key] . ' -> ' . $value;
                    }
                }

                if ($isChanged) {
                    // Update existing record
                    $this->db->where('bu_id', $data['bu_id'])
                        ->update('business_units', $data);

                    // Add to changes array
                    $changes[$data['bu_id']] = $changesDetail;
                }
            } else {
                // Insert new record
                $this->db->insert('business_units', $data);
                $changes[$data['bu_id']] = ['New record added'];
            }
        }

        // Format the changes into a message with HTML line breaks
        if (!empty($changes)) {
            $changeMessages = [];
            foreach ($changes as $bu_id => $details) {
                $changeMessages[] = '*' . $bu_id . ' -> ' . implode(', ', $details);
            }
            $message = 'Business Unit Data updated successfully:<br>' . implode('<br>', $changeMessages);
            return ['success' => $message];
        } else {
            return ['info' => 'No updates available'];
        }

    }

    public function getTotalSuppliers()
    {
        return $this->db->count_all_results('supplier');
    }
    //-------------------------------------------//

    //-------------Reusable Functions---------------//
    #region  Reusable Functions
    public function getNameByField($table, $field, $value, $nameColumn)
    {
        // Validate the inputs
        if (empty($table) || empty($field) || empty($value) || empty($nameColumn)) {
            return ''; // Return empty if parameters are not valid
        }

        $this->db->select($nameColumn);
        $this->db->where($field, $value); // Using the provided field name

        // Add business unit filtering for suppliers if needed
        // if ($table === 'supplier') {
        //     $businessUnit = $this->session->userdata('business_unit');
        //     $this->db->where('sup_bu', $businessUnit);
        // }

        $query = $this->db->get($table);
        $result = $query->row_array();

        return $result ? $result[$nameColumn] : '';
    }

    public function checkDuplicateData($table, $columns)
    {
        buFiltered($this->db); // Apply custom filters via helper function

        foreach ($columns as $column => $value) {
            $this->db->where($column, $value);
        }

        $query = $this->db->get($table);
        return $query->num_rows() > 0;
    }

    public function getById($table, $target_id, $id)
    {
        $this->db->where($target_id, $id);
        $query = $this->db->get($table);
        return $query->row_array();
    }

    public function getTableDataWhereRowArray($table, $where)
    {
        $query = $this->db->get_where($table, $where);
        return $query->row_array();
    }

    public function getTableData($table)
    {
        $result = $this->db->SELECT('*')
            ->FROM($table)
            ->GET()
            ->result();
        return $result;
    }

    public function getFilteredData($table, $select = '*', $conditions = [], $order_by = null, $order = 'ASC')
    {
        buFiltered($this->db); // Apply custom filters via helper function

        $this->db->select($select)
            ->from($table);

        // Apply conditions if provided
        if (!empty($conditions)) {
            $this->db->where($conditions);
        }

        // Apply ordering if provided
        if ($order_by) {
            $this->db->order_by($order_by, $order);
        }

        // Execute the query and return results
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getTableDataByBu($table, $condition = NULL)
    {
        if ($condition !== NULL) {
            $this->db->where($condition);
        }
        $query = $this->db->get($table);
        return $query->result_array();
    }

    public function getOldData($table, $where, $id)
    {
        $query = $this->db->get_where($table, array($where => $id));
        return $query->row_array();
    }

    #region update
    public function update($id, $id_target, $table, $data)
    {
        // Validate parameters
        if (empty($id) || empty($id_target) || empty($table) || empty($data)) {
            return [
                'success' => false,
                'error' => 'Invalid parameters supplied for update()'
            ];
        }

        // Attempt update inside a try-catch-like block
        try {
            $this->db->where($id_target, $id);
            $result = $this->db->update($table, $data);

            // Check for DB-level errors (CI3 doesn’t throw exceptions)
            $db_error = $this->db->error();

            if ($db_error['code'] != 0) {
                // Rollback any transaction if active
                if ($this->db->trans_status() === FALSE) {
                    $this->db->trans_rollback();
                }

                return [
                    'success' => false,
                    'error' => $db_error['message'],
                    'code' => $db_error['code']
                ];
            }

            // Query executed but could return false if no rows affected
            if (!$result) {
                return [
                    'success' => false,
                    'error' => 'Query failed to execute (unknown error)'
                ];
            }

            return [
                'success' => true,
                'affected_rows' => $this->db->affected_rows()
            ];

        } catch (Exception $e) {
            // For completeness (though CI3 rarely throws exceptions)
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    #region insert
    public function insert($table, $data)
    {
        // Validate parameters
        if (empty($table) || empty($data)) {
            return [
                'success' => false,
                'error' => 'Invalid parameters supplied for insert()'
            ];
        }

        // Attempt insert inside a try-catch-like block
        try {
            $result = $this->db->insert($table, $data);

            // Check for DB-level errors (CI3 doesn't throw exceptions)
            $db_error = $this->db->error();

            if ($db_error['code'] != 0) {
                // Rollback any transaction if active
                if ($this->db->trans_status() === FALSE) {
                    $this->db->trans_rollback();
                }

                return [
                    'success' => false,
                    'error' => $db_error['message'],
                    'code' => $db_error['code']
                ];
            }

            // Query executed but could return false if no rows affected
            if (!$result) {
                return [
                    'success' => false,
                    'error' => 'Query failed to execute (unknown error)'
                ];
            }

            return [
                'success' => true,
                'insert_id' => $this->db->insert_id(),
                'affected_rows' => $this->db->affected_rows()
            ];

        } catch (Exception $e) {
            // For completeness (though CI3 rarely throws exceptions)
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    public function getData($table, $target_column, $data_column)
    {
        buFiltered($this->db);
        $this->db->select('*');
        $this->db->from($table);
        $this->db->where($target_column, $data_column);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->row_array();
        } else {
            return null;
        }
    }

    public function getDataByCode($table, $codeColumn, $codeValue)
    {
        $this->db->select('*');
        $this->db->from($table);
        $this->db->where($codeColumn, $codeValue);
        buFiltered($this->db);

        $query = $this->db->get();
        return $query->result_array();
    }

    #region getDynamicTableData
    public function getDynamicTableData($params = array())
    {
        $result = array();

        try {
            if (empty($params['from'])) {
                return array('data' => [], 'totalCount' => 0);
            }

            // --- SELECT ---
            $this->db->select(isset($params['select']) ? $params['select'] : '*');

            // --- DISTINCT ---
            if (isset($params['distinct']) && $params['distinct'] === true) {
                $this->db->distinct();
            }

            // --- FROM ---
            $this->db->from($params['from']);

            // --- JOIN ---
            if (!empty($params['joins'])) {
                foreach ($params['joins'] as $join) {
                    $this->db->join($join['table'], $join['on'], isset($join['type']) ? $join['type'] : 'inner');
                }
            }

            // --- WHERE / ORDER BY ---
            if (!empty($params['condition'])) {
                if (stripos($params['condition'], 'ORDER BY') !== false) {
                    list($wherePart, $orderByPart) = explode('ORDER BY', $params['condition'], 2);
                    if (trim($wherePart) !== '') {
                        $this->db->where('(' . trim($wherePart) . ')');
                    }
                    $this->db->order_by(trim($orderByPart));
                } else {
                    $this->db->where('(' . trim($params['condition']) . ')');
                }
            }

            // --- ORDER BY (from separate parameter) ---
            if (!empty($params['order_by'])) {
                $this->db->order_by($params['order_by']);
            }

            // --- GROUP BY ---
            if (!empty($params['group_by'])) {
                $this->db->group_by($params['group_by']);
            }

            // --- HAVING ---
            if (!empty($params['having'])) {
                $this->db->having($params['having']);
            }

            // --- SEARCH (LIKE) ---
            if (!empty($params['searchText']) && !empty($params['searchColumns'])) {
                $this->db->group_start();
                foreach ($params['searchColumns'] as $column) {
                    $this->db->or_like($column, $params['searchText']);
                }
                $this->db->group_end();
            }

            // --- PAGINATION (LIMIT) ---
            if (isset($params['pageSize']) && $params['pageSize'] > 0) {
                $page = isset($params['page']) ? intval($params['page']) : 0;
                $this->db->limit($params['pageSize'], $page * $params['pageSize']);
            }

            $query = $this->db->get();
            if (!$query) {
                $db_error = $this->db->error();
                return array(
                    'data' => [],
                    'totalCount' => 0,
                    'db_error' => isset($db_error['message']) ? $db_error['message'] : 'Query failed or aborted.'
                );
            }
            $result['data'] = $query->result_array();

            // --- totalCount ---
            $this->db->reset_query();
            $this->db->from($params['from']);

            if (!empty($params['joins'])) {
                foreach ($params['joins'] as $join) {
                    $this->db->join($join['table'], $join['on'], isset($join['type']) ? $join['type'] : 'inner');
                }
            }

            if (!empty($params['condition'])) {
                if (stripos($params['condition'], 'ORDER BY') !== false) {
                    list($wherePart, ) = explode('ORDER BY', $params['condition'], 2);
                    if (trim($wherePart) !== '') {
                        $this->db->where('(' . trim($wherePart) . ')');
                    }
                } else {
                    $this->db->where('(' . trim($params['condition']) . ')');
                }
            }

            if (!empty($params['group_by'])) {
                $this->db->group_by($params['group_by']);
            }

            if (!empty($params['having'])) {
                $this->db->having($params['having']);
            }

            if (!empty($params['searchText']) && !empty($params['searchColumns'])) {
                $this->db->group_start();
                foreach ($params['searchColumns'] as $column) {
                    $this->db->or_like($column, $params['searchText']);
                }
                $this->db->group_end();
            }

            $result['totalCount'] = $this->db->count_all_results();

            return $result;
        } catch (Exception $e) {
            // ✅ Catch unexpected PHP or DB exceptions
            return array(
                'data' => [],
                'totalCount' => 0,
                'db_error' => 'Unexpected error: ' . $e->getMessage()
            );
        }
    }

    #region getTableDataAndTotalCountByBu
    //Michael
    public function getTableDataAndTotalCountByBu($table, $condition = NULL, $page = 0, $pageSize = 10, $searchText = NULL, $searchColumns = array())
    {
        $result = array();

        // Apply condition to the query
        if ($condition !== NULL) {
            if (stripos($condition, 'ORDER BY') !== false) {
                list($whereClause, $orderByClause) = explode('ORDER BY', $condition, 2);
                $this->db->where(trim($whereClause));
                $this->db->order_by(trim($orderByClause));
            } else {
                $this->db->where($condition);
            }
        }

        // If there's a search text, apply it to the specified columns
        if ($searchText !== NULL && !empty($searchColumns)) {
            $this->db->group_start();
            foreach ($searchColumns as $column) {
                $this->db->or_like($column, $searchText, 'both', '!', true);
            }
            $this->db->group_end();
        }

        // Apply pagination limits
        $this->db->limit($pageSize, $page * $pageSize);
        $query = $this->db->get($table);
        $result['data'] = $query->result_array();

        // Reset the query to count results, applying the same conditions as before
        $this->db->reset_query();

        // Re-apply the condition and search filter for counting total results
        if ($condition !== NULL) {
            if (stripos($condition, 'ORDER BY') !== false) {
                list($whereClause, ) = explode('ORDER BY', $condition, 2);
                $this->db->where(trim($whereClause));
            } else {
                $this->db->where($condition);
            }
        }

        // If there's a search text, apply it again for the count query
        if ($searchText !== NULL && !empty($searchColumns)) {
            $this->db->group_start();
            foreach ($searchColumns as $column) {
                $this->db->or_like($column, $searchText, 'both', '!', true);
            }
            $this->db->group_end();
        }

        // Get the total count of the filtered results
        $result['totalCount'] = $this->db->count_all_results($table);

        return $result;
    }

    #region fetchDataWithJoins
    public function fetchDataWithJoins($table, $columns, $joins = array(), $start, $length, $searchValue, $orderColumn, $orderDir, $filters = array())
    {
        try {
            // Start query
            $this->db->select(implode(', ', $columns))
                ->from($table);

            // Apply joins
            foreach ($joins as $join) {
                $this->db->join($join['table'], $join['condition'], $join['type']);
            }

            // Apply filters
            if (!empty($filters)) {
                foreach ($filters as $field => $value) {
                    $this->db->where($field, $value);
                }
            }

            // Apply search
            if (!empty($searchValue)) {
                $this->db->group_start();
                foreach ($columns as $column) {
                    $col = strpos($column, ' as ') !== false ? substr($column, 0, strpos($column, ' as ')) : $column;
                    $this->db->or_where("IF($col IS NULL OR $col = '', 'NO SETUP', $col) LIKE", '%' . $searchValue . '%');
                }
                $this->db->group_end();
            }

            // ✅ Apply ordering safely
            if (!empty($orderColumn) && !empty($orderDir)) {
                $this->db->order_by($orderColumn, $orderDir);
            }

            // Apply pagination
            $this->db->limit($length, $start);
            $query = $this->db->get();

            // ✅ Prevent crash if query fails
            if (!$query) {
                $db_error = $this->db->error();
                return [
                    'totalRecords' => 0,
                    'totalFiltered' => 0,
                    'data' => [],
                    'error' => 'Query failed or aborted during reload.',
                    'db_error' => isset($db_error['message']) ? $db_error['message'] : 'Unknown DB error',
                    'last_query' => $this->db->last_query()
                ];
            }

            $filteredData = $query->result_array();

            // Filtered count
            $this->db->reset_query();
            $this->db->from($table);
            foreach ($joins as $join) {
                $this->db->join($join['table'], $join['condition'], $join['type']);
            }
            if (!empty($filters)) {
                foreach ($filters as $field => $value) {
                    $this->db->where($field, $value);
                }
            }
            if (!empty($searchValue)) {
                $this->db->group_start();
                foreach ($columns as $column) {
                    $col = strpos($column, ' as ') !== false ? substr($column, 0, strpos($column, ' as ')) : $column;
                    $this->db->or_where("IF($col IS NULL OR $col = '', 'NO SETUP', $col) LIKE", '%' . $searchValue . '%');
                }
                $this->db->group_end();
            }
            $totalFiltered = $this->db->count_all_results();

            // Total count
            $this->db->reset_query();
            $this->db->from($table);
            foreach ($joins as $join) {
                $this->db->join($join['table'], $join['condition'], $join['type']);
            }
            if (!empty($filters)) {
                foreach ($filters as $field => $value) {
                    $this->db->where($field, $value);
                }
            }
            $totalRecords = $this->db->count_all_results();

            return [
                'totalRecords' => $totalRecords,
                'totalFiltered' => $totalFiltered,
                'data' => $filteredData
            ];
        } catch (Exception $e) {
            return [
                'totalRecords' => 0,
                'totalFiltered' => 0,
                'data' => [],
                'error' => 'An unexpected error occurred.' . $e->getMessage()
            ];
        }
    }

    //-------------------------------------------//


    public function getPackingById($item_id)
    {
        // Assuming you have a "suppliers" table in your database
        $query = $this->db->get_where('packing', array('item_id' => $item_id));

        // Check if the query was successful
        if ($query->num_rows() > 0) {
            // Return the first row of the result as an object
            return $query->row();
        } else {
            // Return false if no supplier with that ID was found
            return false;
        }
    }

    public function getPriceGroup()
    {
        $query = $this->db->select('id, prc_code, prc_name')
            ->where('status', 1)
            ->order_by('id', 'ASC')
            ->get('price_group');
        return $query->result_array();
    }

    public function getCategory()
    {
        $query = $this->db->select('*')
            ->where('status', 1)
            ->order_by('cat_id', 'ASC')
            ->get('category');
        return $query->result_array();
    }

    public function getLocation()
    {
        $query = $this->db->select('loc_code, loc_name, loc_main')
            ->order_by('loc_code', 'ASC')
            ->where('status', 1)
            ->get('location');
        return $query->result_array();
    }

    public function getSupplierInternal()
    {
        $query = $this->db->select('sup_id, sup_code, sup_name, sup_type')
            // ->where('status', 1)
            ->where('sup_type', 'INTERNAL')
            ->where('status', 1)
            ->order_by('sup_id', 'ASC')
            ->get('supplier');
        return $query->result_array();
    }

    public function getCustomer()
    {
        $query = $this->db->select('cus_code, cus_name, cus_pricegroup')
            ->order_by('cus_code', 'ASC')
            ->get('customer');
        return $query->result_array();
    }

    public function getSalesman()
    {
        $query = $this->db->select('sm_code, sm_name')
            ->order_by('sm_code', 'ASC')
            ->get('salesman');
        return $query->result_array();
    }

    public function getDriver()
    {
        $query = $this->db->select('drv_code, drv_name')
            ->order_by('drv_code', 'ASC')
            ->get('drivers');
        return $query->result_array();
    }

    public function getTruck()
    {
        $query = $this->db->select('trk_code, trk_name')
            ->order_by('trk_code', 'ASC')
            ->get('truckmst');
        return $query->result_array();
    }

    public function getConvoy()
    {
        $query = $this->db->select('cnv_code, cnv_name')
            ->order_by('cnv_code', 'ASC')
            ->get('convoymast');
        return $query->result_array();
    }

    public function getBuilding()
    {
        $query = $this->db->select('bld_code, bld_name')
            ->order_by('bld_code', 'ASC')
            ->get('building');
        return $query->result_array();
    }
}