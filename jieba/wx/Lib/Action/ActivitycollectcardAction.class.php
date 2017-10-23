<?php
/**
 * 
 * 集卡活动
 * 
 * 
 * */
class ActivitycollectcardAction extends  CommonAction {
	private $defaultRule =  array(
			array('pro'=>25,"card_name"=>"孙悟空","card_type"=>"1",'img_url'=>"_STATIC_/2015/activity/image/card/ico_sun_b.png",'count'=>'','class'=>'show'),
			array('pro'=>25,"card_name"=>"猪八戒","card_type"=>"2",'img_url'=>"_STATIC_/2015/activity/image/card/ico_ba_b.png",'count'=>'','class'=>'hide'),
			array('pro'=>25,"card_name"=>"沙僧","card_type"=>"3",'img_url'=>"_STATIC_/2015/activity/image/card/ico_sha_b.png",'count'=>'','class'=>'hide'),
			array('pro'=>25,"card_name"=>"唐僧","card_type"=>"4",'img_url'=>"_STATIC_/2015/activity/image/card/ico_tang_b.png",'count'=>'','class'=>'hide'),
		);
    public $money = 1000;
    public $spot_number = 100;
    public $error;
    public $memberInfo;
    public $startDate = '2017-07-21';//开始日期
    public $endDate = '2017-08-31';//结束日期

	public function _initialize(){
                //APP登陆
		if(!empty($_REQUEST['token'])){
                    $_SESSION['token'] = $_REQUEST['token'];
		}
                
		if(false==$this->memberInfo && false==($this->memberInfo = $this->getMemberInfo())){
                    redirect("/member/login?redirecturl=".urlencode("/Activitycollectcard/index"));
                }
	}
	//首页
	public function index(){
            $this->beginTurn();//开始抽卡片
            $this->assign("myCard",$this->getCardInfo());//我的卡片
            $this->assign("activityInfo",$this->activityInfo());//活动信息
            $this->display();
	}
    private function getCardInfo()
    {
        $myCard = $this->getCard();
        $card_info = [];
        $defaultRule = $this->defaultRule;
        foreach ($defaultRule as &$value) {
            foreach ($myCard as $val) {
                if ($val['card_type'] == $value['card_type'])
                $value['count'] = $val['count'];
            }
        }
        return $defaultRule;
    }
	//开始抽奖送卡
	private function beginTurn(){
            if(time()<strtotime($this->startDate)){
                $this->error = "对不起，活动未开始！";
                return false;
            }
            if(time()>strtotime($this->endDate)){
                    $this->error = "对不起，活动已结束！";
                    return false;
            }
            
            if($this->spot_number-$this->successMember()<=0){
                $this->error = "名额已用完";
                return false;
            }
            //开始集卡
            foreach($this->getRepaymentOrder() as $order){
                $rule = $this->getRule();//dump($rule);
                if(count($rule)>0){
                    $cardInfo = $rule[rand(0,(count($rule)-1))];
                    M("activity_collectcard")->add(array_merge(["memberid"=>$this->memberInfo["id"],"order_id"=>$order["id"]],$cardInfo));
                }
            }
            return true;
                
	}
	//我的卡片
	private function getCard(){
            return  (array)M("activity_collectcard")
                            ->field("count(id) as count,card_type,card_name")
                            ->where(["memberid"=>$this->memberInfo["id"]])
                            ->group("card_type")->select();
	}
        //获取规则
        private function getRule(){
            $cardList = $this->getCard();
            //4选3集卡
            $rule = [];
            if(count($cardList)>=3){
                foreach($cardList as $val){
                    $rule[] = ['pro'=>25,"card_name"=>$val["card_name"],"card_type"=>$val["card_type"]];
                }
            }else{
                $cardTypes =  array_column($cardList,"card_type");
                foreach($this->defaultRule as $rules){
                    if(!in_array($rules["card_type"],$cardTypes)){
                        $rule[] = $rules;
                    }
                }
            }
            return $rule;
        }
        //已还款的订单
	private function getRepaymentOrder(){
            $where["c.back_real_time"] = ["between",[$this->startDate,$this->endDate]];//时间段
            $where["o.status"] = 2;//成单
            $where["c.status"] = 1;//已还款
            $where["c.late_fee"] = 0;//未逾期
            $where["o.memberid"] = $this->memberInfo["id"];
            $where["_string"] = "o.id=c.order_id and o.id not in(select order_id from activity_collectcard where memberid='{$this->memberInfo["id"]}')";
            return (array)M("`order` o,order_credit c")->field("o.id")->where($where)->select();
             //dump(M()->getLastSql());
        }
        //活动信息
        private function activityInfo(){
            //$this->startDate = date("Y年m月d日",strtotime($this->startDate));
            //$this->endDate = date("Y年m月d日",strtotime($this->endDate));
            $actInfo = ["startDate"=>date("Y年m月d日",strtotime($this->startDate)),"endDate"=>date("Y年m月d日",strtotime($this->endDate))];//活动日期
            $actInfo["leftDay"]=time()<strtotime($this->endDate)?floor((strtotime($this->endDate)-time())/86400):0;//剩余天数
            $actInfo["leftHour"]=time()<strtotime($this->endDate)?floor((strtotime($this->endDate)-time())%86400/3600):0;//剩余小时
            $actInfo["leftMinute"]=time()<strtotime($this->endDate)?floor((strtotime($this->endDate)-time())%3600/60):0;//剩余分钟
            $actInfo["leftSecond"] = time()<strtotime($this->endDate)?floor((strtotime($this->endDate)-time())%86400%60):0;//剩余秒
            $actInfo["successMember"] = $this->successMember();//成功集齐人数
            $actInfo['spot_number'] = $this->spot_number;//总获奖名额
            $actInfo['surplus_member'] = intval($this->spot_number - $actInfo['successMember']);//剩余获奖名额
            $actInfo['money'] = $this->money;
           return $actInfo;
        }
        //成功集卡人数
        private function  successMember(){
            $aleadyDays = time()>strtotime($this->startDate)?floor((time()-strtotime($this->startDate))/86400):0;
            if(date("Y-m-d ")>="2017-08-10" && date("Y-m-d ")<"2017-08-11"){//0-15天集卡成功0人
                $success = 28;
            }elseif(date("Y-m-d ")>="2017-08-11" && date("Y-m-d ")<"2017-08-12"){//0-15天集卡成功0人
                $success = 36;
            }elseif(date("Y-m-d ")>="2017-08-12" && date("Y-m-d ")<"2017-08-13"){//0-15天集卡成功0人
                $success = 40;
            }elseif(date("Y-m-d ")>="2017-08-13" && date("Y-m-d ")<"2017-08-14"){//0-15天集卡成功0人
                $success = 51;
            }elseif(date("Y-m-d ")>="2017-08-14" && date("Y-m-d ")<"2017-08-15"){//0-15天集卡成功0人
                $success = 59;
            }elseif(date("Y-m-d ")>="2017-08-15" && date("Y-m-d ")<"2017-08-16"){//0-15天集卡成功0人
                $success = 67;
            }elseif(date("Y-m-d ")>="2017-08-16" && date("Y-m-d ")<"2017-08-17"){//0-15天集卡成功0人
                $success = 80;
            }elseif(date("Y-m-d ")>="2017-08-17" && date("Y-m-d ")<"2017-08-18"){//0-15天集卡成功0人
                $success = 86;
            }elseif(date("Y-m-d ")>="2017-08-18" && date("Y-m-d ")<"2017-08-19"){//0-15天集卡成功0人
                $success = 92;
            }elseif(date("Y-m-d ")>="2017-08-19" && date("Y-m-d ")<"2017-08-20"){//0-15天集卡成功0人
                $success = 97;
            }elseif(date("Y-m-d ")>="2017-08-20" && date("Y-m-d ")<"2017-08-21"){//0-15天集卡成功0人
                $success = 97;
            }elseif(date("Y-m-d ")>="2017-08-21" && date("Y-m-d ")<"2017-08-22"){//0-15天集卡成功0人
                $success = 100;
            }elseif(date("Y-m-d ")>="2017-08-22"){//0-15天集卡成功0人
                $success = 100;
            }
            
            if(time()>=strtotime("2017-08-18 12:00:00")){
                $success = 100;
            }
            
            return $success;
        }
	
}