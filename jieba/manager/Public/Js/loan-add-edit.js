var set_repayment = function( t ){
	var a = b = null;
	/*if(t==1){
		a = true;
		b = false;
	}*/
	if(t==2){
		a = false;
		b = true;
	}
	repayment.each(function(){
		var val = $(this).val();
		if(val>2){
			$(this).attr({'disabled':a,'checked':false});
		}else{
			$(this).attr({'disabled':b,'checked':false});
		}
	})
}
var calculator = {
	T:0,//类型
	A:0,//本金
	D:0,//日
	M:0,//月
	N:0,//期次
	R:0,//月利率
	RM:0,//总利息
	AM:0,//本息
	L:[],//分期信息
	//检测输入内容
	check:function(){
		if(this.T==3){
			if(loanday.val()<1){
				//loanday.focus();
				//this.clearchecked();
				//return false;
			}
			this.D = parseInt(loanday.val());
            this.M = parseInt(loanmonth.val());
		}else{
			if(loanmonth.val()<1){
				loanmonth.focus();
				this.clearchecked();
				return false;
			}
			this.N = this.M = parseInt(loanmonth.val());
		}
		if(loanmoney.val()<0){
			loanmoney.focus();
			this.clearchecked();
			return false;
		}
		this.A = parseFloat(loanmoney.val()*10000);
		if(!yearrate.val()){
			year_rate.focus();
			this.clearchecked();
			return false;
		}
		this.R = parseFloat(yearrate.val())/100/12;
		return true;
	},
	//清除输出内容
	clear:function(){
		this.T=this.A=this.D=this.M=this.N=this.R=this.RM=this.AM=0;
		this.L=[];
		issuelist.css({'display':'none'}).html('');
		totalrm.html('0');
		totalam.html('0');
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
        if(this.D){
            D = this.R * 12 / 360;
            this.RM = this.A * D * this.D;
            this.AM = this.RM + this.A;
        }else{
            this.RM = this.A * this.R * this.M;
            this.AM = this.RM + this.A;
        }
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
		var html='',i=0;
		for(i in this.L){
			var j = parseInt(i);
			html += (j==0) ? '<tr style="border-top:1px solid #dddddd;">' : '<tr>';
			html += '<td>'+(j+1)+'</td>';
    		html += '<td>'+this.L[i][2].toFixed(2)+'</td>';
    		html += '<td>'+this.L[i][0].toFixed(2)+'</td>';
    		html += '<td>'+this.L[i][1].toFixed(2)+'</td>';
    		html += '<td>'+Math.abs(this.L[i][3]).toFixed(2)+'</td>';
			html += '</tr>';
		}
		issuelist.show().html(html);
		totalrm.html(this.RM.toFixed(2));
		totalam.html(this.AM.toFixed(2));
	},
	init:function(v){
		this.clear();
		this.T = v;
		if(this.check()){
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
}
var form_submit = function(){
	var data = {};
	var title = $('input[name=title]').val();
	var cateid= $('select[name=mediacateid]').val();
    var bigcateid = $('select[name=bigcateid]').val();

	//var borrow_memberid=null;
	//if ($('select[name=borrow_memberid]').length>0) borrow_memberid= $('select[name=borrow_memberid]').val();

	if(dotype=='update'){
		if(!loanid)	return top.jdbox.alert(0,'缺少参数');
		$(data).attr('loanid',loanid);
	}
	if(!title){
		return top.jdbox.alert(0,'请输入标题');
	}
	$(data).attr('title',title);
	if(!cateid || !bigcateid){
		return top.jdbox.alert(0,'请选择分类');
	}
	$(data).attr('cateid',cateid);
    $(data).attr('category_admin',bigcateid);
	if(type.val()){
		//天标
		if(isloanday.is(':checked')){
			if(!loanday.val() || parseInt(loanday.val()) <= 0){
				return top.jdbox.alert(0,'请输入天数');
			}
			$(data).attr('loanday',parseInt(loanday.val()));
			$(data).attr('isloanday',1);
		}else{
			if(!loanmonth.val()){
				return top.jdbox.alert(0,'请输入借款月数');
			}
			$(data).attr('loanmonth',parseInt(loanmonth.val()));

        }
		$(data).attr('type',type.val());
	}else{
		return top.jdbox.alert(0,'请选择类型');
	}
    len = $('#pass').val().length;
    if(len>0){
        if(len<6 || len>20){
            return top.jdbox.alert(0,'密码长度必须在6位-20位之间');
        }
    }
    $(data).attr('password',$('#pass').val());
    var repayment_val = 0;
    repayment.each(function(){
        if($(this).is(':checked')){
            repayment_val = $(this).val();
        }
    })
    repayment_val = repayment.val();
    console.log(repayment_val);
    if(!repayment_val){
        return top.jdbox.alert(0,'请选择还款方式');
    }
    $(data).attr('repayment',repayment_val);


    if(!loanmoney.val()){
		return top.jdbox.alert(0,'请输入借款金额');
	}
	$(data).attr('loanmoney',parseFloat(loanmoney.val()));

    if(!mintendermoney.val()){
        return top.jdbox.alert(0,'请输入最低投标金额');
    }
    $(data).attr('mintendermoney',mintendermoney.val());

    if(!maxtendermoney.val()){
        //return top.jdbox.alert(0,'请输入最低投标金额');
    }
    $(data).attr('maxtendermoney',maxtendermoney.val());

    if(!investnumber.val()){
        //return top.jdbox.alert(0,'请输入最低投标金额');
    }
    $(data).attr('investnumber',investnumber.val());

	if(!year_rate.val()){
		return top.jdbox.alert(0,'请输入年化利率');
	}
	$(data).attr('year_rate',parseFloat(year_rate.val()));
    $(data).attr('regiverate',parseFloat(regiverate.val()));
    $(data).attr('activity_rate',parseFloat(activity_rate.val()));
    if(!yearrate.val()){
		return top.jdbox.alert(0,'请输入年化利率');
	}
	$(data).attr('yearrate',parseFloat(yearrate.val()));
	if(!realrate.val()){
		return top.jdbox.alert(0,'请输入实际利率');
	}
	$(data).attr('realrate',parseFloat(realrate.val()));

    var limittime = $('input[name=limittime]').val();
    if(!limittime || parseInt(limittime)<=0){
        return top.jdbox.alert(0,'募集期限必须大于0');
    }
    $(data).attr('limittime',limittime);
    $(data).attr('borrow_memberid',borrow_memberid.val());
    var lssuingtime = $('input[name=lssuingtime]').val();
	if(!lssuingtime){
		//return top.jdbox.alert(0,'请输入预计发布时间');
	}
	$(data).attr('lssuingtime',lssuingtime);

    var security = $('input[name=security]').val();
    $(data).attr('security',security);

	$(data).attr('newtender',$("input[name='newtender']").is(':checked')? 1:0);
	//$(data).attr('shortsn',$("input[name='shortsn']").val());
	//$(data).attr('lawyer',$("select[name='lawyer']").val());
	$(data).attr('redpacket',$("input[name='redpacket']").is(':checked')? 1:0);
	send = false;
	$.post('/Invest/loan'+dotype+'.html',{'data':genboxdata(data)},function(result){
		send = true;
		if(result.status){
			top.jdbox.alert(1,'操作成功')
			window.location='/Invest/loanedit/loanid/'+result.info+'.html';
		}else{
			return top.jdbox.alert(0,result.info)
		}
	},'json');
}
$(function($,_w){
	//贷款月份
	loanmonth.keyup(function(){
		var val = $(this).val();
		var max = 24;
		if(!/^\d$/.test(val)){
			if(/\d/.test(val)){
				$(this).val(parseInt(val));
			}else{
				$(this).val('');
			}
			if(parseInt(val)>max){
				$(this).val(max);
			}
		}
	});
	//净值标天标
	loanday.keyup(function(){
		var val = $(this).val();
		var max = 30;
		if(!/^\d$/.test(val)){
			if(/\d/.test(val)){
				$(this).val(parseInt(val));
			}else{
				$(this).val('');
			}
//			if(parseInt(val)>max){
//				$(this).val(max);
//			}
		}
	});
	//借款金额
	loanmoney.blur(function(){
		var val = $(this).val();
		if(!/^([1-9]\d*|\d+\.\d+)$/.test(val)){
			$(this).val('');
		}
	});
	//最低投资金额
	mintendermoney.blur(function(){
		var val = $(this).val();
		if(!/^([1-9]\d*|\d+\.\d+)$/.test(val)){
			$(this).val('');
		}
	});
	//贷款利率
	year_rate.blur(function(){
		var val = $(this).val();
		var max = 100;
		if(!/^(\d+|\d+\.\d+)$/.test(val)){
			$(this).val('');
		}else{
            var acti_val = parseFloat(activity_rate.val());
            var rete_val = acti_val ? acti_val + parseFloat(val) : val;
            yearrate.val(rete_val);
			realrate.val(rete_val);
		}
	});
    //活动利率
    activity_rate.blur(function(){
		var val = $(this).val();
		var max = 100;
		if(!/^(\d+|\d+\.\d+)$/.test(val)){
			$(this).val('');
		}else{
            var year_val = parseFloat(year_rate.val());
            var rete_val = year_val ? year_val + parseFloat(val) : val;
            yearrate.val(rete_val);
			realrate.val(rete_val);
		}
	});
	//设置标类型
	type.click(function(){
		return true;
		if($(this).val()==2){
			//isloanday.attr({'disabled':false});
		}else{
			//isloanday.attr({'disabled':true,'checked':false});
			loanday.attr({'disabled':true,'value':''});
			loanmonth.attr({'disabled':false});
		}
		set_repayment(1);
	})
	//设置天标
	/*isloanday.click(function(){
		if($(this).is(':checked')){
			loanmonth.attr({'disabled':true,'value':''});
			loanday.attr({'disabled':false});
			set_repayment(2);
		}else{
			loanmonth.attr({'disabled':false});
			loanday.attr({'disabled':true,'value':''});
			set_repayment(1);
		}
        calculator.clear();
	})*/
	//计算分期信息
	repayment.click(function(){
		calculator.init($(this).val());
	});
	//检测输入是否合法
	var _input_check_val = 0;
	$('input.input-check').focus(function(){
		val = $(this).val();
	}).blur(function(){
		if(val != $(this).val()){
			repayment.attr({'checked':false});
			if(isloanday.is(':checked')){
				$('.payment-radio2').attr({'disabled':false});
			}else{
				$('.payment-radio1').attr({'disabled':false});
			}
		}
	})
	//表单提交
	$('button.btn').click(function(){
		if(!send){
			return top.jdbox.alert(0,'表单提交中，请等待。。。');
		}
		form_submit();
	})
    $('#pass').keyup(function () {
        var strongRegex = new RegExp("^(?=.{8,})(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9])(?=.*\\W).*$", "g");
        var mediumRegex = new RegExp("^(?=.{7,})(((?=.*[A-Z])(?=.*[a-z]))|((?=.*[A-Z])(?=.*[0-9]))|((?=.*[a-z])(?=.*[0-9]))).*$", "g");
        var enoughRegex = new RegExp("(?=.{6,}).*", "g");
        var uperRegex = new RegExp("(?=.{20,}).*", "g");

        if (false == enoughRegex.test($(this).val())) {
            $('#div2').css("background-color","#F90");
            $('#passstrength').html('密码必须大于6位'); //密码小于六位的时候，密码强度图片都为灰色
        }else if(uperRegex.test($(this).val())){
            $('#div2').css("background-color","#F00");
            $('#passstrength').html('密码必须小于20位!'); //密码必须小于20位
        }else if (strongRegex.test($(this).val())) {
            $('#div2').css("background-color","#0000FF");
            $('#passstrength').html('强!'); //密码为八位及以上并且字母数字特殊字符三项都包括
        }else if (mediumRegex.test($(this).val())) {
            $('#div2').css("background-color","#7FFF00");
            $('#passstrength').html('中!'); //密码为七位及以上并且字母、数字、特殊字符三项中有两项，强度是中等
        }else {
            $('#div2').css("background-color","#F00");
            $('#passstrength').html('弱!'); //如果密码为6为及以下，就算字母、数字、特殊字符三项都包括，强度也是弱的
        }
        return true;
    });
    $('#cateid').change(function(){
        var id = $('#cateid').val();
        var borrower_id = $('#borrow_memberid').val();
        var bigcateid = $('#bigcateid').val();
        var mediacateid = $('#mediacateid').val();
        if(!id){
            return top.jdbox.alert(0,'参数错误');
        }
        var data = {};
        $(data).attr('is_ajax',true);
        $(data).attr('case','audit-status');
        $(data).attr('lId',id);
        $(data).attr('borrower_id',borrower_id);
        $(data).attr('bigcateid',bigcateid);
        $(data).attr('mediacateid',mediacateid);
        $(data).attr('type',4);
        top.jdbox.alert(2);
        $.post('/Invest/getMonthCategory.html',data,function(result){
            if(result.status == 0){
                return top.jdbox.alert(result.status,result.info);
            }
            console.log(result);
            var result = eval(result);
            if(result.data.loantype==0){
                isloanday.removeAttr('checked');
                loanmonth.val(result.data.loannum);
                loanday.val('');
            }else{
                isloanday.attr('checked', 'checked');
                loanday.val(result.data.loannum);
                loanmonth.val('');
            }
            security.val(result.data.security);
            year_rate_string = result.data.yearrate;
            year_rate.val(year_rate_string.substr(0,year_rate_string.length));
            yearrate.val(year_rate.val()+activity_rate.val());
            realrate.val(year_rate.val()+activity_rate.val());
            repayment.val(result.data.repayment);
            regiverate.val(result.data.regiverate);
            repaymentValue.val(result.data.paymentValue);
            calculator.init(repayment.val());
            return top.jdbox.alert(result.status,result.data.name);
        },'json');
    });
    $('#loanmoney').change(function(){
        calculator.init(repayment.val());
    });
    $('#activity_rate').change(function(){
        calculator.init(repayment.val());
    });
    $('#borrow_memberid').change(function(){
        var data = {};
        var telephoneId = $('#borrow_memberid').val();
        $(data).attr('is_ajax',true);
        $(data).attr('case','audit-status');
        $(data).attr('telephoneId',telephoneId);
        $.post('/Invest/getBorrowPerson.html',data,function(result){
            var results = eval(result);
            if(results.data == '1'){
                $('#is_check').text('验证通过').css({"color":"green","font-weight":"600"});
            }else{
                $('#is_check').text('验证失败').css({"color":"red","font-weight":"600"});
            }
        },'json');
    });
    $('#borrow_memberid').change(function(){
        var data = {};
        var borrower_id = $('#borrow_memberid').val();
        $(data).attr('is_ajax',true);
        $(data).attr('case','audit-status');
        $(data).attr('borrower_id',borrower_id);
        $(data).attr('type',1);
        $.post('/Invest/getMonthCategory.html',data,function(result){console.log('michelle');
            $('#bigcateid').empty();
            console.log(result);console.log('michelle');Aarea='';
            var results = eval(result);
            Aarea += '<option>'+'--请选择--';
            for(var m=0;m<results.data.length;m++){
                Aarea += '<option value="' + results.data[m].id + '">' + results.data[m].names;
            }
            $('#bigcateid').append(Aarea);
        },'json');
    });
    $('#bigcateid').change(function(){
        var data = {};
        var borrower_id = $('#borrow_memberid').val();
        var bigcateid = $('#bigcateid').val();
        $(data).attr('is_ajax',true);
        $(data).attr('case','audit-status');
        $(data).attr('borrower_id',borrower_id);
        $(data).attr('bigcateid',bigcateid);
        $(data).attr('type',2);
        $.post('/Invest/getMonthCategory.html',data,function(result){console.log('michelle');
            $('#mediacateid').empty();
            console.log(result);console.log('michelle');Aarea='';
            var results = eval(result);
            Aarea += '<option>'+'--请选择--';
            for(var m=0;m<results.data.length;m++){
                Aarea += '<option value="' + results.data[m].id + '">' + results.data[m].names;
            }
            $('#mediacateid').append(Aarea);
        },'json');
    });
    $('#mediacateid').change(function(){
        var data = {};
        var borrower_id = $('#borrow_memberid').val();
        var bigcateid = $('#bigcateid').val();
        var mediacateid = $('#mediacateid').val();
        $(data).attr('is_ajax',true);
        $(data).attr('case','audit-status');
        $(data).attr('borrower_id',borrower_id);
        $(data).attr('bigcateid',bigcateid);
        $(data).attr('mediacateid',mediacateid);
        $(data).attr('type',3);
        $.post('/Invest/getMonthCategory.html',data,function(result){console.log('michelle');
            $('#cateid').empty();
            console.log(result);console.log('michelle');Aarea='';
            var results = eval(result);
            Aarea += '<option>'+'--请选择--';
            for(var m=0;m<results.data.length;m++){
                Aarea += '<option value="' + results.data[m].id + '">' + results.data[m].names;
            }
            $('#cateid').append(Aarea);
        },'json');
    });
}(jQuery,window))