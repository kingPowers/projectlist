<?php
/*
 * 下载管理类
 */

namespace manager\index\model;

class Download extends Common{
    
    private $contactTempletePath;//合同模板路径
    
    private $zipPath;//zip压缩文件路径
    
    private $allowExt = "zip,doc,docx,pdf";//允许下载的文件后缀
    
    /*
     * 下载文件
     *  
     */
    public function downloadFile($fileName,$path = null){
        if($path===null){
            $path = $this->getFileOfPath($fileName);
        }
//        if(mb_detect_encoding($fileName,"UTF-8",true)){
//            $fileName = iconv("utf-8","GBK",$fileName);
//        }
        
        if(!in_array(pathinfo($fileName,PATHINFO_EXTENSION),explode(",",$this->allowExt))){
            header("Content-type:text/html;charset=utf-8");
            exit("文件禁止被下载");
        }
        
        $abstractFile = $path.DS.$fileName;
        if(!is_file($abstractFile)){
            header("Content-type:text/html;charset=utf-8");
            exit("ERP罢工了，文件找不到了");
        }
        
        //开始下载
        $file = fopen($abstractFile,"r");
        header("Content-type: application/octet-stream");    
        header("Accept-Ranges: bytes");    
        header("Accept-Length: ".filesize($abstractFile));
        header("Content-Disposition: attachment; filename=".$fileName);
        echo fread ($file,filesize($abstractFile));    
        fclose($file);    
        exit(); 
    }
    /*
     * 合同模板路径
     */
    public function getContactTempletePath(){
        if($this->contactTempletePath===null){
            $this->contactTempletePath = UPLOAD_PATH."contact".DS;
        }
        return $this->contactTempletePath;
    }
    /*
     * Zip压缩文件路径
     */
    public function getZipPath(){
        if($this->zipPath===null){
            $this->zipPath = UPLOAD_PATH."zip".DS;
        }
        return $this->zipPath;
    }
    /*
     * 根据文件名称获取文件路径
     */
    public function getFileOfPath($fileName){
        $path = "";
        switch (strtolower(pathinfo($fileName,PATHINFO_EXTENSION))){
            case "pdf":
            case "doc":
            case "docx":$path = $this->getContactTempletePath();break;
            case "zip":$path = $this->getZipPath();break;
            default :$path = "";
        }
        return $path;
    }
}
