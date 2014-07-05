<?php

/**
 * Settings This class has been auto-generated by the Doctrine ORM Framework
 *
 * @package ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author ##NAME## <##EMAIL##>
 * @version SVN: $Id: Builder.php 7691 2011-02-04 15:43:29Z jwage $
 */
class Settings extends BaseSettings {
	
	public static function getById($id = 0) {
		return Doctrine_Query::create ()->from ( __CLASS__ )->where ( 'id=?', $id )->execute ()->getFirst ();
	}
	
	public static function getAll() {
		return Doctrine_Query::create ()->from ( __CLASS__ )->execute ();
	}
	
	public static function doUpdate($data) {
		foreach ( $data as $k => $v ) {
			if ($ST = self::getById ( $k )) {
				$ST->value = $v;
				$ST->save ();
			}
		}
	}
	
	public static function doGet($key) {
		$ST = self::getById ( $key );
		return json_decode ( $ST->value );
	}

}