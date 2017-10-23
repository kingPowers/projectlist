var yw = 750; //原始图片宽度
var ys = 1294; //原始图片高度

w = window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth;
h = window.innerHeight || document.documentElement.clientHeight || document.body.clientHeight;

$(document).ready(function() {
	ft();
});

$(window).resize(function() {
	ft();
});

function ft() {   
    $("body").css("width", w + "px");
    $("body").css("height", h + "px");
	
    $(".maxDiv").css("width", w + "px");
    $(".maxDiv").css("height", h + "px");
    
	
    $(".size2").css("font-size", parseInt(h * 0.032) + "px");
    $(".size").css("font-size", parseInt(h * 0.028) + "px");
    $(".si").css("font-size", parseInt(h * 0.024) + "px");
   
}

function jsh(a) {
	var j = (parseInt(w) * (parseInt(a) / yw));
	return j;
}
