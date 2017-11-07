<?php
/*
 * 登录用户检索管理
 *      登录用户分类、角色、门店等筛选
 * 
 *      角色要求：
 *              分公司管理
 *                  区域经理
 *                  分公司经理
 *                  分公司客服
 *                  分公司评估师
 *                  分公司团队
 *                      分公司销售
 *              总部
 *                  风控管理中心
 *                      风控经理
 *                      初审
 *                      终审
 *                  稽核管理中心
 *  
 *      eg. UserSearch::getSearch();
 *           返回
 *          
 */
namespace manager\index\model;

class UserSearch  extends Common{
    const DOOR1 = "分公司管理";const DOOR2 = "区域经理";
    const DOOR3 = "分公司经理";const DOOR4 = "分公司客服";
    const DOOR5 = "分公司评估师";const DOOR6 = "分公司团队";
    const DOOR7 = "分公司销售";
    
    const COMPANY1 = "总部";const COMPANY2 = "风控管理中心";
    const COMPANY3 = "风控经理";const COMPANY4 = "初审人员";
    const COMPANY5 = "终审人员";const COMPANY6 = "稽核管理中心";
    
    private $userInfo = [];//当前登录用户信息
    
    private $storeInfo = [];//门店信息
    
    private $ruleInfo = [];//角色信息
    
    private $searchWhere = [];//where条件
    
    /*
     * 登录用户门店筛选、角色分类
     *  @return array
     */
   public static  function getSearch(){
       $userSearchModel = new UserSearch();
       return $userSearchModel->getWhere();
   }
   
   /*
    * 初始化模型
    */
   protected function initialize() {
       parent::initialize();
       $this->userInfo = User::getUserInfo();
       $this->getRule();
       $this->getStore();
       
   }
   
   public function getWhere(){
       if(empty($this->userInfo) || empty($this->ruleInfo))return $this->searchWhere;
       switch($this->ruleInfo["title"]){
           case self::DOOR7://门店销售
               $this->addWhere("storeId",$this->storeInfo[0]["id"]);
               $this->addWhere("salesMan",$this->userInfo["names"]);
               break;
           case self::DOOR4://门店客服
               $this->addWhere("storeId",$this->storeInfo[0]["id"]);
               break;
           case self::DOOR5://门店评估师
               $this->addWhere("storeId",$this->storeInfo[0]["id"]);
               break;
           case self::DOOR3://门店经理
               $this->addWhere("storeId",$this->storeInfo[0]["id"]);
               break;
           case self::COMPANY4://初审
               $this->addWhere("pendUid",$this->userInfo["id"]);
               break;
           
       }
       return $this->searchWhere;
   }

   protected function addWhere($name,$value){
       $this->searchWhere[$name] = $value;
   }
   
   //用户所在角色
   protected function  getRule(){
      if(empty($uid = $this->userInfo["id"]))return;
      $this->ruleInfo =  (new Role())->getUserRule($uid);
   }
   //用户所属门店
   protected function getStore(){
       if(empty($uid = $this->userInfo["id"]))return;
       $this->storeInfo = UserStore::getStores($uid);
   }
   
}
