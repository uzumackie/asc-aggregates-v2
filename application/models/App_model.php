<?php
defined('BASEPATH') or exit('No direct script access allowed');

class App_model extends CI_model
{

    function __construct()
    {
        parent::__construct();
        // $this->load->helper('url');
    }

    function sanitize($string)
    {
        $string = htmlentities($string, ENT_QUOTES, 'UTF-8');
        $string = trim($string);
        return $string;
    }

    public function getUsers($username)
    {
        return $this->db->select('*')
            ->from('users')
            ->where('username', $username)
            ->get()
            ->row();
    }

    public function getAuthenticate($user, $pass)
    {
        $result = $this->db->SELECT('*')
            ->FROM('users')
            ->WHERE('username', $user)
            ->WHERE('password', $pass)
            ->WHERE('status !=', 'Deactivated')
            ->GET()
            ->ROW();

        return $result;
    }

    public function getUserById($userId)
    {
        $this->db->select('username, password');
        $this->db->from('users');
        $this->db->where('user_id', $userId);
        $query = $this->db->get();

        return $query->row();
    }

    #region getUserByEmpId
    public function getUserByEmpId($empId)
    {
        $this->db->select('username, password');
        $this->db->from('users');
        $this->db->where('emp_id', $empId);
        $query = $this->db->get();

        return $query->row();
    }

    #region count_filtered_records
    public function count_filtered_records($table, $business_unit_column)
    {
        $business_unit = $this->session->userdata('business_unit');

        if (!$business_unit) {
            return 0; // fallback if session not set
        }

        $this->db->where($business_unit_column, $business_unit);
        return $this->db->count_all_results($table);
    }

    #region getPurchaseOrderStatusCounts
    public function getPurchaseOrderStatusCounts($filter)
    {
        $business_unit = $this->session->userdata('business_unit');
        // Decide which tables to query
        if ($filter === 'Supplies') {
            $tables = [
                'supplies_replenishment_order_header',
                'supplies_requisition_order_header'
            ];
        } elseif ($filter === 'Rawmats') {
            $tables = [
                'rawmats_replenishment_order_header',
                'rawmats_purchase_order_header'
            ];
        } else { // All
            $tables = [
                'supplies_replenishment_order_header',
                'supplies_requisition_order_header',
                'rawmats_replenishment_order_header',
                'rawmats_purchase_order_header'
            ];
        }

        $statusCounts = [];

        foreach ($tables as $table) {
            // Skip table if it does not exist
            if (!$this->db->table_exists($table)) {
                continue; // skip this table
            }

            $this->db->select('header_status, COUNT(*) as total');
            $this->db->from($table);
            $this->db->group_by('header_status');
            $query = $this->db->get();

            foreach ($query->result() as $row) {
                // Convert Fully Posted → Closed
                $status = ($row->header_status === 'Fully Posted') ? 'Closed' : $row->header_status;

                if (!isset($statusCounts[$status])) {
                    $statusCounts[$status] = 0;
                }
                $statusCounts[$status] += (int) $row->total;
            }
        }

        // Return as array for controller
        $result = [];
        foreach ($statusCounts as $status => $count) {
            $result[] = [
                'header_status' => $status,
                'total' => $count
            ];
        }

        return $result;
    }

    #region getSalesOrderStatusCounts
    public function getSalesOrderStatusCounts()
    {
        $statusCounts = [];

        $this->db->select('header_status, COUNT(*) as total');
        $this->db->from('sales_order_header');
        $this->db->group_by('header_status');
        $query = $this->db->get();

        foreach ($query->result() as $row) {
            $status = $row->header_status;

            if (!isset($statusCounts[$status])) {
                $statusCounts[$status] = 0;
            }
            $statusCounts[$status] += (int) $row->total;
        }

        $result = [];
        foreach ($statusCounts as $status => $count) {
            $result[] = [
                'header_status' => $status,
                'total' => $count
            ];
        }

        return $result;
    }

    //=====================================================================================
    #region getPurchaseOrderStatusMonthlyCounts
    public function getPurchaseOrderStatusMonthlyCounts($filter, $year, $monthRange)
    {
        $currentYear = (int) date('Y');

        // Decide which tables to query
        if ($filter === 'Supplies') {
            $tables = [
                'supplies_replenishment_order_header',
                'supplies_requisition_order_header'
            ];
        } elseif ($filter === 'Rawmats') {
            $tables = [
                'rawmats_replenishment_order_header',
                'rawmats_purchase_order_header'
            ];
        } else { // All
            $tables = [
                'supplies_replenishment_order_header',
                'supplies_requisition_order_header',
                'rawmats_replenishment_order_header',
                'rawmats_purchase_order_header'
            ];
        }

        // Use the selected year if provided, else current year
        $yearToUse = $year ? (int) $year : $currentYear;

        // Get latest month with data
        $currentMonth = $this->getLatestAvailableMonth($tables, $yearToUse, 'order_date');
        if ($currentMonth === 0) {
            $currentMonth = (int) date('n');
        }

        // Month range calculation
        switch ($monthRange) {
            case '4M':
                $maxMonth = $currentMonth;
                $minMonth = max(1, $maxMonth - 3);
                break;
            case '8M':
                $maxMonth = $currentMonth;
                $minMonth = max(1, $maxMonth - 7);
                break;
            case '1M':
                $minMonth = $maxMonth = $currentMonth;
                break;
            case '1Y':
            default:
                $minMonth = 1;
                $maxMonth = 12;
                break;
        }

        // Initialize counts
        $monthlyCounts = [];
        for ($m = $minMonth; $m <= $maxMonth; $m++) {
            $monthName = date('M', mktime(0, 0, 0, $m, 1));
            $monthlyCounts[$monthName] = [
                'Closed' => 0,
                'Unclosed' => 0,
                'Cancelled' => 0
            ];
        }

        $totalClosed = $totalUnclosed = $totalCancelled = 0;

        $monthExpr = "MONTH(order_date)";
        $yearExpr = "YEAR(order_date)";

        foreach ($tables as $table) {
            // Skip table if it does not exist
            if (!$this->db->table_exists($table)) {
                continue;
            }

            $this->db->select("header_status, {$monthExpr} AS month_num, COUNT(*) AS total", false);
            $this->db->from($table);
            $this->db->where("{$monthExpr} BETWEEN $minMonth AND $maxMonth", null, false);
            $this->db->where("$yearExpr = $yearToUse", null, false);
            $this->db->group_by(['month_num', 'header_status']);
            $query = $this->db->get();

            foreach ($query->result() as $row) {
                $status = ($row->header_status === 'Fully Posted') ? 'Closed' : $row->header_status;
                $monthNum = (int) $row->month_num;

                if ($monthNum >= 1 && $monthNum <= 12) {
                    $monthName = date('M', mktime(0, 0, 0, $monthNum, 1));
                    if (isset($monthlyCounts[$monthName][$status])) {
                        $monthlyCounts[$monthName][$status] += (int) $row->total;
                    }
                }

                if ($status === 'Closed') {
                    $totalClosed += (int) $row->total;
                } elseif ($status === 'Unclosed') {
                    $totalUnclosed += (int) $row->total;
                } elseif ($status === 'Cancelled') {
                    $totalCancelled += (int) $row->total;
                }
            }
        }

        $months = array_keys($monthlyCounts);
        $closedData = array_column($monthlyCounts, 'Closed');
        $unclosedData = array_column($monthlyCounts, 'Unclosed');
        $cancelledData = array_column($monthlyCounts, 'Cancelled');

        $availableYears = $this->getAvailableYears($tables, 'order_date');

        return [
            'months' => $months,
            'Closed' => $closedData,
            'Unclosed' => $unclosedData,
            'Cancelled' => $cancelledData,
            'total_orders' => $totalClosed + $totalUnclosed + $totalCancelled,
            'total_closed' => $totalClosed,
            'total_unclosed' => $totalUnclosed,
            'total_cancelled' => $totalCancelled,
            'current_year' => $yearToUse,
            'available_years' => $availableYears
        ];
    }

    #region getSalesOrderStatusMonthlyCounts
    public function getSalesOrderStatusMonthlyCounts($year, $monthRange)
    {
        $currentYear = (int) date('Y');

        // Decide which tables to query
        $table = 'sales_order_header';

        // Use the selected year if provided, else current year
        $yearToUse = $year ? (int) $year : $currentYear;

        // Get latest month with data
        $currentMonth = $this->getLatestAvailableMonth([$table], $yearToUse, 'order_date');
        if ($currentMonth === 0) {
            $currentMonth = (int) date('n');
        }

        // Month range calculation
        switch ($monthRange) {
            case '4M':
                $maxMonth = $currentMonth;
                $minMonth = max(1, $maxMonth - 3);
                break;
            case '8M':
                $maxMonth = $currentMonth;
                $minMonth = max(1, $maxMonth - 7);
                break;
            case '1M':
                $minMonth = $maxMonth = $currentMonth;
                break;
            case '1Y':
            default:
                $minMonth = 1;
                $maxMonth = 12;
                break;
        }

        // Initialize counts
        $monthlyCounts = [];
        for ($m = $minMonth; $m <= $maxMonth; $m++) {
            $monthName = date('M', mktime(0, 0, 0, $m, 1));
            $monthlyCounts[$monthName] = [
                'Posted' => 0,
                'Pending' => 0,
                'Cancelled' => 0
            ];
        }

        $totalPosted = $totalPending = $totalCancelled = 0;

        $monthExpr = "MONTH(order_date)";
        $yearExpr = "YEAR(order_date)";


        $this->db->select("header_status, {$monthExpr} AS month_num, COUNT(*) AS total", false);
        $this->db->from($table);
        $this->db->where("{$monthExpr} BETWEEN $minMonth AND $maxMonth", null, false);
        $this->db->where("$yearExpr = $yearToUse", null, false);
        $this->db->group_by(['month_num', 'header_status']);
        $query = $this->db->get();

        foreach ($query->result() as $row) {
            $status = $row->header_status;
            $monthNum = (int) $row->month_num;

            if ($monthNum >= 1 && $monthNum <= 12) {
                $monthName = date('M', mktime(0, 0, 0, $monthNum, 1));
                if (isset($monthlyCounts[$monthName][$status])) {
                    $monthlyCounts[$monthName][$status] += (int) $row->total;
                }
            }

            if ($status === 'Posted') {
                $totalPosted += (int) $row->total;
            } elseif ($status === 'Pending') {
                $totalPending += (int) $row->total;
            } elseif ($status === 'Cancelled') {
                $totalCancelled += (int) $row->total;
            }
        }

        $months = array_keys($monthlyCounts);
        $postedData = array_column($monthlyCounts, 'Posted');
        $pendingData = array_column($monthlyCounts, 'Pending');
        $cancelledData = array_column($monthlyCounts, 'Cancelled');

        $availableYears = $this->getAvailableYears([$table], 'order_date');

        return [
            'months' => $months,
            'Posted' => $postedData,
            'Pending' => $pendingData,
            'Cancelled' => $cancelledData,
            'total_orders' => $totalPosted + $totalPending + $totalCancelled,
            'total_posted' => $totalPosted,
            'total_pending' => $totalPending,
            'total_cancelled' => $totalCancelled,
            'current_year' => $yearToUse,
            'available_years' => $availableYears
        ];
    }

    private function getLatestAvailableMonth($tables, $year, $dateColumn)
    {
        $monthExpr = "MONTH({$dateColumn})";
        $yearExpr = "YEAR({$dateColumn})";
        $maxMonth = 0;

        foreach ($tables as $table) {
            // Skip table if it does not exist
            if (!$this->db->table_exists($table)) {
                continue;
            }

            $this->db->select("MAX($monthExpr) AS max_month", false);
            $this->db->from($table);
            $this->db->where("$yearExpr = $year", null, false);
            $query = $this->db->get();

            if ($query->num_rows() > 0) {
                $row = $query->row();
                if ($row && $row->max_month > $maxMonth) {
                    $maxMonth = (int) $row->max_month;
                }
            }
        }
        return $maxMonth;
    }

    private function getAvailableYears($tables, $dateColumn)
    {
        $years = [];
        $yearExpr = "YEAR({$dateColumn})";

        foreach ($tables as $table) {
            // Skip table if it does not exist
            if (!$this->db->table_exists($table)) {
                continue;
            }

            $this->db->select("DISTINCT $yearExpr AS year", false);
            $this->db->from($table);
            $query = $this->db->get();

            foreach ($query->result() as $row) {
                if ($row->year) {
                    $years[] = (int) $row->year;
                }
            }
        }

        $years = array_unique($years);
        rsort($years);
        return $years;
    }

    #region getSignatories
    public function getSignatories()
    {
        $this->db->select('*');
        $this->db->from('signatories');
        $this->db->where('status', 1);
        $this->db->order_by('is_default DESC, sign_name ASC');
        $query = $this->db->get();

        return $query->result_array();
    }

    #region getActiveAnnouncement
    public function getActiveAnnouncements()
    {
        $business_unit = $this->session->userdata('business_unit');

        //====================================================================
        // Load CIS DB
        // Load connection config based on BU
        $dbConfig = _getDatabaseConfig($business_unit);

        // 🔹 Disable CI DB debug for testing connection
        $dbConfig['dbDetailsMain']['db_debug'] = FALSE;

        // Suppress warnings
        $cis = @$this->load->database($dbConfig['dbDetailsMain'], TRUE);

        //====================================================================

        $user_type = $this->session->userdata('user_type');
        $currentDateTime = date('Y-m-d H:i:s');
        // Get server IP for conditional logic (safe for PHP 5.6–7.4)
        $serverIP = isset($_SERVER['SERVER_ADDR']) ? $_SERVER['SERVER_ADDR'] : gethostbyname(gethostname());

        // Get host (remove port if present)
        $serverHost = isset($_SERVER['HTTP_HOST']) ? strtok($_SERVER['HTTP_HOST'], ':') : '';

        // Detect environment type
        $environments = _getServerEnvironments();
        $envType = 'local'; // default to local

        // Check by IP first
        if (isset($environments[$serverIP])) {
            $envType = $environments[$serverIP]['type'];
        }
        // Check by host if IP not found
        elseif (isset($environments[$serverHost])) {
            $envType = $environments[$serverHost]['type'];
        }

        // First, update expired announcements to inactive
        $updateSql = "UPDATE announcements 
              SET is_active = 0 
              WHERE is_active = 1 
              AND display_type IN ('scheduled', 'expiry')
              AND display_end IS NOT NULL 
              AND display_end < '$currentDateTime'";

        $cis->query($updateSql);

        // Then get the active announcements
        $sql = "SELECT * FROM announcements 
        WHERE is_active = 1 
        AND (
            display_type = 'permanent' 
            OR (
                display_type IN ('scheduled', 'expiry') 
                AND (display_start IS NULL OR display_start <= '$currentDateTime')
                AND (display_end IS NULL OR display_end >= '$currentDateTime')
            )
        )
        AND (
            affected_bus IS NULL 
            OR affected_bus = '' 
            OR LOWER(affected_bus) = 'all'
            OR FIND_IN_SET('$business_unit', affected_bus) > 0
        )
        ORDER BY created_at DESC";

        $query = $cis->query($sql);

        $announcements = array();

        if ($query && $query->num_rows() > 0) {
            $announcements = $query->result_array();
        }

        // Add permanent fixed announcement ONLY for admin users on LIVE environment
        if ($user_type === 'Admin' && $envType === 'live') {
            $adminAnnouncement = array(
                'id' => 'admin_permanent',
                'title' => 'SYSTEM NOTICE FOR ALL ADMIN USERS',
                'content' => 'This is a <strong>LIVE SYSTEM</strong>. Please be <strong>careful</strong> when using the <strong>transaction modules</strong>. All actions are permanent and affect real data.',
                'alert_type' => 'danger',
                'icon' => 'ri-alarm-warning-line',
                'display_type' => 'permanent',
                'affected_bus' => 'all',
                'created_at' => date('Y-m-d H:i:s')
            );

            // Prepend admin announcement to the array (so it appears first)
            array_unshift($announcements, $adminAnnouncement);
        }
        // Optional: Add a different message for local environment
        elseif ($envType === 'local') {
            $localAnnouncement = array(
                'id' => 'local_permanent',
                'title' => 'LOCAL ENVIRONMENT',
                'content' => 'You are currently on a <strong>LOCAL/TESTING ENVIRONMENT</strong>. Feel free to test features without affecting live data.',
                'alert_type' => 'success',
                'icon' => 'ri-information-line',
                'display_type' => 'permanent',
                'affected_bus' => 'all',
                'created_at' => date('Y-m-d H:i:s')
            );
            array_unshift($announcements, $localAnnouncement);
        }

        return $announcements;
    }
}
