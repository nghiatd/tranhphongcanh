<?php
class My_Plugin_Array extends Zend_Controller_Plugin_Abstract {
	
	public static function array_to_xml(array $arr, SimpleXMLElement $xml) {
		foreach ( $arr as $k => $v ) {
			is_array ( $v ) ? self::array_to_xml ( $v, $xml->addChild ( $k ) ) : $xml->addChild ( $k, $v );
		}
		return $xml;
	}
	
	public static function ArrayKeyValue($key, $value, $array) {
		$return = array ();
		foreach ( $array as $k => $v ) {
			$return [$v [$key]] = $v [$value];
		}
		return $return;
	}
}
