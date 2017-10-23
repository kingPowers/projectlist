 <link rel="stylesheet" href="_STATIC_/2015/index/css/index.css">
 <style type="text/css">
.info_div:hover{cursor: pointer;}
 </style>
    <div class="con_slide_box">
        <div class="center">
        <empty name="Think.session.member.id">
            <div class="tip_box">
                <a class="white_login" href="/member/login">登录</a>
                <a class="bule_reg" href="/member/register">注册</a>
            </div>
        </empty>
        </div>
        <div class="index_banner" id="bannerslide">
            <ul class="slides">                
                <li style="background:url(_STATIC_/2015/index/image/banner_01.jpg); background-position: center center;"></li> 
                <li style="background:url(_STATIC_/2015/index/image/banner_02.jpg); background-position: center center;"></li>                
            </ul>
        </div>
        <!--icon-->
        <div class="icon_div">
            <div class="icon_five">
                <ul>
                    <li><img src="_STATIC_/2015/index/image/icon_cz.png"><span>操作流程</span></li>
                    <li><img src="_STATIC_/2015/index/image/icon_reg.png"><span>注册会员</span></li>
                    <li><img src="_STATIC_/2015/index/image/icon_up.png"><span>上传订单</span></li>
                    <li><img src="_STATIC_/2015/index/image/icon_fd.png"><span>客服分单</span></li>
                    <li><img src="_STATIC_/2015/index/image/icon_jd.png"><span>接单成功</span></li>
                </ul>
            </div>
        </div>
        <!--iconed-->
        <input type="hidden" name="is_login" value="{$Think.session.member.id}">
        <notempty name="Think.session.member.id">
          <if condition="$_SESSION['member']['usertype'] eq 2">
            <div class="info_div" onclick="javascript:window.location.href='/order/my_order_list';" style="display: none;">
                <div class="pai_div" >
                    <img src="_STATIC_/2015/index/image/icon_info.png">
                    <span style="margin-left: 10px;">有派发给你的新订单，请您尽快接单。</span>
                </div>
            </div>
           </if>
        </notempty>
    </div>
    <div class="order_div">
        <div class="order_con">
            <div class="order_up">
                <div class="uporder_left">
                    <a class="btn_upo" href="/order/add_order">点击上传</a>
                </div>
                <div class="uporder_right">
                    <img src="_STATIC_/2015/index/image/icon_news.png" style="position: absolute;">
                    <foreach name="newList" item="vo">
                    <div class="order_one">
                        <div class="order_header">
                            <img src="_STATIC_/2015/index/image/pic_order.png">                            
                            <div class="order_tit">
                                <p>订单编号:<br>{$vo['contract_num']}</p>                            
                                <span style="color: #979797; font-size: 16px;">{$vo['distribute_time']}</span>
                            </div>                            
                        </div>
                        <table>
                            <tr><td class="ls8">姓       名:</td><td>{$vo['names']}</td><td>车辆型号:</td><td>{$vo['car_brand']}</td></tr>
                            <tr><td class="ls8">性       别:</td><td>{$vo['sex']}</td><td>车牌号码:</td><td>{$vo['plate_num']}</td></tr>
                            <tr><td>借款城市:</td><td>{$vo['loan_city']}</td><td>车架号码:</td><td>{$vo['frame_num']}</td></tr>
                        </table>
                        <a class="more_ord" href="/order/newOrderList">更多订单</a>
                    </div>
                    </foreach>
                </div>
            </div>
        </div>
        <div class="order_con">
            <div class="history">
                <div class="history_left">
                    <a class="btn_moreo" href="/order/newOrderList">更多订单</a>
                </div>
                <div class="history_right">
                    <table>
                        <tr>
                            <th>订单编号</th><th>姓名</th><th>性别</th><th>借款城市</th><th>车辆型号</th><th>车牌号码</th><th>车架号码</th>
                        </tr>
                        <foreach name="historyList" item="vi">
                            <tr><td class="order_num"><img src="_STATIC_/2015/index/image/icon_tc.png">{$vi['contract_num']}</td><td>{$vi['names']}</td><td>女</td><td>{$vi['loan_city']}</td><td>{$vi['car_brand']}</td><td>{$vi['plate_num']}</td><td>{$vi['frame_num']}</td></tr>
                        </foreach>
                    </table>
                </div>
            </div>
        </div> 
    </div>
    <div class="js_div">
        <div class="order_con">
            <div class="js_con">
                贷后管家是国内首家主打快捷、高效、合规的贷后催收平台，专业为客户提供互联网金融解决方案。贷后管家依托互联网大数据，通过与多家公司建立数据战略合作关系，凭借自身强大的数据技术实力，量身为自己团队定做了互联网数据管理和贷后催收系统，将每一笔案件的进展和历史记录都完整呈现，让不良资产的处置变得简单，充分提升工作效率。贷后管家以先进的互联网思维和过硬的技术实力做后盾，致力于打造业界效率最高的贷后管理方案，为互联网金融生态圈的健康发展与创新贡献自己的力量。
            </div>
        </div>
    </div>
    <div class="order_div" style="height: 770px;">
        <div class="order_con">
            <div class="tit_media"></div>
            <div class="media_div">
               <foreach name="mediaList" item="vn" key='k'>
                    <div class="mleft" nid="{$vn['id']}" style="display: <?php echo ($k==0)?"inline-block;":"none;";?>">
                        <img style="max-height:250px;margin: 10px auto;margin-bottom: 20px;" src="{$vn['pic_urls'][0]}">
                        <div class="mleft_con">
                            <div class="mleft_data">
                                <h2>{$vn['day']}</h2>
                                <span>{$vn['month']}月<br>{$vn['year']}</span>
                            </div>
                            <div class="mleft_text">
                                <h2>{$vn['title']}</h2>
                                <p style="margin-top: 20px;">{$vn['intro']}</p>
                                <a href="/media/news/id/{$vn['id']}">查看全文</a>
                            </div>
                        </div>
                     </div>
                </foreach>
                <div class="mright">
                    <div class="m_mouse_top" onclick="mouse_top(this);"></div>
                    <div class="m_banner">
                        <ol>
                          <foreach name="mediaList" item="vm">
                              <li nid="{$vm['id']}">
                                <a onclick="changeNews({$vm['id']});"><img style="width: 290px;height: 152px;" src="{$vm['pic_urls'][0]}"></a>
                              </li>
                          </foreach> 
                      </ol>   
                    </div>
                    <div class="m_mouse_down" id="down" ></div>
                </div>
            </div>
        </div>
    </div>
    <script src="_STATIC_/2015/index/js/uislider.js"></script>
    <script src="_STATIC_/2015/index/js/jquery.rotate.min.js" type="text/javascript"></script>
    <script>

    function changeNews(id) {
        // $(".mleft").css("display","none");
        // $("div[nid='"+id+"']").css("display","inline-block");
        $(".mleft").css("display","none");
        $("div[nid='"+id+"']").fadeIn(1000);
    }
    
    function mouse_top(_this)
    {
        var _this_ol = $(_this).next().children().first();
        var _field = _this_ol.find('li:first'); //此变量不可放置于函数起始处,li:first取值是变化的
        var news_id = _field.next("li").attr("nid");
        // $(".mleft").css("display","none");
        // $("div[nid='"+news_id+"']").css("display","inline-block");
        $(".mleft").css("display","none");
        $("div[nid='"+news_id+"']").fadeIn(1000);
        var last = _this_ol.find('li:last'); //此变量不可放置于函数起始处,li:last取值是变化的
        //last.prependTo(_wrap);
        var _h = last.height();
        _this_ol.css('marginTop', -_h + "px");
        last.prependTo(_this_ol);
        _this_ol.stop().animate({
            marginTop: 0
        },        
        function() { //通过取负margin值,隐藏第一行
            //$("#container ol").css('marginTop',0).prependTo(_wrap);//隐藏后,将该行的margin值置零,并插入到最后,实现无缝滚动
        })
    }
function isNewOrder()
{
    var is_login = ($("input[name='is_login']").val())?1:0;
    if(!is_login)return false;
     $.post("{:U('/index/isNewOrder')}",{"is_ajax":1},function(F){
          if(F.status == 1){
              $(".info_div").fadeIn();
          }else{
              $(".info_div").fadeOut();
          }
     },'json');
    
}
isNewOrder();
$(function(){
    setInterval("isNewOrder()",10000);
    var down = document.getElementById("down");
    down.onclick = function()
    {
        var _this_ol = $(this).prev().children().first();
        var _field = _this_ol.find('li:first'); 
        
        //此变量不可放置于函数起始处,li:first取值是变化的
        var news_id = _field.next("li").attr("nid");
        $(".mleft").css("display","none");
        $("div[nid='"+news_id+"']").fadeIn(1000);
        var last = _this_ol.find('li:last'); //此变量不可放置于函数起始处,li:last取值是变化的
        var _h = _field.height();
        _field.stop().animate({
            marginTop: -_h + 'px'
        },
       
        function() { //通过取负margin值,隐藏第一行
            _field.css('marginTop', 0).appendTo(_this_ol); //隐藏后,将该行的margin值置零,并插入到最后,实现无缝滚动
        })
    }
   // down.click();
    var re = setInterval("down.click()",5000);
    $(".m_mouse_top,.m_mouse_down,.m_banner").hover(function(){
        clearInterval(re);
    },
    function(){
        re = setInterval("down.click()",3000);
    })
});
    $(function() {
        //setInterval('mouse_down(this)',1000);
        $('#bannerslide').uislider({
            directionNav: false,
            slideshowSpeed: 5000,
            animationDuration: 500,
            pauseOnAction: true,
            start: function () {
                $('#bannerslide').attr('style', 'background:none');
            }
        });
    });
    </script> 

<!--[if lte IE 9]>-->
<!--    <script>
        $(function(){
            $('#itop').click(function(){
                uploadhide();
            })
        })
    </script>-->
<!--<![endif]-->