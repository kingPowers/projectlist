var yw = 750; //原始图片宽度
var ys = 1294; //原始图片高度
var w = window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth;
var h = window.innerHeight || document.documentElement.clientHeight || document.body.clientHeight;

function jsCSS() {
	var d = document;
	var dd = d.getElementsByClassName("d")[0];
	dd.style.height = jsh(100) + "px";
	this.ddCSS(dd);
	onload = function() {
		window.setTimeout(function() {
			lodingEND();
		}, 2000);
	}
}

jsCSS.prototype = {
	ddCSS: function(dd) {
		for (var i = 0; i < dd.getElementsByClassName("page1").length; i++) {
			var page1 = dd.getElementsByClassName("page1")[i];
			page1.style.top = jsh(15) + "px";
		}
		for (var i = 0; i < dd.getElementsByClassName("p1").length; i++) {
			var p1 = dd.getElementsByClassName("p1")[i];
			p1.style.fontSize = zk(0.03) + "px";
		}
	},
}

function jsh(a) {
	var j = (parseInt(w) * (parseInt(a) / yw));
	return j;
}

function zk(a) {
	var j = parseInt(w) * a;
	return j;
}

function lodingEND() {
	var outer = document.getElementsByClassName("outer");
	//document.getElementsByClassName("lodingICON")[0].style.opacity = "0";
	for (var i = 0; i < outer.length; i++) {
		outer[i].style.opacity = "1";
	}

}

new jsCSS();