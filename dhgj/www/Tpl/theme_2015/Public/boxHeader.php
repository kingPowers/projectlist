<style>
body{font-size:14px;font-family:'微软雅黑';margin:0;}
.box-header{
    width:350px;
    height:40px;
    background:#322C2C;
    color:#FFF;
}
.box-header .box-title{
    display:inline-block;
    height:40px;
    line-height:40px;
    background:#C29540;
    padding:0 20px;
}
.box-header .box-close-icon{
    display:inline-block;
    background: url(_STATIC_/2015/box/box-icon.png) no-repeat 0 0;
    height: 22px;
    width: 22px;
    cursor: pointer;
    float:right;
    margin:9px 10px 0 0;
    opacity: 1;
}
</style>
<div class="box-header">
	<span class="box-title">{$box_title}</span>
	<i class="box-close-icon"></i>
</div>
<script src="_STATIC_/2015/js/jquery.min.js" type="text/javascript" charset="utf-8"></script>
<script>
$(function(){
    $('.box-close-icon,.cancel-box').click(function(){
        window.parent.closeIframe();
    })
})
</script>