<?php

class ContentController extends Zend_Controller_Action {
	
	public function indexAction() {
		$Content = Contents::getById (13);
		//print_r($this->getRequest()->getParams());die();
		$this->view->headTitle ( 'Giới thiệu' );
		$this->view->Content = $Content;
		$youarehere [] = array (
				'name' => 'Giới thiệu' 
		);
		$this->view->YouAreHere = $youarehere;
		
	}

}