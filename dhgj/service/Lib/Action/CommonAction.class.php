<?php
class CommonAction extends Action {
	
	public function __CONSTRUNCT(){
		parent::__CONSTRUNCT();
	}
	public function _empty()
	{
		R("Index/index");
	}
}
?>
