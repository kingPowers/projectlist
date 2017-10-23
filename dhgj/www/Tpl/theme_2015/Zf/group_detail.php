<link href="_STATIC_/2015/js/lightbox/css/lightbox.css" rel="stylesheet" type="text/css" />
<div class="ui-container ui-mt20">
	<h3>
		<label>
			<span>{$loan['title']}</span>
			<span>（编号：{$loan['loansn']}）</span>
		</label>
	</h3>

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