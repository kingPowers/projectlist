function box_verify(action,todo){
	action = action||'';
	todo = todo||'';
	jdbox.iframe('/public/boxverify.html',{data:genboxdata({action:action,todo:todo})});
}
function genboxdata(data){
	var string = 'r='+parseInt(Math.random()*1000);
	$.each(data,function(i,n){
		string += '&'+i+'='+n;
	});
	return string;
}
function refreshverifycode(id){
	$('#'+id).attr('src','/public/verifycode.html?'+Math.random());
}
function re_load(){
	//box_close();
	window.location.reload();
}
function repositionImgBtns(){
	var window_width = $(window).width();
	$('.imgBannerbtns').each(function(i){
		var left = (window_width-1000)/2+parseInt($(this).attr('offset_left'));
		$(this).css({'left':left+'px','top':$(this).attr('offset_top')+'px'});
	});
}
function referpage(){
	window.location.reload();
}
function string2json(string){
	try{
		eval('var result='+string);
		return result;
	}catch(e){
		alert(e.description);
		return false;
		exit;
	}
}
function notice(container,msgtype,content){
	var color = msgtype==1?'#090':(msgtype==0?'#f00':'#09c');
	$('#'+container).html('<span style="color:'+color+'">'+content+'</span>').show();
}
if (!Array.prototype.indexOf) {
	Array.prototype.indexOf = function(obj, start) {
	     for (var i = (start || 0), j = this.length; i < j; i++) {
	         if (this[i] === obj) { return i; }
	     }
	     return -1;
	}
}

if(!String.prototype.trim) {
	String.prototype.trim = function () {
		return this.replace(/^\s+|\s+$/g,'');
	};
}

if (!String.prototype.startsWith) {
	String.prototype.startsWith = function (searchString, position) {
        position = position || 0;
        return this.indexOf(searchString, position) === position;
   };
}
if(typeof Modernizr != 'undefined' && !Modernizr.input.placeholder){
	$(function(){
		$('[placeholder]').focus(function() {
			  var input = $(this);
			  if (input.val() == input.attr('placeholder')) {
			    input.val('');
			    input.removeClass('placeholder');
			  }
			}).blur(function() {
			  var input = $(this);
			  if (input.val() == '' || input.val() == input.attr('placeholder')) {
			    input.addClass('placeholder');
			    input.val(input.attr('placeholder'));
			  }
			}).blur();
		$('[placeholder]').parents('form').submit(function() {
			  $(this).find('[placeholder]').each(function() {
			    var input = $(this);
			    if (input.val() == input.attr('placeholder')) {
			      input.val('');
			    }
			  })
			});
	});
}
