<?php

class Admin_AjaxController extends Zend_Controller_Action {
	
	function init() {
		if (! $_SESSION ['Member'])
			$Member = new My_Plugin_Auth ( $this->getRequest () );
		$this->Member = $_SESSION ['Member'];
	}
	
	function preDispatch() {
		$this->_helper->layout->disableLayout ();
		$this->_helper->viewRenderer->setNoRender ();
	}
	public function getpathofcategoryAction() {
		$this->_helper->viewRenderer->setNoRender ( false );
		
		$Category = ( int ) $this->getRequest ()->getParam ( 'category' );
		$Categories = Categories::getParentTree ( $Category );
		$Categories = array_reverse ( $Categories );
		$this->view->Category = $Category;
		$this->view->index = ( int ) $this->getRequest ()->getParam ( 'index' );
		$this->view->Categories = $Categories;
	}
	public function getcitiesbycountryAction() {
		$id = $this->getRequest ()->getParam ( 'country' );
		echo Zend_Json::encode ( City::getOption ( $id, array ('--Select--' ) ) );
	}
	public function getcitiesbycountrytransportAction() {
		$id = $this->getRequest ()->getParam ( 'country' );
		echo Zend_Json::encode ( City::getOptionTransport ( $id, array ('--Select--' ) ) );
	}
	public function getlocationbycityAction() {
		$id = $this->getRequest ()->getParam ( 'city' );
		echo Zend_Json::encode ( Location::getOption ( $id, array ('--Select--' ) ) );
	}
	
	public function getlisthotelierAction() {
		$Hotelier = Customers::getHotelier ( array (), 'id,username,firstname,lastname', DOCTRINE::HYDRATE_ARRAY );
		echo Zend_Json::encode ( $Hotelier );
	}
	
	public function createhotelierAction() {
		if ($this->getRequest ()->isPost ()) {
			$request = $this->getRequest ()->getParams ();
			$test = Customers::getByEmail ( $request ['email'] );
			if (! $test) {
				$Customer = new Customers ();
				$Customer->merge ( $request );
				$Customer->setPassword ( $this->password );
				$Customer->customer_type = 1;
				$Customer->created_date = date ( 'Y-m-d H:i:s' );
				if ($Customer->trySave ()) {
					$this->Member->log ( 'Create Hotelier: ' . $Customer->getName () . '(' . $Customer->id . ')', 'Customers' );
					echo Zend_Json::encode ( array ('code' => 'SUCCESS', 'item' => Zend_Json::encode ( $Customer->toArray () ) ) );
				}
			} else
				echo Zend_Json::encode ( 'Email này đã được sử dụng, vui lòng chọn email khác' );
		}
	}
	
	public function salerseachAction() {
		$keyword = strtolower ( $this->getRequest ()->getParam ( 'term' ) );
		$response = array ();
		$numOfItem = Zend_Registry::get ( 'Setting' )->autocomplete_limit;
		list ( , $aRespon ) = Members::getAll ( array ('LOWER(name)|| LOWER(email) LIKE ?' => array ("%{$keyword}%" ) ), 1, $numOfItem );
		foreach ( $aRespon as $Obj ) {
			$response [] = array ('id' => $Obj->id, 'label' => $Obj->getName (), 'value' => $Obj->getName () );
		}
		echo json_encode ( $response );
	}
	
	public function hotelseachAction() {
		$keyword = strtolower ( $this->getRequest ()->getParam ( 'term' ) );
		$response = array ();
		$numOfItem = Zend_Registry::get ( 'Setting' )->autocomplete_limit;
		list ( , $aRespon ) = Hotels::getAll ( array ('LOWER(name) LIKE ?' => array ("%{$keyword}%" ) ), 1, $numOfItem );
		foreach ( $aRespon as $Obj ) {
			$response [] = array ('id' => $Obj->id, 'label' => $Obj->name, 'value' => $Obj->name );
		}
		echo json_encode ( $response );
	}
	
	public function customersearchAction() {
		$keyword = strtolower ( $this->getRequest ()->getParam ( 'term' ) );
		$response = array ();
		$numOfItem = Zend_Registry::get ( 'Setting' )->autocomplete_limit;
		list ( , $aRespon ) = Customers::getAll ( array ('LOWER(username) LIKE ?' => array ("%{$keyword}%" ) ), 1, $numOfItem );
		foreach ( $aRespon as $Obj ) {
			$response [] = array ('id' => $Obj->id, 'label' => $Obj->username, 'value' => $Obj->username );
		}
		echo json_encode ( $response );
	}
	
	public function visaseachAction() {
		$keyword = strtolower ( $this->getRequest ()->getParam ( 'term' ) );
		$response = array ();
		$numOfItem = Zend_Registry::get ( 'Setting' )->autocomplete_limit;
		list ( , $aRespon ) = VisaType::getAll ( array ('LOWER(name) LIKE ?' => array ("%{$keyword}%" ) ), 1, $numOfItem );
		foreach ( $aRespon as $Obj ) {
			$response [] = array ('id' => $Obj->id, 'label' => $Obj->name, 'value' => $Obj->name );
		}
		echo json_encode ( $response );
	}
	
	public function getdefaultAction() {
		$this->_helper->viewRenderer->render ();
	}
	public function roomconditionAction() {
		$Room = Rooms::getById ( $this->getRequest ()->getParam ( 'id' ) );
		if ($this->getRequest ()->isPost ()) {
			$Room->conditions_detail = $this->getRequest ()->getParam ( 'condition' );
			$Room->save ();
			echo 'Success';
		} else
			echo json_encode ( array ('condition' => $Room->conditions_detail, 'id' => $Room->id ) );
	}
	public function checktitleAction() {
		$response = array ();
		$title = $this->getRequest ()->getParam ( 'title' );
		$id = $this->getRequest ()->getParam ( 'id' );
		$title = My_Plugin_Libs::text2url ( $title );
		$Hotels = Hotels::getByNamePlain ( $title, array ('id!=?' => $id ) );
		if ($Hotels) {
			$response = array ('code' => 'Error', 'name_plain' => $title );
		} else {
			$response = array ('code' => 'Success', 'name_plain' => $title );
		}
		echo Zend_Json::encode ( $response );
	}
	public function checktitleplainAction() {
		$response = array ();
		$title = $this->getRequest ()->getParam ( 'title' );
		$id = $this->getRequest ()->getParam ( 'id' );
		
		$Hotels = Hotels::getByNamePlain ( $title, array ('id!=?' => $id ) );
		if ($Hotels) {
			$response = array ('code' => 'Error', 'name_plain' => $title );
		} else {
			$response = array ('code' => 'Success', 'name_plain' => $title );
		}
		echo Zend_Json::encode ( $response );
	}
	public function removeadvAction() {
		$id = $this->getRequest ()->getParam ( 'adv_id' );
		Ads::DeleteById ( $id );
		die ( json_encode ( array ('code' => 0 ) ) );
	}
	public function addadvAction() {
		if (( int ) ($this->getRequest ()->getParam ( 'id' )) == FALSE) {
			$Ads = new Ads ();
		} else {
			$Ads = Ads::getById ( ( int ) ($this->getRequest ()->getParam ( 'id' )) );
		}
		$Ads->link = $this->getRequest ()->getParam ( 'link' );
		$Ads->file = $this->getRequest ()->getParam ( 'file' );
		$Ads->width = $this->getRequest ()->getParam ( 'width' );
		$Ads->height = $this->getRequest ()->getParam ( 'height' );
		$Ads->status = 1;
		$Ads->domains_id = 1;
		//print_r($Ads->toArray());	die;
		$Ads->save ();
		die ( json_encode ( array ('code' => 0, 'img' => $Ads->file ) ) );
	}
}

