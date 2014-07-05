<?php

class My_Plugin_Localizer extends Zend_Controller_Plugin_Abstract {
	private static $LANG = 0;
	private static $RATE = 1;
	private static $CURRENCY = 'US$';

	public function preDispatch(Zend_Controller_Request_Abstract $request) {
		if (! isset ( $_SESSION ['LANGUAGE'] )) {
			$_SESSION ['LANGUAGE'] = LOCALIZER_LANG_ID;
		}
		if (is_numeric ( $_REQUEST ['lang_id'] )) {
			$_SESSION ['LANGUAGE'] = $_REQUEST ['lang_id'];
		}
		// Sử lý về đơn vị tiền tệ
		if ($_SESSION ['LANGUAGE']) {
			//$Language = Languages::getById ( $_SESSION ['LANGUAGE'] );
			//self::$CURRENCY = $Language->currency;
			//self::$RATE = $Language->rate;
		}
		/*
		 * // Sử lý về đơn vị tiền tệ if (is_numeric ( $_REQUEST ['curr_id'] ))
		 * { $Language = Languages::getById ( $_REQUEST ['curr_id'] );
		 * self::$CURRENCY = $Language->currency; self::$RATE = $Language->rate;
		 * }
		 */
		if (TRANSLATION_CACHE) {
			$cache = Zend_Registry::get ( 'Cacher' );
			Zend_Translate::setCache ( $cache );
		}
		$locale = new Zend_Locale ();
		$locale->setLocale ( LOCALIZER );
		$dir = APPLICATION_PATH . 'modules/' . $request->module . '/languages/' . $request->controller;
		$translate_global = new Zend_Translate ( array ('adapter' => 'tmx','content' => APPLICATION_PATH . 'modules/' . $request->module . '/languages/layout.tmx','locale' => LOCALIZER ) );
		if (TRANSLATION_LOG === true) {
			$writer = new Zend_Log_Writer_Stream ( APPLICATION_PATH . 'tmp' . DIRECTORY_SEPARATOR . 'translate-' . $request->module . '.log' );
			$log = new Zend_Log ( $writer );
			$translate_global->setOptions ( array ('log' => $log,'logUntranslated' => true ) );
		} else {
			$translate_global->setOptions ( array ('logUntranslated' => false ) );
		}
		if (is_dir ( $dir )) {
			$translate = new Zend_Translate ( array ('adapter' => 'tmx','content' => $dir,'locale' => LOCALIZER ) );
			
			if ($request->controller != 'ajax') {
				$translate_global->addTranslation ( array ('content' => $translate ) );
			}
		
		}
		
		$translate_global->setLocale ( $locale->getLanguage () );
		Zend_Registry::set ( 'Zend_Translate', $translate_global );
	}

	public function setLangId($lang_id = 0) {
		self::$LANG = $lang_id;
	}

	public static function getLocalizer($Localizer, $Original) {
		if ($_SESSION ['LANGUAGE'] == 0)
			return $Original;
		$aLocalizer = $Localizer::getLocalizer ( $Original->id, $_SESSION ['LANGUAGE'] );
		if ($aLocalizer) {
			$Translated = $Original;
			foreach ( $Original->toArray () as $key => $value ) {
				if (isset ( $aLocalizer [$key] ))
					$Translated->$key = $aLocalizer [$key];
			}
			return $Translated;
		}
		return $Original;
	}

	public static function getCacheLocalizer($Localizer, $Original) {
		if ($_SESSION ['LANGUAGE'] == 0)
			return $Original;
		$aLocalizer = $Localizer::getLocalizer ( $Original ['id'], $_SESSION ['LANGUAGE'] );
		if ($aLocalizer) {
			$Translated = $Original;
			foreach ( $Original as $key => $value ) {
				if (isset ( $aLocalizer [$key] ))
					$Translated->$key = $aLocalizer [$key];
			}
			return $Translated;
		}
		return $Original;
	}

	public static function getCurrency($value, $char = true) {
		if (! $char)
			return $value * self::$RATE;
		return number_format ( $value * self::$RATE ) . ' ' . self::$CURRENCY;
	}

	public static function currency() {
		return self::$CURRENCY;
	}

	public static function getRate() {
		return self::$RATE;
	}

}
