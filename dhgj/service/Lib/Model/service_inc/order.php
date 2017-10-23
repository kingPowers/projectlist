<?php
/*
  拖车订单
 */
$this->need('type','类型');
$type = $this->v('type');
if(!in_array($type,array("newOrderList","historyOrder","passOrder")))
{
  $member_info = $this->token_check_force();
  $memberid = $member_info['id'];
}
import("Think.ORG.Util.Order");
$order = new Order();
//获取订单配置
if ($type == "setting")
{
  $order_setting = $order->getSetting();
  $this->data['setting'] = $order_setting;
  return true;
}
//增加新拖车订单(空订单)
if($type == 'addOrder')
{
  if(false==($order_info = $order->addOrder($memberid)))$this->error("ORDER_ERROR",$order->getError());
  $this->data = $order_info;
  return true;
}
//上传订单图片
if($type == 'addImg')
{
  $this->need('file_type','照片类型');
  $this->need('order_id','订单id');
  $order_id = $this->v('order_id');
  
  $file_type = $this->v('file_type');
  if(false == ($add_url = $order->addImg($memberid,$order_id,$file_type)))$this->error("UPLOAD_IMG_ERROR",$order->getError());
  $this->data = $add_url;
  return true;
}
//删除订单图片
if($type == 'deleteImg')
{
  $this->need('file_type','照片类型');
  $this->need('file_name','照片名称');
  $this->need('order_id','订单id');
  $order_id = $this->v('order_id');
  $file_type = $this->v('file_type');
  $file_name = $this->v('file_name');
  if(false == $order->deleteImg($memberid,$order_id,$file_type,$file_name))$this->error("DELETE_IMG_ERROR",$order->getError());
  $this->data = '删除图片成功';
  return true;
}
//获取编辑订单的详情
if($type == "editInfo")
{
  $this->need('order_id',"订单id");
  $order_id = $this->v("order_id");
  if(false == ($editInfo = $order->editInfo($order_id,$memberid)))$this->error("EDIT_ERROR",$order->getError());
  $this->data = $editInfo;
  return true;  
}
//提交编辑的订单(添加订单文本信息)
if($type == "editOrder")
{
  $this->need('order_id',"订单id");
  $order_id = $this->v("order_id");
  if(false == $order->editOrder($memberid,$order_id,$_REQUEST))$this->error("EDIT_ERROR",$order->getError());
  $this->data = "订单提交成功";
  return true;
}
//我的订单列表
if($type == "myOrderList")
{
  $this->need('page','分页page');
  $page = $this->v('page');
  $number = $this->v('number')?$this->v('number'):10;
  if(false === ($orderList = $order->myOrderList($memberid,$page,$number)))$this->error("ORDER_LIST_ERROR",$order->getError());
  $this->data = $orderList;
  return true;
}
//他人订单详情
// if($type == "orderDetail")
// {
//    $this->need("order_id","订单id");
//    if(false == ($orderDetail = $order->orderDetail($order_id)))$this->error("DETAIL_ERROR",$order->getError());
//    $this->data = $orderDetail;
//    return true;
// }
//最新订单列表
if($type == "UploadImg")
{
  $this->need("order_id","订单ID");
  if(fase === ($imgUrl = $order->getImgUrl($order_id)))$this->error("UPLOAD_ERROR",$order->getError());
  $this->data = $imgUrl;
  return true;
}
if($type == "newOrderList")
{
   $number = $this->v("number")?$this->v("number"):2;
   if(false === ($newOrderList = $order->newOrderList($number)))$this->error("NEW_ORDER_ERROR",$order->getError());
   $this->data = $newOrderList;
   return true;
}
//历史订单列表
if ($type == "historyOrder")
{
  $this->need("page","分页");
  $page = $this->v("page");
  $number = $this->v("number")?$this->v("number"):4;
  if(false === ($historyOrder = $order->historyOrder($number,$page)))$this->error("HISTORY_ORDER_ERROR",$order->getError());
   $this->data = $historyOrder;
   return true;
}
//获取审核通过的订单列表
if ($type == "passOrder")
{
  $this->need("page","分页");
  $page = $this->v("page");
  $number = $this->v('number')?$this->v('number'):10;
  if(false === ($passOrderList = $order->passOrderList($page,$number)))$this->error("PASS_ORDER_ERROR",$order->getError());
  $this->data = $passOrderList;
  return true;
}
//完成订单（接单人接单）
if($type == "finishOrder")
{
  $this->need("order_id","订单id");
  $order_id = $this->v("order_id");
  if(false == $order->finishOrder($order_id,$memberid))$this->error("FINISH_ERROR",$order->getError());
  $this->data = "订单完成";
  return true;
}
//接单详情
if($type == "receiveOrderInfo")
{
  $this->need("order_id","订单id");
  $order_id = $this->v("order_id");
  if(false == ($orderInfo = $order->receiveOrderInfo($memberid,$order_id)))$this->error("RECEIVE_INFO_ERROR",$order->getError());
  $this->data = $orderInfo;
  return true;
}
$this->error('TYPE_ERROR','类型错误');
?>
