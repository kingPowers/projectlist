<div class="ui-brrow-wp">
	<div class="ui-container">
		<div class="ui-panel pg-applyloan">
			<h3><b class="ui-text-brown ui-text-large">借款流程</b>4步轻松搞定，即刻放标！</h3>
			<ul class="ui-steps">
				<li><span class="ui-bg-red"></span>在线填写资料申请</li>
				<li><span class="ui-bg-orange"></span>等待客服电话沟通确定资料</li>
				<li><span class="ui-bg-green"></span>实地考察抵押物</li>
				<li><span class="ui-bg-red"></span>审核通过开始放标</li>
			</ul>
		</div>
	</div>
</div>
<div class="ui-container pg-applyloan ui-mt20">
		<div class="ui-left">
			<form class="ui-form">
				<fieldset>
					<div class="ui-form-item">
						<label class="ui-label">真实姓名</label>
						<input class="ui-input " type="text"  placeholder="请输入真实姓名" name="name"/>
					</div>
					<div class="ui-form-item">
						<label class="ui-label">手机号码</label>
						<input class="ui-input " type="text"  placeholder="请输入手机号码" name="mobile"/>
					</div>
					<div class="ui-form-item">
						<label class="ui-label">验证码</label>
						<input class="ui-input ui-input-checkcode" type="text" name="smscode" placeholder="请输入验证码"/>
						<a href="javascript:void(0)" id="getsmscode" class="ui-button ui-button-big ui-bg-gray ui-text-center">获取短信验证码</a>
					</div>
					<div class="ui-form-item">
						<label class="ui-label">身份证号</label>
						<input class="ui-input " type="text"  placeholder="请输入身份证号" name="certinumber"/>
					</div>
					<div class="ui-form-item">
						<label class="ui-label">借款金额</label>
						<input class="ui-input " type="text"  placeholder="请输入借款金额" name="amount"/>
					</div>
					<div class="ui-form-item">
						<a href="javascript:void(0)" id="submit-btn" class="ui-button ui-button-big ui-bg-brown ui-text-center ui-text-large"><b>确认提交</b></a></div>
				</fieldset>
			</form>{__TOKEN__}
		</div>
		<div class="ui-right">
			<h3><b>重要声明</b></h3>
			<ul class="ui-list-block ui-authenticate-list statement">
				<li class="ui-list-item"><span class="zggm"></span><label>20岁以上公民</label></li>
				<li class="ui-list-item"><span class="zggj"></span><label>具有中国国籍</label></li>
				<li class="ui-list-item"><span class="clcq"></span><label>车辆产权</label></li>
			</ul>
			<h3 class="ui-clearfix"><b>申请条件</b></h3>
			<ul class="ui-list-block ui-authenticate-list condition">
				<li class="ui-list-item"><span class="sfz"></span><label>身份证</label></li>
				<li class="ui-list-item"><span class="djxs"></span><label>登记证、行驶证</label></li>
				<li class="ui-list-item"><span class="qxbd"></span><label>车辆全险保单</label></li>
				<li class="ui-list-item"><span class="xybg"></span><label>人行信用报告</label></li>
			</ul>
		</div>
	</div>
</div>
<script language="javascript">
var send = false;
var isMobile = function(mobile){
	if(mobile == '' || mobile.length != 11 || isNaN(mobile) || mobile.indexOf(1) > 0){
		return false;
	}
	return true;
};
var sendSms = function(){
	var mobile = $("input[name='mobile']").val();
	if(!isMobile(mobile)){
		return jdbox.alert(0,'请正确输入手机号码');
	}
	if(send){
		return jdbox.alert(0,'数据传送中...');
	}
	send = true;
	jdbox.alert(2);
	var data = {}
	$.post('/public/verifysms.html', {
		mobile: mobile,
		notmember: true
	}, function(result) {
		send = false;
		$('input[name="smscode"]').focus();
		return jdbox.alert(result.status, result.info);
	}, 'json');
};
$(function(){
	$('a#getsmscode').click(function(){
		var mobile = $("input[name='mobile']").val();
		if(!isMobile(mobile)){
			return jdbox.alert(0,'请正确输入手机号码');
		}
		box_verify('', 'top.sendSms');
	});
	$('a#submit-btn').click(function(){
		var data = {};
		$(data).attr('name',$("input[name='name']").val());
		$(data).attr('mobile',$("input[name='mobile']").val());
		$(data).attr('smscode',$("input[name='smscode']").val());
		$(data).attr('certinumber',$("input[name='certinumber']").val());
		$(data).attr('amount',$("input[name='amount']").val());
		if($(data).attr('name') == '' || $(data).attr('name').length < 2){
			return jdbox.alert(0,'请正确输入您的姓名');
		}
		if($(data).attr('mobile') == '您的手机号码'  ||  !isMobile($(data).attr('mobile'))){
			return jdbox.alert(0,'请正确输入您的手机号码');
		}
		if($(data).attr('smscode').length != 6 || isNaN($(data).attr('smscode'))){
			return jdbox.alert(0,'请正确输入您收到的验证码');
		}
		if($(data).attr('certinumber')==''){
			return jdbox.alert(0,'请正确输入您的身份证号');
		}
		if($(data).attr('amount')==''){
			return jdbox.alert(0,'请正确输入您的贷款金额');
		}
		jdbox.alert(2);
		$.post('/borrow/borrowadd.html',{'data':genboxdata(data)},function(R){
			if(!R.status){
				return jdbox.alert(0,R.info);
			}
			return jdbox.alert(1,R.info,'window.location.reload()');
		},'json');
	});
});
</script> 
