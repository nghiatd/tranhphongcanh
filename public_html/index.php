<?php

// ob_start ( "ob_gzhandler" );
require realpath ( '../application' ) . '/init.php';
/**
 * Zend_Application tdb
 */
require_once 'Zend/Application.php';
$application = new Zend_Application ( APPLICATION_ENV, APPLICATION_PATH . 'configs/application.ini' );
Zend_Session::start ();
$application->bootstrap ()->run ();
