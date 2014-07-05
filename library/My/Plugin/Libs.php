<?php
class My_Plugin_Libs extends Zend_Controller_Plugin_Abstract {

	public static function randomStr($lengthgth = 6, $type = '') {
		$base = '';
		if ($type == 'numeric') {
			$base = '0123456789';
		} elseif ($type == 'string') {
			$base = 'ABCDEFGHKLMNOPQRSTWXYZabcdefghjkmnpqrstwxyz';
		} else {
			$base = 'ABCDEFGHKLMNOPQRSTWXYZabcdefghjkmnpqrstwxyz123456789';
		}
		$max = strlen ( $base ) - 1;
		$str = '';
		mt_srand ( ( double ) microtime () * 1000000 );
		while ( strlen ( $str ) < $lengthgth )
			$str .= $base {mt_rand ( 0, $max )};
		return $str;
	}

	public static function array_to_xml(array $arr, SimpleXMLElement $xml) {
		foreach ( $arr as $k => $v ) {
			is_array ( $v ) ? self::array_to_xml ( $v, $xml->addChild ( $k ) ) : $xml->addChild ( $k, $v );
		}
		return $xml;
	}

	function str2array($string = '', $sparator) {
		$array = array ();
		$array = explode ( $sparator, $string );
		return $array;
	}

	function array2str($array = '', $sparator) {
		$str = '';
		$str = implode ( $sparator, $array );
		return $str;
	}

	public static function utf8_strtolower($str = '') {
		$source = array ("À", "Á", "Ạ", "Ả", "Ã", "Â", "Ầ", "Ấ", "Ậ", "Ẩ", "Ẫ", "Ă", "Ằ", "Ắ", "Ặ", "Ẳ", "Ẵ", "È", "É", "Ẹ", "Ẻ", "Ẽ", "Ê", "Ề", "Ế", "Ệ", "Ể", "Ễ", "Ì", "Í", "Ị", "Ỉ", "Ĩ", "Ò", "Ó", "Ọ", "Ỏ", "Õ", "Ô", "Ồ", "Ố", "Ộ", "Ổ", "Ỗ", "Ơ", "Ờ", "Ớ", "Ợ", "Ở", "Ỡ", "Ù", "Ú", "Ụ", "Ủ", "Ũ", "Ư", "Ừ", "Ứ", "Ự", "Ử", "Ữ", "Ỳ", "Ý", "Ỵ", "Ỷ", "Ỹ", "Đ" );
		$target = array ("à", "á", "ạ", "ả", "ã", "â", "ầ", "ấ", "ậ", "ẩ", "ẫ", "ă", "ằ", "ắ", "ặ", "ẳ", "ẵ", "è", "é", "ẹ", "ẻ", "ẽ", "ê", "ề", "ế", "ệ", "ể", "ễ", "ì", "í", "ị", "ỉ", "ĩ", "ò", "ó", "ọ", "ỏ", "õ", "ô", "ồ", "ố", "ộ", "ổ", "ỗ", "ơ", "ờ", "ớ", "ợ", "ở", "ỡ", "ù", "ú", "ụ", "ủ", "ũ", "ư", "ừ", "ứ", "ự", "ử", "ữ", "ỳ", "ý", "ỵ", "ỷ", "ỹ", "đ" );
		return str_replace ( $source, $target, $str );
	}

	public static function unUnicode($str = '') {
		$marTViet = array ("à", "á", "ạ", "ả", "ã", "â", "ầ", "ấ", "ậ", "ẩ", "ẫ", "ă", "ằ", "ắ", "ặ", "ẳ", "ẵ", "è", "é", "ẹ", "ẻ", "ẽ", "ê", "ề", "ế", "ệ", "ể", "ễ", "ì", "í", "ị", "ỉ", "ĩ", "ò", "ó", "ọ", "ỏ", "õ", "ô", "ồ", "ố", "ộ", "ổ", "ỗ", "ơ", "ờ", "ớ", "ợ", "ở", "ỡ", "ù", "ú", "ụ", "ủ", "ũ", "ư", "ừ", "ứ", "ự", "ử", "ữ", "ỳ", "ý", "ỵ", "ỷ", "ỹ", "đ", "À", "Á", "Ạ", "Ả", "Ã", "Â", "Ầ", "Ấ", "Ậ", "Ẩ", "Ẫ", "Ă", "Ằ", "Ắ", "Ặ", "Ẳ", "Ẵ", "È", "É", "Ẹ", "Ẻ", "Ẽ", "Ê", "Ề", "Ế", "Ệ", "Ể", "Ễ", "Ì", "Í", "Ị", "Ỉ", "Ĩ", "Ò", "Ó", "Ọ", "Ỏ", "Õ", "Ô", "Ồ", "Ố", "Ộ", "Ổ", "Ỗ", "Ơ", "Ờ", "Ớ", "Ợ", "Ở", "Ỡ", "Ù", "Ú", "Ụ", "Ủ", "Ũ", "Ư", "Ừ", "Ứ", "Ự", "Ử", "Ữ", "Ỳ", "Ý", "Ỵ", "Ỷ", "Ỹ", "Đ" );
		$marKoDau = array ("a", "a", "a", "a", "a", "a", "a", "a", "a", "a", "a", "a", "a", "a", "a", "a", "a", "e", "e", "e", "e", "e", "e", "e", "e", "e", "e", "e", "i", "i", "i", "i", "i", "o", "o", "o", "o", "o", "o", "o", "o", "o", "o", "o", "o", "o", "o", "o", "o", "o", "u", "u", "u", "u", "u", "u", "u", "u", "u", "u", "u", "y", "y", "y", "y", "y", "d", "A", "A", "A", "A", "A", "A", "A", "A", "A", "A", "A", "A", "A", "A", "A", "A", "A", "E", "E", "E", "E", "E", "E", "E", "E", "E", "E", "E", "I", "I", "I", "I", "I", "O", "O", "O", "O", "O", "O", "O", "O", "O", "O", "O", "O", "O", "O", "O", "O", "O", "U", "U", "U", "U", "U", "U", "U", "U", "U", "U", "U", "Y", "Y", "Y", "Y", "Y", "D" );
		return str_replace ( $marTViet, $marKoDau, $str );
	}

	public static function trimSpacer($str) {
		return urlencode ( str_replace ( ' ', '-', self::unUnicode ( $str ) ) );
	}
	
	// This function makes any text into a url frienly
	// This script is created by wallpaperama.com
	public static function clean_url($text) {
		$text = strtolower ( $text );
		$code_entities_match = array (' ', '--', '&quot;', '!', '@', '#', '$', '%', '^', '&', '*', '(', ')', '_', '+', '{', '}', '|', ':', '"', '<', '>', '?', '[', ']', '\\', ';', "'", ',', '.', '/', '*', '+', '~', '`', '=' );
		$code_entities_replace = array ('-', '-', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '' );
		$text = str_replace ( $code_entities_match, $code_entities_replace, $text );
		return $text;
	}

	public static function text2url($text = '') {
		return self::clean_url ( self::unUnicode ( $text ) );
	}

	public static function setSplash($message, $code = 'success', $timer = 5000) {
		/*
		 * $code=success|error|inf|notice
		 */
		$_SESSION ['splash'] = array ('message' => $message, 'code' => $code, 'timer' => $timer );
	}

	public static function getSplash() {
		if ($_SESSION ['splash'] ['message']) {
			$html = ' <div id="splash" class="response-msg ' . $_SESSION ['splash'] ['code'] . ' ui-corner-all" style="width:40%;margin:0 auto;">' . $_SESSION ['splash'] ['message'] . '</div>
                <script>
		        setTimeout(\'$("#splash").fadeOut("slow")\',' . $_SESSION ['splash'] ['timer'] . ');
		        </script>';
		}
		unset ( $_SESSION ['splash'] );
		return $html;
	}

	public static function appendCsv($file, $data) {
		$fp = fopen ( $file, 'a' );
		fputcsv ( $fp, $data );
		fclose ( $fp );
	}

	public static function readFileCsv($file, $start = 0, $end = 20, $fields = array()) {
		if (($handle = fopen ( $file, "r" )) !== FALSE) {
			$data = array ();
			for($i = 1; $i <= $end; $i ++) {
				$row = fgets ( $handle, $start + $i, "," );
				if ($fields) {
					foreach ( $fields as $key => $field ) {
						$data [$i] [$field] = $row [$key];
					}
				} else
					$data [] = $row;
			}
			fclose ( $handle );
			return $data;
		}
		return false;
	}

	public static function validCreditCard($credit = array()) {
		$error = array ();
		if ($credit ['card_type'] == '')
			$error [] = 'Please select card type';
		if ($credit ['card_holder_name'] == '')
			$error [] = 'Name of card holder cannot be empty';
		if ($credit ['card_cvc_code'] == '')
			$error [] = 'CVC Code cannot be empty';
		$cardValidator = new Zend_Validate_CreditCard ( array ('type' => array (Zend_Validate_CreditCard::VISA, Zend_Validate_CreditCard::AMERICAN_EXPRESS ) ) );
		if (! $cardValidator->isValid ( $credit ['card_number'] )) {
			$error [] = 'Credit card not valid';
		}
		return $error;
	}

	public static function Date2Timestamp($date, $format = 'dd/mm/yyyy') {
		if ($date == '')
			return false;
		$format = strtoupper ( $format );
		switch ($format) {
			case 'YYYY/MM/DD' :
			case 'YYYY-MM-DD' :
				list ( $y, $m, $d ) = preg_split ( '/[-\.\/ ]/', $date );
				break;
			
			case 'YYYY/DD/MM' :
			case 'YYYY-DD-MM' :
				list ( $y, $d, $m ) = preg_split ( '/[-\.\/ ]/', $date );
				break;
			
			case 'DD-MM-YYYY' :
			case 'DD/MM/YYYY' :
				list ( $d, $m, $y ) = preg_split ( '/[-\.\/ ]/', $date );
				break;
			
			case 'MM-DD-YYYY' :
			case 'MM/DD/YYYY' :
				list ( $m, $d, $y ) = preg_split ( '/[-\.\/ ]/', $date );
				break;
			
			case 'YYYYMMDD' :
				$y = substr ( $date, 0, 4 );
				$m = substr ( $date, 4, 2 );
				$d = substr ( $date, 6, 2 );
				break;
			
			case 'YYYYDDMM' :
				$y = substr ( $date, 0, 4 );
				$d = substr ( $date, 4, 2 );
				$m = substr ( $date, 6, 2 );
				break;
			
			default :
				throw new Exception ( "Invalid Date Format: $date -  $format" );
		}
		;
		return mktime ( 0, 0, 0, $m, $d, $y );
	}

	function fileDetail($sFilePath) {
		// First, see if the file exists
		if (is_file ( $sFilePath )) {
			// Gather relevent info about file
			$File ['size'] = filesize ( $sFilePath );
			$File ['name'] = basename ( $sFilePath );
			$File ['extension'] = strtolower ( substr ( strrchr ( $File ['name'], "." ), 1 ) );
			$File ['modified'] = date ( "F d Y H:i:s.", filemtime ( $sFilePath ) );
			$File ['accessed'] = date ( "F d Y H:i:s.", fileatime ( $sFilePath ) );
			$File ['path'] = $sFilePath;
			// This will set the Content-Type to the appropriate setting for the
			// file
			switch ($File ['extension']) {
				case "pdf" :
					$ctype = "application/pdf";
					break;
				case "exe" :
					$ctype = "application/octet-stream";
					break;
				case "zip" :
					$ctype = "application/zip";
					break;
				case "doc" :
					$ctype = "application/msword";
					break;
				case "xls" :
					$ctype = "application/vnd.ms-excel";
					break;
				case "ppt" :
					$ctype = "application/vnd.ms-powerpoint";
					break;
				case "gif" :
					$ctype = "image/gif";
					break;
				case "png" :
					$ctype = "image/png";
					break;
				case "jpeg" :
				case "jpg" :
					$ctype = "image/jpg";
					break;
				case "mp3" :
					$ctype = "audio/mpeg";
					break;
				case "wav" :
					$ctype = "audio/x-wav";
					break;
				case "mpeg" :
				case "mpg" :
				case "mpe" :
					$ctype = "video/mpeg";
					break;
				case "mov" :
					$ctype = "video/quicktime";
					break;
				case "avi" :
					$ctype = "video/x-msvideo";
					break;
				// The following are for extensions that shouldn't be downloaded
				// (sensitive stuff, like php files)
				case "txt" :
					die ( "<b>Cannot be used for " . $File ['extension'] . " files!</b>" );
					break;
				default :
					$ctype = "application/force-download";
			}
			$File ['type'] = $ctype;
			return $File;
		} else {
			return false;
		}
	}

	public static function toSize($size) {
		$i = 0;
		$iec = array ("b", "Kb", "Mb", "Gb", "Tb", "Pb", "Eb", "Zb", "Yb" );
		while ( ($size / 1024) >= 1 ) {
			$size /= 1024;
			$i ++;
		}
		return round ( $size, 1 ) . ' ' . $iec [$i];
	}

	public static function getFileExt($file) {
		return substr ( $file, - 3 );
	}

	public static function rmdir($dir) {
		if (is_dir ( $dir )) {
			$objects = scandir ( $dir );
			foreach ( $objects as $object ) {
				if ($object != "." && $object != "..") {
					if (filetype ( $dir . "/" . $object ) == "dir")
						self::rmdir ( $dir . "/" . $object );
					else
						unlink ( $dir . "/" . $object );
				}
			}
			reset ( $objects );
			rmdir ( $dir );
		}
	}

	public static function mkdir($dirName, $rights = 0777) {
		$dirs = explode ( '/', $dirName );
		$dir = '';
		foreach ( $dirs as $part ) {
			$dir .= $part . '/';
			if (! is_dir ( $dir ) && strlen ( $dir ) > 0)
				mkdir ( $dir, $rights );
		}
	}

	public static function getRemoteFile($url, $local) {
		if (! $out = fopen ( $local, 'w' ))
			return false;
		
		$ch = curl_init ();
		curl_setopt ( $ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)" );
		curl_setopt ( $ch, CURLOPT_FILE, $out );
		curl_setopt ( $ch, CURLOPT_HEADER, 0 );
		curl_setopt ( $ch, CURLOPT_URL, $url );
		curl_setopt ( $ch, CURLOPT_AUTOREFERER, true );
		curl_setopt ( $ch, CURLOPT_BINARYTRANSFER, true );
		curl_setopt ( $ch, CURLOPT_TIMEOUT, 10 );
		if (curl_exec ( $ch )) {
			curl_close ( $ch );
			fclose ( $out );
			return true;
		}
		return false;
	}

	public static function getHowLongAgo($start, $end = false) {
		$sdate = strtotime ( $start );
		if ($end)
			$edate = strtotime ( $end );
		else
			$edate = time ();
		
		$time = $edate - $sdate;
		
		$pday = ($edate - $sdate) / 86400;
		$preday = explode ( '.', $pday );
		
		$phour = $pday - $preday [0];
		$prehour = explode ( '.', $phour * 24 );
		
		$premin = ($phour * 24) - $prehour [0];
		$min = explode ( '.', $premin * 60 );
		
		$presec = '0.' . $min [1];
		$sec = $presec * 60;
		$timeshift = '';
		if ($preday [0])
			$timeshift = ' ' . $preday [0] . self::doPlural ( $preday [0], ' ngày' );
		if ($prehour [0])
			$timeshift .= ' ' . $prehour [0] . self::doPlural ( $prehour [0], ' giờ' );
		if ($min [0])
			$timeshift .= ' ' . $min [0] . self::doPlural ( $min [0], ' phút' );
			
			// if ($sec)
			// $timeshift .= ' ' . round ( $sec, 0 ) . self::doPlural ( $sec, '
			// second' );
		
		return $timeshift . ' trước';
	}

	public static function doPlural($nb, $str) {
		return $nb > 1 ? $str . '' : $str;
	}

	public static function Num2Text($s) {
		$l = 0;
		$i = 0;
		$j = 0;
		$dk = false;
		$c = "";
		
		$l = strlen ( $s );
		
		$A = array ();
		
		if ($l > 32) // so qua lon
			return "Số quá lớn";
		
		for($i = 0; $i < $l; $i ++) {
			$A [$i] = substr ( $s, $i, 1 );
		}
		for($i = 0; $i < $l; $i ++) {
			if ((($l - $i) % 3 == 0) && ($A [$i] == 0) && (($A [$i + 1] != 0 || $A [$i + 2] != 0)))
				$c .= " không";
			
			if ($A [$i] == 2)
				$c .= " hai";
			if ($A [$i] == 3)
				$c .= " ba";
			if ($A [$i] == 4) {
				if (($i - 1 > 0) && $A [$i - 1] == 0)
					$c .= " tư";
				else
					$c .= " bốn";
			}
			if ($A [$i] == 6)
				$c .= " sáu";
			if ($A [$i] == 7)
				$c .= " bảy";
			if ($A [$i] == 8)
				$c .= " tám";
			if ($A [$i] == 9)
				$c .= " chín";
			
			if ($A [$i] == 5) {
				if ($i > 0 && (($l - $i) % 3 == 1) && ($A [$i - 1] != 0))
					$c .= " lăm";
				else
					$c .= " năm";
			}
			
			if ($i == 1 && ($A [$i] == 1) && (($l - $i) % 3 == 1) && ($A [$i - 1] > 1))
				$c .= " mốt";
			
			else if ((($l - $i) % 3 != 2) && ($A [$i] == 1))
				$c .= " một";
			
			if ((($l - $i) % 3 == 2) && ($A [$i] != 0) && ($A [$i] != 1))
				$c .= " mươi";
			else if ((($l - $i) % 3 == 2) && $A [$i] != 0)
				$c .= " mười";
			
			if ((($l - $i) % 3 == 2) && ($A [$i] == 0) && ($A [$i + 1] != 0))
				$c .= " linh";
			
			if ((($l - $i) % 3 == 0) && ($A [$i + 1] != 0 || $A [$i + 2] != 0))
				$c .= " trăm";
			else if ((($l - $i) % 3 == 0) && ($A [$i] != 0))
				$c .= " trăm";
			
			if (($l - $i) == 4)
				$c .= " nghìn";
			if (($l - $i) == 7)
				$c .= " triệu";
			if (($l - $i) == 10)
				$c .= " tỷ";
			if (($l - $i) == 13)
				$c .= " nghìn tỷ";
			if (($l - $i) == 16)
				$c .= " triệu tỷ";
			if (($l - $i) == 19)
				$c .= " tỷ tỷ";
			if (($l - $i) == 22)
				$c .= " triệu tỷ tỷ";
			if (($l - $i) == 25)
				$c .= " triệu tỷ tỷ";
			if (($l - $i) == 28)
				$c .= " tỷ tỷ tỷ";
			
			if ((($l - $i) % 3 == 0) && ($A [$i] == 0) && ($A [$i + 1] == 0) && ($A [$i + 2] == 0))
				$i = $i + 2;
			
			if (($l - $i) % 3 == 1)
				$dk = true;
			for($j = $i; $j < $l; $j ++) {
				if ($A [$j] != 0)
					$dk = false;
			}
			
			if ($dk)
				break;
		}
		return ucfirst ( trim ( $c ) );
	}
}
