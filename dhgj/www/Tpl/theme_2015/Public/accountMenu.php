<link rel="stylesheet" href="_STATIC_/2015/member/css/accountMenu.css">
    <!--content-->
    <div class="content">
        <div class="c_nav">
            <p>当前位置：<a href="/">首页</a> &gt; <a href="/member/account" class="c_nav_now">我的账户</a></p>
        </div> 
        <div class="center user_center">
            <div class="account_l c_left">
                <div class="acc_now1">
                    <ul>
                        <li class="acc_first acc_first1"><a class="user_curr">我的账户</a></li>
                    </ul>
                </div>
                <div>                    
                    <a href="/member/account" class="choose_a <?php if((strtolower(MODULE_NAME)=='member')&&(strtolower(ACTION_NAME)=='account')) echo 'blue_line';?>">基本信息</a>
                    <a href="/member/reset" class="choose_a <?php $module = array('member');$action=array('reset','reset_success');if(in_array(strtolower(MODULE_NAME),$module)&&in_array(strtolower(ACTION_NAME),$action)) echo 'blue_line';?> ">修改密码</a>
                </div>
                <div class="acc_now1">
                    <ul>
                        <li class="acc_first acc_first2"><a class="user_curr">我的订单</a></li>
                    </ul>
                </div>
                <div>                                       
                    <a href="/order/my_order_list" class="choose_a <?php if((strtolower(MODULE_NAME)=='order')&&(strtolower(ACTION_NAME)=='my_order_list')) echo 'blue_line';?>">我的订单</a>                   
                </div>               
            </div>
            <div class="account_r c_right">
                