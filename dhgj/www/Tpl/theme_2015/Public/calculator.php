<!DOCTYPE>
<html>
<style>
.content{width:360px;font-family:'微软雅黑';font-size:14px;padding-right:20px;}
.calculator-table{width:360px;color:#737373;}
.calculator-table tr td{height:50px;font-size:14px;}
.calculator-table tr td:first-child{width:100px;text-align:right;padding-right:15px;}
.calculator-table tr td input,.calculator-table tr td select{border:1px solid #d1d1d1;width:180px;height:30px;line-height:30px;padding-left:5px;color:#737373;float:left;}
.cal-btn{border-radius:3px;display:inline-block;width:110px;text-align:center;float:left;margin-left:10px;text-decoration:none;background-color:#C29540;height:30px;line-height:30px;border:1px solid #C29540;color:#FFF;}
.opa-div{width:360px;height:40px;margin:10px 0;}
.opa-div p{padding-left:120px;text-align:left;margin:0;overflow:hidden;white-space:nowrap;}
.bigDiv{width:340px;height:80px;margin:15px 15px;line-height:35px;border:2px solid #feedd6;background:#fff6ea;}
</style>
<div ><span>收益计算器</span></div>
<!--<p style="float:left;width:300px;height:50px;border:1px solid red;">收益计算器</p>-->
<div class="content">
	<table class="calculator-table">
		<input type="hidden" name="type" value="name">
		<tr>
			<td>投资金额:</td>
                        <td><input type="text" id="cal_amount"><span style="border:1px solid #d1d1d1;float:left;width:30px;height:28px;line-height:30px;background:#eeeeee;text-align:center;">元</span></td>
		</tr>
		<tr>
			<td>投资期限:</td>
			<td><input type="text" id="cal_time"><span style="border:1px solid #d1d1d1;float:left;width:30px;height:28px;line-height:30px;background:#eeeeee;text-align:center;">月</span></td>
		</tr>
		<tr>
			<td>年化利率:</td>
			<td><input type="text" id="cal_rate"><span style="border:1px solid #d1d1d1;float:left;width:30px;height:28px;line-height:30px;background:#eeeeee;text-align:center;">%</span></td>
		</tr>
		<tr>
			<td>还款方式:</td>
			<td>
				<select id="cal_repay" class="cal_select" style="width:215px;">
<!--					<option value="1" select="selected">等额本息</option>-->
					<option value="2"  select="selected">付息还本</option>
                                        <option value="3">本息到付</option>
				</select>
			</td>
		</tr>
	</table>
	<div class="opa-div">
		<a href="javascript:;" id="sub-cal" class="cal-btn" style="margin:0 15px 0 80px;">计算</a>
		<a href="javascript:;" id="reset-cal" class="cal-btn">重置</a>
	</div>
	<div class="opa-div bigDiv">
		<p>
			本息合计：<span style="font-size:17px;color:#FF3000;margin-right:45px;"><span id="compareincomemoney_total">0</span>元</span>
		</p>
		<p>
			利息收入：<span style="font-size:18px;color:#FF3000;"><span id="compareincomemoney_lixi">0</span>元</span>
		</p>
	</div>
</div>
<script src="_STATIC_/2015/js/jquery.min.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript">
var comparemoneyObj = $('#compareincomemoney_lixi');
var comparemoneyObj_T = $('#compareincomemoney_total');
$(function(){
	$('#sub-cal').click(function(){
		var type = $('#cal_repay').val();
		var amount = $('#cal_amount').val();
		var rate = $('#cal_rate').val();
		var time = $('#cal_time').val();
		if(!amount || /^[1-9][0-9]*$/.test(amount)==false){
			alert('输入的金额不正确');
			return; 
		}
		if(!time || /^[1-9][0-9]*$/.test(time)==false){
			alert('输入的时间不正确');
			return;
		}
		if(!rate || /^[0-9]+[\.]?[0-9]*$/.test(rate)==false){
			alert('输入的收益率不正确');
			return;
		}
		global_calculator.init(amount,rate,time,type);
	})

	$('#reset-cal').click(function(){
		$('#cal_amount,#cal_time,#cal_rate').val('');
		$('#cal_repay').val(1);
	})
})

var global_calculator = {
	T:0,//类型
	A:0,//本金
	D:0,//日
	M:0,//月
	N:0,//期次
	R:0,//月利率
	RM:0,//总利息
	AM:0,//本息
	L:[],//分期信息
	//清除输出内容
	clear:function(){
		this.T=this.A=this.D=this.M=this.N=this.R=this.RM=this.AM=0;
		this.L=[];
		comparemoneyObj.html('0');
		comparemoneyObj_T.html('0');
	},
	clearchecked:function(){
		repayment.attr('checked',false);
	},
	//等额本息
	debx:function(){
		var C=0, I=0, D=0;
		C = this.A * this.R * this.power( 1 + this.R, this.N ) / ( this.power( 1 + this.R, this.N ) -1 );
		this.AM = C * this.N;
		for (var i=0; i< this.N ; i++) {
			D = i == 0 ? this.A : this.L[ i-1 ][3];
			I = D * this.R;
			this.RM += I;
			this.L.push( [ I, C-I, C, D-( C-I )] );	
		}
	},
	//付息还本
	fxhb:function(){
		var I=this.A*this.R;
		for (var i=0; i< this.N ; i++) {
			this.RM += I;
			if(i < (this.N-1)){
				this.L.push( [ I, 0, I, this.A] );
			}else{
				this.L.push( [ I, this.A, this.A + I, 0] );
			}
		}	
		this.AM = this.A + this.RM ;
	},
	//本息到付
	bxdf:function(){
		var D=0,F=0;
		D = this.R * 12 / 365;
		//this.RM = this.A * D * this.D; 天数标计算方式
                this.RM = this.A*this.R*this.N;
		this.AM = this.RM + this.A;
		this.L.push([ this.RM, this.A, this.AM, F]);
	},
	power:function( x, y ){
		var t = x;
		while( y-- > 1 ){
			t *= x;
		}
		return t;
	},
	//显示输出
	show:function(){
		comparemoneyObj.html( format( this.RM.toFixed(2) ) );
		comparemoneyObj_T.html( format( (this.RM+this.A).toFixed(2) ) );
	},
	init:function(_amount,_rate,_time,_type){
		this.clear();
		this.T = _type;
		this.A = parseInt(_amount);
		this.R = parseFloat(_rate/100/12);
		this.N = _time;
		if(this.T==1){
			this.debx();
		}else if(this.T==2){
			this.fxhb();
		}else if(this.T==3){
			this.bxdf();
		}
		this.show();
	}
}
var format = function( s ){
	s=s.toString();
        s=s.replace(/^(\d*)$/,"$1.");
	s=(s+"00").replace(/(\d*\.\d\d)\d*/,"$1");
	s=s.replace(".",",");
	var re=/(\d)(\d{3},)/;
	while(re.test(s)){
	s=s.replace(re,"$1,$2");
	}
	s=s.replace(/,(\d\d)$/,".$1");
	return s.replace(/^\./,"0.");
}
</script>
</html>
{__NOLAYOUT__} 