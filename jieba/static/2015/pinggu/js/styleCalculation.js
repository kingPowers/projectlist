var d = document;
//classId：用于判断class还是id，styleCss：需要改变样式，value：需要计算的内容，
styleCalculation = function(classId, styleCss, value) {
    if (classId.substring(0, 1) === ".") {
        var cls = classId.substring(1);
        switch (styleCss) {
            case "width":
                this.widthByClassName(cls, value);
                break;
            case "height":
                this.heightByClassName(cls, value);
                break;
            case "lineHeight":
                this.lineHeightByClassName(cls, value);
                break;
            case "fontSize":
                this.fontSizeByClassName(cls, value);
                break;
            case "top":
                this.topByClassName(cls, value);
                break;
            case "left":
                this.leftByClassName(cls, value);
                break;
            case "marginTop":
                this.marginTopByClassName(cls, value);
                break;
            case "marginLeft":
                this.marginLeftByClassName(cls, value);
                break;
            case "paddingTop":
                this.paddingTopByClassName(cls, value);
                break;
            case "paddingLeft":
                this.paddingLeftByClassName(cls, value);
                break;
        }
    } else if (classId.substring(0, 1) === "#") {
        var id = classId.substring(1);
        switch (styleCss) {
            case "width":
                this.widthById(id, value);
                break;
            case "height":
                this.heightById(cls, value);
                break;
            case "lineHeight":
                this.lineHeightById(cls, value);
                break;
            case "fontSize":
                this.fontSizeById(cls, value);
                break;
            case "top":
                this.topById(cls, value);
                break;
            case "left":
                this.leftById(cls, value);
                break;
            case "marginTop":
                this.marginTopById(cls, value);
                break;
            case "marginLeft":
                this.marginLeftById(cls, value);
                break;
            case "paddingTop":
                this.paddingTopById(cls, value);
                break;
            case "paddingLeft":
                this.paddingLeftById(cls, value);
                break;
        }
    }
}
styleCalculation.prototype = {
    //getElementsByClassName区域
    widthByClassName: function(cls, value) {
        for (var i = 0; i < d.getElementsByClassName(cls).length; i++) {
            d.getElementsByClassName(cls)[i].style.width = value;
        }
    },
    heightByClassName: function(cls, value) {
        for (var i = 0; i < d.getElementsByClassName(cls).length; i++) {
            d.getElementsByClassName(cls)[i].style.height = value;
        }
    },
    lineHeightByClassName: function(cls, value) {
        for (var i = 0; i < d.getElementsByClassName(cls).length; i++) {
            d.getElementsByClassName(cls)[i].style.lineHeight = value;
        }
    },
    fontSizeByClassName: function(cls, value) {
        for (var i = 0; i < d.getElementsByClassName(cls).length; i++) {
            d.getElementsByClassName(cls)[i].style.fontSize = value;
        }
    },
    topByClassName: function(cls, value) {
        for (var i = 0; i < d.getElementsByClassName(cls).length; i++) {
            d.getElementsByClassName(cls)[i].style.top = value;
        }
    },
    leftByClassName: function(cls, value) {
        for (var i = 0; i < d.getElementsByClassName(cls).length; i++) {
            d.getElementsByClassName(cls)[i].style.left = value;
        }
    },
    marginTopByClassName: function(cls, value) {
        for (var i = 0; i < d.getElementsByClassName(cls).length; i++) {
            d.getElementsByClassName(cls)[i].style.marginTop = value;
        }
    },
    marginLeftByClassName: function(cls, value) {
        for (var i = 0; i < d.getElementsByClassName(cls).length; i++) {
            d.getElementsByClassName(cls)[i].style.marginLeft = value;
        }
    },
    paddingTopByClassName: function(cls, value) {
        for (var i = 0; i < d.getElementsByClassName(cls).length; i++) {
            d.getElementsByClassName(cls)[i].style.paddingTop = value;
        }
    },
    paddingLeftByClassName: function(cls, value) {
        for (var i = 0; i < d.getElementsByClassName(cls).length; i++) {
            d.getElementsByClassName(cls)[i].style.paddingLeft = value;
        }
    },
    //getElementById区域
    widthById: function(id, value) {
        d.getElementById(id).style.width = value;
    },
    heightById: function(id, value) {
        d.getElementById(id).style.height = value;
    },
    lineHeightById: function(id, value) {
        d.getElementById(id).style.lineHeight = value;
    },
    fontSizeById: function(id, value) {
        d.getElementById(id).style.fontSize = value;
    },
    topById: function(id, value) {
        d.getElementById(id).style.top = value;
    },
    leftById: function(id, value) {
        d.getElementById(id).style.left = value;
    },
    marginTopById: function(id, value) {
        d.getElementById(id).style.marginTop = value;
    },
    marginLeftById: function(id, value) {
        d.getElementById(id).style.marginLeft = value;
    },
    paddingTopById: function(id, value) {
        d.getElementById(id).style.paddingTop = value;
    },
    paddingLeftById: function(id, value) {
        d.getElementById(id).style.paddingLeft = value;
    },
}



function getOffset(classId, styleCss) {
    if (classId.substring(0, 1) === ".") {
        var cls = classId.substring(1);
        switch (styleCss) {
            case "fontSize":
                return d.getElementsByClassName(cls)[0].style.fontSize;
                break;
            case "width":
                return d.getElementsByClassName(cls)[0].offsetWidth;
                break;
            case "height":
                return d.getElementsByClassName(cls)[0].offsetHeight;
                break;
            case "top":
                return d.getElementsByClassName(cls)[0].offsetTop;
                break;
            case "left":
                return d.getElementsByClassName(cls)[0].offsetLeft;
                break;
        }
    } else if (classId.substring(0, 1) === "#") {
        var id = classId.substring(1);
        switch (styleCss) {
            case "fontSize":
                return d.getElementsById(cls).style.fontSize;
                break;
            case "width":
                return d.getElementsById(id).offsetWidth;
                break;
            case "height":
                return d.getElementsById(id).offsetHeight;
                break;
            case "top":
                return d.getElementsById(id).offsetTop;
                break;
            case "left":
                return d.getElementsById(id).offsetLeft;
                break;
        }
    }
}