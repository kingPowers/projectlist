var yw = 750; //ԭʼͼƬ���
var ys = 1420; //ԭʼͼƬ�߶�

w = window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth;
h = window.innerHeight || document.documentElement.clientHeight || document.body.clientHeight;

$(document).ready(function () {
    ft();
});

$(window).resize(function () {
    ft();
});

function ft() {
    $(".maxDiv").css("height", jsh(1420) + "px");
    $(".fhwz").css("font-size", parseInt(w * 0.045) + "px");
    $(".zxwx").css("font-size", parseInt(w * 0.045) + "px");
    $(".phone1").css("font-size", parseInt(w * 0.044) + "px");
    $(".phone2").css("font-size", parseInt(w * 0.044) + "px");
    $(".font1").css("font-size", parseInt(w * 0.044) + "px");
    $(".font2").css("font-size", parseInt(w * 0.044) + "px");
    $(".font3").css("font-size", parseInt(w * 0.044) + "px");
    $(".size").css("font-size", parseInt(w * 0.032) + "px");

}

function jsh(a) {
    var j = (parseInt(w) * (parseInt(a) / yw));
    return j;
}
