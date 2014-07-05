<?php

class ProductController extends Zend_Controller_Action {
	
	public function indexAction() {
		//$this->_helper->layout()->disableLayout();
		//$this->_helper->viewRenderer()->setNoRender();
		$page = $this->getRequest ()->getParam ( 'page' );
		
		$condition = array (
				'status=?' => 1 
		);
		$this->view->headTitle ( 'Sản phẩm' );
		$type = $this->getRequest ()->getParam ( 'type' );
		$id = $this->getRequest ()->getParam ( 'id' );
		//print_r($this->getRequest()->getParams());die();
		$ProductCate = ProductCategories::getByNamePlain ( $id );
		
		
		//$ad=Ads::getByLocation(2);
		//print_r($ad['file']);die();
		if ($ProductCate) {
			$condition ['product_categories_id=?'] = $ProductCate->id;
		}
		switch ($type) {
			case 'all' :
				break;
			case 'new' :
				$condition ['is_new=?'] = 1;
				break;
			case 'promotion' :
				$condition ['is_sale_off>?'] = 0;
				break;
			default :
				$condition ['status=?'] = 1;
		}
		
		list ( $this->view->Pager, $this->view->Products ) = Products::getAll ( $condition, $page, Zend_Registry::get ( 'Setting' )->SAN_PHAM );
		
		if ($id) {
			$this->view->ProductCate = $ProductCate->title;
		} elseif (! $id) {
			$this->view->ProductCate = 'Sản phẩm mới';
		}
		
		$requests = $this->_request->getQuery ();
		
		if (count ( $requests ) > 0) {
			$Params = '';
			$i = 0;
			foreach ( $requests as $key => $request ) {
				
				if ($key != 'page' && $key != 'id') {
					if ($i ++ == 0) {
						$Params .= $key . '=' . $request;
					} else {
						$Params .= '&' . $key . '=' . $request;
					}
				}
			}
			$this->view->UrlPagging = ($Params != '') ? '?' . $Params . '&page={%page}' : '?page={%page}';
		} else {
			$this->view->UrlPagging = '?page={%page}';
		}
		$youarehere [] = array (
				'name' => 'Sản phẩm',
				'link' => 'san-pham.html' 
		);
		if ($ProductCate) {
			$youarehere [] = array (
					'name' => $ProductCate->title 
			);
		}
		// You Are Here
		
		$this->view->YouAreHere = $youarehere;
		//$this->_helper->layout->setLayout ( 'layout' );
		//print_r($requests);die();
		//print_r(Products::getProByCate($requests['id']));die();
		//$this->view->product_detail=Products::getById(55);
		/*if($this->getRequest()->isXmlHttpRequest()){
			print_r('coa');die();
			$id=$this->getRequest()->getParam('id');
			$mnews=new Products();
			$detail=$mnews->getById($id);	
			$_SESSION['cart'][]=$detail['id'].",".$detail['title'].",".$detail['price'];
			header('Content-type: application/json');
			echo json_encode($_SESSION['cart']);die();
		}*/
		
	}
	
	
	public function categoriesAction() {
		
		$page = $this->getRequest ()->getParam ( 'page' );
		$condition = array (
				'status=?' => 1 
		);
		$id = $this->getRequest ()->getParam ( 'id' );
		if ($id) {
			$Categories = ProductCategories::getByNamePlain ( $id );
			
			$condition ['product_categories_id=?'] = $Categories->id;
			$this->view->id = $id;
			$this->view->Categories = $Categories;
			$this->view->headTitle ( $Categories->title );
		} else
			$this->view->headTitle ( 'Sản phẩm' );
		list ( $this->view->Pager, $this->view->Products ) = Products::getAll ( $condition, $page );
		
	}
	
	public function detailAction() {
		$Product = Products::getByNamePlain ( $this->getRequest ()->getParam ( 'id' ) );
		// print_r($this->getRequest()->getParams());die();
		
		if ($Product) {
			$this->view->headTitle ( $Product->title );
			$this->view->Product = $Product;
			$this->view->Cate = $Product->ProductCategories;
			$this->view->Type = $Product->ProductType;
			
			// print_r($Product->product_categories_id);die();
			$this->view->ListPro = Products::getWithOutPage ( array (
					'status=?' => 1,
					'product_categories_id=?' => $Product->product_categories_id,
					'id!=?' => $Product->id 
			), 3 );
			$this->view->ProductsSale = Products::getWithOutPage ( array (), 1 );
			
			$youarehere [] = array (
					'name' => 'Sản phẩm',
					'link' => 'san-pham.html' 
			);
			$youarehere [] = array (
					'name' => $Product->ProductCategories->title,
					'link' => $Product->ProductCategories->getLink () 
			);
			$youarehere [] = array (
					'name' => $Product->title 
			);
			// You Are Here
			$this->view->YouAreHere = $youarehere;
		} else {
			$this->_redirect ( '.' );
		}
		
		$this->_helper->_layout->setLayout('layout2');
	}
	
	public function searchAction() {
	
		$this->view->headTitle ( 'Tìm kiếm' );
		//print_r($keyword = $this->getRequest ()->getParam('keyword'));die();
		if ($this->getRequest ()->getParam ( 'keyword' ) != '') {
			$keyword = $this->getRequest ()->getParam ( 'keyword' );
			//print_r($keyword);die();
			$condition = array (
					'status=?' => 1 
			);
			if ($keyword) {
				$condition ['title LIKE ? OR detail LIKE ? OR description LIKE ?'] = array (
						"%{$keyword}%",
						"%{$keyword}%",
						"%{$keyword}%" 
				);
			}
			$page = $this->getRequest ()->getParam ( 'page' );
			list ( $this->view->Pager, $this->view->Products ) = Products::getAll ( $condition, $page );
		}
		
		$youarehere [] = array (
				'name' => 'Tìm kiếm' 
		);
		// You Are Here
		$this->view->YouAreHere = $youarehere;
		$this->_helper->_layout->setLayout('layout2');		
	}

}