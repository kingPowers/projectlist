//var d = document;
var w = window.innerWidth || d.documentElement.clientWidth || d.body.clientWidth;
var h = window.innerHeight || d.documentElement.clientHeight || d.body.clientHeight;
var headers, retrunImg, fhwz, zxwx, noneDiv, chooseDiv, threeDiv, fourDiv, fiveDiv;

var OriginalWidth = 750; //原始图纸的宽度

jsCSS = function() {
    //字体
    new styleCalculation(".sizeOne", "fontSize", (parseInt(w) * 0.047 + "px"));
    new styleCalculation(".sizeTwo", "fontSize", (parseInt(w) * 0.045 + "px"));
    new styleCalculation(".sizeThree", "fontSize", (parseInt(w) * 0.040 + "px"));

    new styleCalculation(".datepickerDiv", "width", (parseInt(w) + "px"));
    new styleCalculation(".datepickerDiv", "height", (parseInt(h) + "px"));

    new styleCalculation(".headers", "width", (parseInt(w) + "px"));
    new styleCalculation(".headers", "height", (getSize(86) + "px"));

    new styleCalculation(".sizeOne", "top", (((getOffset(".headers", "height") - getOffset(".sizeOne", "height")) / 2) + "px"));
    new styleCalculation(".retrunImg", "top", (((getOffset(".headers", "height") - getOffset(".retrunImg", "height")) / 2) + "px"));

    new styleCalculation(".noneDiv", "height", (getOffset(".headers", "height") + "px"));

    new styleCalculation(".part1", "marginTop", (getSize(13) + "px"));
    new styleCalculation(".sp", "height", (getSize(99) + "px"));
    new styleCalculation(".bt", "height", (getSize(99) + "px"));
    new styleCalculation(".bt", "lineHeight", (getOffset(".bt", "height") + "px"));
    new styleCalculation(".ipt", "height", (getSize(99) + "px"));
    new styleCalculation(".ipt", "lineHeight", (getOffset(".ipt", "height") + "px"));
    new styleCalculation(".jw", "height", (getSize(99) + "px"));
    new styleCalculation(".jw", "lineHeight", (getOffset(".jw", "height") + "px"));
    new styleCalculation(".righticonImg", "height", (getSize(25) + "px"));
    new styleCalculation(".righticonImg", "top", (((getOffset(".sp", "height") - getOffset(".righticonImg", "height")) / 2) + "px"));

    // new styleCalculation(".select", "marginTop", (getSize(90) + "px"));
    // new styleCalculation(".select", "height", (getSize(90) + "px"));
    // new styleCalculation(".select", "lineHeight", (getOffset(".select", "height") + "px"));

    this.jzOver();
}
jsCSS.prototype = {
    jzOver: function() {
        for (var i = 0; i < d.getElementsByClassName("outer").length; i++) {
            d.getElementsByClassName("outer")[i].style.opacity = "1";
        }
    }
}

new jsCSS();

//alue传入图纸上你测量出来的高度;(此方法只能用于高度溢出时测了层高度)
function getSize(value) {
    var v = parseInt(w) * (value / OriginalWidth);
    return v;
}