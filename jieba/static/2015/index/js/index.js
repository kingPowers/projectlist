var yw = 750; //原始图片宽度
var ys = 1294; //原始图片高度
var w = window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth;
var h = window.innerHeight || document.documentElement.clientHeight || document.body.clientHeight;

function jsCSS() {
	var d = document;
	var d1 = d.getElementsByClassName("d1")[0];
	d1.style.height = jsh(490) + "px";
	this.d1CSS(d1);
	var d2 = d.getElementsByClassName("d2")[0];
	d2.style.height = jsh(320) + "px";
	d2.style.marginTop = jsh(5) + "px";
	this.d2CSS(d2);
	var d3 = d.getElementsByClassName("d3")[0];
	d3.style.height = jsh(50) + "px";
	this.d3CSS(d3);
	var d4 = d.getElementsByClassName("d4")[0];
	d4.style.height = jsh(230) + "px";
	d4.style.paddingTop = jsh(18) + "px";
	this.d4CSS(d4);
        var d5= d.getElementsByClassName("d5")[0];
	d5.style.fontSize =  zk(0.06) + "px";
//	var dd = d.getElementsByClassName("d")[0];
//	d.getElementsByClassName("d5")[0].style.height = jsh(100) + "px";
//	dd.style.height = jsh(100) + "px";
//	this.ddCSS(dd);
//	onload = function() {
//		window.setTimeout(function() {
//			lodingEND();
//		}, 2000);
//	}
}

jsCSS.prototype = {
	d1CSS: function(d1) {
		d1.getElementsByClassName("bannerDiv")[0].style.height = jsh(470) + "px";
		var banner = d1.getElementsByClassName("banner");
		for (var i = 0; i < banner.length; i++) {
			banner[i].style.height = jsh(470) + "px";
			// banner[i].style.marginLeft = (i * parseInt(banner[i].offsetWidth)) + "px";
		}
		// d1.getElementsByClassName("doteDiv")[0].style.lineHeight = jsh(20) + "px";
		// d1.getElementsByClassName("doteh")[0].style.width = jsh(20) + "px";
		// for (var i = 0; i < d1.getElementsByClassName("dote").length; i++) {
		// 	d1.getElementsByClassName("dote")[i].style.width = jsh(16) + "px";
		// }
		// for (var i = 2; i <= 6; i++) {
		// 	document.getElementById("dote" + i).style.marginLeft = jsh(10) + "px";
		// }
		// d1.getElementsByClassName("doteDiv")[0].style.left = ((parseInt(w) - parseInt(d1.getElementsByClassName("doteDiv")[0].offsetWidth)) / 2) + "px";
		d1.getElementsByClassName("dingwei")[0].style.width = jsh(300) + "px";
		var dingweiDiv = d1.getElementsByClassName("dingweiDiv")[0]
		dingweiDiv.style.height = jsh(46) + "px";
		dingweiDiv.style.top = jsh(22) + "px";
		dingweiDiv.style.left = ((parseInt(w) - parseInt(d1.getElementsByClassName("dingwei")[0].offsetWidth)) / 2) + "px";
		var dwlogo = d1.getElementsByClassName("dwlogo")[0]
		dwlogo.style.width = jsh(25) + "px";
		dwlogo.style.left = jsh(44) + "px";
		dwlogo.style.top = jsh(8) + "px";
		var dwz = d1.getElementsByClassName("dwz")[0];
		dwz.style.width = jsh(185) + "px";
		dwz.style.fontSize = zk(0.04) + "px";
		dwz.style.lineHeight = parseInt(dingweiDiv.offsetHeight) + "px";
		dwz.style.left = jsh(82) + "px";
		d1.getElementsByClassName("addicon")[0].style.top = jsh(22) + "px";
	},
	d2CSS: function(d2) {
		var div1 = d2.getElementsByClassName("div1")[0];
		div1.style.height = (d2.offsetHeight / 2) + "px";
		var div2 = d2.getElementsByClassName("div2")[0];
		div2.style.height = (d2.offsetHeight / 2) + "px";
		for (var i = 1; i <= 6; i++) {
			var icon = document.getElementById("icon" + i);
			icon.style.width = (d2.offsetWidth / 3) + "px";
			icon.style.height = (d2.offsetHeight / 2) + "px";
			if (i <= 3) {
				icon.style.left = ((i - 1) * parseInt(icon.offsetWidth)) + "px";
			} else {
				icon.style.left = ((i - 4) * parseInt(icon.offsetWidth)) + "px";
			}
		}
		for (var i = 0; i < d2.getElementsByClassName("icont").length; i++) {
			d2.getElementsByClassName("icont")[i].style.width = jsh(90) + "px";
		}
		for (var i = 0; i < d2.getElementsByClassName("icons").length; i++) {
			var icons = d2.getElementsByClassName("icons")[i];
			icons.style.lineHeight = jsh(40) + "px";
			icons.style.fontSize = zk(0.03) + "px";
		}
	},
	d3CSS: function(d3) {
		var line = d3.getElementsByClassName("line")[0];
		line.style.top = jsh(8) + "px";
		var btz = d3.getElementsByClassName("btz")[0];
		btz.style.lineHeight = parseInt(d3.offsetHeight) + "px";
		btz.style.fontSize = zk(0.035) + "px";
		var ckgd = d3.getElementsByClassName("ckgd")[0];
		ckgd.style.lineHeight = parseInt(d3.offsetHeight) + "px";
		ckgd.style.fontSize = zk(0.035) + "px";
	},
	d4CSS: function(d4) {
		for (var i = 0; i < d4.getElementsByClassName("zsDiv").length; i++) {
			var zsDiv = d4.getElementsByClassName("zsDiv")[i];
			zsDiv.style.height = jsh(180) + "px";
			if (i == 0) {
				zsDiv.style.marginLeft = jsh(25) + "px";
			} else {
				zsDiv.style.marginLeft = ((jsh(25) * (i + 1)) + (i * parseInt(zsDiv.offsetWidth))) + "px";
			}
			d4.getElementsByClassName("zsImg")[i].style.height = jsh(150) + "px";
			var zsz = d4.getElementsByClassName("zsz")[i];
			zsz.style.height = jsh(38) + "px";
			zsz.style.lineHeight = jsh(38) + "px";
			zsz.style.fontSize = zk(0.03) + "px";
		}
	},
//	ddCSS: function(dd) {
//		for (var i = 0; i < dd.getElementsByClassName("page1").length; i++) {
//			var page1 = dd.getElementsByClassName("page1")[i];
//			page1.style.top = jsh(15) + "px";
//		}
//		for (var i = 0; i < dd.getElementsByClassName("p1").length; i++) {
//			var p1 = dd.getElementsByClassName("p1")[i];
//			p1.style.fontSize = zk(0.03) + "px";
//		}
//	},
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