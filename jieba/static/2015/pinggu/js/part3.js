var part3, letterCtValue, part3_2, letterCt3Level;

(function () {
    part3 = d.getElementsByClassName('part3')[0];
    letterCtValue = d.getElementsByClassName('letterCtValue');
    part3_2 = d.getElementsByClassName('part3_2')[0];
    letterCt3Level = d.getElementsByClassName('letterCt3Level');
}())

function part3Css() {
    part3.classList.remove("rightMove");
    part3.classList.add("leftMove");
    d.getElementsByClassName('fhwz')[0].onclick = function () {
        part3.classList.remove("leftMove");
        part3.classList.add("rightMove");
        addP3Listener();
        if ($(".changehref").attr("href") != "/index/index")
        {
            $(".changehref").attr("href", "/index/index");
            return false;
        }
    }
    $(".changehref").attr("href", "javascript:void(0)");
    d.getElementsByClassName('zxwx')[0].innerHTML = "选择城市";
    d.getElementsByClassName('part2')[0].style.display = "none";
    part3.style.display = "block";
    d.getElementsByClassName('part4')[0].style.display = "block";
    part4Css("cT");

    new styleCalculation(".part3", "top", (getSize(8.6) + "px"));
    new styleCalculation(".part3", "height", ((parseInt(h) - getSize(86)) + "px"));

    new styleCalculation(".letterCt", "height", (getSize(50) + "px"));
    new styleCalculation(".letterCt", "lineHeight", (getOffset(".letterCt", "height") + "px"));
    new styleCalculation(".letterCtValue", "height", (getSize(100) + "px"));
    new styleCalculation(".letterCtValue", "lineHeight", (getOffset(".letterCtValue", "height") + "px"));
}

var level2, level3;
//显示三级窗口
function showCt3Level(v,id) {
    var data = {};
    $(data).attr('id',id);
    $.post('/Pinggu/getCitys.html',data,function(F){
        F = eval(F);
        if(F.status==0){
            alert(F.info);
        }else{
            $(".part3_2").html("");
            $.each(F.data,function(index,item){
                var part_content = "<div style='height: 50px;line-height:50px;' class='letterCt3Level sizeTwo' onclick='confirmCt("+index+","+item.city_code+")'>"+item.city_name+"</div>";
                $(".part3_2").append(part_content);
            });
        }
    },'json');
    for (var i = 0; i < letterCtValue.length; i++) {
        if (i != v) {
            letterCtValue[i].style.background = "#fff";
        } else {
            letterCtValue[i].style.background = "#d8d8d8";
            level2 = letterCtValue[i].innerHTML;
        }
    }
    d.getElementsByClassName('part4')[0].style.display = "none";
    part3_2.style.display = "block";

    part3_2.classList.remove("rightMove");
    part3_2.classList.add("leftMove");

    d.getElementsByClassName('fhwz')[0].onclick = function () {
      //  alert("123");
        part3.classList.remove("leftMove");
        part3.classList.add("rightMove");
        part3_2.classList.remove("leftMove");
        part3_2.classList.add("rightMove");
        addP3Listener();
        if ($(".changehref").attr("href") != "/index/index")
        {
            $(".changehref").attr("href", "/index/index");
            return false;
        }
    }
    $(".changehref").attr("href", "javascript:void(0)");

    new styleCalculation(".part3_2", "top", (getSize(86) + "px"));
    new styleCalculation(".part3_2", "height", ((parseInt(h) - getSize(86)) + "px"));

    new styleCalculation(".letterCt3Level", "height", (getSize(100) + "px"));
    new styleCalculation(".letterCt3Level", "lineHeight", (getOffset(".letterCt3Level", "height") + "px"));
}

var p3;

function confirmCt(v, id) {
    $(".spCity").append("<input name='city_id' type='hidden' value='" + id + "' id='city_id'>");
    var opi = 1;
    d.getElementsByClassName('fhwz')[0].onclick = function () {
        if ($(".changehref").attr("href") != "/index/index")
        {
            $(".changehref").attr("href", "/index/index");
            return false;
        }
    }
    $(".changehref").attr("href", "javascript:void(0)");
    for (var i = 0; i < letterCt3Level.length; i++) {
        if (i != v) {
            letterCt3Level[i].style.background = "#fff";
        } else {
            letterCt3Level[i].style.background = "#d8d8d8";
            level3 = letterCt3Level[i].innerHTML;
        }
    }
    d.getElementsByClassName("btCity")[0].innerHTML = level2 + "-" + level3;
    d.getElementsByClassName('zxwx')[0].innerHTML = "爱车估价";
    var input_valeu = level2 + "-" + level3;
    $(".spCity").append("<input name='city_name' type='hidden' value='"+input_valeu+"' id='city_name'>");
    p3 = setInterval(function() {
        part3.style.opacity = parseFloat(opi);
        part3_2.style.opacity = parseFloat(opi);
        if (opi >= 0) {
            opi = opi - 0.01;
        } else {
            clearP3();
        }
    }, 1);
}

function clearP3() {
    clearInterval(p3);
    part3_2.style.display = "none";
    part3.style.display = "none";
    part3_2.style.opacity = "1";
    part3.style.opacity = "1";
}

function addP3Listener() {
    d.getElementById("part3").addEventListener("webkitAnimationEnd", clearP3Css, false);
}

function removeP3Listener() {
    d.getElementById("part3").removeEventListener("webkitAnimationEnd", clearP3Css, false);
}

clearP3Css = function () {
    part3.style.display = "none";
    part3_2.style.display = "none";
    d.getElementsByClassName('part4')[0].style.display = "none";
    removeP3Listener();
}