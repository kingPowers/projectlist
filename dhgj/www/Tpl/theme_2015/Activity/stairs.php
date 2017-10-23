<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>“爬楼活动” 加息又有礼，精品好礼等您来拿！ - 智信创富</title>
    <link rel="stylesheet" type="text/css" href="_STATIC_/2015/building/style.css">
    <script type="text/javascript" src="_STATIC_/2015/js/jquery.min.js"></script>
    <script type="text/javascript" src="_STATIC_/2015/building/js.js"></script>
</head>
<body>
<!--top-->
<div class="head" style="min-width:1100px;">
    <div class="head_top">
        <h1><a href="/"><img src="_STATIC_/2015/building/logo.jpg?20150514"  alt=""></a></h1>

        <div class="head_right" style="color:#fff;">
            <empty name="Think.session.member.id">
                <a href="/register.html" class="firstchild" style="margin-right:80px;">注册</a>
                <a href="/login.html" class="lastchild">登陆</a>
                <else/>
                您好：<a href="/member.html" class="ui-account-login" style="color:#fdfb6d;margin:0 20px 0 0; ">{$Think.session.member.username}</a>
            </empty>
        </div>
    </div>
</div>
<!--baaner-->
<div class="con_main" style="min-width:1100px;"></div>
<?php foreach($loan_group as $k=>$v){?>
<div class="mainlist <?php echo $k%2==0?'mainlist_single':'mainlist_double';?>">
	<div class="door">
    	<h2 class="nums">第<?php echo $v['dx'];?>层</h2>
    </div>
    <div class="people"></div>
	<div class="loanmain">
    	<ul class="loanmain-title">
        	<h2 class="yellow"><?php echo $v['title'];?></h2>
            <?php if($v['status']==1 && $v['loan']['status']==0){?>
                <a class="lt-botton">未开启</a>
            <?php }?>
        </ul>
        <ul class="loanmain-title">
        	<span class="lt-rate">年化利率 &nbsp; <b class="yellow hg30"><?php echo $v['loan']['yearrate'];?></b>%</span>
            <span class="lt-rate">贷款期限 &nbsp; <?php echo $v['loan']['loanmonth'];?>个月</span>
        </ul>
        <ul class="loanmain-money fl">
        	<span class="lt-rate ">贷款金额 &nbsp; <?php echo $v['loan']['loanmoney'];?>元</span>
        </ul>
        <?php if($v['status']>=2){?>
        <ul class="loanmain-progress">
        	<div class="l-progress">
            	<p><?php echo intval($v['money']['tendermoney'] / $v['money']['loanmoney'] * 100);?>%</p>
            	<span style="width:<?php echo intval($v['money']['tendermoney'] / $v['money']['loanmoney'] * 100);?>%;"></span>
            </div>
        </ul>
        <ul class="loanmain-lineup">
        	<h3><?php echo $v['loan']['title'];?></h3>
            <?php if($v['status']==3){?>
                <span class="ll-botton">投资完成</span>
            <?php }elseif($v['status']==2){?>
                <a class="ll-botton" href="/zf-<?php echo $v['loan']['loansn'];?>.html">立即投资</a>
            <?php }?>
        </ul>
        <?php }?>
    </div>
</div>
<?php }?>

<div class="bootom-a" style="min-width:1100px;"></div>
<div class="bootom-b" style="min-width:1100px;">
    <div class="bootom-c">
        <div class="bootom-b-g"></div>
        <div class="bootom-b-d">
            <?php foreach($member as $k=>$v){?>
                <ul class="bootom-b-l <?php if($k==0){echo 'yw';}elseif($k==1){echo 'rd';}elseif($k==2){echo 'gn';}?>">
                    <b class="bbl-no">NO.<?php echo $k+1;?>&nbsp; <?php echo hide_username($v['username']);?></b>
                    <b class="bbl-mo"><?php echo $v['money'];?>元</b>
                </ul>
            <?php }?>
        </div>
    </div>
</div>



<div class="nav">
    <ul id="navlist">
        <?php foreach($loan_group as $k=>$v){?>
            <li><a href="javascript:;">第<?php echo $v['dx'];?>层</a>
                <?php if($v['status']==2){?>
                    <div class="img">
                        <img src="_STATIC_/2015/building/coming_03.jpg"  alt="">
                    </div>
                <?php }?>
            </li>
        <?php }?>

    </ul>
    <div class="top">
        <a href="javascript:;" id="tops"></a>
    </div>
</div>
<script>
    $(function(){
        $('#navlist li').click(function(){
            $('html,body').animate({scrollTop: $('.mainlist').eq($(this).index()).offset().top}, 500);
        })

        $('#tops').click(function(){
            $('html,body').animate({scrollTop:0}, 500);
        })
    })
</script>
<script type="text/javascript">
var _mvq = window._mvq || []; 
window._mvq = _mvq;
_mvq.push(['$setAccount', 'm-132786-0']);

_mvq.push(['$setGeneral', '', '', /*用户名*/ '', /*用户id*/ '']);//如果不传用户名、用户id，此句可以删掉
_mvq.push(['$logConversion']);
(function() {
    var mvl = document.createElement('script');
    mvl.type = 'text/javascript'; mvl.async = true;
    mvl.src = ('https:' == document.location.protocol ? 'https://static-ssl.mediav.com/mvl.js' : 'http://static.mediav.com/mvl.js');
    var s = document.getElementsByTagName('script')[0];
    s.parentNode.insertBefore(mvl, s); 
})();   

</script>
</body>
</html>
{__NOLAYOUT__}