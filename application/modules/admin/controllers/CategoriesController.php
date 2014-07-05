<?php
class Admin_CategoriesController extends Zend_Controller_Action {
	
	function init() {
		// $Member = new My_Plugin_Auth ( $this->getRequest () );
		$this->Member = $_SESSION ['Member'];
	}
	
	private function _uploadFiles($uploadDir = '') {
		if (! is_dir ( $uploadDir )) {
			@mkdir ( $uploadDir );
			chmod ( $uploadDir, 0777 );
		}
		// upload thumb image
		$image = new Zend_Form_Element_File ( 'image' );
		$image->setDestination ( $uploadDir );
		$image->addValidator ( 'Count', false, 1 );
		$image->addValidator ( 'Extension', false, 'jpg,png,gif' );
		$image->addFilter ( 'Rename', array (
				'target' => $uploadDir . DIRECTORY_SEPARATOR . 'thumb.jpg',
				'overwrite' => true 
		) );
		$image->receive ();
		// uploads images for gallery
		$images = new Zend_Form_Element_File ( 'images' );
		$images->setDestination ( $uploadDir );
		// $element->addValidator ( 'Size', false, 512000 );
		$images->addValidator ( 'Extension', false, 'jpg,png,gif' );
		$images->setMultiFile ( count ( $_POST ['images'] ) );
		$images->receive ();
		
		return $images->getFileName ( null, false );
	}
	
	public function indexAction() {
		
		$this->view->Title = 'Quản lý danh mục';
		$this->view->headTitle ( $this->view->Title );
		$id = ( int ) $this->getRequest ()->getParam ( 'id' );
		
		$Category = ProductCategories::getById ( $id );
		
		$this->view->headTitle ( $this->viewTitle );
		$this->view->Categories = ProductCategories::getAll ( array (), $id );
	
	}
	
	public function createAction() {
		
		if ($this->getRequest ()->isPost ()) {
			$request = $this->getRequest ()->getParams ();
			$Category = new ProductCategories ();
			// if ($request ['conditions_group_id'])
			// $request ['conditions_group_id'] = ',' . implode ( ',', $request
			// ['conditions_group_id'] ) . ',';
			$Category->merge ( $request );
			if ($Category->title_plain == '')
				$Category->title_plain = My_Plugin_Libs::text2url ( $Category->title );
			
			if ($Category->trySave ()) {
				if ($_FILES) {
					$uploadDir = "uploads" . DIRECTORY_SEPARATOR . "productcate" . DIRECTORY_SEPARATOR . $Category->id . DIRECTORY_SEPARATOR;
					
					$images = $this->_uploadFiles ( $uploadDir );
				}
				ProductCategories::doUpdateChildrent ( $Category->product_categories_id );
				$this->Member->log ( 'Tạo Danh mục: ' . $Category->title . ' (' . $Category->id . ')', 'Categories' );
				My_Plugin_Libs::setSplash ( 'Danh mục <b>' . implode ( '<br>', $Category->title ) . '</b><br> đã được tạo thành công!' );
				// Categories::db2js ();
				
				$this->_redirect ( 'admin/categories?id=' . $Category->product_categories_id );
			}
		}
	}
	
	public function editAction() {
		$this->_helper->layout->disableLayout ();
		$this->_helper->viewRenderer->setNoRender ();
		$Category = ProductCategories::getById ( intval ( $this->getRequest ()->getParam ( 'id' ) ) );
		if ($this->getRequest ()->isPost ()) {
			$request = $this->getRequest ()->getParams ();
			$old_categories_id = $Category->product_categories_id;
			// if ($request ['conditions_group_id'])
			// $request ['conditions_group_id'] = ',' . implode ( ',', $request
			// ['conditions_group_id'] ) . ',';
			$Category->merge ( $request );
			// if ($Category->name_plain == '')
			$Category->title_plain = My_Plugin_Libs::text2url ( $Category->title );
			if ($Category->trySave ()) {
				// iểm tra nhóm cha để cập nhật lại childrent_id
				if ($_FILES) {
					$uploadDir = "uploads" . DIRECTORY_SEPARATOR . "productcate" . DIRECTORY_SEPARATOR . $Category->id . DIRECTORY_SEPARATOR;
					$images = $this->_uploadFiles ( $uploadDir );
				}
				// if ($old_categories_id != $Category->product_categories_id) {
				// ProductCategories::doUpdateChildrent ( $old_categories_id );
				// ProductCategories::doUpdateChildrent (
				// $Category->product_categories_id );
				// }
				// #
				$this->Member->log ( 'Sửa Danh mục: ' . $Category->title . ' (' . $Category->id . ')', 'Categories' );
				My_Plugin_Libs::setSplash ( 'Danh mục <b>' . $Category->title . '</b> đã được cập nhật thành công!' );
				// NewsCategories::db2js ();
				$this->_redirect ( 'admin/categories' );
			}
		}
		echo Zend_Json::encode ( $Category->toArray () ); // basically, $data
		                                                  // array will also be
		                                                  // available in the JS.
	}
	
	public function deleteAction() {
		//
		// print_r( $this->getRequest ()->getParam ( 'id' ));die;
		$id = intval ( $this->getRequest ()->getParam ( 'id' ) );
		$Category = ProductCategories::getById ( $id );
		if ($Category) {
			$title = $Category->title;
			$id = $Category->id;
			
			// $Categories = ProductCategories::getTreeOption ( array (), $id );
			if ($this->getRequest ()->isPost ()) {
				if ($Category->delete ()) {
					// foreach ( $Categories as $key => $title ) {
					// ProductCategories::getById ( $key )->delete ();
					// }
					$this->Member->log ( 'Xóa Danh mục: ' . $title . ' (' . $id . ')', 'Categories' );
					
					My_Plugin_Libs::setSplash ( 'Danh mục <b>' . $title . '</b> đã được xóa' );
				}
				// NewsCategories::db2js ();
				$this->_redirect ( 'admin/categories' );
			}
			$this->view->Category = $Category;
			$this->view->Categories = $Categories;
		
		}
	}
}
?>