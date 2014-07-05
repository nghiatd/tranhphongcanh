<?php
class AjaxController extends Zend_Controller_Action{
	public function addcartAction(){
		$id=$this->getRequest()->getParam('id');
		//unset($_SESSION['cart']);die();
		header('Content-type: application/json');
		if($this->getRequest()->isXmlHttpRequest()){
			if($this->getRequest()->getParam('act')=='add'){
				//print_r('add dadfi');die();
			$mnews=new Products();
			$detail=$mnews->getById($id);
			//unset($_SESSION['cart']);die();
			header('Content-type: application/json');
				if($_SESSION['cart']){
					foreach($_SESSION['cart'] as $key=>$value){
						//echo $key."/".$id;
						if($key==$id){
							$quan=split(",",$_SESSION['cart'][$id]);
							//echo $key."/".$id;die();
							//print_r($quan);die();
							$quan[3]=$quan[3]+1;
							//print_r($quan);
							$_SESSION['cart'][$id]=$detail['title'].",".$detail['price'].",".$id.",".$quan[3];
							echo json_encode($_SESSION['cart']);die();
						}
					}
				}
			$_SESSION['cart'][$id]=$detail['title'].",".$detail['price'].",".$id.",".$quan=1;
			echo json_encode($_SESSION['cart']);die();
			}else{
				unset($_SESSION['cart'][$id]);
				echo json_encode($_SESSION['cart']);die();
			}
		}
	}	
}