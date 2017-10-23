<include file="Article:header" />
<style>
#content{width:1140px;padding:30px;background:#FFF;margin:0 auto;line-height:25px;}
#content h1{width:1130px;padding-left:7px;font-size:18px;color:#626467;border-left:3px solid #090;font-weight:normal;margin-bottom:30px;}
#content table{width:450px;margin:0 auto;table-layout:fixed;}
#content table td{height:50px;font-size:14px;}
#content table td input[type="text"]{width:260px;height:40px;line-height:40px;padding:0 5px;border:1px solid #A8A8A8;border-radius:3px;}
#content table td .change_a{display:inline-block;width:20px;height:20px;background:url(_STATIC_/user/img/login_reg.png) no-repeat -305px -6px;margin:0 0 3px 2px;}
#content table td #get_smscode{display:inline-block;width:100px;height:40px;line-height:40px;margin-left:10px;border:1px solid #CDD7DB;color:#989FB0;border-radius:1px;background:#E8ECEF;border-radius:3px;text-align:center;text-decoration:none;}
#content table td .sub_btn{display:block;width:260px;height:40px;padding-top:10px;color:#FFF;border-radius:3px;text-align:center;text-decoration:none;font-size:15px;}
#copywrite{width:1200px;height:56px;line-height:56px;text-align:center;margin:0 auto;font-size:13px;color:#ADADAD;}
</style>
<div id="content">
	<table>
		<tr>
			<td style="width:86px;">您的手机号</td>
			<td style="width:314px;">
				<input type="text" id="mobilenumber">
			</td>
			<td style="width:40px;"></td>
		</tr>
		<tr>
			<td>手机验证码</td>
			<td>
				<input type="text" style="width:150px;" id="smscode">
				<a href="javascript:;" id="get_smscode">获取验证码</a>
			</td>
			<td></td>
		</tr>
		<tr>
			<td></td>
			<td>
				<a href="javascript:;" class="sub_btn ui-button ui-bg-brown" onclick="sendsmspassword();">发送新的密码到手机</a>
			</td>
			<td></td>
		</tr>
	</table>
</div>
<script>
$('#get_smscode').click(function(){
	var mobile = $('#mobilenumber').val();
	if(/^1[3|4|5|7|8][0-9]{9}$/.test(mobile)==false){
		return jdbox.alert(0,'请输入正确的手机号');
	}
	box_verify('忘记密码','top.sendsms');
});

function sendsms(){
	var mobile = $('#mobilenumber').val();
	if(/^1[3|4|5|7|8][0-9]{9}$/.test(mobile)==false){
		return jdbox.alert(0,'请输入正确的手机号');
	}
	$.post('/public/verifysms.html',{'mobile':mobile,'notmember':1},function(res){
		return jdbox.alert(res.status,res.info,"$('#smscode').focus()");
	},'json');
}

function sendsmspassword(){
	var mobile = $('#mobilenumber').val();
	var smscode= $('#smscode').val();
	$.ajax({
		url : '/public/get_newpassword.html',
		data:{mobile:mobile,smscode:smscode},
		cache : false,
		async : false,
		type : "POST",
		dataType : 'json',
		success : function (result){
			if(result.status){
				alert('新密码已发送至您的手机');
				top.location.href='/login.html';
			}else{
				return jdbox.alert(0,result.info);
			}
		}
	});
}			
</script>
<include file="Article:footer" />
{__NOLAYOUT__}