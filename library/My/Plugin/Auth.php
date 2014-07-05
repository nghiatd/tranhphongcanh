<?php
class My_Plugin_Auth extends Zend_Controller_Plugin_Abstract {

	public function __construct($request) {
		if (count ( $request->getParams () ) == 0)
			return;
		if ($_SESSION ['Member'])
			return true;
		$controller = strtolower ( $request->controller );
		$action = strtolower ( $request->action );
		$module = strtolower ( $request->module );
		if ($_SESSION ['Role'] == '*')
			return true;
		
		//if ($_SESSION ['Role'] [$module] [$controller] [$action] != 1) {
		//	if (Groups::doCheckRole ( $module, $controller, $action, $_SESSION ['Role'] ))
		//		return true;
		//	if ($_SESSION ['Member'])
		//		$_SESSION ['Member']->log ( 'Access denied: ' . $controller . '-' . $action, $controller );
		My_Plugin_Libs::setSplash ( 'Bạn không đươc quyền truy cập phân mục đó. Hay liên hệ người quản lý cao nhất', 'error' );
		$_SESSION ['REDIRECT'] = $_SERVER ['REQUEST_URI'];
		header ( 'location: /admin' );
	
		//}
	

	}
}