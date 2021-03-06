<?php

/**
 * BaseProductType
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property string $name
 * @property integer $name_plain
 * @property integer $orders
 * @property string $alt_text
 * @property Doctrine_Collection $ProductCategories
 * @property Doctrine_Collection $Products
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7691 2011-02-04 15:43:29Z jwage $
 */
abstract class BaseProductType extends Doctrine_Record {
	public function setTableDefinition() {
		$this->setTableName ( 'product_type' );
		$this->hasColumn ( 'id', 'integer', 4, array (
				'type' => 'integer',
				'primary' => true,
				'autoincrement' => true,
				'length' => '4' 
		) );
		$this->hasColumn ( 'name', 'string', 45, array (
				'type' => 'string',
				'length' => '45' 
		) );
		$this->hasColumn ( 'name_plain', 'integer', 1, array (
				'type' => 'integer',
				'length' => '1' 
		) );
		$this->hasColumn ( 'orders', 'integer', 1, array (
				'type' => 'integer',
				'length' => '1' 
		) );
		$this->hasColumn ( 'alt_text', 'string', 255, array (
				'type' => 'string',
				'length' => '255' 
		) );
		
		$this->option ( 'charset', 'utf8' );
		$this->option ( 'type', 'MyISAM' );
	}
	
	public function setUp() {
		parent::setUp ();
		$this->hasMany ( 'ProductCategories', array (
				'local' => 'id',
				'foreign' => 'product_type_id' 
		) );
		
		$this->hasMany ( 'Products', array (
				'local' => 'id',
				'foreign' => 'product_type_id' 
		) );
	}
}