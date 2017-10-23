<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta content="telephone=no,email=no" name="format-detection">
    <meta content="yes" name="apple-touch-fullscreen">
    <meta content="yes" name="apple-mobile-web-app-capable">
    <meta name="viewport" content="width=device-width,height=device-heght,initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <title>{$pageseo.title}</title>
    <link rel="stylesheet" href="_STATIC_/2015/member/css/feedback.css" />
    <link rel="stylesheet" href="_STATIC_/2015/index/css/reset.css" />
    <script type="text/javascript" src="_STATIC_/2015/index/js/jquery-1.10.2.min.js"></script>
    <script type="text/javascript" src="_STATIC_/2015/member/js/feedback.js"></script>
</head>
<body onload="ft()" bgcolor="#efefef">
<div class="maxDiv"><!--大盒子-->
    <div class="headers"><!--头-->
        <div class="rd">
            <a href="/member/more" style="color:white;text-decoration:none;">
            <img src="_STATIC_/2015/member/image/register/return.png" class="retrunImg"/>
            <span class="fhwz">返回</span>
            </a>
            <span class="zxwx">意见反馈</span>
        </div>
    </div>
    <div class="centerDiv"><!--中-->
		<div class="center1">
                <textarea class="textarea font"  name="content" runat="server" id="textarea" placeholder="请留下您的宝贵意见和建议，我们将努力改进（不少于五个字）"
                          onfocus="cx()" onblur="xs()" style="width:98%;height:92%;border:none;line-height:125%;color:#c2c2c2;padding-top:2%;border-radius:2%;">
                </textarea>
        </div>

        <div class="center3">
		   <img src="_STATIC_/2015/member/image/about/icon.png" class="iconImg"/>
           <span class="font1">如有问题请致电客服 :</span><p class="font2"><a href="TEL:400 6639 066" style="color:#5495e6;text-decoration:none;">400 663 9066</a></p>		   
        </div>
    </div>
</div>
</body>
<script>
    var content = document.getElementById("textarea");
    content.value = "请留下您的宝贵意见和建议，我们将努力改进（不少于五个字）";
    function cx() {
        if (content.value == "请留下您的宝贵意见和建议，我们将努力改进（不少于五个字）")
        {
            content.value = " ";
            $("#textarea").css("color","black");

        }
    }
    function xs() {
        if (content.value == " ")
        {
            content.value = "请留下您的宝贵意见和建议，我们将努力改进（不少于五个字）";
            $("#textarea").css("color","#c2c2c2");

        }
    }

    $(function(){
        $(".iconImg").click(function(){
            var content = $("#textarea").val();
            //textareavalue.value = " ";
            //this.textarea.Value.ToString();
            // var text = document.getElementById('textarea').innerHTML
            var value= "请留下您的宝贵意见和建议，我们将努力改进（不少于五个字）";
            if(content == value){
                alert("请留下您的宝贵意见和建议，我们将努力改进（不少于五个字）");
                return false;
            }
            //$('form[name="view_submit"]').submit();
            $.ajax({
                'type':'post',
                'dataType':'json',
                'url':"/member/feedback",
                "data":{'content':content},
                success:function(json){
                    alert(json.info);
                    if(json.status==1){
                        location.reload();
                    }else{
                    }

                }
            });

        });

    });
    </script>
</html>   
{__NOLAYOUT__}