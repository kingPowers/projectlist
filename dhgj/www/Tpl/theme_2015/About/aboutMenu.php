<link rel="stylesheet" href="_STATIC_/2015/about/css/aboutMenu.css">
<div class="content">
    <div class="center">
        <div class="c_left about_menu">
            <ul class="about_menu_ul">
                <li class="li_first <?php if(strtolower(MODULE_NAME)=='cms'&&(strtolower(ACTION_NAME)=='about')) echo 'abcurr';?>"><a href="/about">公司简介</a></li>
<!--                <li class="ab_li2 <?php if(strtolower(MODULE_NAME)=='cms'&&(strtolower(ACTION_NAME)=='team')) echo 'abcurr';?>"><a href="/team">高管团队</a></li>-->
                <li <?php if((strtolower(MODULE_NAME)=='about'&&(strtolower(ACTION_NAME)=='notice')) || $catename['id']==1) echo 'class="abcurr"';?> ><a href="/about/notice">最新公告</a></li>
                <li <?php if((strtolower(MODULE_NAME)=='about'&&(strtolower(ACTION_NAME)=='news')) || $catename['id']==2) echo 'class="abcurr"';?> ><a href="/about/news">媒体报道</a></li>
                <li <?php if((strtolower(MODULE_NAME)=='about'&&(strtolower(ACTION_NAME)=='zxnews')) || $catename['id']==3) echo 'class="abcurr"';?> ><a href="/about/zxnews">果粉资讯</a></li>
                <li <?php if((strtolower(MODULE_NAME)=='about'&&(strtolower(ACTION_NAME)=='school')) || $catename['id']==8) echo 'class="abcurr"';?> ><a href="/about/school">果粉学堂</a></li>
                <li <?php if(strtolower(MODULE_NAME)=='about'&&(strtolower(ACTION_NAME)=='cooperate')) echo 'class="abcurr"';?> ><a href="/about/cooperate">合作伙伴</a></li>
                <li <?php if((strtolower(MODULE_NAME)=='about'&&(strtolower(ACTION_NAME)=='help')) || $cate==10) echo 'class="abcurr"';?> ><a href="/about/help">帮助中心</a></li>
                <li <?php if((strtolower(MODULE_NAME)=='about'&&(strtolower(ACTION_NAME)=='discipline')) || $cate==11) echo 'class="abcurr"';?> ><a href="/about/discipline">自律公约</a></li>
<!--                <li <?php if(strtolower( )=='about'&&(strtolower(ACTION_NAME)=='recruit')) echo 'class="abcurr"';?> ><a href="/about/recruit">招聘信息</a></li>-->
                <li <?php if(strtolower(MODULE_NAME)=='cms'&&(strtolower(ACTION_NAME)=='contact')) echo 'class="abcurr"';?> ><a href="/contact">联系我们</a></li>
            </ul>
        </div>