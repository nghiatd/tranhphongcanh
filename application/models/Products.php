<?php

/**
 * Products
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class Products extends BaseProducts {
	private static $Status = array (
			'Disable',
			'Enable' 
	);
	
	public static function getAll($Condition = array(), $currentPage = 1, $recordPerPage = 10, $order = 'id DESC', $limit = 0) {
		$Items = Doctrine_Query::create ()->from ( __CLASS__ )->orderBy ( $order )->limit ( $limit );
		if ($Condition ['product_categories_id=?']) {
			
			$whereIn = explode ( ',', $Condition ['product_categories_id=?'] );
			unset ( $Condition ['product_categories_id=?'] );
			
			$Items->whereIn ( 'product_categories_id', $whereIn );
		}
		
		if (count ( $Condition ))
			foreach ( $Condition as $key => $item ) {
				$Items->addWhere ( $key, $item );
			}
			
		$pager = new Doctrine_Pager ( $Items, $currentPage, $recordPerPage );
		$Data = $pager->execute ();
		
		return array (
				$pager,
				$Data 
		);
	}
	
	public static function getWithOutPage($Condition = array(), $limit = 0) {
		$NewsCategories = Doctrine_Query::create ()->from ( __CLASS__ )->where ( 'status=?', 1 )->orderBy ( 'id DESC' )->limit ( $limit );
		foreach ( $Condition as $key => $item ) {
			$NewsCategories->addWhere ( $key, $item );
		}
		return $NewsCategories->execute ();
	}
	
	public static function getById($id = 0) {
		return Doctrine_Query::create ()->from ( __CLASS__ )->where ( 'id=?', $id )->execute ()->getFirst ();
	}
	public static function getByNamePlain($id = 0) {
		return Doctrine_Query::create ()->from ( __CLASS__ )->where ( 'title_plain=?', $id )->execute ()->getFirst ();
	}
	public static function getNewestPro($Condition = array(), $limit = 0) {
		$NewsCategories = Doctrine_Query::create ()->from ( __CLASS__ )->where ( 'status=?', 1 )->orderBy ( 'id DESC' )->limit ( $limit );
		foreach ( $Condition as $key => $item ) {
			$NewsCategories->addWhere ( $key, $item );
		}
		return $NewsCategories->execute ();
	}
	public function getProductBrands() {
		return $this->ProductBrands->title;
	}
	public function getLink() {
		return 'san-pham/' . $this->ProductCategories->title_plain . '/' . $this->title_plain . '.html';
	}
	public function getLinkDv() {
		return 'dong-vat/' . $this->ProductCategories->title_plain . '/' . $this->title_plain . '.html';
	}
	public function getLinkSv() {
		return 'sinh-vat-canh/' . $this->ProductCategories->title_plain . '/' . $this->title_plain . '.html';
	}
	public static function getProByCate($id, $Condition = array()) {
		$Product = Doctrine_Query::create ()->from ( __CLASS__ )->where ( 'product_categories_id=?', $id )->addWhere ( 'status=?', 1 );
		foreach ( $Condition as $key => $item ) {
			$Product->addWhere ( $key, $item );
		}
		return $Product->execute ();
	}
	
	public function status() {
		return self::getStatus ( $this->status );
	}
	
	public static function getStatus($status = '') {
		return ($status == '') ? self::$Status : self::$Status [$status];
	}
	
	public function getCreateDate() {
		return date ( Zend_Registry::get ( 'Setting' )->default_time_format_long, strtotime ( $this->created_date ) );
	}
	public function getSale() {
		if ($this->brand_price != 0) {
			$price = (($this->brand_price - $this->price) / $this->brand_price) * 100;
			return round ( $price ) . '%';
		}
	}
	
	public static function DeleteById($id = 0) {
		Doctrine_Query::create ()->delete ( __CLASS__ )->where ( 'id=?', $id )->execute ();
	}
	
	public function getImagesDir() {
		return 'uploads/product/' . $this->id;
	}
	
	private function images2xml($file = '', $images = array()) {
		$content = '<?xml version="1.0" encoding="UTF-8"?>';
		$content .= "\n	<images>\n		<photos>";
		if ($images)
			foreach ( $images as $key => $image ) {
				if ($image ['file'] == 'thumb.jpg' || $image ['file'] == '')
					continue;
				$content .= "\n\t\t\t<photo>";
				foreach ( $image as $key => $value ) {
					$content .= "\n\t\t\t\t<$key><![CDATA[$value]]></$key>";
				}
				$content .= "\n\t\t\t</photo>";
			}
		$content .= "\n		</photos>\n	</images>";
		file_put_contents ( $file, $content );
	}
	
	public function updateImages($images = array()) {
		$xml = "uploads/product/" . $this->id . '/images.xml';
		$content = '<?xml version="1.0" encoding="UTF-8"?>';
		$content .= "\n	<images>\n		<photos>";
		if ($images)
			foreach ( $images as $key => $image ) {
				if ($image ['file'] == 'thumb.jpg' || $image ['file'] == 'logo.jpg')
					continue;
				$content .= "\n			<photo>\n\t\t\t\t<caption><![CDATA[{$image['caption']}]]></caption>\n\t\t\t\t<file><![CDATA[{$image['file']}]]></file>\n\t\t\t</photo>";
			}
		$content .= "\n		</photos>\n	</images>";
		file_put_contents ( $xml, $content );
	}
	public function updateImagesThumb($images = array()) {
		$xml = "uploads/product/" . $this->id . '/thumb/images.xml';
		$content = '<?xml version="1.0" encoding="UTF-8"?>';
		$content .= "\n	<images>\n		<photos>";
		if ($images)
			foreach ( $images as $key => $image ) {
				if ($image ['file'] == 'thumb.jpg' || $image ['file'] == 'logo.jpg')
					continue;
				$content .= "\n			<photo>\n\t\t\t\t<caption><![CDATA[{$image['caption']}]]></caption>\n\t\t\t\t<file><![CDATA[{$image['file']}]]></file>\n\t\t\t</photo>";
			}
		$content .= "\n		</photos>\n	</images>";
		file_put_contents ( $xml, $content );
	}
	public function getImages() {
		$file = $this->getImagesDir () . DIRECTORY_SEPARATOR . 'images.xml';
		if (file_exists ( $file )) {
			$xml = new Zend_Config_Xml ( $file, 'photos' );
			if ($xml->photo) {
				$images = $xml->photo->toArray ();
				if (! is_array ( $images [0] ))
					$images = array (
							0 => $images 
					);
				return $images;
			} else
				return array ();
		} else
			return array ();
	}
	public function getImagesThumb() {
		$file = "uploads/product/" . $this->id . '/thumb/images.xml';
		if (file_exists ( $file )) {
			$xml = new Zend_Config_Xml ( $file, 'photos' );
			if ($xml->photo) {
				$images = $xml->photo->toArray ();
				if (! is_array ( $images [0] ))
					$images = array (
							0 => $images 
					);
				return $images;
			} else
				return array ();
		} else
			return array ();
	}
	public function removeImages($images = array()) {
		$dir = $this->getImagesDir ();
		foreach ( $images as $image ) {
			@unlink ( $dir . DIRECTORY_SEPARATOR . $image );
		}
	}
	public function removeImagesThumb($images = array()) {
		$dir = 'uploads/product/' . $this->id . '/thumb';
		foreach ( $images as $image ) {
			@unlink ( $dir . DIRECTORY_SEPARATOR . $image );
		}
	}
	public function getThumbImage() {
		$file_path = '/uploads/product/' . $this->id . '/thumb.jpg';
		
		return $file_path;
	}
	public static function getCategoriesMenu() {
		$AllCategories = ProductCategories::getWithOutPage ( array () );
		$menu = '';
		foreach ( $AllCategories as $category ) {
			$menu .= '<div class="items">';
			$Products = Products::getWithOutPage ( array (
					'product_categories_id=?' => $category->id 
			) );
			if ($Products->count () > 0) {
				$menu .= '<p class="title-i">' . $category->title . '</p>';
				$menu .= '<ul class="navDropdown">';
				foreach ( $Products as $Product ) {
					$menu .= '<li><a href="' . $Product->getLink () . '">' . $Product->title . '</a></li>';
				}
				$menu .= '</ul>';
			}
			$menu .= '</div>';
		}
		return $menu;
	}
}