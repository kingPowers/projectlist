<include file="Public:accountMenu"/>
<link rel="stylesheet" href="_STATIC_/2015/member/css/recomm.css">
    <div class="rec_t">
        <ul class="ba_min_nav">
            <li class="now"><a href="/member/recomm">邀请方式</a></li>
            <li><a href="/member/friend">好友管理</a></li>
            <li><a href="/member/privilege">特权收益</a></li>
        </ul>
    </div>
    <div class="reco_box">
        <h3 class="reco_h3">方式一：复制邀请链接发好友</h3>
        <div class="reco_padd">
            <div class="copybox"><p class="copyhtml" id="copyhtml">_WWW_/member/register/invitecode/{$member['invitecode']}</p></div>
            <button type="button" class="reco_btn" id="copybtn">复制</button>
        </div>
        <h3 class="reco_h3">方式二：微信扫描分享好友</h3>
        <div class="reco_padd">
            <div class="reco_code">
                <img src="_WWW_/member/qrDemo" alt="" class="code" alt="微信二维码">
                <p class="code_p">微信扫一扫，邀请更方便</p>
            </div>
        </div>
    </div>
<script src="_STATIC_/2015/member/js/jquery.zclip.min.js"></script>
    <script>
    var i_flash;
    if (navigator.plugins) {
        for (var i=0; i < navigator.plugins.length; i++) {
            if (navigator.plugins[i].name.toLowerCase().indexOf("shockwave flash") >= 0) {
                i_flash = true;
            }
        }
    }
    
    if (i_flash) {
        $(function(){
            $("#copybtn").zclip({
                path: "_STATIC_/2015/member/js/ZeroClipboard.swf",
                copy: function(){
                return $("#copyhtml").text();
                },
                afterCopy:function(){
                   alert("复制成功"); 
                }
            })
        })
    } else {
        $("#copybtn").click(function(){
            alert("复制失败\n\n请选中上方链接手动复制")
        })
    }
    </script>

<include file="Public:accountFooter"/>