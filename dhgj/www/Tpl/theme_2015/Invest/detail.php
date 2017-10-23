<script type="text/javascript" src="_STATIC_/2015/js/invest-detail.js"></script>
<link rel="stylesheet" href="_STATIC_/2015/invest/css/band.css">
    <!--content-->
    <div class="content">
        <div class="c_nav">
            <p>当前位置：<a href="/">首页</a> > <a class="c_nav_now" href="/invest">我要投资</a> > 项目详情</p>
        </div>
        <div class="center band_center">
            <h1 class="ba_h1">项目详情</h1>
            <div class="ba_head">
                <div class="ba_head_l">
                    <h3 class="ba_h3"><img src="_STATIC_/2015/index/image/iconindex.jpg" style="float:left;margin-top:20px;margin-left:20px;"/>【{$loan.name}】{$loan['title']}</h3>
                    <div class="ba_head_l_m">
                        <span class="ba_first">
                            <p>借款金额</p>
                            <p class="ba_bottom">{$loan['loanmoney']}</p>
                        </span>
                        <span>
                            <p>年化收益</p>
                            <p class="ba_bottom">{$loan['year_rate']}<if condition="$loan['activity_rate'] gt 0">+{$loan['activity_rate']}</if>%</p>
                        </span>
                        <span>
                            <p>借款期限</p>
                            <if condition="$loan.isloanday eq 1">
                                <p class="ba_bottom">
                                    <if condition="$loan['loanday'] eq 90">3月<elseif condition="$loan['loanday'] eq 30" />1月<else/>{$loan['loanday']} 天</if>
                                </p>
                            <else/>
                                <p class="ba_bottom">{$loan['loanmonth']} 个月</p>
                            </if>
                        </span>
                        <if condition="$loan['category_admin'] eq 2">
                            <span class="ba_last">
                            <p>剩余金额</p>
                            <p class="ba_bottom">{$loan['loanmoney']-$loan['tendermoney']}</p>
                            </span>
                        <else/>
                            <span class="ba_last">
                            <p>投标模式</p>
                            <p class="ba_bottom">1标1投</p>
                            </span>
                        </if>

                    </div>
                    <div class="ba_head_ldiv">
                        <p class="ba_head_l_p c_left">还款方式：
                            <span style="color:#ed0000;">
                                <if condition="$repayment[$loan['repayment']] eq '付息还本'" >
                                    按月付息，到期还本
                                <elseif condition="$repayment[$loan['repayment']] eq '本息到付'" />
                                    一次性还本付息
                                <else />
                                    {$repayment[$loan['repayment']]}
                                </if>
                            </span>
                        </p>
                        <p class="ba_head_l_p c_right">保障方式：<span style="color:#00a726;">{$loan['security']}</span></p>
                    </div>

                    <?php //if($loan['loanstatus']<=1){ ?>
                        <div class="ba_head_l_p" style="overflow:hidden;">
                            <span  style="float: left">剩余时间:</span>
                            <h3 class="ui-countdown" style="float: left">
                                <span id="ui-countdown-d"><b>0</b><b>0</b></span><label>天</label>
                                <span id="ui-countdown-h"><b>0</b><b>0</b></span><label>时</label>
                                <span id="ui-countdown-m"><b>0</b><b>0</b></span><label>分</label>
                            </h3>
                            
                        <p class="ba_head_l_p c_right" style="color:#ea5413;color:#ea5413;margin-left:50px;cursor:pointer;font-weight:bold">投资有风险，入市需谨慎!</p>
                        </div>
                    <?php //}else{ ?>
                        <!--<p class="ba_head_l_p">剩余时间：<span style="color:#be9320;">已完成</span></p>-->
                    <?php //} ?>
                </div>
                <div class="ba_head_r">
                    <div class="ba_head_r_t">
                        <p class="ba_head_r_p">当前状态：<span class="c_plen"><span class="speed"></span></span> <span class="num">{$loan['tenderspeed']}%</span></p>
                        <p class="ba_head_r_p">账户金额：<span class="c_money">{:number_format($_SESSION['member']['memberInfo']['availableAmount'],2)}</span><span class="c_cz"><a href="/member/recharge">充值</a>|<a href="javascript:;" id="allmade">全投</a></span></p>
                    </div>
                    <div class="ba_head_r_m">
<input type="text" placeholder="{$loan['loanmoneydisplay']}" id="detail-tender-money" autocomplete="off">
                        <!--<input type="text" placeholder="<if condition="$loan['loanmoney']-$loan['tendermoney'] lt $loan['mintendermoney']">可投{$loan['loanmoney']-$loan['tendermoney']}元<else/><if condition="$loan['mintendermoney'] gt 0">{$loan['mintendermoney']}<else/>0.00</if>元起投</if>" id="detail-tender-money" autocomplete="off">
                    -->
                    </div>
<!--                    <div class="ba_head_r_m">
                        <input type="text" placeholder="<if condition="$loan['loanmoney']-$loan['tendermoney'] lt $loan['mintendermoney'] && $loan['loanmoney']-$loan['tendermoney'] gt 0">可投{$loan['loanmoney']-$loan['tendermoney']}元<else/><if condition="$loan['mintendermoney'] gt 0">{$loan['mintendermoney']}<else/>0.00</if>元起投</if>" id="detail-tender-money" autocomplete="off">
                    </div>-->
                    
                    
                    <div class="ba_head_r_b">
                        <p>预期收益: <em id="detail-tender-rate">0.00</em>元</p>
                        <p><input type="checkbox"  name="agreement"> 我已阅读并同意<a href="/about/agreement" target="_blank">《借款协议》</a><a href="/about/fxprotocal">《风险提示》 </a></p>
                        
                        <if condition="$loan.loanstatus gt 1">
                            <button type="button" class="ba_btn" disabled>投标已结束</button>
                        <else/>
                            <button type="button" class="ba_btn" style="background:#ea5413;color: #fff;" id="detail-tender-button">立即投标</button>
                        </if>
                    </div>
                </div>
            </div>

            <div class="ba_min">
                <ul class="ba_min_nav">
                    <li class="now">项目介绍</li>
                    <li>车辆信息</li>
                    <li>借款人信息</li>
                    <li>相关图片</li>
                    <li>GPS7*24</li>
                    <li>风控措施</li>
                    <li>资金流向</li>
                </ul>
                <div class="ba_min_b ba_min_js">
                    <h6 class="ba_h6">智信创富项目介绍：</h6>
                    <ul id="ba_min_show">
                        {$userinfo.content}
                    </ul>
                    <p class="ba_p_more"><span class="c_right"><img src="_STATIC_/2015/invest/image/ba_more.png" alt="更多"> 更多详情</span></p>
                </div>
                
                <div class="ba_min_b ba_min_cl">
                    <div class="ba_min_cl1"><img src="_STATIC_/2015/invest/image/cl_test.jpg" alt="车辆照片"></div>
                    <div class="ba_min_cl2">
                        <p>车辆品牌：{$userinfo.cheliangpinpai}</p>
                        <p>车辆年限：{$userinfo.cheliangnianxian}</p>
                        <p>过户次数：{$userinfo.guohucishu}</p>
                    </div>
                    <div class="ba_min_cl3">
                        <p>车牌号：{$userinfo.cheliangxinghao|substr=0,4}***</p>
                        <p>评估价格：￥{$userinfo['pinggujiage']*10000}</p>
                        <p>大修记录：{$userinfo.daxiujilu}</p>
                    </div>
                    <div class="ba_min_cl4">
                        <p>行驶里程：{$userinfo.xingshilicheng}公里</p>
                        <p>新车价格：￥{$userinfo['xinchejiage']*10000}</p>
                        <p>交通事故：{$userinfo.jiaotongshigu}</p>
                    </div>
                </div>
                
                <div class="ba_min_b ba_min_cl">
                    <div class="ba_min_info1">
                        <p>姓名：{$userinfo.name|hide_username}</p>
                        <p>是否有房有车：{$userinfo.fanche}</p>
                        <p>籍贯：{$userinfo.censes}</p>
                    </div>
                    <div class="ba_min_info2">
                        <p>性别：{$userinfo.sex}</p>
                        <p>有无房贷或其他贷款：{$userinfo.fandai}</p>
                        <p>是否有重大疾病：{$userinfo.jibing}</p>
                    </div>
                    <div class="ba_min_info3">
                        <p>学历：{$userinfo.education}</p>
                        <p>借款用途：{$userinfo.jiekuanyongtu}</p>
                        <p>是否有犯罪行为：{$userinfo.fanzui}</p>
                    </div>
                    <div class="ba_min_info4">
                        <p>月收入：{$userinfo.income}</p>
                        <p>借款用途年收益：{$userinfo.yongtuishouyi}</p>
                        <p>交通事故：{$userinfo.jiaotongshigu}</p>
                    </div>
                </div>
                
                <div class="ba_min_b ba_min_tp">
                    <a href="javascript:;" id="left" class="ba_lr"></a>
                    <div class="ba_tp">
                        <div class="ba_tp_box">
                            <ul class="ba_tp_scroll" id="third">
                                
                                <foreach name="material['result']" item="vo">
                                    <li code="{$vo['code']}">
                                        <img src="_STATIC_/{$vo['path']}" alt="相关图片"/>
                                        <p>{$vo['name']}<p>
                                    </li>
                                </foreach>
                                
                            </ul>
                            <ul class="ba_tp_scroll" id="four"></ul>
                        </div>
                    </div>
                    <a href="javascript:;" id="right" class="ba_lr"></a>
                </div>
                
                <script>
                    $(function(){
                        $("#third li").each(function(index){
                            var liW = $(this).width()*(index+1);
                            var liMargin = 12*(index+1);
                            $(".ba_tp_scroll").width(liW+liMargin);
                            $(".ba_tp_box").width($(".ba_tp_scroll").width()*2);
                        })
                    })
                    var left = $("#left");
                    var right = $("#right");
                    var first = $(".ba_tp");
                    var third = $("#third");
                    var four = $("#four");
                    $("#four").html($("#third").html());
                    
                    var dir=1;//每步移动像素，大＝快
                    var speed=5;//循环周期（毫秒）大＝慢
                    var MyMar=null;

                    function Marquee(){
                        if(dir>0&&(third[0].offsetWidth-first[0].scrollLeft)<=0){
                            first[0].scrollLeft=0;
                        }
                        if(dir<0 &&(first[0].scrollLeft<=0)){
                            first[0].scrollLeft=four[0].offsetWidth;
                        }
                            first[0].scrollLeft+=dir;
                    }//正常移动
                    
                    function r_left(){
                        if(dir==-1)
                        dir=1;
                    }//换向左移

                    function r_right(){
                        if(dir==1)
                        dir=-1;
                    }//换向右移
                    
                    first.mouseover(function(){
                        clearInterval(MyMar);
                    }).mouseout(function(){
                        MyMar = setInterval(Marquee,speed);
                    })
                    MyMar = setInterval(Marquee,speed);
                    $("#left").click(function(){
                        clearInterval(MyMar);
                        r_left();
                        MyMar = setInterval(Marquee,speed);
                    })
                    $("#right").click(function(){
                        clearInterval(MyMar);
                        r_right();
                        MyMar = setInterval(Marquee,speed);
                    })
                </script>
                
                <div class="ba_min_b ba_min_gps">
                    <div class="ba_gps" id="l-map"></div>
                </div>
                
                <div class="ba_min_b ba_min_fk">
                    <div class="ba_fk">
                        <div class="ba_fk_now">
                            <h5 class="ba_h5">贷前</h5>
                            <p><span>风控1.</span>申请材料分为身份证明材料及车辆材料身份证明包括不仅限于个人信息表、身份证、信用报告、流水，房产相关证明等。车辆材料为车辆登记证，行驶证，购置发票，保险单据等。</p>
                            <h5 class="ba_h5">贷中</h5>
                            <p><span>风控2.</span>借款人提供材料后，分公司进行初审分公司初审包括对借款人本人、家庭、工作信息及贷款目的等相关情况进行初步核实，着重材料完整性，出具初审报告。分公司车辆评估师，对该借款人抵押车辆进行评估，着重观察车辆有无撞损及违章情况，以专业技术角度完善贷款审批的佐证依据，综合当地市场情况出具评估报告。</p>
                            <p><span>风控3.</span>总部风险管理人员，通过多样化侦别形式，再次对借款人提供材料进行复核。除收集借款人初审材料外，包括相关公司、人员法院记录、负债计算、还款能力与还款计划等各个维度的信息，进行综合考量。着重借款人提供资料的真实性，本次贷款是否符合本公司的放贷要求，出具《借款人信息调查复议评审表》。</p>
                            <p><span>风控4.</span>公司总部车检专家，根据实际市场交易环节，车辆使用周期、已发事故、车辆手续、二手车市场供需情况、新车市场表现等做出综合分析对比，出具的评估报告，得出二手车价值的参考价格，同时交付风险管理部。</p>
                            <p><span>风控5.</span>法务法规部：借贷关系形成前需草拟、审查《借款协议》、《借款人服务协议》、《抵押合同》和相关的其他协议及法律文书。保证合同的主要条款、签订与生效条件、合同解除条件等约定明确并且符合法律规定，并且需要根据门店情况、市场情况及法律法规变更等情况进行修改。</p>
                            <p><span>风控6.</span>稽核部：待分公司借贷关系形成后，需对分公司提供的客户所填写的合同的合法性、完整性、真实性进行书面形式上审查。稽核通过后出具放款单，交付风险管理部进行终审，完毕后予以签署《贷款同意书》。</p>
                            <h5 class="ba_h5">贷后</h5>
                            <p><span>风控7.</span>借款人在贷款合同期内，出现GPS异常或发生逾期，由GPS和财务2个部门直接和风险管理部对接，进行初步风险分析，分析结果借款人确实构成GPS恶意掉线或恶意逾期的，上报催收部门对借款人进行资产保全。</p>
                            <p><span>风控8.</span>客户发生逾期，催收部门进行催收无效后，收到催收部门催收无效报告后及时联系合作律师事务所律师提起诉讼。与律师实时对接，及时获悉诉讼进展情况。</p>
                            <p><span>风控9.</span>每笔贷款放贷后，风险管理部实施对借款人进行贷后管理，银行监督委员会要求：50万贷款以下的借款人，按每年2个月一次借款人进行实地回访，贷后管理分明访和暗访，在回访过程中，回访专员必须做到（拍）（收）（问）（挖），深层挖掘风险，回访结束必须当天提交回访报告，风险管理部对回访报告进行横向与纵向风险审核和考核，能及时了解借款人的贷款用途，主营业务的延续或改变，作借款人营业额的对比。</p>
                        </div>
                    </div>
                </div>
                <div class="ba_min_b ba_min_zj">
                    <div class="ba_zj">
                        <h5 class="ba_h5">资金流向：</h5>
                        <p>在投资过程当中，由专业的第三方支付机构对资金流向进行全流程监管，产品募集满额后，由第三方支付机构直接将资金转给债权出让人，保障投资人的资金安全。</p>
                    </div>
                </div>
            </div>
            <div class="ba_bottom">
                <ul class="ba_b_t">
                    <li>投资记录</li>
                </ul>
                <table class="ba_table tab-invest-record">
                    <thead>
                    <tr>
                        <th>投标人</th>
                        <th>当前利率</th>
                        <th>投标金额</th>
                        <th>投标时间</th>
                        <th>投资方式</th>
                    </tr>
                    </thead>
                    <tbody><tr><td colspan="5" class="ui-text-center">获取数据中。。</td></tr></tbody>
                    <tfoot></tfoot>
                </table>
            </div>
<!--            
            <div class="ba_inte">
                <ul class="ba_b_t">
                    <li>投资交流</li>
                </ul>
                <ul class="de_jl_t">


                </ul>
                <ul class="msgpage">

                </ul>
                <div class="de_jl_b">
                    <table class="de_jl_table">
                        <tr>
                            <td class="de_first">网友表情：</td>
                            <td>
                                <input type="radio" value="1" name="face" checked="checked">
                                <img src="_STATIC_/2015/invest/image/face/pic1.gif">
                                <input type="radio" value="2" name="face">
                                <img src="_STATIC_/2015/invest/image/face/pic2.gif">
                                <input type="radio" value="3" name="face">
                                <img src="_STATIC_/2015/invest/image/face/pic3.gif">
                                <input type="radio" value="4" name="face">
                                <img src="_STATIC_/2015/invest/image/face/pic4.gif">
                                <input type="radio" value="5" name="face">
                                <img src="_STATIC_/2015/invest/image/face/pic5.gif">
                                <input type="radio" value="6" name="face">
                                <img src="_STATIC_/2015/invest/image/face/pic6.gif">
                                <input type="radio" value="7" name="face">
                                <img src="_STATIC_/2015/invest/image/face/pic7.gif">
                            </td>
                        </tr>
                        <tr>
                            <td class="de_first">留言内容：</td>
                            <td>
                                <textarea id="textarea" rows="5" maxlength="400" class="de_textarea"></textarea>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" class="de_first">
                                <button type="button" class="de_btn de_submit">提交</button>
                                <button type="reset" class="de_btn de_reset">重置</button>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>-->
        </div>
    </div>
<?php
$loanJson = array('loansn'=>$loan['loansn'],'repayment'=>$loan['repayment'],'loanmonth'=>$loan['loanmonth'],'loanday'=>$loan['loanday'],
    'yearrate'=>$loan['yearrate'],'year_rate'=>$loan['year_rate'],'activity_rate'=>$loan['activity_rate'],'loanstatus'=>$loan['loanstatus'],'status'=>$loan['status'],'loanmoney'=>$loan['loanmoney'],
    'tendermoney'=>$loan['tendermoney'],'mintendermoney'=>$loan['mintendermoney'],'newtender'=>$loan['newtender'],'maxtendermoney'=>$loan['maxtendermoney']);
$loanJson =  json_encode($loanJson) ;
$memberJson = json_encode(array('id'=>$_SESSION['member']['id'],'amount'=>$_SESSION['member']['memberInfo']['availableAmount'],
    'newtender'=>$_SESSION['member']['memberInfo']['newtender']));
$successnote = '提交成功';
?>
<script>
    //投标信息
    var loanStr =  '{$loanJson}',
        memberStr = '{$memberJson}',
        loanJson = eval( '(' + loanStr + ')' ),
        memberJson = eval( '(' + memberStr + ')' );

    $(function(){

        var ba_index = 0;
        $(".ba_min_b").eq(ba_index).addClass("ba_min_show");
        $("#ba_min_show").css({height:'260px',overflow:'hidden'});
        
        $(".ba_min_nav>li").click(function(){
            ba_index = $(this).index();
            $(this).addClass("now").siblings("li").removeClass("now");
            $(".ba_min_b").eq(ba_index).addClass("ba_min_show").siblings(".ba_min_b").removeClass("ba_min_show");
        })
        $(".ba_p_more>span").click(function(){
            $(".ba_p_more img").toggleClass("rotate");
            if($(".ba_p_more img").hasClass("rotate")){
                $("#ba_min_show").css({height:'auto'});
            }else{
                $("#ba_min_show").css({height:'265px',overflow:'hidden'});
            }
        })

        $('.de_submit').click(function(){
            if (!memberJson.id) {
                return jdbox.alert(0, '请登录后进行交流');
            }

            var text = $.trim($('#textarea').val());
            if(!text || text.length<10){
                return jdbox.alert(0,"您的内容不能少于10个字符!");
            }
            var img = $('input[name="face"]').val();

            $.post('/invest/message',{'text':text,'sn':sn,'img':img},function(response){
                jdbox.close(response.status,response.info);
                if(response.status){
                    location.href="/invest/detail/sn/"+sn+".html";
                }
            }, "json");

        })

        $('#allmade').click(function(){
            var maxtendermoney = parseInt(loanJson.loanmoney - loanJson.tendermoney);
            if(loanJson.category_admin==2){
                if(parseInt(loanJson.maxtendermoney)){
                    maxtendermoney = maxtendermoney<parseInt(loanJson.maxtendermoney)?maxtendermoney:parseInt(loanJson.maxtendermoney);
                }
                maxtendermoney = parseInt(maxtendermoney>memberJson.amount?memberJson.amount:maxtendermoney);
            }

            $('#detail-tender-money').val(maxtendermoney).keyup();

        })

        //计时和投标
        $('h3.ui-countdown').showClock("{$loan['timestamp']}","{$loan['_startTime']}");
        $('button#detail-tender-button').tenderLoan(
            {'inputObj':$('input#detail-tender-money'),'ratemoneyObj':$('em#detail-tender-rate'),'successnote':'<?php echo $successnote;?>'});

    })

    initpage=0,p=0,sn="<?php echo $loan['loansn'];?>",
        showajaxpage = function(type,count,pagenumber){
            var html = '<tr><td class="ui-text-center" colspan="5">第<span style="color:#c29540;">';
            html += p +'</span>页 / 共<span style="color:#c29540;">'+ pagenumber +'</span>页';
            if(p <= 1){
                html+= '<a href="javascript:void(0)" style="color:#a5a5a5;padding:0 3px;text-decoration:none;">首页</a>';
                html+= '<a href="javascript:void(0)" style="color:#a5a5a5;padding:0 3px;text-decoration:none;">上一页</a>';
            }else{
                html+= '<a href="javascript:void(0)" style="color:#c29540;padding:0 3px;" onclick="return ajaxgetrecord(\'prev\',1);">首页</a>';
                html+= '<a href="javascript:void(0)" style="color:#c29540;padding:0 3px;" onclick="return ajaxgetrecord(\'prev\');">上一页</a>';
            }
            if(p >= pagenumber ){
                html+= '<a href="javascript:void(0)" style="color:#a5a5a5;padding:0 3px;text-decoration:none;">下一页</a>';
                html+= '<a href="javascript:void(0)" style="color:#a5a5a5;padding:0 3px;text-decoration:none;">尾页</a>';
            }else{
                html+= '<a href="javascript:void(0)" style="color:#c29540;padding:0 3px;" onclick="return ajaxgetrecord(\'last\');">下一页</a>';
                html+= '<a href="javascript:void(0)" style="color:#c29540;padding:0 3px;" onclick="return ajaxgetrecord(\'last\','+pagenumber+');">尾页</a>';
            }
            html+='</td></tr>';
            $('.tab-invest-record tfoot').html(html);
        },ajaxgetrecord = function(type,page){
        p = (type == 'last') ? ++p : --p;
        p = page||p;
        if(initpage > 0){
            jdbox.alert(2);
        }
        initpage++;
        $.post('/invest/detailextend.html',{'sn':sn,'p':p},function(response){
            jdbox.close();
            $('.tab-invest-record tbody').html(response.info);
            if(response.status){
                var count = response.data.count,pagenumber = response.data.pagenumber;
                showajaxpage(type,count,pagenumber);
            }
            return true;
        }, "json");
    };
    setTimeout(function(){ajaxgetrecord('last');},1000);

    mspage=0,msp=0,
        msajaxpage = function(type,count,pagenumber){
            var html = '第<span style="color:#c29540;">';
            html += msp +'</span>页 / 共<span style="color:#c29540;">'+ pagenumber +'</span>页';
            if(msp <= 1){
                html+= '<a href="javascript:void(0)" style="color:#a5a5a5;padding:0 3px;text-decoration:none;">首页</a>';
                html+= '<a href="javascript:void(0)" style="color:#a5a5a5;padding:0 3px;text-decoration:none;">上一页</a>';
            }else{
                html+= '<a href="javascript:void(0)" style="color:#c29540;padding:0 3px;" onclick="return ajaxmsghtml(\'prev\',1);">首页</a>';
                html+= '<a href="javascript:void(0)" style="color:#c29540;padding:0 3px;" onclick="return ajaxmsghtml(\'prev\');">上一页</a>';
            }
            if(msp >= pagenumber ){
                html+= '<a href="javascript:void(0)" style="color:#a5a5a5;padding:0 3px;text-decoration:none;">下一页</a>';
                html+= '<a href="javascript:void(0)" style="color:#a5a5a5;padding:0 3px;text-decoration:none;">尾页</a>';
            }else{
                html+= '<a href="javascript:void(0)" style="color:#c29540;padding:0 3px;" onclick="return ajaxmsghtml(\'last\');">下一页</a>';
                html+= '<a href="javascript:void(0)" style="color:#c29540;padding:0 3px;" onclick="return ajaxmsghtml(\'last\','+pagenumber+');">尾页</a>';
            }
            html+='';
            $('.msgpage').html(html);
        },ajaxmsghtml = function(type,page){
        msp = (type == 'last') ? ++msp : --msp;
        msp = page||msp;
        if(mspage > 0){
            jdbox.alert(2);
        }
        mspage++;
        $.post('/invest/loanmessage.html',{'sn':sn,'p':msp},function(response){
            jdbox.close();
            $('.de_jl_t').html(response.info);
            if(response.status){
                var count = response.data.count,pagenumber = response.data.pagenumber;
                msajaxpage(type,count,pagenumber);
            }
            return true;
        }, "json");
    };
    setTimeout(function(){ajaxmsghtml('last');},1000);
</script>
<script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=kjYdTpN97co3AaawNXgoxPuS"></script>
<script type="text/javascript">
    var imei = "{$userinfo['imei']}";
    var pd = "{$userinfo['pwd']}";
    function coordinates(){
        $.post('/invest/gpscoordinates.html',{'imei':imei,'pd':pd},function(R){
            if(R.status){
                var sContent ="车辆信息.";
                var map = new BMap.Map("l-map");
                var point = new BMap.Point(R.info.longitude,R.info.latitude);
                map.centerAndZoom(point, 20);
                var infoWindow = new BMap.InfoWindow(sContent);  // 创建信息窗口对象
                map.openInfoWindow(infoWindow,point); //开启信息窗口
            }
        }, "json");
    }
    setTimeout(function(){coordinates('last');},1000);
</script>

