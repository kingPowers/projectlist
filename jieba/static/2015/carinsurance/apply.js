$(function () {
	 $("input[name='store_name']").keyup(function(F){
	     $keywords = $(this).val();
	     if($keywords == '')return false;
	     $(this).attr('pid','');//alert(get_store);
	     $.post(get_store,{"store_name":$keywords},function(F){
	        console.log(F);
	        var search = F.data;
	        var searchStr = '';
	        $(".search-div").html('');
	        if(search != '')$(".search-div").show();
                $.each(search,function(key,value){
                    searchStr += "<p onclick='chose(this);'>" + value + "</p>";
                });
	        /*for (var value in search)
	        {
	          searchStr += "<p onclick='chose(this);'>" + value + "</p>";
	        }*/
	        if(searchStr != '')searchStr += "<span class='close_search' onclick='closes(this);'>关闭</span>";
	        $(".search-div").append(searchStr);
	     },'json')
 	 })
})
function chose(_this)
{
  var store_name = $(_this).html();
  $("input[name='store_name']").val(store_name);
  $(".search-div").hide();
}