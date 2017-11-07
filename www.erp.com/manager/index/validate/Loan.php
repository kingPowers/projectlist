<?php
namespace manager\index\validate;

use \think\Validate;
/**
* 
*/
class Loan extends Validate
{
	
	protected function checkCertiNumber ($value,$rule,$data,$field,$title)
    {
        //$this->error = "是啊比";
        return "失败";
    }
}