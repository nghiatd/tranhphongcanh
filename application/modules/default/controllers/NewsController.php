<?php

class NewsController extends Zend_Controller_Action {
	
	public function indexAction() {
		$this->view->headTitle ( 'Tin tức' );
		$page = 1;
		if ($this->getRequest ()->getParam ( 'page' ) != '') {
			$page = $this->getRequest ()->getParam ( 'page' );
			$next = $Requests ['next'];
			$prev = $Requests ['prev'];
		}
		list ( $this->view->Pager, $this->view->News ) = Contents::getAll ( array (
				'categories_id=?' => 1,
				'status=?' => 1 
		), $page, Zend_Registry::get ( 'Setting' )->TIN_TUC );
		
		$youarehere [] = array (
				'name' => 'Tin tức' 
		);
		// You Are Here
		$this->view->YouAreHere = $youarehere;
		
	}
	
	public function detailAction() {
		$News = Contents::getByNamePlain ( $this->getRequest ()->getParam ( 'id' ) );
		if ($News) {
			$this->view->headTitle ( $News->title );
			$this->view->News = $News;
			$this->view->NewsList = Contents::getWithOutPage ( array (
					'id!=?' => $News->id,
					'categories_id=?' => 1,
					'status=?' => 1 
			), 5 );
			$this->view->NewsKienthuc = Contents::getWithOutPage ( array (
					'categories_id=?' => 2,
					'status=?' => 1 
			), 5 );
			$this->view->NewsSale = Contents::getWithOutPage ( array (
					'categories_id=?' => 3,
					'status=?' => 1 
			), 1 );
			
			$youarehere [] = array (
					'name' => 'Tin tức',
					'link' => 'tin-tuc.html' 
			);
			$youarehere [] = array (
					'name' => $News->title_plain,
					'link' => $News->getLink () 
			);
			// You Are Here
			$this->view->YouAreHere = $youarehere;
		} else {
			$this->_redirect ( '.' );
		}
		
	}
	public function khachhangAction() {
		$page = $this->getRequest ()->getParam ( 'page' );
		$condition = array (
				'status=?' => 1,
				'categories_id=?' => 3 
		);
		$this->view->headTitle ( 'Khách hàng' );
		$id = $this->getRequest ()->getParam ( 'id' );
		$Content = Contents::getByNamePlain ( $id );
		
		list ( $this->view->Pager, $this->view->Products ) = Contents::getAll ( $condition, $page, Zend_Registry::get ( 'Setting' )->KHACH_HANG );
		$youarehere [] = array (
				'name' => 'Khách hàng' 
		);
		$this->view->YouAreHere = $youarehere;
		
	}
	public function khachhangdetailAction() {
		
		$Content = Contents::getByNamePlain ( $this->getRequest ()->getParam ( 'id' ) );
		$this->view->Khachhang = $Content;
		
		// print_r($Content);die;
		$this->view->headTitle ( 'Khách hàng' );
		
		$youarehere [] = array (
				'name' => 'Khách hàng',
				'link' => 'khach-hang.html' 
		);
		if ($Content) {
			$youarehere [] = array (
					'name' => $Content->title 
			);
		}
		$this->view->YouAreHere = $youarehere;
		
	}
	public function khuyenmaiAction() {
		$this->view->headTitle ( 'Khuyến mãi' );
		$page = 1;
		if ($this->getRequest ()->getParam ( 'page' ) != '') {
			$page = $this->getRequest ()->getParam ( 'page' );
		}
		list ( $this->view->Pager, $this->view->NewsKienthuc ) = Contents::getAll ( array (
				'categories_id=?' => 2,
				'status=?' => 1 
		), $page, Zend_Registry::get ( 'Setting' )->KHUYEN_MAI );
		$this->view->News = Contents::getWithOutPage ( array (
				'categories_id=?' => 1,
				'status=?' => 1 
		), 5 );
		$this->view->NewsSale = Contents::getWithOutPage ( array (
				'categories_id=?' => 3,
				'status=?' => 1 
		), 1 );
		$youarehere [] = array (
				'name' => 'Khuyến mãi' 
		);
		// You Are Here
		$this->view->YouAreHere = $youarehere;
		
	}
	public function khuyenmaidetailAction() {
		$News = Contents::getByNamePlain ( $this->getRequest ()->getParam ( 'id' ) );
		if ($News) {
			$this->view->headTitle ( $News->title );
			$this->view->NewsKienthuc = $News;
			$this->view->NewsList = Contents::getWithOutPage ( array (
					'id!=?' => $News->id,
					'categories_id=?' => 2,
					'status=?' => 1 
			), 5 );
			$this->view->News = Contents::getWithOutPage ( array (
					'categories_id=?' => 1,
					'status=?' => 1 
			), 5 );
			$this->view->NewsSale = Contents::getWithOutPage ( array (
					'categories_id=?' => 3,
					'status=?' => 1 
			), 1 );
			$youarehere [] = array (
					'name' => 'Khuyến mãi',
					'link' => 'khuyen-mai.html' 
			);
			$youarehere [] = array (
					'name' => $News->title_plain,
					'link' => $News->getLinkKhuyenmai () 
			);
			// You Are Here
			$this->view->YouAreHere = $youarehere;
		} else {
			$this->_redirect ( '.' );
		}
		
	}
}