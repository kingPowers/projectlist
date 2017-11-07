<?php
/*
 * 门店系统申请资料图片管理控制器
 */
namespace manager\index\controller;

use manager\index\controller\Common;
use think\Db;
use ZipArchive;

class Image extends Common
{
    protected $photo_type = [
        'card_photo'        => '2',
        'credit_report'     => '5',
        'bank_card'         => '3',
        'account_statement' => '4',
        'phone_bill'        => '6',
        'net_info'          => '7',
        'life_photo'        => '8',
        'property_info'     => '9',
        'group_photo'       => '13',
        'driving_license'   => '14',
        'driver'            => '15',
        'vehicle_regi'      => '16',
        'insurance'         => '17',
        'car_net_info'      => '19',
        'car_photos'        => '18',
        'key'               => '20',
        'other'             => '11',
        'contact'          => '21',
    ];
    public function _initialize()
    {
        $loanid = $_GET['loanid'];
        $this->assign('loanid', $loanid);
    }
    //人员证件照
    public function cardPhoto()
    {
        $this->assign('loanid', $_GET['loanid']);
        $list = Db::table('photo')->where('loanid', $_GET['loanid'])->field('card_photo')->find();
        if ($list['card_photo']) {
            $list = explode('|', $list['card_photo']);
            $this->assign('list', $list);
        }
        $this->assign('field', 'card_photo');
        $this->assign('name', '添加身份证正面照与反面照(共2张)');
        if ($this->request->request("readonly") == 1) {
            return $this->fetch('material/photoinfo');
        }
        return $this->fetch('photo');

    }
    //合影
    public function groupPhoto()
    {
        $this->assign('loanid', $_GET['loanid']);
        $list = Db::table('photo')->where('loanid', $_GET['loanid'])->field('group_photo')->find();
        if ($list['group_photo']) {
            $list = explode('|', $list['group_photo']);
            $this->assign('list', $list);
        }
        $this->assign('field', 'group_photo');
        $this->assign('name', '合影');
        if ($this->request->request("readonly") == 1) {
            return $this->fetch('material/photoinfo');
        }
        return $this->fetch('photo');

    }
    //行驶证
    public function drivingLicense()
    {
        $this->assign('loanid', $_GET['loanid']);
        $list = Db::table('photo')->where('loanid', $_GET['loanid'])->field('driving_license')->find();
        if ($list['driving_license']) {
            $list = explode('|', $list['driving_license']);
            $this->assign('list', $list);
        }
        $this->assign('field', 'driving_license');
        $this->assign('name', '行驶证');
        if ($this->request->request("readonly") == 1) {
            return $this->fetch('material/photoinfo');
        }
        return $this->fetch('photo');

    }
    //驾驶证
    public function driver()
    {
        $this->assign('loanid', $_GET['loanid']);
        $list = Db::table('photo')->field('driver')->where('loanid', $_GET['loanid'])->find();
        if ($list['driver']) {
            $list = explode('|', $list['driver']);
            $this->assign('list', $list);
        }
        $this->assign('field', 'driver');
        $this->assign('name', '驾驶证');
        if ($this->request->request("readonly") == 1) {
            return $this->fetch('material/photoinfo');
        }
        return $this->fetch('photo');

    }
    //车辆登记证
    public function vehicleRegi()
    {
        $this->assign('loanid', $_GET['loanid']);
        $list = Db::table('photo')->field('vehicle_regi')->where('loanid', $_GET['loanid'])->find();
        if ($list['vehicle_regi']) {
            $list = explode('|', $list['vehicle_regi']);
            $this->assign('list', $list);
        }
        $this->assign('field', 'vehicle_regi');
        $this->assign('name', '车辆登记证');
        if ($this->request->request("readonly") == 1) {
            return $this->fetch('material/photoinfo');
        }
        return $this->fetch('photo');

    }
    //保险及理赔查询
    public function insurance()
    {
        $this->assign('loanid', $_GET['loanid']);
        $list = Db::table('photo')->field('insurance')->where('loanid', $_GET['loanid'])->find();
        if ($list['insurance']) {
            $list = explode('|', $list['insurance']);
            $this->assign('list', $list);
        }
        $this->assign('field', 'insurance');
        $this->assign('name', '保险及理赔查询');
        if ($this->request->request("readonly") == 1) {
            return $this->fetch('material/photoinfo');
        }
        return $this->fetch('photo');

    }
    //车辆照片
    public function carPhotos()
    {
        $this->assign('loanid', $_GET['loanid']);
        $list = Db::table('photo')->field('car_photos')->where('loanid', $_GET['loanid'])->find();
        if ($list['car_photos']) {
            $list = explode('|', $list['car_photos']);
            $this->assign('list', $list);
        }
        $this->assign('field', 'car_photos');
        $this->assign('name', '车辆照片');
        if ($this->request->request("readonly") == 1) {
            return $this->fetch('material/photoinfo');
        }
        return $this->fetch('photo');

    }
    //车辆网查信息(违章查询)
    public function carnetinfo()
    {
        $this->assign('loanid', $_GET['loanid']);
        $list = Db::table('photo')->field('car_net_info')->where('loanid', $_GET['loanid'])->find();
        if ($list['car_net_info']) {
            $list = explode('|', $list['car_net_info']);
            $this->assign('list', $list);
        }
        $this->assign('field', 'car_net_info');
        $this->assign('name', '车辆网查信息');
        if ($this->request->request("readonly") == 1) {
            return $this->fetch('material/photoinfo');
        }
        return $this->fetch('photo');

    }
    //钥匙
    public function key()
    {
        $this->assign('loanid', $_GET['loanid']);
        $list = Db::table('photo')->field('key')->where('loanid', $_GET['loanid'])->find();
        if ($list['key']) {
            $list = explode('|', $list['key']);
            $this->assign('list', $list);
        }
        $this->assign('field', 'key');
        $this->assign('name', '钥匙');
        if ($this->request->request("readonly") == 1) {
            return $this->fetch('material/photoinfo');
        }
        return $this->fetch('photo');

    }
    //合同照片
    public function contact()
    {
        $this->assign('loanid', $this->request->get('loanid'));
        $list = Db::table('photo')->field('contact')->where('loanid', $_GET['loanid'])->find();
        if ($list['contact']) {
            $list = explode('|', $list['contact']);
            $this->assign('list', $list);
        }
        $this->assign('field', 'contact');
        $this->assign('name', '合同照片');
        if ($this->request->request("readonly") == 1) {
            return $this->fetch('material/photoinfo');
        }
        return $this->fetch('photo');

    }

    //征信报告
    public function creditReport()
    {
        $this->assign('loanid', $_GET['loanid']);
        $list = Db::table('photo')->where('loanid', $_GET['loanid'])->field('credit_report')->find();
        if ($list['credit_report']) {
            $list = explode('|', $list['credit_report']);
            $this->assign('list', $list);
        }
        $this->assign('field', 'credit_report');
        $this->assign('name', '个人征信报告');
        if ($this->request->request("readonly") == 1) {
            return $this->fetch('material/photoinfo');
        }
        return $this->fetch('photo');
    }
    //银行卡
    public function bankCard()
    {
        $this->assign('loanid', $_GET['loanid']);
        $list = Db::table('photo')->where('loanid', $_GET['loanid'])->field('bank_card')->find();
        if ($list['bank_card']) {
            $list = explode('|', $list['bank_card']);
            $this->assign('list', $list);
        }
        $this->assign('field', 'bank_card');
        $this->assign('name', '银行卡');
        if ($this->request->request("readonly") == 1) {
            return $this->fetch('material/photoinfo');
        }
        return $this->fetch('photo');
    }
    //流水
    public function accountStatement()
    {
        $this->assign('loanid', $_GET['loanid']);
        $list = Db::table('photo')->where('loanid', $_GET['loanid'])->field('account_statement')->find();
        if ($list['account_statement']) {
            $list = explode('|', $list['account_statement']);
            $this->assign('list', $list);
        }
        $this->assign('field', 'account_statement');
        $this->assign('name', '流水');
        if ($this->request->request("readonly") == 1) {
            return $this->fetch('material/photoinfo');
        }
        return $this->fetch('photo');
    }
    //话单
    public function phoneBill()
    {
        $this->assign('loanid', $_GET['loanid']);
        $fordownload = $_GET['fordownload'];
        if ($fordownload) {
            $this->assign('fordownload', $fordownload);
        }

        $list = Db::table('photo')->where('loanid', $_GET['loanid'])->field('phone_bill')->find();
        if ($list['phone_bill']) {
            $list = json_decode($list['phone_bill'], true);
            $this->assign('list', $list);
        }
        $this->assign('field', 'phone_bill');
        $this->assign('name', '话单');
        if ($this->request->request("readonly") == 1) {
            return $this->fetch('material/photoinfo');
        }
        return $this->fetch();
    }
    //人员网查信息
    public function netInfo()
    {
        $this->assign('loanid', $_GET['loanid']);
        $list = Db::table('photo')->where('loanid', $_GET['loanid'])->where('net_info', 'not null')->find();
        if ($list['net_info']) {
            $list = explode('|', $list['net_info']);
            $this->assign('list', $list);
        }
        $this->assign('field', 'net_info');
        $this->assign('name', '人员网查信息');
        if ($this->request->request("readonly") == 1) {
            return $this->fetch('material/photoinfo');
        }
        return $this->fetch('photo');
    }
    //生活照合影
    public function lifePhoto()
    {
        $this->assign('loanid', $_GET['loanid']);
        $list = Db::table('photo')->where('loanid', $_GET['loanid'])->where('life_photo', 'not null')->find();
        if ($list['life_photo']) {
            $list = explode('|', $list['life_photo']);
            $this->assign('list', $list);
        }
        $this->assign('field', 'life_photo');
        $this->assign('name', '生活照合影');
        if ($this->request->request("readonly") == 1) {
            return $this->fetch('material/photoinfo');
        }
        return $this->fetch('photo');
    }
    //资产信息
    public function propertyInfo()
    {
        $this->assign('loanid', $_GET['loanid']);
        $list = Db::table('photo')->where('loanid', $_GET['loanid'])->where('property_info', 'not null')->find();
        if ($list['property_info']) {
            $list = explode('|', $list['property_info']);
            $this->assign('list', $list);
        }
        $this->assign('field', 'property_info');
        $this->assign('name', '资产信息');
        if ($this->request->request("readonly") == 1) {
            return $this->fetch('material/photoinfo');
        }
        return $this->fetch('photo');
    }
    //其他
    public function other()
    {
        $this->assign('loanid', $_GET['loanid']);
        $list = Db::table('photo')->where('loanid', $_GET['loanid'])->where('other', 'not null')->find();
        if ($list['other']) {
            $list = explode('|', $list['other']);
            $this->assign('list', $list);
        }
        $this->assign('field', 'other');
        $this->assign('name', '其他');
        if ($this->request->request("readonly") == 1) {
            return $this->fetch('material/photoinfo');
        }
        return $this->fetch('photo');
    }
    /**
     * addPhoto 添加文件
     * @return array
     */
    public function addPhoto()
    {
        if ($_FILES) {
            $value = $_FILES['file']['tmp_name'];
            $dir   = UPLOAD_PATH . $_POST['lid'];
            if (!is_dir($dir)) {
                if (false == mkdir($dir, 0755)) {
                    return ['error' => 1, 'status' => 0, 'msg' => '创建目录失败'];
                }
            }
            //判断话单excel json
            if ($_POST['field'] == 'phone_bill') {
                //查手机号
                $loanModel = \manager\index\model\Loan::get($_POST['lid']);
                $phone     = $loanModel->mobile;
                $fname     = $_FILES['file']['name'];
                $field = $_POST['field'];
                $add_arr=array();
                foreach ($fname as $key => $val) {
                    $ext = end(explode('.', $val));
                    if (!in_array($ext, ['xls', 'xlsx'])) {
                        return ['error' => 1, 'status' => 0, 'msg' => '请上传以.xls或.xlsx为后缀的excel文件'];
                    }
                    //上传重名  覆盖原文件
                    $fileUrl = $dir . '/' . $phone . '-' . $val;
                    // if (file_exists($fileUrl)) {
                    //     //检查图片文件是否存在
                    //     return ['status' => 0, 'msg' => '此文件名存在，请重新命名'];
                    // }
                    $tmp = $_FILES['file']['tmp_name'][$key];
                    if (false ==  move_uploaded_file($tmp, $fileUrl)) {
                        return ['error' => 1, 'status' => 0, 'msg' => '上传文件失败'];
                    } 
                    $title = $_POST['lid'] . '/' . $phone . '-' . $val;
                    $add_arr[] = ['title' => $title, 'addtime' => time()];
                }

                \think\Db::startTrans();
                try {
                    if (!in_array($field, array_keys($this->photo_type))) {
                        throw new \Exception("类型错误");
                    }
                    if (false == $loanModel = \manager\index\model\Loan::get($_POST['lid'])) {
                        throw new \Exception("订单id错误");
                    }
                    $res_loanid = Db::table('photo')->where('loanid', $_POST['lid'])->find();
                    if ($res_loanid) {
                        $res_field = Db::table('photo')->where(['loanid' => $_POST['lid']])->field($field)->find();
                        $arr = json_decode($res_field[$field], true);                        
                          foreach ($add_arr as $k => $v) {
                                foreach ($arr as $key => $value) {
                                    if($value['title']==$v['title']){
                                       unset($arr[$key]);
                                    }
                                }                              
                           }
                         foreach ($add_arr as $key => $value) {
                             $arr[]=$value;
                         }
                           // return $arr;
                          // return $arr1;
                          // foreach ($add_arr as $k => $v) {
                          //     $arr[] = $v;
                          // }
                        if (false == Db::table('photo')->where('loanid', $_POST['lid'])->update([$field => json_encode($arr)])) {
                            throw new \Exception("保存失败");
                        }
                    } else {
                        $data[$field]   = $jsonStr;
                        $data['loanid'] = $_POST['lid'];
                        if (false == Db::table('photo')->insert($data)) {
                            throw new \Exception("保存失败");
                        }
                    }

                    if (!in_array($this->photo_type[$field], explode(',', $loanModel->material))) {
                        if (false == $loanModel->allowField(true)->save(['material' => $loanModel->material . "," . $this->photo_type[$field]])) {
                            $e = [
                                'type'    => $this->photo_type[$field],
                                'bemater' => $loanModel->material,
                                'save'    => ['material' => $loanModel->material . "," . $this->photo_type[$field]],
                                'sql'     => $loanModel->getLastSql(),
                            ];
                            throw new \Exception("订单修改失败" . json_encode($e));
                        }
                    }
                    \think\Db::commit();
                    // $field      = str_replace('_', '', $field);
                    $upload_url = config("view_replace_str")['_STATIC_'] ? config("view_replace_str")['_STATIC_'] . DS . 'Upload' . DS : "http://erpstatic.lingqianzaixian.com/Upload/";
                    return ['error' => 0, 'status' => 1, 'msg' => '操作成功'];
                } catch (\Exception $ex) {
                    \think\Db::rollback();
                    return ['error' => 1, 'status' => 0, 'msg' => $ex->getMessage()];
                }
                //图片类型
            } else {
                $img    = time() . rand(111, 999) . '.jpg';
                $imgUrl = $dir . '/' . $img;
                if (false == ($add = move_uploaded_file($value, $imgUrl))) {
                    return ['error' => 1, 'msg' => '上传图片失败'];
                }
                $field = $_POST['field'];
                $img   = $_POST['lid'] . '/' . $img;
                \think\Db::startTrans();
                try {
                    if (!in_array($field, array_keys($this->photo_type))) {
                        throw new \Exception("图片类型错误");
                    }
                    if (false == $loanModel = \manager\index\model\Loan::get($_POST['lid'])) {
                        throw new \Exception("订单id错误");
                    }
                    $res_loanid = Db::table('photo')->where('loanid', $_POST['lid'])->find();
                    if ($res_loanid) {
                        $res_field    = Db::table('photo')->where(['loanid' => $_POST['lid']])->field($field)->find();
                        $addField     = $res_field[$field] . '|' . $img;
                        $addField     = trim($addField, '|');
                        $data[$field] = $addField;
                        if (false == Db::table('photo')->where('loanid', $_POST['lid'])->update($data)) {
                            throw new \Exception("图片保存失败");
                        }
                    } else {
                        $data[$field]   = $img;
                        $data['loanid'] = $_POST['lid'];
                        if (false == Db::table('photo')->insert($data)) {
                            throw new \Exception("图片保存失败");
                        }
                    }

                    if (!in_array($this->photo_type[$field], explode(',', $loanModel->material))) {
                        if (false == $loanModel->allowField(true)->save(['material' => $loanModel->material . "," . $this->photo_type[$field]])) {
                            $e = [
                                'type'    => $this->photo_type[$field],
                                'bemater' => $loanModel->material,
                                'save'    => ['material' => $loanModel->material . "," . $this->photo_type[$field]],
                                'sql'     => $loanModel->getLastSql(),
                            ];
                            throw new \Exception("订单修改失败" . json_encode($e));
                        }
                    }
                    \think\Db::commit();
                    $field      = str_replace('_', '', $field);
                    $upload_url = config("view_replace_str")['_STATIC_'] ? config("view_replace_str")['_STATIC_'] . DS . 'Upload' . DS : "http://erpstatic.lingqianzaixian.com/Upload/";
                    return ['error' => 0, 'status' => 1, 'msg' => 'ok', 'url' => $upload_url . $img, 'src' => $upload_url . $img, 'name' => $img, 'loanid' => $_POST['lid'], 'field' => $field];
                } catch (\Exception $ex) {
                    \think\Db::rollback();
                    return ['error' => 1, 'status' => 0, 'msg' => $ex->getMessage()];
                }
            }
            //拼成字符串
        }
    }
    /**
     * delphoto 删除图片
     * @return array
     */
    public function delPhoto()
    {
        if ($_POST) {
            $name     = $_POST['name'];
            $loanid   = $_POST['loanid'];
            $field    = $_POST['field'];
            $dir      = ROOT_PATH . 'public/static' . DS . 'Upload/';
            $filename = $dir . $name;
            if (file_exists($filename)) {
                //检查图片文件是否存在
                unlink($filename);
            }
            //事务
            if ($field == 'phone_bill') {
                \think\Db::startTrans();
                try {
                    /*
                    if (!file_exists($filename)) {
                    throw new \Exception('文件不存在');
                    } else {
                    if (false == unlink($filename)) {
                    throw new \Exception('删除文件失败');
                    }
                    }
                     */
                    if (!$loanid) {
                        throw new \Exception('获取订单id错误');
                    }
                    if (flase == $loanModel = \manager\index\model\Loan::get($loanid)) {
                        throw new \Exception('获取订单id错误');
                    }
                    if ($name && $loanid) {
                        $res_field = Db::table('photo')->where('loanid', $loanid)->field($field)->find()[$field];
                        $arr       = json_decode($res_field, true);
                        foreach ($arr as $key => $value) {
                            if ($value['title'] == $name) {
                                unset($arr[$key]);
                            }
                        }
                        if (empty($arr)) {
                            $material = $loanModel->material;
                            $type     = $this->photo_type[$field];
                            $rep      = str_replace($type, '', $material);
                            $rep      = trim(str_replace(',,', ',', $rep), ',');
                            if (false == $loanModel->allowField(true)->update(['id' => $loanid, 'material' => $rep])) {
                                throw new \Exception('订单修改失败');
                            }
                        }
                    }
                    $str = json_encode($arr);
                    if (false == Db::table('photo')->where('loanid', $loanid)->update([$field => $str])) {
                        throw new \Exception('更新失败');
                    }

                    \think\Db::commit();
                    return ['status' => 1, 'info' => '删除成功'];
                } catch (\Exception $ex) {
                    \think\Db::rollback();
                    return ['status' => 0, 'info' => $ex->getMessage()];
                }
            } else {

                \think\Db::startTrans();
                try {
                    /*
                    if (!file_exists($filename)) {
                    throw new \Exception('文件不存在');
                    } else {
                    if (false == unlink($filename)) {
                    throw new \Exception('删除文件失败');
                    }
                    }
                     */
                    if (!$loanid) {
                        throw new \Exception('获取订单id错误');
                    }
                    if (flase == $loanModel = \manager\index\model\Loan::get($loanid)) {
                        throw new \Exception('获取订单id错误');
                    }
                    if ($name && $loanid) {
                        $res_field = Db::table('photo')->where('loanid', $loanid)->field($field)->find()[$field];
                        $str       = str_replace($name, '', $res_field);
                        $str       = trim(str_replace('||', '|', $str), '|');
                        if ($str == '') {
                            $material = $loanModel->material;
                            $type     = $this->photo_type[$field];
                            $rep      = str_replace($type, '', $material);
                            $rep      = trim(str_replace(',,', ',', $rep), ',');
                            if (false == $loanModel->allowField(true)->update(['id' => $loanid, 'material' => $rep])) {
                                throw new \Exception('订单修改失败');
                            }
                        }
                    }
                    if (false == Db::table('photo')->where('loanid', $loanid)->update([$field => $str])) {
                        throw new \Exception('更新失败');
                    }
                    \think\Db::commit();
                    return ['status' => 1, 'info' => '删除成功'];
                } catch (\Exception $ex) {
                    \think\Db::rollback();
                    return ['status' => 0, 'info' => $ex->getMessage()];
                }
            }

            /*
        if (file_exists($filename)) {
        //检查图片文件是否存在
        unlink($filename);
        }
        if ($name && $loanid) {
        $res_field = Db::table('photo')->where('loanid', $loanid)->field($field)->find()[$field];
        $str       = str_replace($name, '', $res_field);
        $str       = trim(str_replace('||', '|', $str), '|');
        if($str==''){
        $loanModel = \manager\index\model\Loan::get($loanid);
        $material= $loanModel->material;
        $type=$this->photo_type[$field];
        $rep=str_replace($type, '', $material);
        $rep=trim(str_replace(',,', ',', $rep), ',');
        $loanModel->allowField(true)->update(['id'=>$loanid,'material'=>$rep]);
        }
        $res       = Db::table('photo')->where('loanid', $loanid)->update([$field => $str]);
        if ($res) {
        return ['status' => 1, 'info' => '删除成功'];
        } else {
        return ['status' => 0, 'info' => '删除失败'];
        }
        }
         */

        }

    }
    /**
     *  文件下载
     * @return array
     */
    public function downloads()
    {
        //删除zip压缩包
        $this->ShanChu(UPLOAD_PATH . 'zip');
        $fileNameArr = explode(',', $_GET['name']);
        $dir         = UPLOAD_PATH . 'zip';
        if (!is_dir($dir)) {
            mkdir($dir, 0755);
        }
        //文件下载
        $name     = date('YmdHis') . ".zip";
        $filename = $dir . '/' . $name;
        $zip      = new \ZipArchive(); //使用本类，linux需开启zlib，windows需取消php_zip.dll前的注释
        if ($zip->open($filename, ZIPARCHIVE::CREATE) !== true) {
            $this->error = "文件冲突";
            return false;
        }
        foreach ($fileNameArr as $val) {
            // $zip->addFile(UPLOAD_PATH.$val, basename($val));
            if ($zip->addFile(UPLOAD_PATH . $val, basename($val)) !== true) {
                return ['status' => 0, 'info' => '文件不存在'];
            }
        }
        $zip->close();
        $data = config("view_replace_str")['_STATIC_'] . '/Upload/zip/' . $name;
        return ['status' => 1, 'info' => '成功', 'data' => $data];
    }

    /**
     *  删除文件
     */
    public function ShanChu($fname)
    {
        if (is_dir($fname)) {
            //在删除之前，把里面的文件全部删掉
            $dir = opendir($fname);
            while ($dname = readdir($dir)) {
                //必须加这一项，不然可能会将整个磁盘给删掉
                if ($dname != "." && $dname != "..") {
                    $durl = $fname . "/" . $dname;
                    if (is_file($durl)) {
                        unlink($durl);
                    } else {
                        $this->ShanChu($durl);
                    }
                }
            }
            closedir($dir);
            //删除该文件夹
            //rmdir($fname);
        } else {
            //如果是文件，直接删掉
            unlink($fname);
        }
    }

}
