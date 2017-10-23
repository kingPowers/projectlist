<?php

/**
 * Description of CommonAction
 * 布局首页
 * @author Nydia
 */
class IndexAction extends Action{
    //框架加载
    public function index() {
    	//累计申请人次
    	$count = M('order')->count();
    	//累计放款金额
    	$sum = M('order')->sum('loanmoney');
    	$this->assign('data',array('total_count'=>$count,'total_sum'=>$sum));
        $this->display();
    }
    
    public function bf(){
//        import("Think.ORG.Util.Baofu");
//    	$dd = new Baofu(142);
//    	header("Content-type: text/html; charset=utf-8");
//        dump("单号：sn8219_1496374476638");
//    	dump($dd->prePaySearch("sn8219_1496374476638"));
//    	dump($dd->getError());
//        dump("---------------------------");
//         dump("单号：sn7884_1495008");
//    	dump($dd->prePaySearch("sn7884_1495008"));
//    	dump($dd->getError());
//        
//        dump("over");
    }
    public function eqian(){
        import("Think.ORG.Util.Eqian");
        $eqian = new Eqian();
        dump($eqian->delUserAccount("3E274FB216754D02B2D45DA5C87EE9BF"));
        dump($eqian->geterror());
        echo "error";
    }

}
