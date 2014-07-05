<?php

class My_Plugin_Email extends Zend_Controller_Plugin_Abstract {
	

	public function __construct() {
		$aConfig = array ('auth' => 'login', 'username' => Zend_Registry::get ( 'Setting' )->EMAIL_SMTP_USER, 'password' => Zend_Registry::get ( 'Setting' )->EMAIL_SMTP_PASS, 'port' => Zend_Registry::get ( 'Setting' )->EMAIL_SMTP_PORT );
		if (Zend_Registry::get ( 'Setting' )->EMAIL_SMTP_SSL)
			$aConfig ['ssl'] = Zend_Registry::get ( 'Setting' )->EMAIL_SMTP_SSL;
		$tr = new Zend_Mail_Transport_Smtp ( Zend_Registry::get ( 'Setting' )->EMAIL_SMTP_HOST, $aConfig );
		Zend_Mail::setDefaultTransport ( $tr );
		Zend_Mail::setDefaultFrom ( Zend_Registry::get ( 'Setting' )->webmaster_email, Zend_Registry::get ( 'Setting' )->webmaster_name );
		Zend_Mail::setDefaultReplyTo ( Zend_Registry::get ( 'Setting' )->webmaster_email, Zend_Registry::get ( 'Setting' )->webmaster_name );
	}
	public function setReplyTo($email = '', $name = '') {
		Zend_Mail::setDefaultReplyTo ( $email, $name );
	}
	private function _send($subject, $email, $name, $message, $files = false) {
		$oEmail = new My_Plugin_Email ();
		$oEmail->send ( $subject, $email, $name, $message, $files );
	}
	
	/**
	 * Gửi Email đến 1 địa chỉ nào đó
	 * @param string $subject Tiêu đề Email
	 * @param string $email Email người nhận
	 * @param string $name Tên người nhận
	 * @param string $message Nội dung Email
	 * @param string $type kiểu dữ liệu gửi đi html|text
	 */
	public function send($subject, $email, $name, $message, $files = false) {
		set_time_limit ( 120 );
		$mail = new Zend_Mail ( 'utf-8' );
		//if ($type == 'html')
		$mail->setBodyHtml ( $message );
		//else
		//	$mail->setBodyText ( $message );
		$mail->addTo ( $email, $name );
		$mail->setSubject ( $subject );
		if ($files) {
			if (count ( $files )) {
				foreach ( $files ['name'] as $i => $file ) {
					$myImage = file_get_contents ( $files ['tmp_name'] [$i] );
					$at = new Zend_Mime_Part ( $myImage );
					$at->type = $files ['type'] [$i];
					$at->disposition = Zend_Mime::DISPOSITION_INLINE;
					$at->encoding = Zend_Mime::ENCODING_BASE64;
					$at->filename = $file;
					$mail->addAttachment ( $at );
				}
			}
		}
		
		$mail->send ();
	}
	
	/**
	 * Tạo Object Layout cho Email
	 */
	private function generateLayout() {
		$layout = new Zend_Layout ();
		$layout->setLayoutPath ( EMAIL_TEMPLATES_PATH );
		$layout->setLayout ( 'layout' );
		return $layout;
	}
	
	/**
	 * Tạo Object View cho Email
	 */
	private function generateView() {
		$view = new Zend_View ();
		$view->setScriptPath ( EMAIL_TEMPLATES_PATH );
		return $view;
	}
	public static function sendRegisterHotelier($email, $message, $file = false) {
		
		$layout = self::generateLayout ();
		$view = self::generateView ();
		$view->assign ( 'Message', $message );
		$layout->content = $view->render ( 'registerhotelier.phtml' );
		$subject = 'Đăng ký tài khoản Hotelier mới ';
		$content = $layout->render ();
		self::_send ( $subject, $email, 'Webmaster', $content, $file );
	}
	public static function sendRegisterTravelAgent($email, $message, $return = FALSE) {
		$layout = self::generateLayout ();
		$view = self::generateView ();
		$view->assign ( 'Message', $message );
		$layout->content = $view->render ( 'registertravelagent.phtml' );
		$subject = 'Đăng ký tài khoản Travel Agent mới ';
		$content = $layout->render ();
		if ($return) {
			return array ($subject, $content );
		} else
			self::_send ( $subject, $email, 'Webmaster', $content );
	}
}
