<?php

class App_View_Helper_Pagination2 extends Zend_View_Helper_Abstract {
	/**
	 * preDispatch function.
	 *
	 * Define the layout path based on what module is being used.
	 *
	 * @author Aaron (bachya1208[at]googlemail.com)
	 * @access public
	 * @param Zend_Controller_Request_Abstract $request        	
	 * @return void <ul>
	 *         <li class="Activities"><a href="#">1</a></li>
	 *         <li><a href="#">2</a></li>
	 *         <li><a href="#">3</a></li>
	 *         <li><a href="#">Next >></a></li>
	 *         </ul>
	 */
	public function Pagination2($pager, $text = '', $Condition = array(), $show_number = true, $style = 'advanced') {
		
		unset ( $Condition ['page'] );
		unset ( $Condition ['msg'] );
		$_t = '';
		foreach ( $Condition as $key => $item ) {
			if (is_array ( $item ))
				foreach ( $item as $a => $b )
					$_t .= '&' . $key . '[' . $a . ']=' . $b;
			else
				$_t .= '&' . $key . '=' . $item;
		}
		$text = str_replace ( '{%Condition}', $_t, $text );
		
		$pager_layout = new Doctrine_Pager_Layout ( $pager, new Doctrine_Pager_Range_Sliding ( array (
				'chunk' => 10 
		) ), $text );
		
		$str = '<ul class="pagination">';
		if ($pager->getPage () == 1 && $pager->getNextPage () > 1) {
			$str .= '<li class="blur">Trang trước</li>';
			$str .= '<li class="blur"><img width="13" height="13" src="images/page-back.png"></li>';
		}
		if ($pager->getPage () > 1) {
			$str .= '<li class="blur"><a href="' . str_replace ( '{%page}', $pager->getPreviousPage (), $text ) . '" class="prev">Trang trước</a></li>';
			$str .= '<li class="blur"><img width="13" height="13" src="images/page-back.png"></li>';
		}
		// $str .= $pager_layout->display ( array (), true );
		if ($pager->getPage () < $pager->getLastPage ()) {
			$str .= '<li><a href="' . str_replace ( '{%page}', $pager->getNextPage (), $text ) . '"><img width="13" height="13" src="images/page-next.png"></a></li>';
			$str .= '<li><a href="' . str_replace ( '{%page}', $pager->getNextPage (), $text ) . '" class="arr_right">Trang sau</a></li>';
		
		}
		if ($pager->getPage () == $pager->getLastPage ()) {
			$str .= '<li class="blur"><img width="13" height="13" src="images/page-next.png"></li>';
			$str .= '<li class="blur">Trang sau</li>';
		
		}
		$str .= '</ul>';
		if ($style == 'advanced')
			// $str = '<b>Trang:</b> ' . $str .= '<br>' . 'Hiển thị ' .
			// $pager->getFirstIndice () . ' - ' . $pager->getLastIndice () . '
			// của ' . $pager->getNumResults () . '/ ' . $pager->getLastPage
			// () . ' trang';
			return $str;
	}
}
