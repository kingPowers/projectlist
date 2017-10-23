<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="renderer" content="webkit">
    <meta name="keywords" content="{$pageseo.keywords}">
    <meta name="description" content="{$pageseo.description}">
    <title>{$pageseo.title}</title>
    <script type="text/javascript">var WWW = "_WWW_", STATIC = "_STATIC_/2015/", APP = "_APP_";</script>
    <link rel="stylesheet" type="text/css" href="_STATIC_/2015/css/style.css">
    <link href="_STATIC_/2015/box/wbox.css" type="text/css" rel="stylesheet" />
	<script src="_STATIC_/2015/js/jquery.min.js" type="text/javascript" charset="utf-8"></script>
	<script src="_STATIC_/2015/js/jquery.box.js" type="text/javascript" charset="utf-8"></script>
	<script src="_STATIC_/2015/js/public.js" type="text/javascript" charset="utf-8"></script>
	<script src="_STATIC_/2015/js/pintuer.js" type="text/javascript" charset="utf-8"></script>
</head>
<body>
	<div class="ui-header <if condition="(MODULE_NAME neq 'Index') AND (ACTION_NAME neq 'Index')">ui-bd</if>">
		<div class="ui-top">
			<div class="ui-top-wp">
				<div class="ui-serviceinfo ui-right">
					<span class="ui-icon service-phone"></span>
					服务热线：{:C('SERVICE_TELEPHONE')}（9:00~18:00）
				</div>
			</div>
		</div>
		<div class="ui-header-container ui-clearfix">
			<div class="ui-logo ui-ring-hover ui-left"></div>
			<ul class="ui-nav ui-right">
				<li class="ui-nav-item">
					<a href="javascript:;" <eq name="MODULE_NAME" value="Index">class="active"</eq>>首页</a>
				</li>

				<li class="ui-nav-item">
					<a href="javascript:;" <eq name="MODULE_NAME" value="Zf">class="active"</eq>>我要投资</a>
				</li>
				<?php if(strpos($httphost,DOMAIN_ROOT) > 0):?>
				<li class="ui-nav-item">
					<a href="javascript:;" <eq name="MODULE_NAME" value="Borrow">class="active"</eq>>我要借款</a>
				</li>
				<?php endif;?>
				<li class="ui-nav-item">
					<a href="javascript:;" <eq name="MODULE_NAME" value="Member">class="active"</eq>>我的账户</a>
				</li>
				<li class="ui-nav-item">
					<a href="javascript:;" <eq name="MODULE_NAME" value="Cms">class="active"</eq>>关于我们</a>
				</li>
			</ul>
		</div>
	</div>
	<notempty name="currentactionname">
	<div class="ui-current-nav ui-clearfix">
		<a href="javascript:;">智信创富首页</a><span style="padding:0 5px;">></span><span>{$currentactionname}</span>
	</div>
	</notempty>
<div class="ui-wp preview-detail-wp">
<link href="_STATIC_/2015/js/lightbox/css/lightbox.css" rel="stylesheet" type="text/css" />
<div class="ui-container ui-mt20">
	<h3>
		<label>
			<span>{$loan['title']}</span>
			<span>（编号：{$loan['loansn']}）</span>
		</label>
	</h3>
	<div class="ui-grid13 ui-mt20 ui-project-intro ui-clearfix">
		<div class="ui-left">
			<ul class="ui-tabulata-data">
				<li>
					<p>年化利率</p>
					<h3><em class="ui-text-orange">{:number_format($loan['yearrate'],2)}</em> %</h3>
				</li>
				<li>
					<p>借款金额</p>
					<h3><em class="ui-text-red">{:($loan['loanmoney']/10000)}</em>万</h3>
				</li>
				<li>
					<p>借款期限</p>
					<h3>
						<eq name="loan['isloanday']" value="1">
                            <if condition="$loan['loanday'] eq 90">
                                <em class="ui-text-peagreen">3</em>月
                            <elseif condition="$loan['loanday'] eq 30" />
                                <em class="ui-text-peagreen">1</em>月
                            <else/>
                                <em class="ui-text-peagreen">{$loan['loanday']}</em>天
                            </if>
                            <em class="ui-text-peagreen">{$loan['loanday']}</em>天
                        <else/><em class="ui-text-peagreen">{$loan['loanmonth']}</em>个月</eq>
					</h3>
				</li>
			</ul>
			<ul class="ui-list-inline ui-clearfix">
				<li class="ui-list-item">
					<label>投资进度：</label>
					<div class="ui-meter-line">
						<span style="width:{$loan['tenderspeed']}%;"></span>
					</div>
					<span>{$loan['tenderspeed']}%</span>
				</li>
				<li class="ui-list-item">
					<label>已投金额：</label>
					<span>{:number_format($loan['tendermoney'],2)}元</span>
				</li>
				<li class="ui-list-item">
					<label>还款方式：</label>
					<span>{$repayment[$loan['repayment']]}</span>
				</li>
			</ul>
			<ul class="ui-list-inline ui-clearfix">
				<li class="ui-list-item">
					<label class="ui-text-brown">{$loan['category']['name']}</label>
					<span>欢迎投资人来公司实地考察和查看抵押原件！</span>
				</li>
			</ul>
		</div>
		<div class="ui-grid4 ui-project-invest ui-right">
			<ul class="ui-list-block">
				<li class="ui-list-item ui-text-big">
					<label>可投金额</label>
					<span class="ui-text-green">{:number_format($loan['lastmoney'],2)}</span>
					<em>元</em>
				</li>
				<li class="ui-list-item  ui-text-big">
					<label>账户余额</label>
					<span class="ui-text-red">{:number_format($_SESSION['member']['memberInfo']['availableAmount'],2)}</span>
					<em>元</em>
				</li>
				<li class="ui-list-item">
					<input type="text" class="ui-input" id="detail-tender-money" placeholder="最小投资金额{:number_format($loan['mintendermoney'],2)}元" />
				</li>
				<li class="ui-list-item ui-text-small">
					<label>预期收益</label>
					<span>￥<em id="detail-tender-rate">0.00</em></span>
				</li>
				<li class="ui-list-item">
					<a href="javascript:void(0)" class="ui-button ui-big-button ui-bg-brown" disabled="disabled">未开始</a>
					<a href="javascript:void(0)" target="_blank" class="ui-button ui-big-button ui-bg-peagreen ui-right">我要充值</a>
				</li>
			</ul>
		</div>
	</div>

	<div class="ui-grid13 ui-mt20 ui-project-info ui-clearfix">
		<h5><strong>借款人信息</strong></h5>
		<div class="ui-pannel">
			<ul class="ui-authenticate-list loandetail">
				<eq name="userinfo.mobile_approve" value="1">
				<li>
					<span class="mobile"></span>
					<label>手机认证</label>
				</li>
				</eq>
				<eq name="userinfo.card_approve" value="1">
				<li>
					<span class="sfz"></span>
					<label>身份证</label>
				</li>
				</eq>
				<eq name="userinfo.hukouben_approve" value="1">
				<li>
					<span class="hkb"></span>
					<label>户口本认证</label>
				</li>
				</eq>
				<eq name="userinfo.zxbg_approve" value="1">
				<li>
					<span class="zxbg"></span>
					<label>征信报告认证</label>
				</li>
				</eq>
				<eq name="userinfo.income_approve" value="1">
				<li>
					<span class="sszm"></span>
					<label>收入证明</label>
				</li>
				</eq>
				<eq name="userinfo.water_approve" value="1">
				<li>
					<span class="zzxajcx"></span>
					<label>流水证明</label>
				</li>
				</eq>
			</ul>
		</div>
	</div>

	<div class="ui-grid13 ui-mt20 ui-project-extend ui-clearfix">
		<h5><strong>项目概况</strong></h5>
		<div class="ui-pannel">
			<ul class="ui-list-inline ui-left">
				<li class="ui-list-item">
					<label class="ui-text-brown ui-text-default"><strong>借款用途:</strong></label>
					<span>{$userinfo['jiekuanyongtu']}</span>
				</li>
			</ul>
			<ul class="ui-list-inline ui-left">
				<li class="ui-list-item">
					<label class="ui-text-brown ui-text-default"><strong>还款来源:</strong></label>
					<span>{$userinfo['huankuanlaiyuan']}</span>
				</li>
			</ul>
		</div>
	</div>

	<div class="ui-grid13 ui-mt20 ui-car-info ui-clearfix">
		<h5><strong>车辆状况</strong></h5>
		<div class="ui-pannel">
			<ul class="ui-list-inline ui-left">
				<li class="ui-list-item">
					<label>车辆品牌:</label>
					<span class="ui-text-brown">{$userinfo['cheliangpinpai']}</span>
				</li>
				<li class="ui-list-item">
					<label>车辆年限:</label>
					<span class="ui-text-brown">{$userinfo['cheliangnianxian']}年</span>
				</li>
				<li class="ui-list-item">
					<label>过户次数:</label>
					<span class="ui-text-brown">{$userinfo['guohucishu']}次</span>
				</li>
			</ul>
			<ul class="ui-list-inline ui-left">
				<li class="ui-list-item">
					<label>车辆型号:</label>
					<span class="ui-text-brown">{$userinfo['cheliangxinghao']}</span>
				</li>
				<li class="ui-list-item">
					<label>评估价格:</label>
					<span class="ui-text-brown">{$userinfo['pinggujiage']}万元</span>
				</li>
				<li class="ui-list-item">
					<label>大修记录:</label>
					<span class="ui-text-brown">{$userinfo['daxiujilu']}</span>
				</li>
			</ul>
			<ul class="ui-list-inline ui-left">
				<li class="ui-list-item">
					<label>行驶里程:</label>
					<span class="ui-text-brown">{$userinfo['xingshilicheng']}公里</span>
				</li>
				<li class="ui-list-item">
					<label>新车价格:</label>
					<span class="ui-text-brown">{$userinfo['xinchejiage']}万元</span>
				</li>
				<li class="ui-list-item">
					<label>交通事故:</label>
					<span class="ui-text-brown">{$userinfo['jiaotongshigu']}</span>
				</li>
			</ul>
		</div>
	</div>

	<div class="ui-grid13 ui-mt20 ui-project-photo ui-clearfix">
		<h5><strong>抵押物信息</strong></h5>
		<div class="ui-imgscroll-box">
			<ul class="ui-imgscroll-box-header">
				<?php $number = 0;?>
				<foreach name="material['category']" item="vo">
				<php>$number += $vo['number'];</php>
				<li code="{$key}" index="{$number}" number="{$vo['number']}"><a href="javascript:;">{$vo['name']}</a><em></em></li>
				</foreach>
			</ul>
			<div class="ui-imgscroll-pannel">
				<div class="ui-imgscroll-pannel-box">
					<a href="javascript:;" class="ui-left"></a>
					<div style="position:relative;overflow:hidden;height:240px;width:470px;">
						<ul style="position:absolute;width:10000px;height:240px;top:0;left:0;">
							<foreach name="material['result']" item="vo">
							<li style="display:block;float:left;width:470px;height:240x;" code="{$vo['code']}">
								<span><a href="_STATIC_/{$vo['path']}" class="example-image-link" data-lightbox="example-set" title="{$vo['title']}" style="display:block;overflow:hidden;"><img src="_STATIC_/{$vo['path']}" style="width:470px;height:240px;"/></a></span>
								<label>{$vo['name']}</label>
							</li>
							</foreach>
						</ul>
					</div>
					<a href="javascript:;" class="ui-right"></a>
				</div>
				<div class="ui-imgscroll-link">
					<?php $number = 0;?>
					<foreach name="material['category']" item="vo">
					<ul class="ui-link-button" code="{$key}">
					<?php for($i=1;$i<=$vo['number'];$i++):?>
						<li><a href="javascript:;" index="{$number}">{$vo['name']}({$i})</a></li>
					<?php $number++; endfor;?>
					</ul>
					</foreach>
				</div>
			</div>
		</div>
	</div>

</div>
 <div class="ui-clearfix"></div>
</div>
<?php
$loanJson = array('loansn'=>$loan['loansn'],'repayment'=>$loan['repayment'],'loanmonth'=>$loan['loanmonth'],'loanday'=>$loan['loanday'],
'yearrate'=>$loan['yearrate'],'loanstatus'=>$loan['loanstatus'],'status'=>$loan['status'],'loanmoney'=>$loan['loanmoney'],
'tendermoney'=>$loan['tendermoney'],'mintendermoney'=>$loan['mintendermoney'],'newtender'=>$loan['newtender']);
$loanJson =  json_encode($loanJson) ;
$memberJson = json_encode(array('id'=>$_SESSION['member']['id'],'amount'=>$_SESSION['member']['memberInfo']['availableAmount'],
'newtender'=>$_SESSION['member']['memberInfo']['newtender']));
?>
<script type="text/javascript" src="_STATIC_/2015/js/lightbox/lightbox-2.6.min.js"></script>
<script type="text/javascript" src="_STATIC_/2015/js/invest-detail.js"></script> 
<script language="javascript">
//投标信息
var loanStr =  '{$loanJson}',
memberStr = '{$memberJson}',
loanJson = eval( '(' + loanStr + ')' ),
memberJson = eval( '(' + memberStr + ')' ),
//相册切换
numberLi = 0,
defaultSize = $('.ui-imgscroll-pannel-box ul li').outerWidth(),
ulcode = $('ul.ui-imgscroll-box-header >li:first').find('a').attr('code'),
setbuttonstatus = function(){
	var currentcode = $('.ui-imgscroll-pannel-box ul li').eq(numberLi).attr('code');
	//左右按钮状态
	if(numberLi<=0){
		$('.ui-imgscroll-pannel-box a.ui-left').addClass('unclick');
	}else{
		$('.ui-imgscroll-pannel-box a.ui-left').removeClass('unclick');
	}
	if(numberLi >= ($('.ui-imgscroll-pannel-box ul li').size()-1)){
		$('.ui-imgscroll-pannel-box a.ui-right').addClass('unclick');
	}else{
		$('.ui-imgscroll-pannel-box a.ui-right').removeClass('unclick');
	}
	//上下菜单切换
	if(currentcode != ulcode){
		ulcode = currentcode;
		$('ul.ui-imgscroll-box-header >li').each(function(){
			if($(this).hasClass('current')){
				$(this).find('a').removeClass();
				$(this).find('em').removeClass();
			}
			if($(this).attr('code') == ulcode){
				$(this).find('a').addClass('ui-text-brown');
				$(this).find('em').addClass('ui-icon bigarrows');
				$(this).addClass('current');
			}
		});
		$('.ui-imgscroll-pannel .ui-imgscroll-link >ul').each(function(){
			if($(this).attr('code') == ulcode){ $(this).show(); }else{ $(this).hide();}
		});
	}
	$('.ui-imgscroll-pannel-box ul').animate({'left':'-'+numberLi*defaultSize+'px'},'slow');
	$('.ui-imgscroll-pannel .ui-imgscroll-link >ul a').removeClass('current');
	$('.ui-imgscroll-pannel .ui-imgscroll-link >ul a').eq(numberLi).addClass('current');
},
//投资记录AJAX分页
initpage=0,p=0,sn="<?php echo $loan['loansn'];?>",
showajaxpage = function(type,count,pagenumber){
	var html = '<tr><td class="ui-text-center" colspan="4">第<span style="color:#c29540;">';
	html += p +'</span>页 / 共<span style="color:#c29540;">'+ pagenumber +'</span>页';
	if(p <= 1){
		html+= '<a href="javascript:void(0)" style="color:#a5a5a5;padding:0 3px;text-decoration:none;">首页</a>';
		html+= '<a href="javascript:void(0)" style="color:#a5a5a5;padding:0 3px;text-decoration:none;">上一页</a>';
	}else{
		html+= '<a href="javascript:void(0)" style="color:#c29540;padding:0 3px;" onclick="return ajaxgetrecord(\'prev\',1);">首页</a>';
		html+= '<a href="javascript:void(0)" style="color:#c29540;padding:0 3px;" onclick="return ajaxgetrecord(\'prev\');">上一页</a>';
	}
	if(p >= pagenumber ){
		html+= '<a href="javascript:void(0)" style="color:#a5a5a5;padding:0 3px;text-decoration:none;">下一页</a>';
		html+= '<a href="javascript:void(0)" style="color:#a5a5a5;padding:0 3px;text-decoration:none;">尾页</a>';
	}else{
		html+= '<a href="javascript:void(0)" style="color:#c29540;padding:0 3px;" onclick="return ajaxgetrecord(\'last\');">下一页</a>';
		html+= '<a href="javascript:void(0)" style="color:#c29540;padding:0 3px;" onclick="return ajaxgetrecord(\'last\','+pagenumber+');">尾页</a>';
	}
	html+='</td></tr>';
	$('.tab-invest-record tfoot').html(html);
},ajaxgetrecord = function(type,page){
	p = (type == 'last') ? ++p : --p;
	p = page||p;
	if(initpage > 0){
		jdbox.alert(2);
	}
	initpage++;
	$.post('/zf/detailextend.html',{'sn':sn,'p':p},function(response){
		jdbox.close();
		$('.tab-invest-record tbody').html(response.info);
		if(response.status){
			var count = response.data.count,pagenumber = response.data.pagenumber;
			showajaxpage(type,count,pagenumber);
		}
		return true;
	}, "json");
};
setbuttonstatus();
setTimeout(function(){ajaxgetrecord('last');},1000);
$(function(){
	//上菜单状态切换
	$('ul.ui-imgscroll-box-header >li').each(function(){
		$(this).hover(function(){
			if($(this).hasClass('current')){return false;}
			$(this).find('a').addClass('ui-text-brown');
			$(this).find('em').addClass('ui-icon bigarrows');
		},function(){
			if($(this).hasClass('current')){return false;}
			$(this).find('a').removeClass();
			$(this).find('em').removeClass();
		}).click(function(){
			$('ul.ui-imgscroll-box-header >li.current').find('a').removeClass();
			$('ul.ui-imgscroll-box-header >li.current').find('em').removeClass();
			$('ul.ui-imgscroll-box-header >li.current').removeClass();
			$(this).find('a').addClass('ui-text-brown');
			$(this).find('em').addClass('ui-icon bigarrows');
			$(this).addClass('current');
			numberLi = parseInt($(this).attr('index')) - parseInt($(this).attr('number'));
			setbuttonstatus();
		});
	});
	//左右按钮点击
	$('.ui-imgscroll-pannel-box a.ui-right').click(function(){
		if(numberLi >= ($('.ui-imgscroll-pannel-box ul li').size()-1)){
			return false;
		}
		numberLi++;
		setbuttonstatus();
	});
	$('.ui-imgscroll-pannel-box a.ui-left').click(function(){
		if(numberLi <= 0){
			return false;
		}
		numberLi--;
		setbuttonstatus();
	});
	//下按钮点击
	$('.ui-imgscroll-pannel .ui-imgscroll-link >ul a').click(function(){
		numberLi = parseInt($(this).attr('index'));
		setbuttonstatus();
	});
	//tab切换
	$('.ui-tab-nav li').click(function(){
		$('.ui-tab-nav li').removeClass('active');
		$(this).addClass('active');
		$('.ui-tab-panel').hide();
		$('.'+$(this).attr('for')).show();
	});
	//计时和投标
	$('h3.ui-countdown').showClock("{$loan['timestamp']}","{$loan['_startTime']}");
	$('a#detail-tender-button').tenderLoan({'inputObj':$('input#detail-tender-money'),'ratemoneyObj':$('em#detail-tender-rate')});
});
</script>
<div class="ui-footer">
	<p class="ui-text-center">智信创富金融信息服务（上海）有限公司 | <a href="http://www.miitbeian.gov.cn/" target="_blank">沪ICP备13036559号-4</a> <?php if(strpos($httphost,DOMAIN_ROOT) <= 0):?> <br/>合作支持：北京众鑫联合投资有限公司<?php endif;?></p>
</div>
<script type="text/javascript">
var module_name = "<?php echo MODULE_NAME;?>", coop_scroll =  function(name){
	var speed=30;
	var media_tab=document.getElementById(name+"-wp");
	var media_tab1=document.getElementById(name+"-wp-1");
	var media_tab2=document.getElementById(name+"-wp-2");
	media_tab2.innerHTML=media_tab1.innerHTML;
	function Marquee(){
		if(media_tab2.offsetWidth-media_tab.scrollLeft<=0){
			media_tab.scrollLeft-=media_tab1.offsetWidth
		}else{
			media_tab.scrollLeft++;
		}
	}
	var MyMar=setInterval(Marquee,speed);
	media_tab.onmouseover=function() {clearInterval(MyMar)};
	media_tab.onmouseout=function() {MyMar=setInterval(Marquee,speed)};
}
$(function() {
	if(module_name == 'Index'){
		coop_scroll('links-bank');
		coop_scroll('links-media');
	}
	$.fn.backTop = function() {
		var winh = $(window).height();
		var $backToTopEle = $('<a href="javascript:;" title="返回顶部" class="backtop"></a>').appendTo($(".ui-toolbox")).click(function() {
				$("html, body").animate({
					scrollTop: 0
				}, 120);
			}),
			$backToTopFun = function() {
				var st = $(document).scrollTop();
				(st > 0) ? $backToTopEle.show(): $backToTopEle.hide();

			};
		$(window).bind("scroll", $backToTopFun);
	};
	$(".ui-toolbox").backTop();
});
</script>
<script>
var _hmt = _hmt || [];
(function() {
  var hm = document.createElement("script");
  hm.src = "//hm.baidu.com/hm.js?649cc7995121f6b1f18f35f6101a7766";
  var s = document.getElementsByTagName("script")[0]; 
  s.parentNode.insertBefore(hm, s);
})();
</script>
<script type="text/javascript">
var _mvq = _mvq || [];
_mvq.push(['$setAccount', 'm-132786-0']); 
_mvq.push(['$setGeneral', '', '', /*用户名*/ '', /*用户id*/ '']);
_mvq.push(['$logConversion']);
(function() {
	var  mvl = document.createElement('script');
	mvl.type = 'text/javascript'; mvl.async = true;
	mvl.src = ('https:' == document.location.protocol ? 'https://static-ssl.mediav.com/mvl.js' : 'http://static.mediav.com/mvl.js');
	var s = document.getElementsByTagName('script')[0];
	s.parentNode.insertBefore(mvl, s); 
})();	
</script>
</body>
</html>
{__NOLAYOUT__}