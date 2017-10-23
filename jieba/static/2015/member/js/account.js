//var yw = 750; //原始图片宽度
//var ys = 1290; //原始图片高度

w = window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth;
h = window.innerHeight || document.documentElement.clientHeight || document.body.clientHeight;

$(document).ready(function() {
	ft();
});

$(window).resize(function() {
	ft();
});

function ft() {   
    $(".maxDiv").css("height",jsh(1388)+"px");
    $(".numsize").css("font-size", parseInt(h * 0.030) + "px");
    $(".qsize").css("font-size", parseInt(h * 0.024) + "px");
    $(".cqsize").css("font-size", parseInt(h * 0.032) + "px");
    $(".wzsize").css("font-size", parseInt(h * 0.002) + "px");
    $(".sharesize").css("font-size", parseInt(h * 0.020) + "px");
    $(".csize").css("font-size", parseInt(h * 0.024) + "px");
    $(".fxDiv").css("height", parseInt(h) + "px");
	$(".tsize").css("font-size", parseInt(h * 0.025) + "px");
    $(".ssize").css("font-size", parseInt(h * 0.025) + "px");
}

function jsh(a) {
	var j = (parseInt(w) * (parseInt(a) / yw));
	return j;
}
