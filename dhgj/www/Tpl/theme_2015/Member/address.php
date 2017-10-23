<include file="Public:accountMenu"/>
<script src="_STATIC_/2015/js/province&bank.js"></script>
<style>
    .add_box,.add_table{width:843px;}
    .add_table th,.add_table td{border:1px #d3d3d3 solid;height:37px;font-size:12px;text-align:center;}
    .add_table th{font-weight:bold;color:#848383;}
    .add_table .min_width{min-width:80px;}
    .add_table td{color:#989797;}
    .add_table td>a{color:#2072c6;}
    .add_b{width:843px;}
    .add_b .button{width:115px;height:33px;line-height:33px;text-align:center;color:#fff;background:#e56229;border:none;border-radius:3px;margin:14px 0 23px;}
    a.button{display:block;text-decoration:none;}
    .add_b .button:hover{background:#ea5413;}
    .add_b_box{border:1px #d3d3d3 solid;font-size:12px;}
    .add_b_box .add_b_ul{padding:16px 0 22px;}
    .add_b_ul>li{margin-bottom:10px;}
    .add_b_ul>li .add_sp_l{width:130px;height:26px;display:block;float:left;text-align:right;line-height:26px;}
    .add_b_ul>li .input{width:139px;height:24px;border:1px #c9c9c9 solid;text-indent:8px;vertical-align:middle;font-size:12px;}
    .add_b_ul>li .select{width:141px;height:26px;border:1px #c9c9c9 solid;text-indent:5px;vertical-align:middle;font-size:12px;}
    .add_b_ul>li.add_last{margin:6px 0 0;}
    .add_b_ul>li .cancel{margin-left:8px;background:#236ac9;}
    .add_b_ul>li .cancel:hover{background:#2a7bc9;}
    .hidden{display:none;}
</style>

<div class="add_box">
    <table class="add_table">
        <thead>
            <tr>
                <th class="min_width">默认地址</th>
                <th class="min_width">收件人</th>
                <th class="min_width">地区</th>
                <th>详细地址</th>
                <th class="min_width">邮编</th>
                <th class="min_width">联系方式</th>
                <th class="min_width">操作</th>
            </tr>
        </thead>
        <tbody>
        <foreach name="list" item="vo" >
            <tr addrid="{$vo.id}">
                <td><if condition="$vo.isdefault eq 0"><a js="setdef">设为默认地址</a><else/>默认地址</if></td>
                <td>{$vo.name}</td>
                <td>{$vo.province} {$vo.area}</td>
                <td>{$vo.address}</td>
                <td>{$vo.zip}</td>
                <td>{$vo['mobile']}</td>
                <td><a js="xgaddr" href="javascript:;">修改</a> | <a href="javascript:;" js="scaddr">删除</a>
                <input type="hidden" name="citycode" value="{$vo.areacode}">
                <input type="hidden" name="procode" value="{$vo.procode}">
                </td>
            </tr>
        </foreach>
        </tbody>
    </table>
</div>
<div class="add_b">
    <a class="button" js="addAddr">新增收货地址</a>
    <div class="add_b_box hidden">
        <ul class="add_b_ul">
           <li>
               <span class="add_sp_l">收 件 人：</span>
               <input type="text" class="input" placeholder="请输入收件人姓名" maxlength="8" name="addname">
            </li>
           <li>
               <span class="add_sp_l">联系方式：</span>
               <input type="text" class="input" placeholder="请输入手机号或固话" maxlength="20" name="addphone">
            </li>
           <li>
               <span class="add_sp_l">地 区：</span>


               <select name="province" class="select" id="province" name="addpro">
                   <option value="0">- - 请选择省份 - -</option>
               </select>
               <select name="city" class="select" id="city" name="addcity" style="margin-left:6px;">
                   <option value="0">- - 请选择地区 - -</option>
               </select>
               <input type="text" placeholder="请输入详细地址" class="input" style="width:240px;margin-left:6px;" maxlength="40" name="addmore">
            </li>
           <li>
               <span class="add_sp_l">邮政编码：</span>
               <input type="text" class="input" placeholder="请输入邮政编码"  maxlength="6" name="zip">
            </li>
            <li class="add_last">
                <span class="add_sp_l"></span>
                <button type="button" js="saveAdd" class="button">保存地址</button>
                <button type="button" id="closeAdd" class="button cancel">取消</button>
            </li>
        </ul>
    </div>
</div>

<script type="text/javascript">

var options;
$.each(GP,function(index,now){
    $("#province").append('<option value="'+(index+1)+'">'+now+'</option>');
})
$("#province").change(function(){
    $('#city')
    options = $("#province>option:selected").val()-1;
    $("#city option:gt(0)").remove();
    $.each(GT[options],function(index1,now1){
        $("#city").append('<option value="'+options+'_'+(index1+1)+'">'+now1+'</option>');
    })
})

    var host = "http://" + window.location.host;
    $(document).ready(function(){

		//保存或者修改地址
		$("a[js='addAddr']").click(function(){
			$(".add_b_box").show();
			$(".add_b_box  .add_b_ul li").find("input").val("");
			$(".add_b_box  .add_b_ul li select").find("option[value='0']").attr("selected",true);//.change()
			$("#city").append("<option id=\"0\">--请选择城市--</option>");
			$(".add_b_box").find("input[type='hidden']").remove();
		});

		$("#closeAdd").click(function(){
			$(".add_b_box").hide();
		});
		//修改地址
		$("a[js='xgaddr']").click(function(){
			var procode = $(this).parents("tr").find("input[name='procode']").val();
			var citycode = $(this).parents("tr").find("input[name='citycode']").val();


			$(".add_b_box").show();
			$(".add_b_box").find("input[name='addrid']").remove();
			$(".add_b_box").append("<input type='hidden' name='addrid' value='"+$(this).parents("tr").attr('addrid')+"' />");

			$("input[name='addname']").val($(this).parents("tr").find("td").eq(1).text());
			$("input[name='addphone']").val($(this).parents("tr").find("td").eq(5).text());
			$("input[name='addmore']").val($(this).parents("tr").find("td").eq(3).text());
			$("input[name='zip']").val($(this).parents("tr").find("td").eq(4).text());

			$("#province").find("option[value="+procode+"]").attr("selected",true).change();
			$("#city").find("option[value="+citycode+"]").attr("selected",true);
		});
		
		//保存地址方法
		function saveAdd(url){
			if($.trim($("[name='addname']").val()).length<1||$.trim($("[name='addphone']").val()).length<1||$.trim($("[name='addmore']").val()).length<1){
				alert("请完整填写地址信息");
				return false;
			}else if($("#province").val()=="0"||$("#area").val()=="0"){
				alert("请选择省份城市");
				return false;
			}else{
                jdbox.alert(2);
				$.ajax({
				   type: "post",
				   async: false,
				   url: url,
				   data: {
				   	   "id": $("[name='addrid']").val(),
					   "name": $("[name='addname']").val(),
					   "mobile": $("[name='addphone']").val(),
					   "address": $("[name='addmore']").val(),
                       "province": $("[name='province']").find("option:selected").text(),
                       "area": $("[name='city']").find("option:selected").text(),
                       "zip": $("[name='zip']").val(),
                       "procode": $("[name='province']").val(),
                       "areacode": $("[name='city']").val()
				   },
				   dataType: "json",
				   success: function(result){
                       jdbox.alert(result.status,result.info);
				   		if(result.status){
				   			setTimeout(function(){window.location.reload();},800);
				   		}
				   }
				});
			};
		}
		
		//保存或者修改地址
		$("button[js='saveAdd']").click(function(){
			if($(this).parent().next("input[type='hidden']").val()!=undefined){
				saveAdd(host + '/uajax/addaddress');
			}else{
				saveAdd(host + '/uajax/addaddress');
			}
		});
		
		//设为默认
		$("a[js='setdef']").click(function(){
			var addrtr = $(this).parents("tr");
			var addrid = addrtr.attr('addrid');
			$.ajax({
                type: "post",
                async: false,
                data: { "addrid" : addrid },
                url: host + "/uajax/addrdefault",
                dataType: "json",
                success: function(result) {
                    jdbox.alert(result.status,result.info);
                    if(result.status){
                        setTimeout(function(){window.location.reload();},800);
                    }
                }
			});
		});
		
		//删除地址
		$("a[js='scaddr']").click(function(){
            var addrtr = $(this).parents("tr");
            var addrid = addrtr.attr('addrid');
			$.ajax({
                type: "post",
                async: false,
                data: { "addrid" : addrid },
                url: host + "/uajax/addrdel",
                dataType: "json",
                success: function(result) {
                    jdbox.alert(result.status,result.info);
                    if(result.status){
                        setTimeout(function(){window.location.reload();},800);
                    }
                }
			});
		});
	});
    </script>




<include file="Public:accountFooter"/>