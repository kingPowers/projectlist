<?php

/**
 * @file Avatar.class.php
 * @brief 上传头像
 */
class Avatar {

    private $original = '';
    private $thumb = '';
    private $action = '';
    private $webPath = '';
    private $code = 1;
    private $msg = '';
    private $user = array();
    private $config = array(
        'AVATAR_ORIGINAL' => '/Upload/avatar/original/',
        'AVATAR_THUMB' => '/Upload/avatar/thumb/',
        'AVATAR_TYPE' => '.jpg',
        'THUMB_WIDTH' => 160,
        'RESIZE_WIDTH' => 320,
        'RESIZE_HEIGHT' => 280,
        'AVATAR_ALLOW_TYPE' => 'image/jpg,image/jpeg,image/pjpeg,image/gif,image/png',
        'AVATAR_MAX_SIZE' => 1048576,
        'AVATAR_MIX_SIZE' => 6144
    );

    public function __construct($action) {
        $this->action = strtolower($action);
        if (!in_array($this->action, array('upload', 'save'))) {
            $this->msg = '非法操作';
        }
        $this->_checkDir();
    }

    public function doAction() {
        if (empty($this->msg)) {
            if ($this->action == 'upload') {
                $this->_upload();
                session('_avatar_tmp_', array('original' => $this->original, 'thumb' => $this->thumb));
            } else {
                $_avatar_tmp_ = session('_avatar_tmp_');
                $this->original = $_avatar_tmp_['original'];
                $this->thumb = $_avatar_tmp_['thumb'];
                $this->_save();
            }
        }
        $return = $this->_getError();
        return $return;
    }

    private function _checkDir() {
        try {
            $this->user = session('member');
            if (empty($this->config)) {
                throw new Exception('获取头像配置失败');
            }
            if (empty($this->user)) {
                throw new Exception('获取用户信息失败');
            }
            $this->webPath = str_replace('ThinkPHP', 'static', THINK_PATH);
            $date = date('Ymd');
            $time = date('YmdHis');
            $original_path = $this->webPath . $this->config['AVATAR_ORIGINAL'] . $date;
            $thumb_path = $this->webPath . $this->config['AVATAR_THUMB'] . $date;
            if (!is_dir($original_path)) {
                mk_dir($original_path);
            }
            if (!is_dir($thumb_path)) {
                mk_dir($thumb_path);
            }
            $this->original = $original_path . '/' . $this->user['id'] . 'uid' . $time . $this->config['AVATAR_TYPE'];
            $this->thumb = $thumb_path . '/' . $this->user['id'] . 'uid' . $time . $this->config['AVATAR_TYPE'];
            return true;
        } catch (Exception $e) {
            $this->msg = $e->getMessage();
            return false;
        }
    }

    private function _getError() {
        $return['code'] = $this->code;
        $return['msg'] = $this->msg;
        return $return;
    }

    private function _upload() {
        try {
            $fileType = explode(',', $this->config['AVATAR_ALLOW_TYPE']);
            if ($_FILES['picture']['error'] > 0) {
                throw new Exception('上传错误');
            } else if ($_FILES['picture']['size'] > $this->config['AVATAR_MAX_SIZE']) {
                throw new Exception('上传文件过大');
            } else if ($_FILES['picture']['size'] < $this->config['AVATAR_MIX_SIZE']) {
                throw new Exception('上传文件过小');
            } else if (!in_array($_FILES['picture']['type'], $fileType)) {
                throw new Exception('上传文件格式不正确');
            }
            if (!move_uploaded_file($_FILES['picture']['tmp_name'], $this->original)) {
                throw new Exception('上传失败');
            }
            if (!$this->_resize()) {
                throw new Exception('缩放上传图片失败');
            }
            $url = 'http://static.' . DOMAIN_ROOT . str_replace($this->webPath, '', $this->original);
            $this->code = 0;
            $this->msg = $url;
            unset($_FILES['picture']);
            return true;
        } catch (Exception $e) {
            $this->msg = $e->getMessage();
            return false;
        }
    }

    private function _save() {
        $x1 = trim($_GET['x1']);
        $y1 = trim($_GET['y1']);
        $x2 = trim($_GET['x2']);
        $y1 = trim($_GET['y1']);
        $width = trim($_GET['width']);
        $height = trim($_GET['height']);
        $scale = $this->config['THUMB_WIDTH'] / $width;
        $newImageWidth = ceil($width * $scale);
        $newImageHeight = ceil($height * $scale);
        $newImage = imagecreatetruecolor($newImageWidth, $newImageHeight);
        $source = imagecreatefromjpeg($this->original);
        imagecopyresampled($newImage, $source, 0, 0, $x1, $y1, $newImageWidth, $newImageHeight, $width, $height);
        $img = imagejpeg($newImage, $this->thumb, 100);
        if (!$img) {
            $this->msg = '生成缩略图失败';
            return false;
        }
        $this->code = 0;
        $this->msg = str_replace($this->webPath, '', $this->thumb);
    }

    private function _resize() {
        $info = getimagesize($this->original);
        $width = $info[0];
        $height = $info[1];
        if (( $width > $this->config['RESIZE_WIDTH'] ) || ( $height > $this->config['RESIZE_HEIGHT'] )) {
            $w_scale = $this->config['RESIZE_WIDTH'] / $width;
            $h_scale = $this->config['RESIZE_HEIGHT'] / $height;
            $scale = ( $w_scale > $h_scale ) ? $h_scale : $w_scale;
            $newImageWidth = ceil($width * $scale);
            $newImageHeight = ceil($height * $scale);
        } else {
            $newImageWidth = $width;
            $newImageHeight = $height;
        }
        $newImage = imagecreatetruecolor($newImageWidth, $newImageHeight);
        $source = imagecreatefromjpeg($this->original);
        imagecopyresampled($newImage, $source, 0, 0, 0, 0, $newImageWidth, $newImageHeight, $width, $height);
        $img = imagejpeg($newImage, $this->original, 100);
        if (!$img) {
            return false;
        }
        return true;
    }

}

?>
