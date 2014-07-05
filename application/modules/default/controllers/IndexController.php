<?php

class IndexController extends Zend_Controller_Action {
	
	
	public function indexAction() {
		
		$this->view->re=$this->getRequest()->getParams();
		$this->view->headTitle ( 'Trang chủ' );
		// $this->view->headMeta ()->appendName ( 'keywords',
		// Zend_Registry::get('Setting')->KEYWORD)->appendName ( 'description',
		// Zend_Registry::get('Setting')->DESCRIPTION );
		$this->view->ProductsNew = Products::getWithOutPage ( array (
				'is_new=?' => 1,
				'status=?' => 1 
		), 10 );
		$this->view->ProductsSale = Products::getWithOutPage ( array (
				'is_sale_off>?' => 0,
				'status=?' => 1 
		), 10 );
		
		$this->view->NewsKienthuc = Contents::getWithOutPage ( array (
				'categories_id=?' => 2,
				'status=?' => 1 
		), 5 );
		$this->view->NewsSale = Contents::getWithOutPage ( array (
				'categories_id=?' => 3,
				'status=?' => 1 
		), 1 );
		
	}
	public function testAction(){
		
	}
}