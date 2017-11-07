<?php
/*
 * PDF文件管理
 *      生成PDF的默认目录是：public/static/pdf/
 *      生成PDF的默认编码是UTF-8
 *  
 *          eg.  $pdf = new PDF(["fileName"=>"a.pdf","content"=>"文件内容"]);
 *               $pdf->createPDF();//生成PDF并返回文件名称
 */

namespace manager\index\model;

class PDF extends Common{
    private $pdfObject;
   
    public $expiredTime = 259200;//文件过期时间，默认3天
    
    public $autoDelete = true;//过期清除pdf文件
    
    public $savePath;//文件保存路径
    
    public $fileName;//文件名称
    
    public $title = 'erp管理系统';//文件标题
    
    public $content;//文件内容
    
    public $dest = "F";//文件输出类型
    
    public $charset = "UTF-8";//文件编码
    
    public $font = 'cid0cs';//字体
    
    public function __construct($options = [],$data = []) {
        parent::__construct($data);
        $attributes = $this->attributes();
        foreach($options as $attribute=>$value){
            if(in_array($attribute,$attributes)){
                $this->$attribute = $value;
            }
        }
        
        $this->preInit($options);
        
        $this->bootstrap();
    }
    
    public function preInit(&$options){
        if(!isset($options["savePath"])){
            $this->savePath = UPLOAD_PATH."pdf";
            
        }
        !is_dir($this->savePath)?mkdir($this->savePath,0755,true):'';
        
        if(!isset($options["fileName"])){
            throw new \think\Exception("PDF文件名称为空:fileName");
        }else{
            $this->fileName = basename($options["fileName"]);
            unset($options["fileName"]);
        }
        
        if(!isset($options["title"])){
            $this->title = $this->fileName;
        }else{
            $this->title = $options["title"];
        }
        
        if(!isset($this->content)){
            throw  new \think\Exception("PDF文件内容为空:content");
        }
        
    }

    //启动程序
    protected function bootstrap() {
        $this->createObject();
        
        //清空过期文件
        $this->deleteExpireFile();
    }
    //创建PDF扩展对象
    public function createObject(){
        if($this->pdfObject===null){
            vendor("tcpdf.erp");
            $this->pdfObject = new \MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true,$this->charset, false);
        }
        return $this->pdfObject;
    }
    
    /*
     * 生成PDF文件
     *    生成PDF文件返回文件绝对路径
     *    未生成PDF文件返回false
     */
    public function createPDF(){   
        $fileName = $this->savePath.DS.$this->fileName;
        $this->pdfObject->SetTitle($this->title);
        $this->pdfObject->SetFont($this->font,'',12);
        if(is_array($this->content)){//多个文件内容 合并成一个PDF文件
            foreach($this->content as $k=>$content){
                $this->pdfObject->writeHTML($this->resoveContent($content), false, false, true, false, '');
                ($k+1)==count($this->content)?:$this->pdfObject->AddPage();
            }
        }else{
            $this->pdfObject->writeHTML($this->resoveContent($this->content), false, false, true, false, '');
        }
        
        $this->pdfObject->Output($fileName,$this->dest);
        if(is_file($fileName)){
            @touch($fileName,time()+$this->expiredTime);
            return $fileName;
        }
        return false;
    }
    
    
    public function attributes(){
        $names = [];
        $class = new \ReflectionClass($this);
        foreach($class->getProperties(\ReflectionProperty::IS_PUBLIC) as $property){
            if(!$property->isStatic()){
                $names[] = $property->getName();
            }
        }
        return $names;
    }
    
    private function resoveContent($content){
        $search = $replace = [];
        preg_match_all("/<p\s*.*?>\s*(.*?)\s*<\/p>/", $content, $matches);
        
        foreach($matches[1] as $k=>$pContent){
            $search[$k] =  $pContent;
            if(strlen(strip_tags($pContent))>100){//超过指定字数的段落去掉HTML标签
                $replace[$k] = strip_tags($matches[1][$k],"<b><strong><u>");
            }else{
                $replace[$k] = $pContent;
            }
        }
        
        $content = str_replace($search, $replace,$content);
        $content = str_replace("“", "“&nbsp;&nbsp;",$content);
        return $content;
    }
    
    //清空过期的文件
    public function deleteExpireFile(){
        if(!$this->autoDelete)return;
        foreach(scandir($this->savePath) as $file){
            $absoluteFile = $this->savePath.DS.$file;
            if(is_file($absoluteFile) && filemtime($absoluteFile)<=time()){
                @unlink($absoluteFile);
            }
        }
    }
}
