var yw = 750; //ԭʼͼƬ���
var ys = 1294; //ԭʼͼƬ�߶�

w = window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth;
h = window.innerHeight || document.documentElement.clientHeight || document.body.clientHeight;

$(document).ready(function () {
    ft();
});

$(window).resize(function () {
    ft();
});

function ft() {
    $(".maxDiv").css("height", jsh(1294) + "px");
    $(".fhwz").css("font-size", parseInt(h * 0.032) + "px");
    $(".zxwx").css("font-size", parseInt(h * 0.032) + "px");
    $(".font1").css("font-size", parseInt(h * 0.028) + "px");
    $(".font2").css("font-size", parseInt(h * 0.016) + "px");
    // $(".font3").css("font-size", parseInt(h * 0.018) + "px");
    // $(".font4").css("font-size", parseInt(h * 0.020) + "px");  
    $(".size2").css("font-size", parseInt(h * 0.028) + "px");
    $(".size3").css("font-size", parseInt(h * 0.028) + "px");
    $(".size4").css("font-size", parseInt(h * 0.028) + "px");

}

function jsh(a) {
    var j = (parseInt(w) * (parseInt(a) / yw));
    return j;
}
