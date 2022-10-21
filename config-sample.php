<?php

/**
 * The basic Osclass Enterprise settings
 */

// Multi-site setup
define('MULTISITE', 0);

// MySQL hostname
define('DB_HOST', 'localhost');

// MySQL database name
define('DB_NAME', 'database_name');

// MySQL database username
define('DB_USER', 'username');

// MySQL database password
define('DB_PASSWORD', 'password');

// Database Table prefix
define('DB_TABLE_PREFIX', 'oc_');

// Relative web URL
define('REL_WEB_URL', 'rel_here');

// Web address - modify here for SSL version of the site
define('WEB_PATH', 'http://localhost');


// *************************************** //
// ** OPTIONAL CONFIGURATION PARAMETERS ** //
// *************************************** //

// Enable debugging for PHP and MySQL (DB)
// define('OSC_DEBUG', true);
// define('OSC_DEBUG_LOG', true);
// define('OSC_DEBUG_DB', true);
// define('OSC_DEBUG_DB_LOG', true);
// define('OSC_DEBUG_DB_EXPLAIN', true);

// PHP memory limit (ideally should be more than 128MB)
// define('OSC_MEMORY_LIMIT', 128);

// MemCache caching option (database queries cache)
// define('OSC_CACHE', 'memcache');
// $_cache_config[] = array('default_host' => 'localhost', 'default_port' => 11211, 'default_weight' => 1);

// Increase default login time for the user - in seconds (30 days)
// session_set_cookie_params(2592000);
// ini_set('session.gc_maxlifetime', 2592000);

// Lowers the password hashing mechanism from the default 15 to a safe 10, in order to improve performance
// define('BCRYPT_COST', 10);

?>