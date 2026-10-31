<?php

#region JSONResponse
function JSONResponse($data)
{
	header('Content-Type: application/json');
	die(json_encode($data));
}

#region get_real_mac_addr
function get_real_mac_addr()
{
	ob_start();
	system('ipconfig /all');
	$mycom = ob_get_clean();
	$findme = "Physical";
	$pmac = strpos($mycom, $findme);
	$mac = substr($mycom, ($pmac + 36), 17);
	return $mac;
}

#region getAddressByBu
function getAddressByBu()
{
	$CI = &get_instance();
	$currentBusinessUnit = $CI->session->userdata('business_unit');

	if ($currentBusinessUnit == '13') { //BRDR BIL-AG003
		return 'Bilar, Bohol';
	} elseif ($currentBusinessUnit == '14') { //HTCH BIL-AG004
		return 'Bilar, Bohol';
	} elseif ($currentBusinessUnit == '15') { //BRDR DIMCAN-AG005
		return 'Dimiao, Canhayupon, Bohol';
	} elseif ($currentBusinessUnit == '16') { //BRDR DIMLAP-AG006
		return 'Dimiao, Lapsaon, Bohol';
	} elseif ($currentBusinessUnit == '43') { //RIZAL BIL-AG015
		return 'Bilar, Rizal, Bohol';
	} elseif ($currentBusinessUnit == '11') { //PGRY CORT-AG001
		return 'Lourdes, Cortes, Bohol';
	} elseif ($currentBusinessUnit == '12') { //LAYER CORT-AG002
		return 'Lourdes, Cortes, Bohol';
	} elseif ($currentBusinessUnit == '25') { //ICE-NF001
		return 'CPG North Avenue, Tagbilaran City, Bohol';
	} elseif ($currentBusinessUnit == '26') { //PK TAG-NF002
		return 'CPG North Avenue, Tagbilaran City, Bohol';
	} elseif ($currentBusinessUnit == '50') { //GPFARM-AG021
		return 'Balili, Jagna, Bohol';
	} elseif ($currentBusinessUnit == '19') { //FDML UBAY-AG009
		return 'Lomangog, Ubay, Bohol';
	} elseif ($currentBusinessUnit == '21') { //DMO UBAY-AG011
		return 'Lomangog, Ubay, Bohol';
	} elseif ($currentBusinessUnit == '41') { //FARMS MARKET
		return 'Lomangog, Ubay, Bohol';
	} elseif ($currentBusinessUnit == '20') { //GRWT UBAY-AG010
		return 'Lomangog, Ubay, Bohol';
	} elseif ($currentBusinessUnit == '46') { //MPP-UBAY-AG017
		return 'Lomangog, Ubay, Bohol';
	} elseif ($currentBusinessUnit == '22') { //FERP UBAY-AG012
		return 'Lomangog, Ubay, Bohol';
	} elseif ($currentBusinessUnit == '17') { //DRSP UBAY-AG007
		return 'Lomangog, Ubay, Bohol';
	} elseif ($currentBusinessUnit == '18') { //REND UBAY-AG008
		return 'Lomangog, Ubay, Bohol';
	} elseif ($currentBusinessUnit == '23') { //PGRY ALC-AG013
		return 'Untaga, Alicia, Bohol';
	} elseif ($currentBusinessUnit == '42') { //FERP CORTES-AG015
		return 'Cortes, Bohol';
	} elseif ($currentBusinessUnit == '2') { //CAB-AQ002
		return 'Catagbacan, Loon, Bohol';
	} elseif ($currentBusinessUnit == '3') { //TILBRDR-AQ003
		return 'Catagbacan, Loon, Bohol';
	} elseif ($currentBusinessUnit == '9') { //TIPCAN
		return 'Tipcan, Calape, Bohol';
	} elseif ($currentBusinessUnit == '7') { //ORTIGAS
		return 'Tultugan, Calape, Bohol';
	} elseif ($currentBusinessUnit == '1') { //BMC
		return 'Pangangan, Calape, Bohol';
	} elseif ($currentBusinessUnit == '6') { //MARIBOJOC
		return 'Lincod, Maribojoc, Bohol';
	} elseif ($currentBusinessUnit == '44') { //SLURRY
		return 'Cantumocad, Loon, Bohol';
	} elseif ($currentBusinessUnit == '45') { //BOHOLAB
		return 'Cantumocad, Loon, Bohol';
	} elseif ($currentBusinessUnit == '55') { //BACONG
		return 'Pinayagan Sur, Tubigon, Bohol';
	}

	return 'Address Not Found';
}

#region buFiltered
function buFiltered($db)
{
	$CI = &get_instance();
	$db->where('business_unit', $CI->session->userdata('business_unit'));
}

#region columnExists
function columnExists($column, $table)
{
	$CI = &get_instance();
	return $CI->db->field_exists($column, $table);
}

#region create_column
function create_column($table_name, $column_name, $type, $constraint = NULL, $null = TRUE)
{
	//EXAMPLE
	// Integer types
	// create_column('users', 'age', 'INT', 11);
	// create_column('users', 'user_id', 'INT', 11, FALSE); // NOT NULL
	// create_column('users', 'small_count', 'SMALLINT', 5);
	// create_column('users', 'big_number', 'BIGINT', 20);
	// create_column('users', 'tiny_flag', 'TINYINT', 1);
	// create_column('users', 'medium_value', 'MEDIUMINT', 8);

	// // Decimal/Float types
	// create_column('products', 'price', 'DECIMAL', '10,2', FALSE);
	// create_column('products', 'tax_rate', 'FLOAT', '10,2', FALSE);
	// create_column('products', 'double_value', 'DOUBLE', '10,2', FALSE);
	// create_column('products', 'exact_amount', 'NUMERIC', '10,2', FALSE);

	// Character types
	// create_column('users', 'username', 'VARCHAR', 255);
	// create_column('users', 'char_code', 'CHAR', 10);
	// create_column('users', 'bio', 'TEXT');
	// create_column('users', 'description', 'LONGTEXT');
	// create_column('users', 'medium_text', 'MEDIUMTEXT');

	// Binary types
	// create_column('documents', 'file_data', 'BLOB');
	// create_column('documents', 'medium_file', 'MEDIUMBLOB');
	// create_column('documents', 'large_file', 'LONGBLOB');

	// Date & Time Types
	// create_column('orders', 'created_at', 'DATETIME');
	// create_column('orders', 'order_date', 'DATE');
	// create_column('orders', 'order_time', 'TIME');
	// create_column('orders', 'timestamp_col', 'TIMESTAMP');
	// create_column('orders', 'year_col', 'YEAR', 4);

	$CI =& get_instance();
	$CI->load->dbforge();

	// Check if the column exists in the table
	if (!$CI->db->field_exists($column_name, $table_name)) {
		// Define the column with the provided type and constraint
		$fields = array(
			$column_name => array(
				'type' => $type,
				'constraint' => $constraint,
				'null' => $null,
			),
		);

		// Add the column to the table
		$CI->dbforge->add_column($table_name, $fields);
	}

	return true; // Return true if column creation/checking was successful
}

#region api_url
function api_url($apiUrl, $data = null, $method = 'GET')
{
	// Extra safety for long calls
	set_time_limit(0);
	ini_set('default_socket_timeout', 0);

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $apiUrl);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	// UNLIMITED
	curl_setopt($ch, CURLOPT_TIMEOUT, 0);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);

	$method = strtoupper($method);

	if ($method === 'POST') {
		curl_setopt($ch, CURLOPT_POST, true);
		if ($data !== null) {
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		}
	} elseif ($method === 'PUT' || $method === 'PATCH' || $method === 'DELETE') {
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
		if ($data !== null) {
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		}
	}

	if ($data !== null) {
		curl_setopt($ch, CURLOPT_HTTPHEADER, [
			'Content-Type: application/json',
			'Accept: application/json'
		]);
	}

	$response = curl_exec($ch);
	$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

	if (curl_errno($ch)) {
		$error_msg = curl_error($ch);
		curl_close($ch);
		return ['error' => $error_msg, 'http_code' => 0];
	}

	curl_close($ch);

	$apiData = json_decode($response, true);

	if (!is_array($apiData)) {
		return ['error' => 'Invalid API response', 'http_code' => $httpcode];
	}

	return $apiData;
}

#region remove_commas
function remove_commas($number)
{
	return floatval(str_replace(',', '', $number));
}

#region format_qty
function format_qty($qty)
{
	if (strpos($qty, '.') !== false) {
		return rtrim(rtrim(number_format($qty, 2, '.', ','), '0'), '.');
	} else {
		return number_format($qty, 0, '.', ',');
	}
}

#region number_format_custom
function number_format_custom($value)
{
	$value = remove_commas($value);
	// First format with 3 decimals, NO commas to match original behavior
	$num_amt = number_format($value, 5, '.', '');

	// Remove trailing zeros and decimal point if needed (original logic)
	$num_amt = rtrim(rtrim($num_amt, '0'), '.');

	// Ensure at least 2 decimal places (original logic)
	if (strpos($num_amt, '.') !== false) {
		$decimals = strlen(substr(strrchr($num_amt, '.'), 1));
		if ($decimals < 2) {
			$num_amt .= str_repeat('0', 2 - $decimals);
		}
	} else {
		$num_amt .= '.00';
	}

	// Now add commas as thousands separators
	if (strpos($num_amt, '.') !== false) {
		list($whole, $decimal) = explode('.', $num_amt);
		$whole = number_format($whole, 0, '.', ',');
		$num_amt = $whole . '.' . $decimal;
	} else {
		$num_amt = number_format($num_amt, 0, '.', ',');
	}

	return $num_amt;
}
#endregion

#region getSequenceNoWithLock
function getSequenceNoWithLock(array $seq, array $option)
{
	$seq = (object) $seq;
	$option = (object) $option;

	$CI = &get_instance();
	$db = &$CI->db;

	$db->trans_start();

	// Lock the row
	$where = [];
	$params = [];

	$description = isset($seq->description) ? trim($seq->description) : null;

	if (!empty($seq->sequence_id)) {
		$where[] = "sequence_id = ?";
		$params[] = $seq->sequence_id;
	}

	if (!empty($seq->for_column)) {
		$where[] = "for_column = ?";
		$params[] = $seq->for_column;
	}

	if (!empty($description)) {
		$where[] = "description = ?";
		$params[] = $description;
	}

	if (empty($where)) {
		$db->trans_rollback();
		return null;
	}

	$query = $db->query("
                        SELECT * FROM sequence
                        WHERE " . implode(' AND ', $where) . "
                        FOR UPDATE
                    ", $params);

	if ($query->num_rows() == 0) {
		$db->trans_rollback();
		return null;
	}

	$seqRow = $query->row();

	$fullYear = (int) date('Y');   // 2026
	$yearPrefix = date('y');         // "26"

	// ✅ Reset sequence on new year (START AT 1)
	if ((int) $seqRow->year !== $fullYear) {
		$number = 1;

		if (!empty($seq->sequence_id)) {
			$db->where('sequence_id', $seq->sequence_id);
		}

		if (!empty($seq->for_column)) {
			$db->where('for_column', $seq->for_column);
		}

		if (!empty($description)) {
			$db->where('description', $description);
		}
		$db->update('sequence', [
			'number' => $number + 1,
			'year' => $fullYear
		]);
	} else {
		$number = (int) $seqRow->number;
	}

	// Generate sequence (YY + padded number)
	$sequence = (int) (
		$yearPrefix .
		str_pad($number, $seqRow->lpad, $seqRow->pad_string, STR_PAD_LEFT)
	);

	// Ensure uniqueness (loop-safe)
	$fquery = $db->query("
        SELECT 1 FROM {$option->table}
        WHERE {$option->column} = ?
        LIMIT 1
    ", [$sequence]);

	while ($fquery->num_rows() > 0) {
		$number++;
		$sequence = (int) (
			$yearPrefix .
			str_pad($number, $seqRow->lpad, $seqRow->pad_string, STR_PAD_LEFT)
		);

		$fquery = $db->query("
            SELECT 1 FROM {$option->table}
            WHERE {$option->column} = ?
            LIMIT 1
        ", [$sequence]);
	}

	// Update next sequence number
	if ((int) $seqRow->year === $fullYear) {
		if (!empty($seq->sequence_id)) {
			$db->where('sequence_id', $seq->sequence_id);
		}

		if (!empty($seq->for_column)) {
			$db->where('for_column', $seq->for_column);
		}

		if (!empty($description)) {
			$db->where('description', $description);
		}
		$db->update('sequence', ['number' => $number + 1]);
	}

	$db->trans_complete();

	if ($db->trans_status() === FALSE) {
		return null;
	}

	return $sequence;
}

#region getSequenceNoWithLockAR
function getSequenceNoWithLockAR(array $seq, array $option, $arDb)
{
	$seq = (object) $seq;
	$option = (object) $option;

	$CI = &get_instance();

	// Use the AR connection for all database operations
	$db = $arDb;

	$db->trans_start();

	// Build WHERE conditions
	$where = [];
	$params = [];

	$description = isset($seq->description) ? trim($seq->description) : null;

	if (!empty($seq->sequence_id)) {
		$where[] = "sequence_id = ?";
		$params[] = $seq->sequence_id;
	}

	if (!empty($seq->for_column)) {
		$where[] = "for_column = ?";
		$params[] = $seq->for_column;
	}

	if (!empty($description)) {
		$where[] = "description = ?";
		$params[] = $description;
	}

	if (empty($where)) {
		$db->trans_rollback();
		return null;
	}

	// Lock the row
	$query = $db->query("
        SELECT * FROM sequence
        WHERE " . implode(' AND ', $where) . "
        FOR UPDATE
    ", $params);

	if ($query->num_rows() == 0) {
		$db->trans_rollback();
		return null;
	}

	$seqRow = $query->row();

	$fullYear = (int) date('Y');   // 2026
	$yearPrefix = date('y');         // "26"

	// ✅ Reset sequence on new year (START AT 1)
	if ((int) $seqRow->year !== $fullYear) {
		$number = 1;

		// Build WHERE for update

		if (!empty($seq->sequence_id)) {
			$db->where('sequence_id', $seq->sequence_id);
		}

		if (!empty($seq->for_column)) {
			$db->where('for_column', $seq->for_column);
		}

		if (!empty($description)) {
			$db->where('description', $description);
		}

		$db->update('sequence', [
			'number' => $number + 1,
			'year' => $fullYear
		]);
	} else {
		$number = (int) $seqRow->number;
	}

	// Generate sequence (YY + padded number)
	$sequence = (int) (
		$yearPrefix .
		str_pad($number, $seqRow->lpad, $seqRow->pad_string, STR_PAD_LEFT)
	);

	// Ensure uniqueness (loop-safe)
	$fquery = $db->query("
        SELECT 1 FROM {$option->table}
        WHERE {$option->column} = ?
        LIMIT 1
    ", [$sequence]);

	while ($fquery->num_rows() > 0) {
		$number++;
		$sequence = (int) (
			$yearPrefix .
			str_pad($number, $seqRow->lpad, $seqRow->pad_string, STR_PAD_LEFT)
		);

		$fquery = $db->query("
            SELECT 1 FROM {$option->table}
            WHERE {$option->column} = ?
            LIMIT 1
        ", [$sequence]);
	}

	// Update next sequence number
	if ((int) $seqRow->year === $fullYear) {
		if (!empty($seq->sequence_id)) {
			$db->where('sequence_id', $seq->sequence_id);
		}

		if (!empty($seq->for_column)) {
			$db->where('for_column', $seq->for_column);
		}

		if (!empty($description)) {
			$db->where('description', $description);
		}

		$db->update('sequence', ['number' => $number + 1]);
	}

	$db->trans_complete();

	if ($db->trans_status() === FALSE) {
		return null;
	}

	return $sequence;
}

#region cleanFilename
function cleanFilename($text)
{
	$URL = strtoupper(preg_replace(array('/[^a-z0-9\- ]/i', '/[ \-]+/'), array('', '_'), $text));
	return $URL;
}

#region filePath
function filePath($type)
{
	$CI = &get_instance();
	$currentBusinessUnit = $CI->session->userdata('business_unit');
	// Get server IP for conditional logic (safe for PHP 5.6–7.4)
	$serverIP = isset($_SERVER['SERVER_ADDR']) ? $_SERVER['SERVER_ADDR'] : gethostbyname(gethostname());

	// Detect host from browser (localhost, 127.0.0.1, domain)
	$serverHost = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '';

	// 🔥 FIX: Remove port number from host (e.g., "172.16.220.1:81" -> "172.16.220.1")
	$serverHost = strtok($serverHost, ':');

	// Local environment checks
	$serverEnvironments = _getServerEnvironments();

	// 🔥 FIX: Add null coalescing to prevent undefined index errors
	$serverIPType = isset($serverEnvironments[$serverIP]) ? $serverEnvironments[$serverIP]['type'] : null;
	$serverHostType = isset($serverEnvironments[$serverHost]) ? $serverEnvironments[$serverHost]['type'] : null;

	// Check if server IP is not recognized
	if ($serverIPType === null) {
		$errorMessage = "Server IP '{$serverIP}' is not configured in serverEnvironments . ";
		$errorMessage .= "Please add this IP to the configuration with either 'local' or 'live' type.";

		return [
			'error' => true,
			'message' => $errorMessage,
			'server_ip' => $serverIP,
			'server_host' => $serverHost
		];
	}

	// Check if server host is not recognized (for network mode validation)
	if ($serverHostType === null) {
		$errorMessage = "Server Host '{$serverHost}' is not configured in serverEnvironments. ";
		$errorMessage .= "Please add this host to the configuration with either 'local' or 'live' type.";

		return [
			'error' => true,
			'message' => $errorMessage,
			'server_ip' => $serverIP,
			'server_host' => $serverHost
		];
	}

	// Validate that the type is either 'local' or 'live'
	$validTypes = ['local', 'live'];

	if (!in_array($serverIPType, $validTypes)) {
		$errorMessage = "Invalid server IP type '{$serverIPType}' for IP '{$serverIP}'. ";
		$errorMessage .= "Type must be either 'local' or 'live'.";

		return [
			'error' => true,
			'message' => $errorMessage,
			'server_ip' => $serverIP,
			'server_ip_type' => $serverIPType,
			'server_host' => $serverHost,
			'valid_types' => $validTypes
		];
	}

	if (!in_array($serverHostType, $validTypes)) {
		$errorMessage = "Invalid server host type '{$serverHostType}' for host '{$serverHost}'. ";
		$errorMessage .= "Type must be either 'local' or 'live'.";

		return [
			'error' => true,
			'message' => $errorMessage,
			'server_ip' => $serverIP,
			'server_host' => $serverHost,
			'server_host_type' => $serverHostType,
			'valid_types' => $validTypes
		];
	}

	// Define paths based on business unit using switch
	if ($serverIPType == 'live' && $serverHostType == 'live') {
		//FOR LIVE SERVERS
		switch ($currentBusinessUnit) {
			case '11'://PIGGERY
				$pomPath = realpath(APPPATH . '../files/Transactions/PurchaseOrder');
				$glPath = realpath(APPPATH . '../files/Textfile');
				$variancePath = realpath(APPPATH . '../files/Textfile');
				$siPath = realpath(APPPATH . '../files/Textfile');
				$customerPath = realpath(APPPATH . '../files/Textfile');
				$pathType = 'local';
			default:
				$pomPath = 'Empty';
				$glPath = 'Empty';
				$variancePath = 'Empty';
				$siPath = 'Empty';
				$pathType = 'Empty';
				$customerPath = 'Empty';
				break;
		}

	} else {
		$pomPath = realpath(APPPATH . '../files/Transactions/PurchaseOrder');
		$glPath = realpath(APPPATH . '../files/Textfile');
		$variancePath = realpath(APPPATH . '../files/Textfile');
		$siPath = realpath(APPPATH . '../files/Textfile');
		$customerPath = realpath(APPPATH . '../files/Textfile');
		$pathType = 'local';
	}

	$pathConfig = [
		'POM' => [
			'type' => $pathType,
			'path' => $pomPath
		],
		'GL' => [
			'type' => $pathType,
			'path' => $glPath
		],
		'VARIANCE' => [
			'type' => $pathType,
			'path' => $variancePath
		],
		'SI' => [
			'type' => $pathType,
			'path' => $siPath
		],
		'CUSTOMER' => [
			'type' => $pathType,
			'path' => $customerPath
		]
	];

	if (!isset($pathConfig[$type])) {
		return [
			'error' => true,
			'message' => "Unsupported file type: {$type}"
		];
	}

	$config = $pathConfig[$type];
	$path = $config['path'];

	if ($config['type'] === 'local') {
		if (!$path || !is_dir($path) || !is_readable($path)) {
			return [
				'error' => true,
				'message' => "Local path for type '{$type}' is not accessible: {$path}"
			];
		}
	} else { // network path
		// Lightweight network path validation
		// Try to get directory listing (most reliable for network paths)
		$dir_listing = @scandir($path);

		if ($dir_listing === false) {
			// If scandir fails, try file_exists as fallback
			if (!@file_exists($path)) {
				return [
					'error' => true,
					'message' => "Network path for type '{$type}' is not accessible: {$path}. " .
						"The folder may have been renamed, moved, or network connection is down."
				];
			}
		}
	}

	return [
		'error' => false,
		'path' => $path,
		'server_ip' => $serverIP,
		'server_host' => $serverHost,
		'environment' => $pathType
	];
}

#region format_name
function format_name($name)
{
	if (empty($name))
		return '';

	// Trim and normalize spaces
	$name = strtoupper(trim(preg_replace('/\s+/', ' ', $name)));

	// Keep Ñ properly but normalize weird encodings
	$name = strtr($name, ['Ã‘' => 'Ñ']);

	// Split into LASTNAME and the rest by comma
	$parts = explode(',', $name);
	if (count($parts) < 2) {
		return $name; // Invalid format, return as-is
	}

	$lastname = trim($parts[0]);
	$rest = trim($parts[1]); // first + middle name part

	// Split remaining part into words
	$names = explode(' ', $rest);
	$firstname = $names[0];
	$middlename = isset($names[1]) ? $names[1] : '';

	// Handle if user has two given names (e.g., "MARK JAMES MARCHADO")
	if (count($names) > 2) {
		// All except last are first names
		$firstname = implode(' ', array_slice($names, 0, -1));
		// Last one is middle name
		$middlename = end($names);
	}

	// Convert middle name to initial + period
	$middleInitial = $middlename ? substr($middlename, 0, 1) . '.' : '';

	// Final formatted name
	return "{$lastname}, {$firstname} {$middleInitial}";
}

#region _getServerEnvironments
function _getServerEnvironments()
{
	return [
		'172.16.43.105' => ['type' => 'local'],
		'127.0.0.1' => ['type' => 'local'],
		'::1' => ['type' => 'local'],          // IPv6 localhost
		'localhost' => ['type' => 'local'],
	];
}

// Dynamically load database by business unit
#region _getDatabaseConfig
function _getDatabaseConfig($bu)
{
	// Get server IP for conditional logic (safe for PHP 5.6–7.4)
	$serverIP = isset($_SERVER['SERVER_ADDR']) ? $_SERVER['SERVER_ADDR'] : gethostbyname(gethostname());

	// Get host (remove port if present)
	$serverHost = isset($_SERVER['HTTP_HOST']) ? strtok($_SERVER['HTTP_HOST'], ':') : '';

	// Local environment checks
	$environments = _getServerEnvironments();

	// CHECK IF IP OR HOST EXISTS IN ENVIRONMENT CONFIGURATION
	$ipExists = isset($environments[$serverIP]);
	$hostExists = isset($environments[$serverHost]);

	// If neither IP nor host is found in the environment configuration
	if (!$ipExists && !$hostExists) {
		error_log("Database Config Error: Server IP '$serverIP' and Host '$serverHost' not found in environment configuration");
		return false; // Return false to indicate configuration not found
	}

	// Determine if local - using AND condition (BOTH must be local if they exist)
	$isLocal = false;

	if ($ipExists && $hostExists) {
		// Both exist - check if both are local
		$isLocal = ($environments[$serverIP]['type'] == 'local' && $environments[$serverHost]['type'] == 'local');
	} elseif ($ipExists) {
		// Only IP exists - check its type
		$isLocal = ($environments[$serverIP]['type'] == 'local');
	} elseif ($hostExists) {
		// Only host exists - check its type
		$isLocal = ($environments[$serverHost]['type'] == 'local');
	}

	// Default DB config (common to all connections)
	$baseConfig = array(
		'dsn' => '',
		'dbdriver' => 'mysqli',
		'dbprefix' => '',
		'pconnect' => FALSE,
		'db_debug' => TRUE,
		'cache_on' => FALSE,
		'cachedir' => '',
		'char_set' => 'utf8mb4',
		'dbcollat' => 'utf8mb4_unicode_ci',
		'swap_pre' => '',
		'encrypt' => FALSE,
		'compress' => FALSE,
		'stricton' => FALSE,
		'failover' => array(),
		'save_queries' => TRUE
	);

	// Set only the BU-specific connection details
	switch ($bu) {
		#region PIGGERY CORTES
		case '11':
			$dbDetails = array(
				'hostname' => $isLocal ? '172.16.43.105' : '',
				'port' => $isLocal ? 3306 : '',
				'username' => 'root',
				'password' => '',
				'database' => 'asc_aggregates'
			);
			$dbDetailsMain = array(
				'hostname' => $isLocal ? '172.16.43.105' : '',
				'port' => $isLocal ? 3306 : '',
				'username' => 'root',
				'password' => '',
				'database' => $isLocal ? 'centralized_aggregates' : ''
			);
			$arDbDetails = array(
				'hostname' => $isLocal ? '172.16.43.105' : '',
				'port' => $isLocal ? 3306 : 3306,
				'username' => $isLocal ? 'admin' : '',
				'password' => $isLocal ? 'admin' : '',
				'database' => 'cortes_piggery_new'
			);
			break;

		default:
			return FALSE;
	}

	// Merge base config with specific details
	return [
		'dbDetails' => array_merge($baseConfig, $dbDetails),
		'dbDetailsMain' => array_merge($baseConfig, $dbDetailsMain),
		'arDbDetails' => array_merge($baseConfig, $arDbDetails)
	];
}
#endregion

#region fallbackRandomBytes
function fallbackRandomBytes($length)
{
	$bytes = '';
	if (function_exists('mcrypt_create_iv')) {
		// PHP 5.3+ with mcrypt
		$bytes = mcrypt_create_iv($length, MCRYPT_DEV_URANDOM);
	}

	if ($bytes === false || strlen($bytes) < $length) {
		// Final fallback - less secure but works
		$bytes = '';
		for ($i = 0; $i < $length; $i++) {
			$bytes .= chr(mt_rand(0, 255));
		}
	}

	return $bytes;
}

#region versioned_script_css
function versioned_script_css($file)
{
	// Add safety check to ensure file exists
	if (file_exists($file)) {
		return $file . '?v=' . filemtime($file);
	}
	return $file; // Return original if file doesn't exist
}


#region QUERY HELPER
#===================================================


#region getNameByField
function getNameByFieldHelper($table, $field, $value, $nameColumn)
{
	// CI instance
	$CI = &get_instance();

	// Validate
	if (empty($table) || empty($field) || empty($value) || empty($nameColumn)) {
		return '';
	}

	$CI->db->select($nameColumn);
	$CI->db->where($field, $value);

	$query = $CI->db->get($table);
	$result = $query->row_array();

	return $result ? $result[$nameColumn] : '';
}

#region is_local_theme_ip
function is_local_theme_ip()
{
	// Server IP
	$server_ip = isset($_SERVER['SERVER_ADDR'])
		? $_SERVER['SERVER_ADDR']
		: getHostByName(getHostName());

	// Hostname in URL (localhost, 127.0.0.1, domain, etc.)
	$server_host = isset($_SERVER['HTTP_HOST']) ? strtok($_SERVER['HTTP_HOST'], ':') : '';

	// Get environments from helper function
	$environments = _getServerEnvironments();

	// Check if server IP or host is in the environments array and is 'local'
	$is_local_ip = isset($environments[$server_ip]) && $environments[$server_ip]['type'] === 'local';
	$is_local_host = isset($environments[$server_host]) && $environments[$server_host]['type'] === 'local';

	return $is_local_ip || $is_local_host;
}

#region invoicing_footer
function invoicing_footer($pdf)
{
	$pdf->Cell(125.5, 5, 'Note: This document is not valid without complete signatory.', 0, 0, 'L');
	$pdf->Cell(40, 5, 'Run Date: ' . date('m/d/Y'), 0, 0, 'L');
	$pdf->Cell(20, 5, 'Run Time: ' . date('H:i:s'), 0, 0, 'L');
}

#region get_noted_by
function get_noted_by_helper($bu_id)
{
	$names = [
		'13' => 'MARCELA M. OROT',
		'11' => 'JOSEPH M. GILDORE',
		'22' => 'ALEXIO C. AVENIDO JR.',
		'42' => 'ALEXIO C. AVENIDO JR.',
		'43' => 'JENNIFER C. BALBIDO',
		'16' => 'EMMANUEL DORIA',
		'15' => 'MELISSA QUIÑANOLA',
		'50' => 'LOURDES JALOP',
		'14' => 'ANTONIO ATAN',
		'25' => 'HAYZELMAE PAYOT. OROT',
		// '26' => 'DENNIS T. GABATO',
		'19' => 'ALBERTO B. ELMAN',
	];
	return isset($names[$bu_id]) ? $names[$bu_id] : '';
}

#region clean_string
function clean_string($val, $limit = null)
{
	return is_string($val) ?
		($limit !== null && $limit > 0 ?
			substr(trim(preg_replace('/\s+/', ' ', str_replace([',', "\r", "\n"], ' ', $val))), 0, $limit) :
			trim(preg_replace('/\s+/', ' ', str_replace([',', "\r", "\n"], ' ', $val)))) :
		$val;
}

#region getAcronym
function getAcronym($phrase, $separator = '')
{
	// Split on whitespace
	$words = preg_split('/\s+/', trim($phrase));

	$acronym = '';
	foreach ($words as $word) {
		// Strip anything that isn't a letter/number
		$clean = preg_replace('/[^A-Za-z0-9]/', '', $word);
		if ($clean !== '') {
			if ($acronym !== '' && $separator !== '') {
				$acronym .= $separator;
			}
			$acronym .= strtoupper($clean[0]);
		}
	}

	return $acronym;
}
