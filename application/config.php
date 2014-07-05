<?php
ini_set ( "display_errors", 1 );
error_reporting ( E_ALL & ~ E_NOTICE );
date_default_timezone_set ( 'Asia/Ho_Chi_Minh' );

$db ['host'] = '192.168.0.12';
$db ['db'] = 'tranhphongcanh';
$db ['user'] = 'nghia';
$db ['pass'] = 'SfoR6u';

define ( 'LOCALIZER', 'en' ); // en|vi (en)
define ( 'TRANSLATION_LOG', true );
define ( 'TRANSLATION_CACHE', true );
define ( 'CACHER', 'FILE' ); // FILE|MEMCACHE (FILE)
                             // define ( 'MEMCACHE_SERVER', '192.168.0.50' );
                             // //comment out if CACHER set to MEMCACHE
                             // define ( 'CACHER_CLEAR', true );
define ( 'HAS_MOBILE', false );
define ( 'ROUTES_CACHE', true );
define ( 'ROUTES_EXCLUDE_ADMIN', true ); // Exclude /admin directory
define ( 'DOMAIN', 'http://' . $_SERVER ['SERVER_NAME'] );
define ( 'DATA_DIR', 'd:/www/tranhphongcanh/public_html/uploads' );
define ( 'MAIN_SYSTEM', 'http://tranhphongcanh/' );