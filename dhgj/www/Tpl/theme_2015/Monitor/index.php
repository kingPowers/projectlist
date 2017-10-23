<script type="text/javascript" src="http://api.map.baidu.com/api?v=1.5&ak=Iaih5jNG1xYS2su9HG7XeT0t"></script>
<script type="text/javascript">
$(document).ready(function(){
	var mp = new BMap.Map('map');
	mp.enableScrollWheelZoom();
	mp.enableInertialDragging();

	var top_left_control = new BMap.ScaleControl({anchor: BMAP_ANCHOR_TOP_LEFT});
	var top_left_navigation = new BMap.NavigationControl();
	var top_right_navigation = new BMap.NavigationControl({anchor: BMAP_ANCHOR_TOP_RIGHT, type: BMAP_NAVIGATION_CONTROL_SMALL});
	mp.addControl(top_left_control);        
	mp.addControl(top_left_navigation);     
	mp.addControl(top_right_navigation);	

	var point = new BMap.Point(120.7773570000,31.1951090000); 
	mp.centerAndZoom(point, 10);
	
	<?php
	foreach($list as $k=>$v){
		$device_info = $v['device_info']==3?'离线':'在线';
		if($v['course']==0){
			$course = '正北';
			$course_ico = 'n';
		}elseif($v['course']<90){
			$course = '东北';
			$course_ico = 'ne';
		}elseif($v['course']==90){
			$course = '正东';
			$course_ico = 'e';
		}elseif($v['course']<180){
			$course = '东南';
			$course_ico = 'se';
		}elseif($v['course']==180){
			$course = '正南';
			$course_ico = 's';
		}elseif($v['course']<270){
			$course = '西南';
			$course_ico = 'sw';
		}elseif($v['course']==270){
			$course = '正西';
			$course_ico = 'w';
		}elseif($v['course']<360){
			$course = '西北';
			$course_ico = 'nw';
		}
		$icon = 'var myIcon = new BMap.Icon("http://static.lingqianzaixian.com/2015/monitor/images/green_'.$course_ico.'_1.png", new BMap.Size(25,25));';
		$jsvar = 'var pt'.$k.' = new BMap.Point('.$v['lng'].','.$v['lat'].');';
		$jsmarker = 'var marker'.$k.' = new BMap.Marker(pt'.$k.',{icon:myIcon});';
		$name = explode('-',$v['name']);
		$labeler = 'var label'.$k.' = new BMap.Label("'.msubstr($name[0],0,1).'** '.msubstr($name[1],0,2).'*****，'.$device_info.'，'.($v['speed']?($v['speed'].'Km/h（方向：'.$course.'）'):('停车时长:'.ceil($v['seconds']/3600).'小时')).'",{offset:new BMap.Size(40,0)});';
		$adder = 'mp.addOverlay(marker'.$k.');';
		$setlabel = 'marker'.$k.'.setLabel(label'.$k.');label'.$k.'.setStyle({"color":"#666","border-color":"#090"});';
		echo $icon.$jsvar.$jsmarker.$adder.$labeler.$setlabel;
	}
	?>
});
</script> 
<style type="text/css">
#mon_container{
	width: 1100px;
	margin:0 auto;
}
</style> 
<div id="mon_container">
	<div class="container">
		<div class="borrow_header">
			<h1 style="width:430px;margin:15px 0px;">车辆实时状态监控系统</h1>
			<div class="border left" style="width:650px"></div>
		</div>
		<div style="color:#ccc;font-size:12px;margin:10px 0px;">上次更新时间：<?php echo $timeupdate;?>（每十分钟更新一次）</div>
		<div class="intro">
			{:SITE_NAME}是车贷P2P业内，为数不多采用高科技车辆监控报警系统，并开放给投资人在线查看的平台，智信创富以汽车金融专家的态度，真正做到让您的投资看得见！
		</div>
		<div class="borrow_form" id="map" style="border:1px solid #e1e1e1;margin-top:15px;widht:1100px;height:500px;"></div>
		<div class="borrow_process" style="margin:15px 0 10px 0;line-height:200%;">
			<div style="color:#c29540;font-size:14px;">温馨提示：</h1>
			<div style="font-size:12px;color:#999;margin-top:5px;">
				1.为了保护借款人的隐私，在显示时对车牌号做了部分隐藏，敬请谅解。
				<br>
				2.为了保护借款人的隐私，在显示时对车辆的实际位置按照一定算法做了少量可追溯偏移，敬请谅解。
				<br>
				3.为了保护借款人的安全，此公示系统对部分控制单元如断油断电、强制停车、实时录音、轨迹录像、终端型号等功能做了隐藏，敬请谅解。
			</div>
		</div>
	</div>
</div>
