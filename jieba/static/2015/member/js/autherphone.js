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
        $(".fhwz").css("font-size", parseInt(h * 0.032) + "px");
		$(".zxwx").css("font-size", parseInt(h * 0.032) + "px");
		$(".phone").css("font-size", parseInt(h * 0.032) + "px");
		$(".phone2").css("font-size", parseInt(h * 0.032) + "px")
        $(".size").css("font-size", parseInt(h * 0.028) + "px");
		$(".tsize").css("font-size", parseInt(h * 0.025) + "px");
        $(".ssize").css("font-size", parseInt(h * 0.025) + "px");
        
}

function jsh(a) {
	var j = (parseInt(w) * (parseInt(a) / yw));
	return j;
}
