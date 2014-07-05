<?php
class HtmlController extends Zend_Controller_Action {
	
	public function youarehereAction() {
		$this->view->YouAreHere = $this->getRequest ()->getParam ( 'YouAreHere' );
	}

}