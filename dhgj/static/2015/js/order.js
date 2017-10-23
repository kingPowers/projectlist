(function($){
	var _check = true;
	var _submit = false;
    var checkData=function(element)
				   {
				   	   var check_rule = $(element).attr("check_rule");
                       var remind = $(element).attr("remind")?$(element).attr("remind"):'';
                       var val = $(element).val();
				   	   if(check_rule=='null')
				   	   {
				   	     	if(val=='')
				   	    	{
				   	  		    remind += "不能为空";
				   	  		    checkError(element,remind);
				   	  		    return false;
				   	  	    }
				   	   }
				   	   if(check_rule=='frame_num')
				   	   {
				   	  	    if(!/^[0-9a-zA-Z]{17}$/.test(val))
				   	  	    {
				   	  		    remind += "为17位数字和字母的组合";
				   	  		    checkError(element,remind);
				   	  		    return false;
				   	  	    }
				   	   }
				   	   if(check_rule=='certiNumber')
				   	   {
				   	     	if(!/(^\d{15}$)|(^\d{18}$)|(^\d{17}(\d|X|x)$)/.test(val))
				   	    	{
				   	  	    	remind += "格式不正确";
				   	  	    	checkError(element,remind);
				   	  	    	return false;
				   	  	    } 	
				   	   }
				   	   checkSuccess(element);
				    }
   var checkError = function(element,remind)
					{

				        $(element).attr("valid",0).siblings("small.input_r").html(remind).show();
					}
   var checkSuccess = function(element)
					{
						$(element).attr("valid",1).siblings("small.input_r").hide();
					}
   $.fn.extend({
   	  check:function(check)
   	  {
   	  	_check = check || false;
   	  	if(!_check)return false;
   	  	$(this).find("input.input_or").each(function(i,element){
   	  		if($(element).val() !='')
   	  		{
                var fun = checkData(element);
                    eval(fun);
   	  		}
   	  		$(this).focus(function(){
   	  			$(element).siblings("small.input_r").hide();
   	  		}).blur(function(){
   	  			if($(this).attr("needCheck") == "need")
   	  			{
                    var fun = checkData(element);
                    eval(fun);
   	  			}
   	  		})
   	  	})
   	  },
   	  submitOrder:function(submit)
   	  {
   	  	 _submit = submit || false;
   	  	 if(!_submit)return false;
   	  	 var formThis = $(this);
   	  	 var data = {};    
         $(this).find(".btn_upo").click(function(){
         	var warn = '';
         	var position = '';
	         formThis.find("input.input_or").each(function(i,element){
	              var valid = $(this).attr("valid");
	              if(($(this).attr("needCheck")=="need")&&valid==0)
	              {
	                  $(this).siblings("small.input_r").html($(this).attr("remind")+"信息有误").show();
	                  warn += $(this).attr("remind")+"信息有误<br/>";
	                  if(position=='')position = $(this).offset();
	                  _submit = false;
	              }else{
	             	  $(data).attr($(this).attr("name"),$(this).val());
	              }
	         })
	         if(!_submit){
	         	$(window).scrollTop(position.top-200);
	         	jdbox.alert(0,warn);
	         	_submit = true;
	         	return false;
	         }         	
	         $(data).attr("oid",$("input[name='oid']").val()); 
	         $(data).attr("_edit_session_",$("input[name='_edit_session_']").val());
	         $(data).attr("sex",$("input[name='sex']:checked").val());
	         jdbox.alert(2);
	         //console.log(data);
	         $.post("/order/sub_edit_order.html",data,function(F){
                jdbox.alert(F.status,F.info);
                if(F.status==1)
                {
                	window.location.href = "/order/my_order_list";
                }
	         },'json')
         })
   	  }
   })
})(jQuery);