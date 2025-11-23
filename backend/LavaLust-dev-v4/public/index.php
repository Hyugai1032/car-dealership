<?php
// NUCLEAR FIX — KILL ALL OUTPUT + FORCE JSON FOR /auth/google
if (isset($_SERVER['REQUEST_URI']) && strpos($_SERVER['REQUEST_URI'], '/auth/google') !== false) {
    while (ob_get_level()) ob_end_clean();
    header('Content-Type: application/json; charset=utf-8');
    header('Access-Control-Allow-Origin: http://localhost:5173');
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Allow-Methods: POST, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type, Authorization');

    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        http_response_code(200);
        exit;
    }
}

// ALWAYS LOAD GOOGLE CLIENT — HINDI NA MAGKAKAMALI
require_once __DIR__ . '/../vendor/autoload.php';

// ORIGINAL CORS HEADERS (keep mo ‘to)
header('Access-Control-Allow-Origin: http://localhost:5173');
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
header('Vary: Origin');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

define('PREVENT_DIRECT_ACCESS', TRUE);

// ... lahat ng iba mo (huwag mo nang galawin)
$system_path         = '../scheme';
$application_folder  = '../app';
$public_folder       = '__DIR__';

define('ROOT_DIR',  __DIR__ . DIRECTORY_SEPARATOR);
define('SYSTEM_DIR', ROOT_DIR . $system_path . DIRECTORY_SEPARATOR);
define('APP_DIR', ROOT_DIR . $application_folder . DIRECTORY_SEPARATOR);
define('PUBLIC_DIR', $public_folder);

require_once SYSTEM_DIR . 'kernel/LavaLust.php';
?>