<?php
/*
 * E签宝签约
 */
namespace manager\index\model;

class erpEqian extends Eqian{
    /*
     * 获取公司信息
     *  @return array
     *        [
     *          "merge"=>0//是否三证合一  1：是  0：否
     *          "company_code"=>"组织机构代码" 不可空
     *          "legal_mobile"=>"企业关联手机号" 不可空
     *          "companyname"=>"企业名称" 不可空
     *      ];
     *     eg.
     *          public function getCompanyInfo(){
     *              return $this->companyInfo = [
     *                     "merge"=>0//是否三证合一  1：是  0：否
                           "company_code"=>"组织机构代码" 不可空
                           "legal_mobile"=>"企业关联手机号" 不可空
                           "companyname"=>"企业名称" 不可空
     *              ];
     *          }     
     *      
     */
    public function getCompanyInfo() {
        return $this->companyInfo = [
                "merge"=>1,//是否三证合一  1：是  0：否
                "company_code"=>"91310113398662310G", //不可空
                "legal_mobile"=>"18917777723", //不可空
                "companyname"=>"智信创富金融信息服务(上海)有限公司", //不可空
            ];
    }
    /*
     * 获取用户信息
     * @return array
     *      eg.array(
     *          "names"=>"张三",//不可空
     *          "certiNumber"=>"身份证号",//不可空
     *          "mobile"=>"手机号",//不可空
     *          );
     *      
     *      eg. public function getMemberInfo(){
     *              return $this->memberInfo = [
     *                    "names"=>"张三",//不可空
                          "certiNumber"=>"身份证号",//不可空
                          "mobile"=>"手机号",//不可空
     *              ];
     *          }
     */
    public function getMemberInfo() {
        return $this->memberInfo = [
                    "names"=>"石向前",//不可空
                    "certiNumber"=>"371324199102144316",//不可空
                    "mobile"=>"13651833527",//不可空
                ];
    }

}
