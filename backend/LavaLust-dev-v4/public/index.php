<?php
// NUCLEAR CORS FIX — ISANG BESSES LANG, WALANG LABAN
while (ob_get_level()) ob_end_clean();

// CORS — ISANG BESSES LANG, EXACT, AT WALANG DUPLICATE
header('Access-Control-Allow-Origin: http://localhost:5173');
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
header('Content-Type: application/json; charset=utf-8');

// OPTIONS PREFlight — ISA LANG
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// ALWAYS LOAD GOOGLE CLIENT
require_once __DIR__ . '/../vendor/autoload.php';

define('PREVENT_DIRECT_ACCESS', TRUE);

// Original paths (huwag galawin)
$system_path        = '../scheme';
$application_folder = '../app';

define('ROOT_DIR', __DIR__ . DIRECTORY_SEPARATOR);
define('SYSTEM_DIR', ROOT_DIR . $system_path . DIRECTORY_SEPARATOR);
define('APP_DIR', ROOT_DIR . $application_folder . DIRECTORY_SEPARATOR);

require_once SYSTEM_DIR . 'kernel/LavaLust.php';