<?php

class UserController extends Zend_Controller_Action {
	
	public function registerAction() {
		$requests = $this->getRequest ()->getParams ();
		$json = array (
				'STATUS' => 'FALSE' 
		);
		$User = new Users ();
		$User->merge ( $requests );
		$User->password = md5 ( $requests ['repassword'] );
		$User->created_date = date ( 'Y-m-d H:i:s' );
		$User->status = 1;
		if ($User->trySave ()) {
			$json ['STATUS'] = 'TRUE';
		}
		header ( 'Content-type: application/json' );
		echo json_encode ( $json );
		die ();
	}
	public function loginAction() {
		$requests = $this->getRequest ()->getParams ();
		$json = array (
				'STATUS' => 'FALSE' 
		);
		$User = Users::getWithOutPage ( array (
				'username=?' => $requests ['username'],
				'password=?' => md5 ( $requests ['password'] ),
				'status=?' => 1 
		) );
		if ($User->count () == 1) {
			$json ['STATUS'] = 'TRUE';
			$_SESSION ['User'] = $User->getFirst ();
			$json ['URL'] = DOMAIN;
		}
		header ( 'Content-type: application/json' );
		echo json_encode ( $json );
		die ();
	}

}