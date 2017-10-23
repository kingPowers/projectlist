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
						<eq name="loan['isloanday']" value="1"><em class="ui-text-peagreen">{$loan['loanday']}</em>天<else/><em class="ui-text-peagreen">{$loan['loanmonth']}</em>个月</eq>
					</h3>
				</li>
				<?php if($loan['loanstatus'] <= 1){ ?>
				<li>
					<p><em class="ui-icon time"></em>剩余时间</p>
					<h3 class="ui-countdown">
						<span id="ui-countdown-d"><b>0</b><b>0</b></span><label>天</label>
						<span id="ui-countdown-h"><b>0</b><b>0</b></span><label>时</label>
						<span id="ui-countdown-m"><b>0</b><b>0</b></span><label>分</label>
					</h3>
				</li>
				<?php } ?>
			</ul>
			<ul class="ui-list-inline ui-clearfix">
				<li class="ui-list-item">
					<label>投资进度：</label>
					<div class="ui-meter-line">
						<span style="position:relative;top:-6px;width:{$loan['tenderspeed']}%;"></span>
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
			   <?php if($loan['loanstatus'] == 0): ?>
					<a href="javascript:void(0)" class="ui-button ui-big-button ui-bg-brown" disabled="disabled">未开始</a>
			   <?php elseif($loan['loanstatus'] == 1): ?>
					<a href="javascript:void(0)" id="detail-tender-button" class="ui-button ui-big-button ui-bg-brown">确认投资</a>
			   <?php elseif($loan['loanstatus'] == 2): ?>
					<a href="javascript:void(0)" class="ui-button ui-big-button ui-bg-brown" disabled="disabled">已流标</a>
			   <?php elseif($loan['loanstatus'] == 3): ?>
					<a href="javascript:void(0)" class="ui-button ui-big-button ui-bg-brown" disabled="disabled">招标完成</a>
			   <?php elseif($loan['loanstatus'] == 4): ?>
					<a href="javascript:void(0)" class="ui-button ui-big-button ui-bg-brown" disabled="disabled">还款中</a>           
			   <?php elseif($loan['loanstatus'] == 5): ?>
					<a href="javascript:void(0)" class="ui-button ui-big-button ui-bg-brown" disabled="disabled">还款成功</a>
			   <?php endif; ?>
					<a href="/member/charge.html" target="_blank" class="ui-button ui-big-button ui-bg-peagreen ui-right">我要充值</a>
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

	<div class="ui-tab ui-grid13 ui-mt20 ui-clearfix">
		<div class="ui-tab-head">
			<ul class="ui-tab-nav">
				<li class="active" for="tab-invest-record">
					<a href="javascript:;">投资记录</a>
				</li>
				<li for="tab-repayment">
					<a href="javascript:;">还款方案</a>
				</li>
			</ul>
		</div>
		<div class="ui-tab-body">
			<div class="ui-tab-panel tab-invest-record">
				<table class="ui-table">
					<thead>
						<tr>
							<th>投标人</th>
							<th>财富等级</th>
							<th>投标金额</th>
							<th>利率</th>
							<th>投标时间</th>
						</tr>
					</thead>
					<tbody><tr><td colspan="4" class="ui-text-center">获取数据中。。</td></tr></tbody>
					<tfoot></tfoot>
				</table>
			</div>

			<div class="ui-tab-panel tab-repayment" style="display:none">
				<table class="ui-table ui-table-big">
					<thead>
						<tr>
							<th>序号</th>
							<th>编号</th>
							<th>还款截止日期</th>
							<th>当期本息</th>
							<th>剩余本金</th>
							<th>状态</th>
						</tr>
					</thead>
					<tbody>
					<foreach name="issuelist" item="vo"> 
						<tr>
							<td>{$vo['issuesn']|substr=-1}</td>
							<td>{$vo['issuesn']}</td>
							<td><?php echo $vo['endtime']=='0000-00-00 00:00:00'?'待满标生成':substr($vo['endtime'],0,10).' 23:59:59';?></td>
							<td class="ui-text-peagreen">
								￥{:number_format($vo['money'],2)}
								<em>元</em>
							</td>
							<td class="ui-text-orange">
								￥{:number_format($vo['residuemoney'],2)}
								<em>元</em>
							</td>
							<td>
							<?php if($vo['isrepayment']==1):?>
								<span class="ui-icon"></span>已还款
							<?php elseif($vo['status']==0):?>
								<span class="ui-icon wait"></span>待生成
							<?php else:?>
								<?php $startime=date('Y-m-d 00:00:00',strtotime($vo['starttime']));$endtime=date('Y-m-d 23:59:59',strtotime($vo['endtime']));
								if($startime > date('Y-m-d H:i:s') && $endtime <= date('Y-m-d H:i:s')): ?>
									<span class="ui-icon wait"></span>还款中
								<?php else: ?>
									<span class="ui-icon wait"></span>待还款
								<?php endif;?>
							<?php endif;?>
							</td>
						</tr>
					</foreach>
					</tbody>
					<tfoot><tr><td colspan="6">&nbsp;</td></tr></tfoot>
				</table>
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

$now = date('Y-m-d H:i:s');

if ($now<C('PRIZE_START') || $now>C('PRIZE_END')){
	$successnote = '提交成功';
}else{
	$successnote = '提交成功<a href="/activity/prize">立即抽奖</a>';
}
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
	$('a#detail-tender-button').tenderLoan({'inputObj':$('input#detail-tender-money'),'ratemoneyObj':$('em#detail-tender-rate'),'successnote':'<?php echo $successnote;?>'});
});
</script>