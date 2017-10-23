<?php
require_once('init.php');
define('LOG_NAME_PDO','order_upload');
$Log = Logger::getLogger(date('YmdHis'), LOG_PATH, FAlSE);
require_once(dirname(dirname(__FILE__))."/ThinkPHP/Extend/Vendor/PHPExcel/PHPExcel.php");
require_once(dirname(dirname(__FILE__)).'/ThinkPHP/Extend/Vendor/PHPExcel/PHPExcel/IOFactory.php');
require_once(dirname(dirname(__FILE__)).'/ThinkPHP/Extend/Vendor/PHPExcel/PHPExcel/Reader/Excel5.php');
$objReader = PHPExcel_IOFactory::createReader('Excel2007'); //use Excel5 for 2003 format
$date = date('Y-m-d',time()-24*60*60);
var_dump($date);
$excelpath=dirname(dirname(__FILE__)).'/order_upload/myexcel_'.$date.'.xlsx';
if(!file_exists($excelpath)){
    return;
}
if(!$objReader->canRead($excelpath)){
    $objReader = PHPExcel_IOFactory::createReader('Excel5');
    if(!$objReader->canRead($excelpath)){
        return;
    }
}
$objPHPExcel = $objReader->load($excelpath);
$sheet = $objPHPExcel->getSheet(0);
$highestRow = $sheet->getHighestRow();         //取得总行数
$highestColumn = $sheet->getHighestColumn(); //取得总列数
$error_mem = $error_data = array();
$succ_num = $succ_code = $error_num = $error_code = $error_recints_num = 0;
$Db = pdo_connect();
if ($Db == FALSE) {
    echo "PDO连接失败\n";
    return;
}
$sql = " truncate table order_upload";
$loanpdo = $Db->prepare($sql);
$loanpdo->execute();
for($i=2;$i<=$highestRow;$i++) {
    $mobile = $objPHPExcel->getActiveSheet()->getCell("T".$i)->getValue();
    $cer_card = $objPHPExcel->getActiveSheet()->getCell("Z".$i)->getValue();//身份证号码
    $total_num = $objPHPExcel->getActiveSheet()->getCell("AH".$i)->getValue();//总期数
    $return_num = $objPHPExcel->getActiveSheet()->getCell("AI".$i)->getValue();//已还期数
    $delay_num = $objPHPExcel->getActiveSheet()->getCell("AJ".$i)->getValue();//逾期期数
    $member_status = $objPHPExcel->getActiveSheet()->getCell("AL".$i)->getValue();//账户状态
    $name = $objPHPExcel->getActiveSheet()->getCell("C".$i)->getValue();//客户名称
    $department = $objPHPExcel->getActiveSheet()->getCell("A".$i)->getValue();//签约部门
    $product = $objPHPExcel->getActiveSheet()->getCell("B".$i)->getValue();//签约产品
    if(empty($total_num) || $member_status!='正常'){
        continue;
    }
        $data['mobile'] = $mobile;
        $data['cer_card'] = $cer_card;
        $data['total_num']= $total_num;
        $data['return_num']= $return_num;
        $data['delay_num']= $delay_num;
        $data['member_status']= $member_status;
        $data['name']= $name;
        $data['department']= $department;
        $data['product']= $product;
        $sql = "INSERT INTO `order_upload` (`mobile`,`cer_card`,`total_num`,`return_num`,`delay_num`,`member_status`,`name`,`department`,`product`) VALUES ('".$data['mobile']."','".$data['cer_card']."','".$data['total_num']."','".$data['return_num']."','".$data['delay_num']."','".$data['member_status']."','".$data['name']."','".$data['department']."','".$data['product']."')";
        $loanpdo = $Db->prepare($sql);
        $status = $loanpdo->execute();
        if(!$status) {
            $Log->info($data['mobile'].'写入失败');
        }
}





?>