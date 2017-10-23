var screenWidth;
var screenHeight;
var w;
var h;
var jw;
var jh;

$(document).ready(function () {
    screenWidth = window.screen.width;
    screenHeight = window.screen.height;
    w = window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth;
    h = window.innerHeight || document.documentElement.clientHeight || document.body.clientHeight;
    jw = $(window).width();
    jh = $(window).height();
    ft();
});

$(window).resize(function () {
    screenWidth = window.screen.width;
    screenHeight = window.screen.height;
    w = window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth;
    h = window.innerHeight || document.documentElement.clientHeight || document.body.clientHeight;
    jw = $(window).width();
    jh = $(window).height();
    ft();
});

function ft() {
	//alert("123");
    if (parseInt(w) <= 1100) {
        w = 1100;
    }
	//alert(w);
    $(".main_red").css("width", parseInt(w) + "px");
	 $(".main_red").css("top", parseInt(h) + "0px");
	  $(".main_red").css("left", parseInt(w) + "0px");
    $(".main_red .gzImg").css("top",(parseInt($(".main_red").css("height")) * 0.23) + "px");
	$(".main_red .gzImg").css("left",((parseInt($(".main_red").css("width")) - parseInt($(".gzImg").css("width"))) /2) + "px");
	
	$(".main_red .qImg").css("top",(parseInt($(".main_red").css("height")) * 0.41) + "px");
	$(".main_red .qImg").css("left",(parseInt($(".main_red").css("width")) * 0.19) + "px");
	
	$(".main_red .icon1Img").css("top",(parseInt($(".main_red").css("height")) * 0.41) + "px");
	$(".main_red .icon1Img").css("left",(parseInt($(".main_red").css("width")) * 0.675) + "px");
	
	$("#red_h1Img").css("top",(parseInt($(".main_red").css("height")) * 0.60) + "px");
	$("#red_h1Img").css("left",(parseInt($(".main_red").css("width")) * 0.20) + "px");
	
	$("#red_h2Img").css("top",(parseInt($(".main_red").css("height")) * 0.60) + "px");
	$("#red_h2Img").css("left",(parseInt($(".main_red").css("width")) * 0.42) + "px");
	
	$("#red_h3Img").css("top",(parseInt($(".main_red").css("height")) * 0.60) + "px");
	$("#red_h3Img").css("left",(parseInt($(".main_red").css("width")) * 0.62) + "px");
	
	$("#red_h4Img").css("top",(parseInt($(".main_red").css("height")) * 0.715) + "px");
	$("#red_h4Img").css("left",(parseInt($(".main_red").css("width")) * 0.20) + "px");
	
	$("#red_h5Img").css("top",(parseInt($(".main_red").css("height")) * 0.715) + "px");
	$("#red_h5Img").css("left",(parseInt($(".main_red").css("width")) * 0.42) + "px");
	
	$(".main_red .icon2Img").css("top",(parseInt($(".main_red").css("height")) * 0.71) + "px");
	$(".main_red .icon2Img").css("left",(parseInt($(".main_red").css("width")) * 0.62) + "px");
}