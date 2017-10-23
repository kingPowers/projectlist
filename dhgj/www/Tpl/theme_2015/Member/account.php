<include file="Public:accountMenu"/>
<link rel="stylesheet" href="_STATIC_/2015/member/css/account.css">
<style type="text/css">
.eidt_name{width: 80%;border: 1px solid #C9C9C9;color: #000 }
.eidt_name_s{background: #fff;border:0;color:#A9A9A9}
.ba_min_con .change_ok{border: 0;}
</style>
<script src="_STATIC_/2015/member/js/highcharts.js"></script>
    <div class="user_info">
        <h2>基本信息</h2>
        <div class="acc_tips">
            <div class="user_blank c_left">
                <img src="_STATIC_/2015/member/image/ico_header.png">
            </div>
            <div class="info_m c_left">                
                <p>用户名：<label class="user_name">{$Think.session.member.username}</label></p>
                <p>密码安全度
                    <span><i style="width: <?php echo $_SESSION['member']['pwd_safety'];?>0%"></i></span>
                    <if condition="$_SESSION['member']['pwd_safety'] lt 4">
                    低
                    <elseif condition="$_SESSION['member']['pwd_safety'] lt 7"/>
                    中
                    <elseif condition="$_SESSION['member']['pwd_safety'] gt 6"/>
                    高
                    </if>
                </p>               
            </div>
        </div>
        <div class="acc_user_b">
            <div class="ba_min_nav">个人信息</div>
            <div class="ba_min_con">
                <p>用户名<font><input class="eidt_name_s" disabled="" type="text" name="username" value="{$Think.session.member.username}"></font></p><a class="is_change" onclick="is_change(this);">修改</a>
                <p>用户ID<font>{$Think.session.member.id}</font></p>
            </div>
        </div>
    </div>
<script type="text/javascript">
var is_change = function(_this){
        $(_this).removeClass().addClass("change_name");
        $(".eidt_name_s").removeClass().addClass("eidt_name").removeAttr("disabled");
        $("input[name='username']").val("");
        $(_this).html("确定");
        $(_this).attr("onclick","change_name(this);");
}
var change_name = function(_this)
{
        var new_name = $("input[name='username']").val();
        if(!new_name){jdbox.alert(0,"请输入新的用户名");return false;}
        jdbox.alert(2);
        $.post("{:U('/member/changeUsername')}",{"new_name":new_name,"is_ajax":1},function(F){
            console.log(F);
            jdbox.alert(F.status,F.info);
            if(F.status == 1)
            {
               $(_this).removeClass().addClass("change_ok").attr("onclick","").html("√");
               $(".eidt_name").removeClass("eidt_name").addClass("eidt_name_s").attr("disabled",'disabled');
               $(".user_name").html(F.data.new_name);
            }
        },'json');
}

</script>
<include file="Public:accountFooter"/>