<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Simple Map</title>
        <style type="text/css">
            body, html {width: 100%;height: 100%;margin:0;font-family:"΢���ź�";}
            p{margin-left:5px; font-size:14px;}
        </style>
        <script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=4itF2ygdKkIfshFlQggs7DZA"></script>
    </head>
    <body>
        <div id="content" class="content">
        <input type="text" value="" id="keyword" />
        <input type="button" name="" id="" value="��ѯ" onclick="search()" />
        <div style="width:600px;height:500px;border:0px solid gray" id="container"></div>
        <p id="aa"></p>
        <script type="text/javascript">
            //����Mapʵ��
            var map = new BMap.Map("container");
            var point = new BMap.Point(118.060576,36.842432);
            map.centerAndZoom(point,15);
            //�������������
            map.enableScrollWheelZoom();
            
            //�������ͼ�ؼ�
            map.addControl(new BMap.OverviewMapControl({isOpen:false,anchor:BMAP_ANCHOR_BOTTOM_RIGHT}));
            //�������ƽ�ƿؼ�
            map.addControl(new BMap.NavigationControl());
            //��ӱ����߿ؼ�
            map.addControl(new BMap.ScaleControl());
            //��ӵ�ͼ���Ϳؼ�
            //map.addControl(new BMap.MapTypeControl());
            
            //���ñ�ע��ͼ��
            var icon = new BMap.Icon("img/icon.jpg",new BMap.Size(100,100));
            //���ñ�ע�ľ�γ��
            var marker = new BMap.Marker(new BMap.Point(118.056156,36.840988),{icon:icon});
            //�ѱ�ע��ӵ���ͼ��
            map.addOverlay(marker);
            var content = "<table>";  
                content = content + "<tr><td> ��ţ�001</td></tr>";  
                content = content + "<tr><td> �ص㣺�Ͳ����ŵ���</td></tr>"; 
                content = content + "<tr><td> ʱ�䣺2014-09-26</td></tr>";  
                content += "</table>";
            var infowindow = new BMap.InfoWindow(content);
            marker.addEventListener("click",function(){
                this.openInfoWindow(infowindow);
            });
            
            //�����ͼ����ȡ��γ������
            map.addEventListener("click",function(e){
                document.getElementById("aa").innerHTML = "�������꣺"+e.point.lng+" &nbsp;γ�����꣺"+e.point.lat;
            });
            
            //�ؼ�������
            function search(){
                var keyword = document.getElementById("keyword").value;
                var local = new BMap.LocalSearch(map, {
                renderOptions:{map: map}
            });
            local.search(keyword);
            }
        </script>
        
        </div>
    </body>
</html>