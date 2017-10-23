  <link href="_STATIC_/2015/about/css/about.css" rel="stylesheet" type="text/css"/>
  <script src="_STATIC_/2015/js/owl.carousel.js" type="text/javascript"></script>
  <script src="_STATIC_/2015/js/owl.carousel.min" type="text/javascript"></script>
  <script src="_STATIC_/2015/js/jquery-1.9.1.min" type="text/javascript"></script>
<include file="About:aboutMenu"/>
<div class="c_right about_r">
    <div class="c_nav_box">
        <div class="c_nav">
            <p>当前位置：<a href="/">首页</a> &gt; <a href="/about" class="c_nav_now">公司简介</a></p>
        </div>
    </div>
    <div class="centerMaxDiv">
        <div class="oneDiv">
             <div class="oneDiv_leftDiv ">
                <div class="titleLogoDiv">
                    <img src="_STATIC_/2015/about/image/abouts/comlayjs.png" alt=""/>
                </div>
                 <p class="one-right_wzfirst">"吉祥果"网（www.zxcfchina.com）是由智信创富金融信息服务(上海)有限公司旗下苏州吉祥财富投资管理有限公司独立运营的汽车金融中介平台。平台以先进大数据技术为支撑、车辆抵押为创新手段,通过线上线下的双向运营，为广大投贷人提供更优质的中介服务。</p>
                 <p class="one-right_wzSencond">智信创富金融信息服务(上海)有限公司是一家集财富管理,汽车消费,融资租赁等业务为一体的综合性现代服务企业，为客户量身定制全方位、个性化的投贷金融与财富管理服务。</p>
                 <p class="one-right_wzSencond">目前，公司正在核心打造汽车抵押金融，汽车理财金融，汽车消费金融，汽车基金，二手汽车交易，全新汽车交易六大汽车产品生态圈，未来，将利用自身背景和资源优势并以汽车金融为核心，布局汽车金融，汽车交易，汽车制造三架马车同步发展，深度挖掘每一环节，为中国汽车产业链发展贡献一份力量。
</p>
            </div>
            <div class="oneDiv_rightDiv">
                <img src="_STATIC_/2015/about/image/abouts/oneleft.png" alt=""/>
            </div>
          
        </div>
        <div class="twoDiv">
            <img src="_STATIC_/2015/about/image/abouts/twotuu.png" alt=""/>
        </div>
        <div class="fourDiv"> </div>
        <div class="fiveDiv"> </div>
        <div class="footerDiv">
            <img src="_STATIC_/2015/about/image/abouts/pater.png" alt="合作伙伴" class="paternlImg" />
            <ul class="aboutpal">
                
                    <li><a><img src="_STATIC_/2015/about/image/abouts/patnteher.jpg" alt="智信创富"></a></li>
                    <li><a><img src="_STATIC_/2015/index/image/WCF.jpg" alt="微财富"></a></li>
                    <li><a><img src="_STATIC_/2015/index/image/ZCB.jpg" alt="招财宝"></a></li>
                    <li><a><img src="_STATIC_/2015/index/image/CA.jpg" alt="长安保险"></a></li>
                    <li><a><img src="_STATIC_/2015/index/image/sws.jpg" alt="上海恒隆律师事务所"></a></li>
                     <li><a><img src="_STATIC_/2015/index/image/TG.jpg" alt="新浪托管"></a></li>
                    <li><a><img src="_STATIC_/2015/index/image/ZH.jpg" alt="中国银行"></a></li>
                    <li><a><img src="_STATIC_/2015/index/image/PA.jpg" alt="平安银行"></a></li>
                    <li><a><img src="_STATIC_/2015/index/image/AR.jpg" alt="安融惠众"></a></li>
                    <li><a><img src="_STATIC_/2015/index/image/TX.jpg" alt="腾讯新闻"></a></li>
                    <li><a><img src="_STATIC_/2015/index/image/HFB.jpg" alt="汇付宝"></a></li>
                    <li><a><img src="_STATIC_/2015/index/image/LG.jpg" alt="绿狗"></a></li>
                    <li><a><img src="_STATIC_/2015/index/image/CFCA.jpg" alt="CFCA"></a></li>
                    <li><a><img src="_STATIC_/2015/index/image/XD.jpg" alt="中国小额信贷联盟"></a></li>
                    <li><a><img src="_STATIC_/2015/index/image/YK.jpg" alt="应科律师事务所"></a></li>
                    <li><a><img src="_STATIC_/2015/index/image/HX.jpg" alt="和讯"></a></li>
             </ul>
        </div>
    </div>
</div>

<script>
    $(function(){	
	
	$(".Div1_main div span").mouseover(function(){
		$(this).addClass("Div1_main_span1").siblings("span").removeClass("Div1_main_span1");
	}).mouseout(function(){
		$(this).removeClass("Div1_main_span1").siblings("span");
	})
	
	
	var 
		 index = 0 ;
		Swidth = 1000 ;
		 timer = null ;
		   len = $(".Div1_title span a").length ; 
		
		function NextPage()
		{	 
			if(index>3)
			{
				index = 0 ;
                               
			}
			$(".Div1_title span a").removeClass("Div1_title_a1").eq(index).addClass("Div1_title_a1");
			$(".Div1_main").stop(true, false).animate({left: -index*Swidth+"px"},600)
                          
		}
		
		function PrevPage()
		{	
                  
			if(index<0)
			{
				index = 3 ;
                                 
                           
			}
                     
			$(".Div1_title span a").removeClass("Div1_title_a1").eq(index).addClass("Div1_title_a1");
			$(".Div1_main").stop(true, false).animate({left: -index*Swidth+"px"},600)		
		}
		
		$(".Div1_title span a").each(function(a){
            $(this).mouseover(function(){
				index = a ;
				NextPage();
			});
        });
		//下一页
		$(".Div1_next img").click(function(){
			 index++ ;
			 NextPage();
		});
		//上一页
		$(".Div1_prev img").click(function(){
			 index-- ;
			 PrevPage();
		});
		//自动滚动
		var timer = setInterval(function(){
				index++ ;
				NextPage();
			},4000);
			
		$(".Div1_next img , .Div1_main , .Div1_prev img , .Div1_title span").mouseover(function(){
			clearInterval(timer);
		});
		$(".Div1_next img , .Div1_main , .Div1_prev img , .Div1_title span").mouseleave(function(){
			timer = setInterval(function(){
				index++ ;
				NextPage();
			},4000);	
		});
			
})
</script>

<include file="About:aboutFooter"/>