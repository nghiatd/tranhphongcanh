<?php

class Admin_ContactController extends Zend_Controller_Action {
	function init() {
		$Member = new My_Plugin_Auth ( $this->getRequest () );
		$this->Member = $_SESSION ['Member'];
	}
	
	private function _checkForm($form) {
		$error = array ();
		if (empty ( $form ['content'] ))
			$error [] = 'Chi tiết trả lời không thể để trống';
		return $error;
	}
	public function indexAction() {
		$this->view->Title = "Quản lý liên hệ";
		$this->view->headTitle ( $this->view->Title );
		
		list ( $this->view->Pager, $this->view->Contact ) = Contact::getAll ();
	}
	
	public function viewAction() {
		$this->view->Title = "Xem chi tiết";
		$this->view->headTitle ( $this->view->Title );
		$Contact = Contact::getById ( $this->getRequest ()->getParam ( 'id' ) );
		$this->view->Contact = $Contact;
		if ($this->getRequest ()->isPost ()) {
			$request = $this->getRequest ()->getParams ();
			$error = array ();
			if (empty ( $request ['content'] )) {
				$error [] = 'Chi tiết trả lời không thể để trống';
			} else {
				$message = $request ['content'];
				$oEmail = new My_Plugin_Email ();
				$oEmail->send ( 'Re: ' . $Contact->subject, $Contact->email, $Contact->name, $message );
				My_Plugin_Libs::setSplash ( 'Reply cho: <b>' . $Contact->name . '</b> đã được gửi. ' );
				$this->_redirect ( $this->_helper->url ( 'index', 'contact', 'admin' ) );
			}
		
		}
		if (count ( $error ))
			$this->view->error = $error;
	}
	/**
	 * Delete a Country
	 */
	public function deleteAction() {
		$Contact = Contact::getById ( $this->getRequest ()->getParam ( 'id' ) );
		if ($Contact) {
			if ($this->getRequest ()->isPost ()) {
				$Contact->delete ();
				$this->Member->log ( 'Delete contact :' . $Contact->name . '(' . $this->getRequest ()->getParam ( 'id' ) . ')', 'Contact' );
				My_Plugin_Libs::setSplash ( 'Phản hồi  "<b>' . $Contact->name . '</b>" đã được xóa. ' );
				$this->_redirect ( $this->_helper->url ( 'index', 'contact', 'admin' ) );
			}
			
			$this->view->Contact = $Contact;
		}
	}
}

