function part4Css(cp) {
	var part4 = document.getElementsByClassName("part4")[0];
	part4.innerHTML = "";
	var code = 65;
	for (var i = 0; i < 26; i++) {
		part4.innerHTML += "<div class=\"part4_1 sizeFour\"><a href=\"#" + cp + String.fromCharCode(code) + "\">" + String.fromCharCode(code) + "</a></div>";
		code++;
	}

	new styleCalculation(".sizeFour", "fontSize", (parseInt(w) * 0.030 + "px"));
	new styleCalculation(".part4", "width", (getSize(35) + "px"));
	new styleCalculation(".part4_1", "height", (getSize(35) + "px"));
	new styleCalculation(".part4_1", "lineHeight", (getOffset(".part4_1", "height") + "px"));
	new styleCalculation(".part4_1", "width", (getSize(35) + "px"));
	new styleCalculation(".part4", "top", ((((parseInt(h) - getSize(86) - getOffset(".part4", "height")) / 2) + getSize(86)) + "px"));
}