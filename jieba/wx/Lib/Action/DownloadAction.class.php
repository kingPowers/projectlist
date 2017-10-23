<?php
class DownloadAction extends Action {
    public function index(){
    import("Think.ORG.Util.wxjs");
   	$wxjs = new wxjs;
   	$signPackage = $wxjs->GetSignPackage();
   	$this->assign('signPackage',$signPackage);
    $this->display();
    }
  
}
?>