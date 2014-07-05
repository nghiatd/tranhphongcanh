<?php

class Admin_AdvController extends Zend_Controller_Action {
	
	function init() {
		$Member = new My_Plugin_Auth ( $this->getRequest () );
		$this->Member = $_SESSION ['Member'];
	}
	
	public function indexAction() {
		$this->view->Title = "Danh sách quảng cáo";
		$this->view->headTitle ( $this->view->Title );
		$condition = array ();
		$filter = array ();
		
		if ($this->getRequest ()->getParam ( 'domain' )) {
			$filter ['domain'] = $this->getRequest ()->getParam ( 'domain' );
			$condition ['domains_id=?'] = $filter ['domain'];
		}
		if ($this->getRequest ()->getParam ( 'location' )) {
			$filter ['location'] = $this->getRequest ()->getParam ( 'location' );
			$condition ['location=?'] = $filter ['location'];
		}
		$this->view->filter = $filter;
		$page = $this->getRequest ()->getParam ( 'page' );
		list ( $this->view->Pager, $this->view->Adv ) = Ads::getAll ( $condition, $page, 10 );
	
	}
	
	public function advfileAction() {
		$this->view->Title = "Quản lý thư viện hình ảnh quảng cáo";
		$this->view->headTitle ( $this->view->Title );
		$dir = DATA_DIR . DIRECTORY_SEPARATOR . 'adv/';
		if ($this->getRequest ()->isPost ()) {
			
			$images = new Zend_Form_Element_File ( 'images' );
			//print_r($images);die();
			$images->setDestination ( 'uploads/adv/' );
			
			// $element->addValidator ( 'Size', false, 512000 );
			$images->addValidator ( 'Extension', false, 'jpg,png,gif,swf' );
			$images->setMultiFile ( count ( $_POST ['images'] ) );
			$images->receive ();
		}
		if ($this->getRequest ()->getParam ( 'delete' )) {
			@unlink ( $dir . '/' . $this->getRequest ()->getParam ( 'delete' ) );
		}
		//print_r($dir);die();
		$this->view->Adv = self::getFileDir ( $dir );
	}
	
	public function createAction() {
		$this->view->Title = "Tạo mới quảng cáo";
		$this->view->headTitle ( $this->view->Title );
		
		if ($this->getRequest ()->isPost ()) {
			//print_r($dir = DATA_DIR . DIRECTORY_SEPARATOR . 'adv');die();
			$request = $this->getRequest ()->getParams ();
			$Adv = new Ads ();
			$Adv->merge ( $request );
			$Adv->save ();
			
			
			$this->Member->log ( 'Create quảng cáo: ' . $Adv->link . '(' . $Adv->id . ')', 'Adv' );
			My_Plugin_Libs::setSplash ( ' Quảng cáo :<b>' . $Adv->link . '</b> đã được tạo' );
			// redirect to list
			$this->_redirect ( $this->_helper->url ( 'index', 'adv', 'admin' ) );
		
		}
		
		$dir = DATA_DIR . DIRECTORY_SEPARATOR . 'adv/';
		//print_r($dir);die();
		$this->view->Adv = self::getFileDir ( $dir );
	}
	
	public function editAction() {
		$Adv = Ads::getById ( $this->getRequest ()->getParam ( 'id' ) );
		$this->view->Title = "Sửa quảng cáo " . $Adv->link;
		$this->view->headTitle ( $this->view->Title );
		if ($this->getRequest ()->isPost ()) {
			$request = $this->getRequest ()->getParams ();
			$Adv->merge ( $request );
			$Adv->save ();
			$this->Member->log ( 'Edit quảng cáo: ' . $Adv->link . '(' . $Adv->id . ')', 'Adv' );
			My_Plugin_Libs::setSplash ( 'Quảng cáo <b>' . $Adv->link . '</b> đã được lưu lại thành công' );
			
			$this->_redirect ( $this->_helper->url ( 'index', 'adv', 'admin' ) );
		
		}
		$this->view->Adv = $Adv;
		$dir = DATA_DIR . DIRECTORY_SEPARATOR . 'adv';
		
		$this->view->Advs = self::getFileDir ( $dir );
	}
	
	public function deleteAction() {
		$this->view->Title = "Xóa quảng cáo";
		$this->view->headTitle ( $this->view->Title );
		$dir = DATA_DIR . DIRECTORY_SEPARATOR . 'adv/';
		$Adv = Ads::getById ( $this->getRequest ()->getParam ( 'id' ) );
		if ($Adv) {
			$name = $Adv->link;
			if ($this->getRequest ()->isPost ()) {
				$Adv->delete ();
				$this->Member->log ( 'Delete quảng cáo: ' . $name . '(' . $this->getRequest ()->getParam ( 'id' ) . ')', 'Adv' );
				rmdir ( $dir );
				// redirect to list
				$this->_redirect ( $this->_helper->url ( 'index', 'adv', 'admin' ) );
			}
			$this->view->Adv = $Adv;
		}
	
	}
	
	private function getFileDir($dir) {
		
		$Adv = scandir ( $dir );
		foreach ( $Adv as $key => $file ) {
			if (in_array ( $file, array (
					'.',
					'..',
					'.svn',
					'thumb.db' 
			) )) {
				unset ( $Adv [$key] );
				continue;
			}
			$Adv [$key] = My_Plugin_Libs::fileDetail ( $dir . '/' . $file );
		}
		
		return (array) $Adv;
	}

}

