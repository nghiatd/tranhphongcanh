<?php

class Admin_IndexController extends Zend_Controller_Action {
	
	function init() {
		if (! $_SESSION ['Member']->username)
			$this->_redirect ( 'admin/member/login' );
	}
	
	public function indexAction() {
		// print_r ( $_SESSION ['Role'] );
	}

}

