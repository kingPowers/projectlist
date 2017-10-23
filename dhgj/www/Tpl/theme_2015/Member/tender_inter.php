<include file="Public:accountMenu"/>
<link rel="stylesheet" href="_STATIC_/2015/member/css/tender.css">
    <div class="rec_t">
        <ul class="ba_min_nav">
            <li><a href="/member/tender_now">募集中</a></li>
            <li class="now"><a href="/member/tender_inter">计息中</a></li>
            <li><a href="/member/tender_yes">已到期</a></li>
        </ul>
    </div>

    <div class="tender_div">
        <table class="tender_table">
            <tr>
                <th style="width:210px;">项目名称</th>
                <th style="width:71px;">类型</th>
                <th style="width:78px;">年利率</th>
                <th style="width:71px;">期限</th>
                <th style="width:100px;">投资金额</th>
                <th style="width:92px;">总计本息</th>
                <th style="width:94px;">到期时间</th>
                <th style="width:106px;">操作</th>
            </tr>
            <tr>
                <td>融资租赁-长城-所有权【常熟站】</td>
                <td>安心稳盈</td>
                <td>12.00%</td>
                <td>6个月</td>
                <td>￥100000.00</td>
                <td>102700</td>
                <td>2015-05-09</td>
                <td><a href="javascript:;" class="tender_detail">还款明细</a>
                    <div class="tender_tip">
                        <span class="tip_sp"></span>
                        <div class="tip_box">
                            <ul class="tip_ul">
                                <li style="width:90px;">期数</li>
                                <li style="width:134px;">还款时间</li>
                                <li class="tip_li_money">还款金额</li>
                                <li class="tip_last">状态</li>
                            </ul>
                            <div class="tip_b_scroll">
                                <ul class="tip_ul_each">
                                    <li style="width:90px;">1期</li>
                                    <li style="width:134px;">2014-12-09</li>
                                    <li class="tip_li_money">￥450.00</li>
                                    <li class="tip_last"><span style="color:#009900;">已还</span></li>
                                </ul>

                                <ul class="tip_ul_each"><li style="width:90px;">2期</li><li style="width:134px;">2015-01-09</li><li class="tip_li_money">￥450.00</li><li class="tip_last"><span style="color:#009900;">已还</span></li></ul>
                                <ul class="tip_ul_each"><li style="width:90px;">3期</li><li style="width:134px;">2015-02-09</li><li class="tip_li_money">￥450.00</li><li class="tip_last"><span style="color:#009900;">已还</span></li></ul>
                                <ul class="tip_ul_each"><li style="width:90px;">4期</li><li style="width:134px;">2015-03-09</li><li class="tip_li_money">￥450.00</li><li class="tip_last"><span style="color:#009900;">已还</span></li></ul>
                                <ul class="tip_ul_each"><li style="width:90px;">5期</li><li style="width:134px;">2015-04-09</li><li class="tip_li_money">￥450.00</li><li class="tip_last"><span style="color:#009900;">已还</span></li></ul>
                                <ul class="tip_ul_each"><li style="width:90px;">6期</li><li style="width:134px;">2015-05-09</li><li class="tip_li_money">￥100450.00</li><li class="tip_last"><span style="color:#009900;">已还</span></li></ul>
                            </div>
                        </div>

                    </div>

                    | <a href="">协议</a></td>
            </tr>
            <tr>
                <td>融资租赁-长城-所有权【常熟站】</td>
                <td>安心稳盈</td>
                <td>12.00%</td>
                <td>6个月</td>
                <td>￥100000.00</td>
                <td>102700</td>
                <td>2015-05-09</td>
                <td><a href="javascript:;" class="tender_detail">还款明细</a>

                    <div class="tender_tip">
                        <span class="tip_sp"></span>
                        <div class="tip_box">
                            <ul class="tip_ul">
                                <li style="width:90px;">期数11</li>
                                <li style="width:134px;">还款时间</li>
                                <li class="tip_li_money">还款金额</li>
                                <li class="tip_last">状态</li>
                            </ul>
                            <div class="tip_b_scroll">
                                <ul class="tip_ul_each">
                                    <li style="width:90px;">1期</li>
                                    <li style="width:134px;">2014-12-09</li>
                                    <li class="tip_li_money">￥450.00</li>
                                    <li class="tip_last"><span style="color:#009900;">已还</span></li>
                                </ul>

                                <ul class="tip_ul_each"><li style="width:90px;">2期</li><li style="width:134px;">2015-01-09</li><li class="tip_li_money">￥450.00</li><li class="tip_last"><span style="color:#009900;">已还</span></li></ul>
                                <ul class="tip_ul_each"><li style="width:90px;">3期</li><li style="width:134px;">2015-02-09</li><li class="tip_li_money">￥450.00</li><li class="tip_last"><span style="color:#009900;">已还</span></li></ul>
                                <ul class="tip_ul_each"><li style="width:90px;">4期</li><li style="width:134px;">2015-03-09</li><li class="tip_li_money">￥450.00</li><li class="tip_last"><span style="color:#009900;">已还</span></li></ul>
                                <ul class="tip_ul_each"><li style="width:90px;">5期</li><li style="width:134px;">2015-04-09</li><li class="tip_li_money">￥450.00</li><li class="tip_last"><span style="color:#009900;">已还</span></li></ul>
                                <ul class="tip_ul_each"><li style="width:90px;">6期</li><li style="width:134px;">2015-05-09</li><li class="tip_li_money">￥100450.00</li><li class="tip_last"><span style="color:#009900;">已还</span></li></ul>
                            </div>
                        </div>

                    </div>

                    | <a href="">协议</a>
                </td>
            </tr>
        </table>
    </div>
    
    <script>
        $(".tender_detail").click(function(){
            var tW = $(".tender_tip").width()-55;
            var tR = $(this).position().left-tW;
            var tT = $(this).position().top+22;
            $(this).siblings(".tender_tip").css({top:tT,left:tR});
            $(this).siblings(".tender_tip").toggle();
        })
    </script>
    
<include file="Public:accountFooter"/>