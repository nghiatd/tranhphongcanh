<?php
class Admin_ImagesController extends Zend_Controller_Action {
	private function _uploadFiles($uploadDir = '', $id) {
		if (! is_dir ( $uploadDir ))
			mkdir ( $uploadDir );
			
			// upload thumb image
			// uploads images for gallery
		$images = new Zend_Form_Element_File ( 'file' );
		$images->setDestination ( $uploadDir );
		// $element->addValidator ( 'Size', false, 512000 );
		$images->addValidator ( 'Extension', false, 'jpg,png,gif' );
		$images->addFilter ( 'Rename', array (
				'target' => $uploadDir . DIRECTORY_SEPARATOR . $id . '.jpg',
				'overwrite' => true 
		) );
		$images->setMultiFile ( count ( $_POST ['file'] ) );
		$images->receive ();
		return $images->getFileName ( null, false );
	}
	
	public function indexAction() {
		$this->view->Title = "Danh sách hình ảnh";
		$this->view->headTitle ( $this->view->Title );
		$condition = array ();
		// print_r(Images::getAll ( $condition ));
		$this->view->Music = Images::getAll ( $condition );
	}
	
	public function _checkForm($form) {
		$error = array ();
	}
	public function createAction() {
		$this->view->Title = "Thêm mới hình ảnh";
		$this->view->headTitle ( $this->view->Title );
		if ($this->getRequest ()->isPost ()) {
			$request = $this->getRequest ()->getParams ();
			unset ( $request ['id'] );
			$music = new Images ();
			$music->merge ( $request );
			$music->created_date = date ( 'Y-m-d H:i' );
			if ($_FILES) {
				$uploadDir = "uploads/images/";
				$music->file = $this->_uploadFiles ( $uploadDir, $music->id );
			
			}
			if ($music->trySave ()) {
				
				// $this->Member->log ( 'Create Room: ' . $News->name . '(' .
				// $News->id . ')', 'News' );
				My_Plugin_Libs::setSplash ( 'Ảnh <b>' . $music->file . '</b> đã được tạo. ' );
				// redirect to list
				$this->_redirect ( $this->_helper->url ( 'index', 'images', 'admin' ) );
			}
		}
	}
	public function editAction() {
		$music = Images::getById ( $this->getRequest ()->getParam ( 'id' ) );
		$this->view->Title = "Sửa hình ảnh";
		$this->view->headTitle ( $this->view->Title );
		if ($this->getRequest ()->isPost ()) {
			$request = $this->getRequest ()->getParams ();
			// print_r($_FILES['file']['name']);
			if ($_FILES ['file'] ['name']) {
				$uploadDir = "uploads/images/";
				$images = $this->_uploadFiles ( $uploadDir, $music->id );
				$music->file = $images;
			
			}
			// $music->file = $images;
			$music->created_date = date ( 'Y-m-d H:i' );
			// ập nhật lại kho ảnh hiện tại
			
			$error = $this->_checkForm ( $request );
			if (count ( $error ) == 0) {
				$music->merge ( $request );
				if ($music->trySave ()) {
					// $this->Member->log ( 'Chỉnh sửa tin ' . $Room->name .
					// '(' . $Room->id . ')', 'Room' );
					My_Plugin_Libs::setSplash ( 'Hình ảnh <b>' . $music->file . '</b> đã được cập nhật' );
					// redirect to list
					$this->_redirect ( $this->_helper->url ( 'index', 'images', 'admin' ) );
				} else
					$error [] = 'Thao tác lưu dữ liệu không thành công. Xin hãy thử lại';
			}
			if (count ( $error ))
				$this->view->error = $error;
		}
		
		$this->view->music = $music;
	}
	public function deleteAction() {
		$this->view->Title = "Xóa hình ảnh";
		$this->view->headTitle ( $this->view->Title );
		$Music = Images::getById ( $this->getRequest ()->getParam ( 'id' ) );
		if ($Music) {
			if ($this->getRequest ()->isPost ()) {
				$Music->delete ();
				
				$this->_redirect ( $this->_helper->url ( 'index', 'images', 'admin' ) );
			}
			$this->view->music = $Music;
		}
	
	}
}