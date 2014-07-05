<?php

class Admin_MemberController extends Zend_Controller_Action {
	
	function init() {
		// if (! in_array ( $this->getRequest ()->getParam ( 'action' ), array
		// ('login', 'forgot' ) )) {
		// $Member = new My_Plugin_Auth ( $this->getRequest () );
		$this->Member = $_SESSION ['Member'];
		
		// }
	}
	
	/**
	 * List Customer
	 */
	public function indexAction() {
		$this->view->Title = "Danh sách Thành viên";
		$this->view->headTitle ( $this->view->Title );
		// Filter Condition
		$condition = array ();
		// Filter by Keyword
		if ($this->getRequest ()->getParam ( 'keyword' )) {
			switch ($this->getRequest ()->getParam ( 'condition' )) {
				case 'name' :
					$condition ['name LIKE ?'] = "%{$this->getRequest ()->getParam ( 'keyword' )}%";
					break;
				case 'address' :
					$condition ['address LIKE ?'] = "%{$this->getRequest ()->getParam ( 'keyword' )}%";
					break;
				case 'email' :
					$condition ['email=?'] = $this->getRequest ()->getParam ( 'keyword' );
					break;
			}
		}
		// if ($this->getRequest ()->getParam ( 'type' ) > 0) {
		// $condition ['member_type =?'] = $this->getRequest ()->getParam (
		// 'type' );
		// }
		$page = $this->getRequest ()->getParam ( 'page' );
		list ( $this->view->Pager, $this->view->Members ) = Members::getAll ( $condition, $page );
	}
	
	/**
	 * Create a hotel
	 */
	public function createAction() {
		$this->view->Title = "Tạo thêm thành viên";
		$this->view->headTitle ( $this->view->Title );
		// $Member = Members::getById ( $this->getRequest ()->getParam ( 'id' )
		// );
		$Member = new Members ();
		if ($this->getRequest ()->isPost ()) {
			$request = $this->getRequest ()->getParams ();
			$error = self::_checkform ( $request );
			if (! $error) {
				$Member->merge ( $request );
				$Member->username = $Member->email;
				$this->password = $request ['password'];
				$Member->encodePassword ();
				$Member->created_date = date ( 'Y-m-d H:i:s' );
				$Member->save ();
				// $this->Member->log ( 'Add member:' . $Member->getName () .
				// '(' . $Member->id . ')', 'Member' );
				My_Plugin_Libs::setSplash ( 'Thành viên:<b>' . $Member->getName () . '</b>đã được thêm thành công.' );
				$this->_redirect ( $this->_helper->url ( 'index', 'member', 'admin' ) );
			
			} else {
				$this->view->error = $error;
			}
		}
		$this->view->Member = $Member;
	}
	
	/**
	 * Edit a hotel
	 */
	public function editAction() {
		$this->view->Title = "Chỉnh sửa thông tin thành viên";
		$this->view->headTitle ( $this->view->Title );
		$Member = Members::getById ( $this->getRequest ()->getParam ( 'id' ) );
		
		if ($this->getRequest ()->isPost ()) {
			$request = $this->getRequest ()->getParams ();
			$error = self::_checkformedit ( $request );
			if (! $error) {
				if ($request ['password'] == '') {
					unset ( $request ['password'] );
				}
				$Member->merge ( $request );
				$Member->username = $Member->email;
				if ($request ['password']) {
					$Member->encodePassword ();
				}
				$Member->save ();
				// $this->Member->log ( 'Edit member:' . $Member->getName () .
				// '(' . $Member->id . ')', 'Member' );
				My_Plugin_Libs::setSplash ( 'Thành viên:<b>' . $Member->getName () . '</b>đã được sửa thành công.' );
				$this->_redirect ( $this->_helper->url ( 'index', 'member', 'admin' ) );
			} else {
				$this->view->error = $error;
			}
		}
		$this->view->Member = $Member;
	}
	
	public function historyAction() {
		$this->view->Title = "Nhật ký hoạt động của thành viên";
		$this->view->headTitle ( $this->view->Title );
		if ($this->getRequest ()->getParam ( 'id' ) != '') {
			$id = $this->getRequest ()->getParam ( 'id' );
			
			$file_path = APPLICATION_PATH . 'data/member_' . $id . '.log.csv';
			if (file_exists ( $file_path )) {
				$fp = file ( $file_path );
				$lines = count ( $fp );
				
				$linesPerPage = 50;
				$totalPages = ceil ( $lines / $linesPerPage );
				$page = ( int ) $this->getRequest ()->getParam ( 'page' );
				$page = ($page) ? $page : 1;
				$cLine = ($page * $linesPerPage) - $linesPerPage;
				$this->view->TotalPage = $totalPages;
				$this->view->Pager = $page;
				$this->view->id = $id;
				
				$aLogs = array_slice ( $fp, $cLine, $linesPerPage );
				unset ( $fp );
				$fields = array (
						'time',
						'ip',
						'section',
						'action' 
				);
				foreach ( $aLogs as $i => $log ) {
					$log = explode ( ',', str_replace ( '"', '', $log ) );
					foreach ( $fields as $key => $field ) {
						$log [$field] = $log [$key];
						unset ( $log [$key] );
					}
					$aLogs [$i] = $log;
				}
				$this->view->Log = $aLogs;
			
			} else {
				$this->view->error = 'Thành viên này chưa có bất kỳ hoạt động nào cần theo dõi';
			}
		
		}
	}
	
	public function deleteAction() {
		$this->view->Title = "Xóa thành viên";
		$this->view->headTitle ( $this->view->Title );
		$Member = Members::getById ( $this->getRequest ()->getParam ( 'id' ) );
		
		if ($Member) {
			if ($this->getRequest ()->isPost ()) {
				$this->Member->log ( 'Delete member:' . $Member->name . '(' . $Member->id . ')', 'Member' );
				$Member->delete ();
				
				// redirect to list
				$this->_redirect ( $this->_helper->url ( 'index', 'member', 'admin' ) );
			}
			$this->view->Member = $Member;
		}
	
	}
	
	public function loginAction() {
		$this->_helper->layout->disableLayout ();
		if ($this->getRequest ()->isPost ()) {
			$email = $this->getRequest ()->getParam ( 'username' );
			$password = $this->getRequest ()->getParam ( 'password' );
			$Members = Members::setLogin ( $email, $password );
			if ($Members) {
				$_SESSION ['Member'] = $Members;
				// $_SESSION ['Role'] = Zend_Json::decode ( Groups::getById (
				// $Members->member_type )->role );
				$redirect = $_SESSION ['REDIRECT'];
				if ($redirect)
					unset ( $_SESSION ['REDIRECT'] );
				$this->_redirect ( 'admin' );
			} else {
				$this->view->error = true;
			}
		}
	}
	
	public function logoutAction() {
		$this->Member->log ( 'Signed out', 'Members' );
		$this->Member->setLastLogin ();
		$storage = new Zend_Auth_Storage_Session ();
		$storage->clear ();
		unset ( $_SESSION ['Member'], $_SESSION ['Role'] );
		session_destroy ();
		$this->_redirect ( 'admin' );
	}
	
	public static function _checkformedit($data = array()) {
		
		foreach ( array (
				'email',
				'name',
				'status' 
		) as $a ) {
			if ($data [$a] == '')
				$error [] = ucfirst ( $a ) . ' Không thể để trống';
		}
		if ($data ['password'] != '') {
			if (strlen ( $data ['password'] ) <= 4)
				$error [] = 'Password phải có ít nhất 4 ký tự';
		}
		if ($data ['password'] != $data ['confirmpassword']) {
			$error [] = 'Password and confirm password not same';
		} else {
		}
		
		// Check Email
		if ($data ['email']) {
			$validator = new Zend_Validate_EmailAddress ();
			if (! $validator->isValid ( $data ['email'] ))
				$error [] = 'Sai định dạng email';
		}
		return $error;
	}
	
	public static function _checkform($data = array()) {
		
		foreach ( array (
				'email',
				'password',
				'confirmpassword',
				'status' 
		) as $a ) {
			if ($data [$a] == '')
				$error [] = ucfirst ( $a ) . ' Không thể để trống';
		}
		if ($data ['name'] == '')
			$error [] = 'Tên thành viên không được để trống';
		if ($data ['password'] != $data ['confirmpassword']) {
			$error [] = 'Password and confirm password not same';
		} else {
		}
		
		// Check Email
		if ($data ['email']) {
			$validator = new Zend_Validate_EmailAddress ();
			if (! $validator->isValid ( $data ['email'] ))
				$error [] = 'Sai định dạng email';
			else {
				$User = Members::getByEmail ( $data ['email'] );
				if ($User)
					$error [] = $data ['email'] . ' đã được sử dụng, xin hãy nhập địa chỉ email khác!';
			}
		}
		// check password
		if (strlen ( $data ['password'] ) <= 4)
			$error [] = 'Password phải có ít nhất 4 ký tự';
		return $error;
	}
	
	public static function _checkformmy($data = array()) {
		
		foreach ( array (
				'email',
				'name' 
		) as $a ) {
			if ($data [$a] == '')
				$error [] = ucfirst ( $a ) . ' Không thể để trống';
		}
		if ($data ['password'] != '') {
			if (strlen ( $data ['password'] ) <= 4)
				$error [] = 'Password phải có ít nhất 4 ký tự';
		}
		if ($data ['password'] != $data ['confirmpassword']) {
			$error [] = 'Password and confirm password not same';
		} else {
		}
		
		// Check Email
		if ($data ['email']) {
			$validator = new Zend_Validate_EmailAddress ();
			if (! $validator->isValid ( $data ['email'] ))
				$error [] = 'Sai định dạng email';
		}
		return $error;
	}
	
	public function myAction() {
		$this->view->Title = "Xem thông tin cá nhân";
		$this->view->headTitle ( $this->view->Title );
		$id = $this->Member->id;
		$Member = Members::getById ( $id );
		if ($Member) {
			$file_path = APPLICATION_PATH . 'data/member_' . $id . '.log.csv';
			if (file_exists ( $file_path )) {
				$fp = file ( $file_path );
				$lines = count ( $fp );
				
				$linesPerPage = 50;
				$totalPages = ceil ( $lines / $linesPerPage );
				$page = ( int ) $this->getRequest ()->getParam ( 'page' );
				$page = ($page) ? $page : 1;
				$cLine = ($page * $linesPerPage) - $linesPerPage;
				$this->view->TotalPage = $totalPages;
				$this->view->Pager = $page;
				$this->view->id = $id;
				
				$aLogs = array_slice ( $fp, $cLine, $linesPerPage );
				unset ( $fp );
				$fields = array (
						'time',
						'ip',
						'section',
						'action' 
				);
				foreach ( $aLogs as $i => $log ) {
					$log = explode ( ',', str_replace ( '"', '', $log ) );
					foreach ( $fields as $key => $field ) {
						$log [$field] = $log [$key];
						unset ( $log [$key] );
					}
					$aLogs [$i] = $log;
				}
				$this->view->Log = $aLogs;
			
			} else {
				$this->view->error = 'Thành viên này chưa có bất kỳ hoạt động nào cần theo dõi';
			}
		
		}
		$this->view->My = $Member;
	}
	
	public function profileAction() {
		$this->view->Title = "Chỉnh sửa thông tin cá nhân";
		$this->view->headTitle ( $this->view->Title );
		$id = $this->Member->id;
		$Member = Members::getById ( $id );
		if ($this->getRequest ()->isPost ()) {
			$request = $this->getRequest ()->getParams ();
			unset ( $request ['id'] );
			if ($request ['password'] == '' || $request ['password'] != $request ['confirmpassword'] || $request ['confirmpassword'] == '') {
				unset ( $request ['password'], $request ['confirmpassword'] );
				$Member->merge ( $request );
				$Member->username = $Member->email;
				$Member->save ();
			} else {
				$Member->merge ( $request );
				// $Member->username = $Member->email;
				$Member->encodePassword ();
				$Member->save ();
			}
			$this->Member->log ( 'Edit member:' . $Member->getName () . '(' . $Member->id . ')', 'Member' );
			My_Plugin_Libs::setSplash ( 'Thành viên:<b>' . $Member->getName () . '</b>đã thay đổi thành công' );
			$this->_redirect ( $this->_helper->url ( 'index', 'index', 'admin' ) );
		}
		$this->view->Member = $Member;
	
	}
	
	public function exportAction() {
		$this->view->Title = "Export nhật ký hoạt động của thành viên";
		$this->view->headTitle ( $this->view->Title );
		if ($this->getRequest ()->isPost ()) {
			$id = $this->getRequest ()->getParam ( 'id' );
			$file = APPLICATION_PATH . 'data/member_' . $id . '.log.csv';
			if (file_exists ( $file )) {
				// magic_mime module installed?
				if (function_exists ( 'mime_content_type' )) {
					$mtype = mime_content_type ( $file );
				} else if (function_exists ( 'finfo_file' )) {
					$finfo = finfo_open ( FILEINFO_MIME ); // return mime type
					$mtype = finfo_file ( $finfo, $file_path );
					finfo_close ( $finfo );
				}
			}
			$this->view->layout ()->disableLayout ();
			$this->_helper->viewRenderer->setNoRender ( true );
			header ( $mtype );
			header ( 'Content-Disposition: attachment; filename="member_' . $id . '.log.csv"' );
			readfile ( $file );
			$fh = fopen ( $file, 'w' );
			fclose ( $fh );
		
		}
	}
	
	public function forgotAction() {
		$this->_helper->layout->disableLayout ();
		$this->view->Title = "Forgotten password";
		$this->view->headTitle ( $this->view->Title );
		$error = '';
		
		if ($this->getRequest ()->isPost ()) {
			$Content = Content::getById ( $id );
			$Request = $this->getRequest ()->getParams ();
			$Member = Members::getByEmail ( $Request ['email'] );
			if ($Member) {
				$String = My_Plugin_Libs::randomStr ();
				$Member->password = Members::toPassword ( $String );
				$Member->save ();
				$message = str_replace ( array (
						'%email%',
						'%password%' 
				), array (
						$Member->email,
						$String 
				), $Content ['detail'] );
				$to = $Member->email;
				$subject = $Content->title;
				$oEmail = new My_Plugin_Email ();
				$oEmail->send ( $subject, $to, '', $message );
				$error = 'Password has been sent to your email!';
			} else {
				$error = 'Email is not match please try again!';
			}
			$this->view->error = $error;
		}
	
	}
}