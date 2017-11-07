<?php
/**
* 
*/
namespace services\controller;

use think\Request;
use think\Controller;
use services\model\Common;

class Index extends Controller
{
	public function __construct()
	{
		parent::__construct();
		set_error_handler([__CLASS__,'errorHandler']);
		set_exception_handler([__CLASS__,'exceptionHandle']);
	}
	public function index(Request $request)
	{
		$common = new Common();
		dump($common);
		return json_encode(array_merge($request->post(),['servertime' => time()]));
	}
	static function errorHandler ($errorno,$errorstr,$errorfile,$errorline,$errorcontext)
	{
		self::serverError($errorstr." ON ".$errorfile." AT " . $errorline,200);
	}
	static function exceptionHandle($ex)
	{
		self::serverError($ex->getMessage(),$ex->getCode());
	}
	static function serverError($msg = '',$code = 200)
	{
		header("Content-type: application/json");
		$code = (int)$code;
		$result = [
			'errCode' => $code,
			'errMsg'  => $msg,
			'dataresult' => '',
			'servertime' => time(),
		];
		exit(json_encode($result));
	}
}