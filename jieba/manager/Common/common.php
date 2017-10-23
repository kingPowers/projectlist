<?php


function getExcelIndexs($k){
	$word = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
	$base = count($word);
	if ($k<0) return '';
	else return getExcelIndexs(intval($k/$base)-1).$word[$k%$base];

}

function export_data_excel($data,$cols,$filename){
	vendor('PHPExcel.PHPExcel');
	vendor('PHPExcel.PHPExcel.IOFactory');
	vendor('PHPExcel.PHPExcel.Writer.Excel5');
	$PHPExcel = new PHPExcel();
	//输出内容如下：
	foreach($cols as $k => $v){
		$PHPExcel->getActiveSheet()->setCellValue(getExcelIndexs($k).'1',$cols[$k][1]);
	}
	if($data){
		$i = 2;
		$cols_count = count($cols);
		foreach($data as $val){
			for($j=0;$j<$cols_count;$j++){
				$PHPExcel->getActiveSheet()->setCellValueExplicit(getExcelIndexs($j).$i,$val[$cols[$j][0]],is_numeric($val[$cols[$j][0]])? PHPExcel_Cell_DataType::TYPE_NUMERIC :  PHPExcel_Cell_DataType::TYPE_STRING);
			}
			$i++;
		}
	}
	$xlsWriter = new PHPExcel_Writer_Excel5($PHPExcel);
	header("Content-Type: application/force-download");
	header("Content-Type: application/octet-stream");
	header("Content-Type: application/download");
	header('Content-Disposition:inline;filename="'.$filename.'"');
	header("Content-Transfer-Encoding: binary");
	header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
	header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header("Pragma: no-cache");
	$xlsWriter->save( "php://output" );
}