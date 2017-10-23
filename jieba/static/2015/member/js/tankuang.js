// JavaScript Document
function tk(){
	$(".tkdiv_bg,.tkLayBg").show();
	$(".tkLayBg").height($(document).height());
	$("#tk_cancel").click(function(){$(".tkdiv_bg,.tkLayBg").hide()});
	$(".pic_fdj").click(function(){$(".tkdiv_bg,.tkLayBg").hide()});
	$("#tl_close").click(function(){$(".tkdiv_bg,.tkLayBg").hide()});	
}