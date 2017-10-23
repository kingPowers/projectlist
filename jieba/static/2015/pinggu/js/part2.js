var part2, letterValue, part2_2, part2_3, letter4Level, letter3Level;

(function () {
    part2 = d.getElementsByClassName('part2')[0];
    letterValue = d.getElementsByClassName('letterValue');
    part2_2 = d.getElementsByClassName('part2_2')[0];
    part2_3 = d.getElementsByClassName('part2_3')[0];
    letter4Level = d.getElementsByClassName('letter4Level');
    letter3Level = d.getElementsByClassName('letter3Level');
}())


function part2Css() {
    part2.classList.remove("rightMove");
    part2.classList.add("leftMove");
    d.getElementsByClassName('fhwz')[0].onclick = function () {

        part2.classList.remove("leftMove");
        part2.classList.add("rightMove");
        addP2Listener();

        if ($(".changehref").attr("href") != "/index/index")
        {
            $(".changehref").attr("href", "/index/index");
            return false;
        }


    }
    $(".changehref").attr("href", "javascript:void(0)");
    d.getElementsByClassName('zxwx')[0].innerHTML = "选择车系";
    document.getElementsByClassName('part3')[0].style.display = "none";
    part2.style.display = "block";
    d.getElementsByClassName('part4')[0].style.display = "block";
    part4Css("qC");

    new styleCalculation(".part2", "top", (getSize(8.6) + "px"));
    new styleCalculation(".part2", "width", (parseInt(w) + "px"));
    new styleCalculation(".part2", "height", ((parseInt(h) - getSize(86)) + "px"));
    new styleCalculation(".letter", "height", (getSize(50) + "px"));
    new styleCalculation(".letter", "lineHeight", (getOffset(".letter", "height") + "px"));
    new styleCalculation(".letterValue", "height", (getSize(100) + "px"));
    new styleCalculation(".letterValue", "lineHeight", (getOffset(".letterValue", "height") + "px"));
}

var level2, level3, level4;
//显示三级窗口
function show3Level(v, id) {
    var data = {};
    $(data).attr('id', id);
    $.post('/Pinggu/getCardSec.html', data, function (F) {
        F = eval(F);
        if (F.status == 0) {
            alert(F.info);
        } else {
            $(".part2_2").html("");
            console.log(F.data);
            var i = 0;
            $.each(F.data, function (index, item) {
                var part_title = "<div class='letter sizeTwo' style='background-color:#efefef;padding-left:3%;text-align:left;height: 25px;'>" + index + "</div>";
                $(".part2_2").append(part_title);
                $.each(item, function (idex, ite) {
                    var part_content = "<div style='height: 50px;line-height:50px;' class='letter3Level sizeTwo' onclick='show4Level(" + ite.sec_id + "," + i + ")'>" + ite.sec_name + "</div>";
                    i = i + 1;
                    $(".part2_2").append(part_content);
                });
            });
        }
    }, 'json');

    for (var i = 0; i < letterValue.length; i++) {
        if (i != v) {
            letterValue[i].style.background = "#fff";
        } else {
            letterValue[i].style.background = "#d8d8d8";
            level2 = letterValue[i].innerHTML;
        }
    }
    d.getElementsByClassName('part4')[0].style.display = "none";
    part2_2.style.display = "block";

    part2_2.classList.remove("rightMove");
    part2_2.classList.add("leftMove");

    d.getElementsByClassName('fhwz')[0].onclick = function () {
       // alert("123");
        part2.classList.remove("leftMove");
        part2.classList.add("rightMove");
        part2_2.classList.remove("leftMove");
        part2_2.classList.add("rightMove");
        addP2Listener();
    }

    new styleCalculation(".part2_2", "top", (getSize(86) + "px"));
    new styleCalculation(".part2_2", "height", ((parseInt(h) - getSize(86)) + "px"));

    new styleCalculation(".letter3Level", "height", (getSize(100) + "px"));
    new styleCalculation(".letter3Level", "lineHeight", (getOffset(".letter3Level", "height") + "px"));
}

//显示四级窗口
function show4Level(id, v) {
    console.log(id, v);
    var data = {};
    $(data).attr('id', id);
    $.post('/Pinggu/getCardThird.html', data, function (F) {
        F = eval(F);
        //console.log('michelle',F);
        if (F.status == 0) {
            alert(F.info);
        } else {
            $(".part2_3").html("");//console.log(F.data);
            var i = 0;
            $.each(F.data, function (index, item) {
                var part_title = "<div class='letter4 sizeTwo' style='background-color:#efefef;padding-left:3%;text-align:left;height: 25px;'>" + index + "</div>";
                $(".part2_3").append(part_title);
                $.each(item, function (idex, ite) {
                    var part_content = "<div style='height: 50px;line-height:50px;' class='letter4Level sizeTwo' onclick='confirm(" + ite.thr_id + "," + i + ")'>" + ite.thr_name + "</div>";
                    i = i + 1;
                    $(".part2_3").append(part_content);
                });
            });
        }
    }, 'json');

    d.getElementsByClassName('fhwz')[0].onclick = function () {
        if ($(".changehref").attr("href") != "/index/index")
        {
            $(".changehref").attr("href", "/index/index");
            return false;
        }
    }
    $(".changehref").attr("href", "javascript:void(0)");
    for (var i = 0; i < letter3Level.length; i++) {
        if (i != v) {
            letter3Level[i].style.background = "#fff";
        } else {
            letter3Level[i].style.background = "#d8d8d8";
            level3 = letter3Level[i].innerHTML;
        }
    }
    d.getElementsByClassName('zxwx')[0].innerHTML = "选择车款";
    part2_3.style.display = "block";

    part2_3.classList.remove("rightMove");
    part2_3.classList.add("leftMove");

    d.getElementsByClassName('fhwz')[0].onclick = function () {
        part2.classList.remove("leftMove");
        part2.classList.add("rightMove");
        part2_2.classList.remove("leftMove");
        part2_2.classList.add("rightMove");
        part2_3.classList.remove("leftMove");
        part2_3.classList.add("rightMove");
        addP2Listener();
    }

    new styleCalculation(".part2_3", "top", (getSize(86) + "px"));
    new styleCalculation(".part2_3", "height", ((parseInt(h) - getSize(86)) + "px"));

    new styleCalculation(".letter4", "height", (getSize(50) + "px"));
    new styleCalculation(".letter4", "lineHeight", (getOffset(".letter4", "height") + "px"));
    new styleCalculation(".letter4Level", "height", (getSize(100) + "px"));
    new styleCalculation(".letter4Level", "lineHeight", (getOffset(".letter4Level", "height") + "px"));
}

var p2;

function confirm(hidden_id, v) {
    $(".spModels").append("<input type='hidden' name='brand_id' value='" + hidden_id + "' id='car_model'>");

    var opi = 1;
    d.getElementsByClassName('fhwz')[0].onclick = function () {
       if ($(".changehref").attr("href") != "/index/index")
        {
            $(".changehref").attr("href", "/index/index");
            return false;
        }
    }
    $(".changehref").attr("href", "javascript:void(0)");
    for (var i = 0; i < letter4Level.length; i++) {
        if (i != v) {
            letter4Level[i].style.background = "#fff";
        } else {
            letter4Level[i].style.background = "#d8d8d8";
            level4 = letter4Level[i].innerHTML;
        }
    }//console.log(v,letter4Level[1]);
    d.getElementsByClassName("btModels")[0].innerHTML = level2 + "-" + level3 + "-" + level4;
    d.getElementsByClassName('zxwx')[0].innerHTML = "爱车估价";
    var value_input = level2 + "-" + level3 + "-" + level4;
    $(".spModels").append("<input type='hidden' name='brand_names' value='" + value_input + "'>");
    p2 = setInterval(function () {
        part2.style.opacity = parseFloat(opi);
        part2_2.style.opacity = parseFloat(opi);
        part2_3.style.opacity = parseFloat(opi);
        if (opi >= 0) {
            opi = opi - 0.01;
        } else {
            clearP2();
        }
    }, 1);
}

function clearP2() {
    clearInterval(p2);
    part2_3.style.display = "none";
    part2_2.style.display = "none";
    part2.style.display = "none";
    part2_3.style.opacity = "1";
    part2_2.style.opacity = "1";
    part2.style.opacity = "1";
}

function addP2Listener() {
    d.getElementById("part2").addEventListener("webkitAnimationEnd", clearP2Css, false);
}

function removeP2Listener() {
    d.getElementById("part2").removeEventListener("webkitAnimationEnd", clearP2Css, false);
}

clearP2Css = function () {
    part2.style.display = "none";
    part2_2.style.display = "none";
    part2_3.style.display = "none";
    d.getElementsByClassName('part4')[0].style.display = "none";
    removeP2Listener();
}