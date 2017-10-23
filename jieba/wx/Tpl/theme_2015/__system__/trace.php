<div id="think_page_trace" style="background:white;margin-top:10px;font-size:12px;border-top:0px;padding:10px 0 0 0">
<div style="overflow:auto;height:204Px;text-align:left;color:#ccc;padding:8px 10px 8px 10px;border:1px solid #ccc;">
<?php 
echo '模型名称 : '.MODULE_NAME.'<br>';
echo '控制名称 : '.ACTION_NAME.'<br>';
$_trace = trace();
foreach ($_trace as $key=>$info){
echo $key.' : '.$info.'<br/>';
}?>
</div>
</div>