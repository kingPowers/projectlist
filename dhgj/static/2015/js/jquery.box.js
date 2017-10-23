$.getScript(STATIC+"/js/wbox.js?2015");
var jdbox = {
	iframe : function(url,data){
		$("body").append('<input class="boxactiver" style="display:none;"><input class="wBox_close boxcloser" style="display:none;">');
		$(".boxactiver:last").wBox({requestType: "iframe",target:url,postdata:data}).click();
	},
	alert : function(type,content,back){
		var title ='',msg ='',bwp = '',titleClass='default',back=back||'';
		if(type==0){
			title = '错误';
			msg = '<div class="error-wp"><span class="icon"></span><span>'+content+'</span></div>';
			bwp = '<a href="javascript:;" onclick="jdbox.close();'+back+'" class="bt-error">确定</a>';
			titleClass = 'error';
		}else if(type==1){
			title = '成功';
			msg = '<div class="success-wp"><span class="icon"></span><span>'+content+'</span></div>';
			bwp = '<a href="javascript:;" onclick="jdbox.close();'+back+'" class="bt-success">确定</a>';
		}else if(type==2){
			title = '加载中...';
			msg = '<div class="wait-wp"><p><span class="icon"></span></p><p>'+(content?content:'')+'</p></div>';
		}
		var html  = '<div class="alert-wp">'+msg+'</div><div class="alert-button-wp">'+bwp+'</div>';
		$("body").append('<input class="boxactiver" style="display:none;"><input class="wBox_close boxcloser" style="display:none;">');
		$(".boxactiver:last").wBox({'title':title,'html':html,'titleClass':titleClass}).click();
	},
	info : function(title,content){
		title = title||'详情';
		content = content||'内容';
		var html = '<div style="width:360px;padding:20px;height:auto;overflow:hidden;line-height:25px;color:#444444;font-size:12px;text-align:center;">'+content+'</div>';
		$("body").append('<input class="boxactiver" style="display:none;"><input class="wBox_close boxcloser" style="display:none;">');
		$(".boxactiver:last").wBox({'title':title,'html':html}).click();
	},
	close : function(){
		$(".wBox_close:last").click();
	}
}