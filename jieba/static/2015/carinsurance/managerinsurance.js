function mouseFun(_this){
		var offset = $(_this).offset();
		var left = offset.left-$(_this).next(".imgDiv").width()-50;
		var top = offset.top-($(_this).next(".imgDiv").width()/2)+100;//alert($(".imgDiv").width());
		//alert(offset.left);
        $(_this).next(".imgDiv").css({"display":"block","left":left,"top":top});
        //$(".imgDivs").attr("src",imgName);
    };
function mouseFunout(){
    $(".imgDiv").css("display","none");
};
function moneyInfo (orderid) 
{
    if (orderid == '') top.jdbox.alert(0,"订单id错误");
    return top.jdbox.iframe("/Carinsurance/moneyInfo/id/" + orderid);
}
function uploadPicZip (orderid) 
{
    if (orderid == '') top.jdbox.alert(0,"订单id错误");
    var data = {};
    data.orderid = orderid;
    data.is_upload_pic = 1;
    top.jdbox.alert(2);
    $.post("/Carinsurance/uploadPic.html",data,function (F) {
        console.log(F);
       if (F.status) {
          top.jdbox.close();
          window.open(F.data);
       } else {
          top.jdbox.alert(0,F.info);
       }
    },'json')
}
function detail (orderid)
{
   return top.jdbox.iframe("/Carinsurance/detail/id/" + orderid);
}