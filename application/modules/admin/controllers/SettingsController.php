<?php

class Admin_SettingsController extends Zend_Controller_Action {
	
	function init() {
		$Member = new My_Plugin_Auth ( $this->getRequest () );
		$this->Member = $_SESSION ['Member'];
	}
	
	/**
	 * List Countries
	 */
	public function indexAction() {
		$this->view->Title = Zend_Registry::get ( 'Zend_Translate' )->translate ( "Settings_module_title" );
		$this->view->headTitle ( $this->view->Title );
		if ($this->getRequest ()->isPost ()) {
			$data = $this->getRequest ()->getParam ( 'Setting' );
			Settings::doUpdate ( $data );
			// clear cache
			Zend_Registry::get ( 'Cacher' )->remove ( 'Setting' );
			My_Plugin_Libs::setSplash ( Zend_Registry::get ( 'Zend_Translate' )->translate ( "New settings saved" ) );
		}
		$setting_tab_controller = explode ( '|', Zend_Registry::get ( 'Zend_Translate' )->translate ( "Settings_tab_controller" ) );
		$this->view->Tab_Controller = $setting_tab_controller;
		$this->view->Settings = Settings::getAll ();
	}
}

