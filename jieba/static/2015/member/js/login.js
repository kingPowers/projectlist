var yw = 750; //原始图片宽度
var ys = 1290; //原始图片高度

w = window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth;
h = window.innerHeight || document.documentElement.clientHeight || document.body.clientHeight;

$(document).ready(function() {
	ft();
});

$(window).resize(function() {
	ft();
});

function ft() {   
	$(".maxDiv").css("height",jsh(1294)+"px");
	$(".bgDiv").css("height",jsh(1294)+"px");
        $(".fhwz").css("font-size", parseInt(w * 0.032) + "px");
        $(".srsize").css("font-size", parseInt(w * 0.060) + "px");
        
}

function jsh(a) {
	var j = (parseInt(w) * (parseInt(a) / yw));
	return j;
}
