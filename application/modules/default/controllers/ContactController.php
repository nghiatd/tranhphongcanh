<?php

class ContactController extends Zend_Controller_Action {

	private function _checkForm($form) {
		if (empty ( $form ['name'] ))
			$error [] = 'Họ tên không được để trống';
		if (empty ( $form ['address'] ))
			$error [] = 'Địa chỉ không được để trống';
		if (empty ( $form ['phone'] ))
			$error [] = 'Điện thoại không được để trống';
		if (empty ( $form ['email'] ))
			$error [] = 'Email không được để trống';
		if (empty ( $form ['subject'] ))
			$error [] = 'Nội dung liên hệ không được để trống';
		if (empty ( $form ['content'] ))
			$error [] = 'Nội dung liên hệ không được để trống';
		
		if ($form ['email']) {
			$validator = new Zend_Validate_EmailAddress ();
			if (! $validator->isValid ( $form ['email'] ))
				$error [] = 'Sai định dạng email';
		}
		return $error;
	}
	public function indexAction() {
		$this->view->headTitle ( 'Liên hệ' );

		if ($this->getRequest ()->isPost ()) {
			$request = $this->getRequest ()->getParams ();
			$error = $this->_checkForm ( $request );
			if (count ( $error ) == 0) {
				$Contact = new Contact ();
				$Contact->merge ( $request );
				$Contact->created_date = date ( 'Y-m-d H:i:s' );
				$Contact->ip = $_SERVER ['REMOTE_ADDR'];
				$Contact->save ();
				$error = array ('Gửi liên hệ thành công!' );
			}
			if (count ( $error ))
				$this->view->error = $error;
		}
		$youarehere [] = array ('name' => 'Liên hệ');
		// You Are Here
		$this->view->YouAreHere = $youarehere;
		
		
		
	}
}