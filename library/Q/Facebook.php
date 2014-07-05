<?php
class Q_Facebook extends Zend_Controller_Plugin_Abstract {
	public static $APP_ID = '';
	public static $APP_SECRET = '';
	private static $FACEBOOK = false;
	
	public static function init($APP_ID, $APP_SECRET) {
		self::$APP_ID = $APP_ID;
		self::$APP_SECRET = $APP_SECRET;
		self::$FACEBOOK = new Facebook ( array (
				'appId' => $APP_ID,
				'secret' => $APP_SECRET 
		) );
	}
	
	public static function get() {
		return self::$FACEBOOK;
	}
}