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
        $(".onesize").css("font-size", parseInt(h * 0.028) + "px");
        $(".twosize").css("font-size", parseInt(h * 0.020) + "px");
        $(".threesize").css("font-size", parseInt(h * 0.028) + "px");
         $(".foursize").css("font-size", parseInt(h * 0.040) + "px");
}

function jsh(a) {
	var j = (parseInt(w) * (parseInt(a) / yw));
	return j;
}
