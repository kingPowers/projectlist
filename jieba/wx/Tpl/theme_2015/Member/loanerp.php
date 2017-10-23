<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <meta content="telephone=no,email=no" name="format-detection">
        <meta content="yes" name="apple-touch-fullscreen">
        <meta content="yes" name="apple-mobile-web-app-capable">
        <meta name="viewport" content="width=device-width,height=device-heght,initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <title>{$pageseo.title}</title>
        <link rel="stylesheet" href="_STATIC_/2015/member/css/loanerp.css" />
        <link href="_STATIC_/2015/css/public.css" rel="stylesheet" type="text/css"/>
        <script type="text/javascript" src="_STATIC_/2015/index/js/jquery-1.10.2.min.js"></script>
        <script src="_STATIC_/2015/member/js/loanerp.js" type="text/javascript"></script>
    </head>
    <body bgcolor="#efefef">
        <div class="headers">
            <img src="_STATIC_/2015/member/image/register/return.png" class="retrunImg"/>
            <span class="fhwz onesize"><a href="/member/myorder/status/{$_REQUEST['status']}" style="color:white;text-decoration:none;">返回</a></span>
            <span class="zxwx onesize">贷款详情</span>
            <a href='/member/loandetail/ContractNo/{$_REQUEST["ContractNo"]}'><span class="hqwx onesize">还款明细</span></a>
        </div>
        <!--head结束-->
        <div class="centerDiv">
            <div class="wordDiv">
                <div class="leftwordsDiv threesize">合同编号:</div>
                <div class="rightwordsDiv threesize">{$data.loanerp.ContractNo}</div>
            </div>
            <div style="width:97%;border-top:1px solid #efefef;margin-left:3%;"></div>
            <div class="wordDiv">
                <div class="leftwordsDiv threesize">状&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;态:</div>
                <div class="rightwordsDiv threesize">
       				<notempty name='data.loanerp.is_repayment'><eq name='data.loanerp.is_repayment' value='1'>待还款<else/>还款完成</eq></notempty>         
                </div>
            </div>
            <div style="width:97%;border-top:1px solid #efefef;margin-left:3%;"></div>
            <div class="wordDiv">
                <div class="leftwordsDiv threesize">申请日期:</div>
                <div class="rightwordsDiv threesize"><notempty name='data.loanerp.UpdateDate'>{:date('Y年m月d日',strtotime($data['loanerp']['UpdateDate']))}</notempty></div>
            </div>
            <div style="width:97%;border-top:1px solid #efefef;margin-left:3%;"></div>
            <div class="wordDiv">
                <div class="leftwordsDiv threesize">放款金额:</div>
                <div class="rightwordsDiv threesize"><notempty name='data.loanerp.ApplayMoney'>{$data.loanerp.ApplayMoney}元</notempty></div>
            </div>
            <div style="width:97%;border-top:1px solid #efefef;margin-left:3%;"></div>
            <div class="wordDiv">
                <div class="leftwordsDiv threesize">实际到帐金额:</div>
                <div class="rightwordsDiv threesize"><notempty name='data.loanerp.MatchMoney'>{$data.loanerp.MatchMoney}元</notempty></div>
            </div>
            <div style="width:97%;border-top:1px solid #efefef;margin-left:3%;"></div>
            <div class="wordDiv">
                <div class="leftwordsDiv threesize">剩余还款金额:</div>
                <div class="rightwordsDiv threesize"><notempty name='data.loanerp.leftMoney'>{$data.loanerp.leftMoney}元</notempty></div>
            </div>
            <div style="width:97%;border-top:1px solid #efefef;margin-left:3%;"></div>
            <div class="wordDiv">
                <div class="leftwordsDiv threesize">本期期数:</div>
                <div class="rightwordsDiv threesize"><notempty name='data.loanerp.periodes'>{$data.loanerp.periodes}期</notempty></div>
            </div>
            <div style="width:97%;border-top:1px solid #efefef;margin-left:3%;"></div>
            <div class="wordDiv">
                <div class="leftwordsDiv threesize">到期还款日:</div>
                <div class="rightwordsDiv threesize"><notempty name='data.loanerp.BackDate'>{:date('Y年m月d日',strtotime($data['loanerp']['BackDate']))}</notempty></div>
            </div>
            <div style="width:97%;border-top:1px solid #efefef;margin-left:3%;"></div>
            <div class="wordDiv">
                <div class="leftwordsDiv threesize">本期还款金额:</div>
                <div class="rightwordsDiv threesize"><notempty name='data.loanerp.BackTotalMoney'>{$data.loanerp.BackTotalMoney}元</notempty></div>
            </div>
        </div>
        <div class="secondDiv"></div><!--弹出灰色弹窗-->
        <div class="thirdDiv">
            <img src="_STATIC_/2015/member/image/account/border.png" class="borderImg">
            <p class="info tsize" style='font-weight:normal;top:17%'>您需要与客服联系确认身份证号码后<br/><br/>才可查看详情<a href="tel:400663966">400663966</a></p>
            <a href="javascript:void(0)"  onclick='history.go(-1);' class='jump_url'><input type="button" value="确定" class="surebtn ssize"/></a>
        </div><!--弹出内容-->
        <script type="text/javascript">
        	var is_back = "{$applycode_err}";//合同编号错误
        	if(is_back>0)showWindow('','');
            function showWindow(message,url){
                //$('.info').html(message);
                $(".secondDiv").css("display","block");
                $(".thirdDiv").css("display","block");
                //$('.jump_url').attr('href',url);
            }
            //测试触发点合同编号
            $('.leftwordsDiv').click(function(){
                $(".secondDiv").css("display","block");
                $(".thirdDiv").css("display","block");
            });
            $('.surebtn').click(function(){
                $(".secondDiv").css("display","none");
                $(".thirdDiv").css("display","none");
            });
        </script>
    </body>
</html>
{__NOLAYOUT__}