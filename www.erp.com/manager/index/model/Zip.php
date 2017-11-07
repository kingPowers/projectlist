<?php
/*
 * zip压缩包管理
 */
namespace manager\index\model;

class Zip  extends Common{
    
    const ZIP_EXTENSION = "zip";//zip后缀

    private $autoDelete = true;//自动删除过期的文件
    
    public $expiredTime = 259200;//文件过期时间，默认3天
    
    private $zipObject;     //zip对象
    
    public $zipDir;         //默认目录
    
    public $fileName;       //zip文件名称
    
    public $fileList;       //待压缩文件列表
    
    public $dirList;        //待压缩空文件夹列表
    
    protected function initialize() {
        parent::initialize();
        if($this->zipDir===null){
            $this->zipDir = (new Download())->getZipPath();
        }
        
        if(!is_dir($this->zipDir)){
            mkdir($this->zipDir,0755,true);
        }
    }
    
    /*
     * 生成zip文件
     *    @param $fileName:zip文件名称
     *    @param $fromCache 是否从缓存中读取
     *   
     * @return string  文件全路径 
     * 
     *      eg.$zipObject = new Zip();
     *         $zipObject->addFile($absoluteFile);
     *         $zipObject->createZip("aa");
     */
    public  function createZip($fileName = null,$fromCache = false){
        //清空过期文件
        $this->deleteExpireFile();
        
        $this->fileName = $this->buildFileName($fileName?:$this->createFileName());
        
        //清空已存在压缩文件
        $absoluteFileName = $this->zipDir.DS.$this->fileName;
        if($fromCache && is_file($absoluteFileName)){
            return $absoluteFileName;
        }else{
            @unlink($absoluteFileName);
        }
        
        //覆盖模式生成zip文件
        if(!$resCode = $this->createObject()->open($absoluteFileName, \ZipArchive::CREATE)){$this->error = "文件压缩失败;code:".$resCode;return false;}
        //添加空目录
        foreach($this->dirList as $dir){
            $this->zipObject->addEmptyDir($dir);
        }
        //添加文件
        foreach ($this->fileList as $file){
            if(false==$this->zipObject->addFile($file[0], $file[1]?$file[1].DS.basename($file[0]):basename($file[0]))){
                $this->error = "文件未成功添加压缩包中，请稍后再试";
                return false;
            }
        }
        $this->zipObject->close();
        
        
        @touch($this->zipDir.DS.$this->fileName,time()+$this->expiredTime);
        
        
        return $this->zipDir.DS.$this->fileName;
    }
    /*
     * 添加文件列表
     *   @param $absoluteFile:绝对路径下文件
     *   @param $dir 放到zip压缩目录中
     * 
     *    eg.$this->addFile(UPLOAD_PATH."aa.jpg","picture"));//图片放到picture的目录中
     */
    public function addFile($absoluteFile,$dir = null){
//        if(mb_detect_encoding($absoluteFile,"UTF-8",true)){
//            $absoluteFile = iconv("utf-8","GBK",$absoluteFile);
//        }
        if(is_string($absoluteFile) && is_file($absoluteFile) && !in_array($absoluteFile,$this->fileList)){
            $this->fileList[] = [$absoluteFile,$dir];
            return true;
        }
       return FALSE; 
    }
    //批量添加文件
    public function addFiles(Array $absoluteFiles,$dir = null){
        foreach($absoluteFiles as $file){
            $result = $this->addFile($file,$dir);
            if(false===$result)return false;
        }
        return true;
    }
    /*
     * 添加目录列表
     *  eg. $this->addDir("picture/life_picture"); zip压缩文件中会生成一个空文件夹，
     */
    public function addDir($dirName){
//        if(mb_detect_encoding($dirName,"UTF-8",true)){
//            $dirName = iconv("utf-8","GBK",$dirName);
//        }
        $this->dirList[] = $dirName;
    }
    //创建zip对象
    protected function createObject(){
        if($this->zipObject===null){
            $this->zipObject = new \ZipArchive();
            if(!($this->zipObject instanceof \ZipArchive)){
                $this->zipObject = null;
                throw new \think\Exception("PHP未开启zlib");
            }
        }
        return $this->zipObject;
    }
    
    //分析文件名称
    protected function buildFileName($fileName){
        $pathinfo = pathinfo($fileName);
        if(strtolower($pathinfo["extension"])!= self::ZIP_EXTENSION){
            $fileName = $pathinfo["filename"].".".self::ZIP_EXTENSION;
        }
//        if(mb_detect_encoding($fileName,"UTF-8",true)){
//            $fileName = iconv("utf-8","GBK",$fileName);
//        }
        return $fileName;
    }
    
    //创建zip文件名称
    protected function createFileName() {
        return date("YmdHis")."_".rand(111,999);
    }
    
    //清空过期的文件
    public function deleteExpireFile(){
        if(!$this->autoDelete)return;
        foreach(scandir($this->zipDir) as $file){
            $absoluteFile = $this->zipDir.DS.$file;
            if(is_file($absoluteFile) && filemtime($absoluteFile)<=time()){
                @unlink($absoluteFile);
            }
        }
    }
}
