(function($) {
	var _check = true,
	   _submit = false,
       remind = '';
	var checkData = function(val,element,remind = '',check_rule = '')
	{
		//alert(val);
		if(check_rule[0] == 'null')
		{
            if(val == '')
            {
            	remind += "不能为空";
            	checkError(element,remind);
            	return false;
            }else{
            	checkSuccess(element);
            	return true;
            }
		}
		if(check_rule[0] == 'minLength')
		{
            var length = check_rule[1];
            if(val.length<length)
            {
            	remind += "最小长度为"+length+"个字符";
            	checkError(element,remind);
            	return false;
            }else{
            	checkSuccess(element);
            	return true;
            }
		}
		if(check_rule[0] == 'fixLength')
		{
            var length = check_rule[1];
            if(val.length != length)
            {
            	remind += "长度为"+length+"个字符";
            	checkError(element,remind);
            	return false;
            }else{
            	checkSuccess(element);
            	return true;
            }
		}
		if(check_rule[0] == "repassword")
		{
			var password = $("input[name='password']").val();
      if((password == ''))
      {
        remind = "请输入确认密码";
        checkError(element,remind);
        return false;
      }
			if((password != val))
			{
				remind = "两次密码不一致";
        checkError(element,remind);
        return false;
			}
			checkSuccess(element);
      return true;
		}
	}
	var checkError = function(element,remind)
	{
        if(remind != '')
        {
        	$(element).siblings("label.input_r").css("color","red").html(remind);
        	$(element).siblings("label.input_r").show();
        }
	}
	var checkSuccess = function(element)
	{
		$(element).siblings("label.input_r").css("color","#01A6E9").html("√");
	}
	var resetpwd = function(submit)
	{
		//return false;
		if(submit)
		{
           var data = {};
           $(data).attr("verify_code",$("input[name='smscode']").val());
           $(data).attr("password",$("input[name='password']").val());
           $(data).attr("repassword",$("input[name='repassword']").val());
           console.log(data);
           jdbox.alert(2);
           //$(data).attr("_reset_mobile_",$("input[name='_reset_mobile_']"));
           $.post("/member/reset.html",data,function(F){
               jdbox.alert(F.status,F.info);
               if(F.status == 1)
               {
               	  window.location.href = "/member/reset_success";
               }
           },'json')
		} 
	}
	$.fn.extend({
		constract:function(check)
		{
			_check = check || false;
			if(false == _check)return false;
			$(this).find('input').each(function(i, element) {
				 $(this).focus(function(){
				 }).blur(function(){
                    if($(this).attr("needCheck") == 'need')
                    {
                        var check_rule = $(this).attr("check_rule").split(':');
                        var remind = $(this).attr("remind")?$(this).attr("remind"):'';
                        var val = $(this).val();
                        var fun = checkData(val,element,remind,check_rule);
                        eval(fun);
                    }
				 })
			});
		},
		submitfrom:function(formElement)
		{	
           //if(!_submit)return false;
           var formThis = formElement;
           $(this).click(function(){
           	 formThis.find("input").each(function(i,element){      	 	 
	              if($(this).attr("needCheck") == 'need'){
	           	  	 var check_rule = $(this).attr("check_rule").split(':');
	                 var remind = $(this).attr("remind")?$(this).attr("remind"):'';
	                 var val = $(this).val();
	           	  	 var fun = checkData(val,element,remind,check_rule);
	                  if(false==fun)
	                  {
	                  	jdbox.alert(0,remind+"格式错误");
	                  	_submit = false;
	                  	return false;
	                  }
	                  return _submit = true;
	              }
           	  })
           	  if(!_submit)
           	  {
           	  	return false;
           	 	 //resetpwd(_submit);
           	  }
           	  //alert(1);
              resetpwd(_submit) 
           })

		}
	})
}(jQuery))