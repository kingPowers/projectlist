<?php 
class Sign{
	private $memberid = '';
    private $lastYear=null;
    private $lastMonth=null;
   
    private $curYear=null;
    private $curMonth=null;
    private $curWek=null;
    private $curDay=null;
    private $curDaySum=0;
   
    private $nextYear=null; 
    private $nextMonth=null;

    private $calendar=null;
    
    private $signNum = 0;

    public function __construct($memberid = '',$dateTime=null){
    	$this->curYear  = isset($_REQUEST['yeal']) && is_numeric($_REQUEST['yeal'])?$_REQUEST['yeal']:date('Y');
    	$this->curMonth = isset($_REQUEST['month']) && is_numeric($_REQUEST['month'])?$_REQUEST['month']:date('n');
    	$this->curDay = isset($_REQUEST['day']) && is_numeric($_REQUEST['day'])?$_REQUEST['day']:date('j');
		$this->memberid = $memberid;
        $this->init($dateTime);//初始化
        $this->createCalendar();//创建日历
        $this->isSign();//标记是否签到
        $this->signNum();//本月签到次数
    }
    
    public function __get($name){
    	return isset($this->$name)?$this->$name:"{$name} is not exists";
    }
    
    public function init($dateTime=null){      
        if(!empty($dateTime)){ //当月
            $this->curYear=date('Y',strtotime($dateTime));
            $this->curMonth=date('n',strtotime($dateTime));
            $this->curDay=date('j',strtotime($dateTime));            
        }
        $this->curWek=date('w',strtotime($this->curYear.'-'.$this->curMonth.'-1'));
        $this->lastMonth=$this->curMonth-1; 
        $this->lastYear=$this->curYear;
        if($this->lastMonth<0){
            $this->lastMonth=12;
            $this->lastYear-=1;
        }
        $this->nextMonth=$this->curMonth+1;
        $this->nextYear=$this->curYear; 
        if($this->nextMonth > 12){
            $this->nextMonth=1;
            $this->nextYear+=1;
        }
    }
    
    public function createCalendar(){
        $nextStr=$this->nextYear.'-'.$this->nextMonth.'-1 -1 days';
        $curDaySum=date('j',strtotime($nextStr));    
        $lastStr=$this->curYear.'-'.$this->curMonth.'-1 -1 days';
        $lastDaySum=date('j',strtotime($lastStr)); 
        $prefixLId=$this->lastYear.'-'.$this->lastMonth;
        $prefixCId=$this->curYear.'-'.$this->curMonth;
        $prefixNId=$this->nextYear.'-'.$this->nextMonth;

        if($this->curWek == 0){
            $lastMonthSum=6; 
        }else{
            $lastMonthSum=$this->curWek-1;
        }
        $lastMonthStart=$lastDaySum - $lastMonthSum+1;

        for($i=0,$j=1,$k=1;$i<42;$i++){
            $dateInfo=array();
            if($i<$lastMonthSum){ //上一个月
                $dateInfo['day']=$lastMonthStart + $i;
                $dateInfo['type']=1;
                $id=$prefixLId.'-'.$dateInfo['day'];
                $this->calendar[]=array('id'=>$id,
                                        'info'=>$dateInfo);
            }else if($j >= $curDaySum){//下一个月
                $id=$prefixNId.'-'.$k;
                $dateInfo['day']=$k;
                $dateInfo['type']=3;
                $this->calendar[]=array('id'=>$id,
                                        'info'=>$dateInfo);
                $k++;
            }else{//本月
                $dateInfo['day']=$j;
                $dateInfo['type']=2;
                $this->calendar[]=array('id'=>($prefixCId.'-'.$j),
                                        'info'=>$dateInfo);
                $j++;
                $this->curDaySum+=1;
            }
        }
    }
   
    //指定日期内是否签到
    private function isSign(){
    	if(''==$this->memberid)return '';
    	$starttime = $this->calendar[0]['id'];
    	$endtime = $this->calendar[count($this->calendar)-1]['id'];
    	$signList = M('sign')->field("date_format(`timeadd`,'%Y-%m-%d') as timeadd,score,remark,memberid")->where("type=1 and memberid='{$this->memberid}' and date_format(`timeadd`,'%Y-%m-%d')>='{$starttime}' and date_format(`timeadd`,'%Y-%m-%d')<='{$endtime}'")->select();
    	if(false!=$signList){
    		$timesArr = array_column($signList,'timeadd');
    		foreach($this->calendar as &$val)
    			$val['isSign'] = in_array($val['id'],$timesArr)?1:0;
    	}
    	
    }
    
    public function signNum(){
    	if(''==$this->memberid)return '';
    	$starttime = $this->calendar[0]['id'];
    	$endtime = $this->calendar[count($this->calendar)-1]['id'];
    	$this->signNum = M('sign')->where("type=1 and memberid='{$this->memberid}' and date_format(`timeadd`,'%Y-%m-%d')>='{$starttime}' and date_format(`timeadd`,'%Y-%m-%d')<='{$endtime}'")->count();
    	 
    }
}

?>