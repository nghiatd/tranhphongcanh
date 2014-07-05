<?php
class Admin_ProducttypeController extends Zend_Controller_Action {
	
	function init() {
		// $Member = new My_Plugin_Auth ( $this->getRequest () );
		$this->Member = $_SESSION ['Member'];
	}
	
	public function indexAction() {
		$this->view->Title = 'Quản lý loại danh mục';
		$this->view->headTitle ( $this->view->Title );
		$this->view->headTitle ( $this->viewTitle );
		list ( $this->view->Pager, $this->view->ProductTypes ) = ProductType::getAll ( array () );
	}
	
	public function createAction() {
		if ($this->getRequest ()->isPost ()) {
			$request = $this->getRequest ()->getParams ();
			$ProductType = new ProductType ();
			$ProductType->merge ( $request );
			$ProductType->name_plain = My_Plugin_Libs::text2url ( $request ['name'] );
			if ($ProductType->trySave ()) {
				$this->Member->log ( 'Tạo Loại Danh mục: ' . $ProductType->name . ' (' . $ProductType->id . ')', 'ProductType' );
				My_Plugin_Libs::setSplash ( 'Loại Danh mục <b>' . implode ( '<br>', $ProductType->name ) . '</b><br> đã được tạo thành công!' );
				$this->_redirect ( 'admin/producttype' );
			}
		}
	}
	
	public function editAction() {
		$this->_helper->layout->disableLayout ();
		$this->_helper->viewRenderer->setNoRender ();
		$ProductType = ProductType::getById ( intval ( $this->getRequest ()->getParam ( 'id' ) ) );
		if ($this->getRequest ()->isPost ()) {
			$request = $this->getRequest ()->getParams ();
			$ProductType->merge ( $request );
			// if ($Category->name_plain == '')
			$ProductType->name_plain = My_Plugin_Libs::text2url ( $request ['name'] );
			if ($ProductType->trySave ()) {
				
				$this->Member->log ( 'Sửa Loại Danh mục: ' . $ProductType->name . ' (' . $ProductType->id . ')', '$ProductType' );
				My_Plugin_Libs::setSplash ( 'Loại Danh mục <b>' . $ProductType->name . '</b> đã được cập nhật thành công!' );
				// NewsCategories::db2js ();
				$this->_redirect ( 'admin/producttype' );
			}
		}
		echo Zend_Json::encode ( $ProductType->toArray () ); // basically, $data
			                                                     // array will also be
			                                                     // available in the
			                                                     // JS.
	}
	
	public function deleteAction() {
		// print_r( $this->getRequest ()->getParam ( 'id' ));die;
		$id = intval ( $this->getRequest ()->getParam ( 'id' ) );
		$ProductType = ProductType::getById ( $id );
		if ($ProductType) {
			$title = $ProductType->name;
			$id = $ProductType->id;
			if ($this->getRequest ()->isPost ()) {
				if ($ProductType->delete ()) {
					$this->Member->log ( 'Xóa Loại Danh mục: ' . $title . ' (' . $id . ')', '$ProductType' );
					
					My_Plugin_Libs::setSplash ( 'Loại Danh mục <b>' . $title . '</b> đã được xóa' );
				}
				// NewsCategories::db2js ();
				$this->_redirect ( 'admin/producttype' );
			}
			$this->view->ProductType = $ProductType;
		}
	}
}
?>