<?php
    header("Content-type: text/html; charset=utf-8"); 
    require_once("MyErrorException.class.php");
  /** 
  统一错误码处理 
  **/ 
  /**
  * 
  */
  class ErrorConstant 
  {
    public  $SUCCESS;
    public  $EMAIL_NULLPOINTER;
    public  $CODE_NULLPOINTER;
    public  $SEND_EMAIL_ERR;
    public  $ACCOUNT_IS_EXIST;
    public  $ACCOUNT_NOT_EXIST;
    public  $LOGIN_PWD_NULLPOINTER;
    public  $PERSON_NAME_NULLPOINTER;
    public  $ORGANIZE_NAME_NULLPOINTER;
    public  $PERSON_IDNO_NULLPOINTER;
    public  $ORGAN_CODE_NULLPOINTER;
    public  $DEFAULT_APPLICATION_NOT_EXIST;
    public  $SAVE_ACCOUNT_ERR;
    public  $EMAIL_IS_NOTEXIST;
    public  $PARAM_INVALIDATE;
    public  $CODE_INVALIDATE;
    public  $MOBILE_CODE_NOTEXIST;
    public  $NEW_PASSWORD_NULLPOINTER;
    public  $SESSION_IS_NOTEXIST;
    public  $MOBILE_NULLPOINTER;
    public  $SEND_MOBILE_ERR;
    public  $PASSWORD_INVALIDATE;
    public  $UPDATE_FIELDS_NULLPOINTER;
    public  $ACCOUNT_NULLPOINTER;
    public  $PERSON_NULLPOINTER;
    public  $ORGANIZE_NULLPOINTER;
    public  $DOC_NAME_NULLPOINTER;
    public  $DOC_TYPE_INVALIDATE;
    public  $OSS_UPLOAD_FILE_ERROR;
    public  $RECEIVER_SAME_AS_SENDER;
    public  $RECEIVER_IS_EXIST;
    public  $PAGENUM_NULLPOINTER;
    public  $FILE_ANALYSE_ERR;
    public  $IMAGE_DATA_ERR;
    public  $ONLU_REALNAME_ACCOUNT_SIGN;
    public  $EMAIL_IS_EXIST;
    public  $MOBILE_IS_EXIST;
    public  $PASSWORD_LENGTH_INVALIDATE;
    public  $PWDANSWER_NULLPOINTER;
    public  $PWDANSWER_WRONG;
    public  $PWDREQUEST_SAME;
    public  $REAL_NAME_INVALIDATE;
    public  $PRICE_INVALIDATE;
    public  $ORGAN_TYPE_INVALIDATE;
    public  $ORGAN_REG_TYPE_INVALIDATE;
    public  $LEGAL_AREA_INVALIDATE;
    public  $DELETE_ACCOUNT_FAIL;
    public  $EMAIL_IS_EXIST_ACCOUNT;
    public  $NAME_LENGTH_INVALID;
    public  $PERSON_AREA_ILLEGAL;
    public  $ORGAN_CODE_ERROR;
    public  $IDCARD_ERROR;
    public  $LEGAL_IDCARD_ERROR;
    public  $LEGAL_NAME_NULL;
    public  $LEGAL_IDCARD_NULL;
    public  $AGENT_IDCARD_ERROR;
    public  $AGENT_NAME_NULL;
    public  $AGENT_IDCARD_NULL;
    public  $INVALID_MOBILE;
    public  $ACCOUNTUID_EMPTY;
    public  $SESEALID_NULLPOINTER;
    public  $DOCID_NULLPOINTER;
    public  $KEY_NULLPOINTER;
    public  $RECEIVER_IS_NOTEXIST;
    public  $FILE_NOT_EXIST;
    public  $SAVE_FILE_ERR;
    public  $FILE_OVER_LENGTH;
    public  $PARAM_NULLPOINTER;
    public  $SIGNER_HAVE_SIGNED;
    public  $SIGNPWD_ERROR;
    public  $CERT_NOT_EXIST;
    public  $NOT_FILE;
    public  $SERVER_CERT_SIGN_ERR;
    public  $INFO_NOT_ENOUGH;
    public  $MODEL_SEAL_FILED;
    public  $ONLY_ONE_SEAL;
    public  $ONLY_CLOUD_OR_DRAFT;
    public  $NOT_OWN_DOC;
    public  $ONLY_DRAFT;
    public  $NO_CERT;
    public  $NO_SEAL;
    public  $EQUIPID_INVALIDATE;
    public  $DOC_NOT_EXIST;
    public  $SESEAL_NOT_EXIST;
    public  $NO_SIGN_LEVEL;
    public  $Doc_SVAEPATH_NULLPOINTER;
    public  $CREATE_CERT_ERR;
    public  $SIGNPOS_NULLPOINTER;
    public  $DOC_NO_FLOW;
    public  $CONNECT_PDF_TO_IMAGE_SERVER_FAILED;
    public  $MODEL_SEAL_NAME_LESS_MIN;
    public  $MODEL_SEAL_NAME_OVER_MAX;
    public  $MODEL_SQUARE_SEAL_FILED;
    public  $MODEL_SEAL_NUMBER_OVER_MAX;
    public  $MODEL_SEAL_PENDING_OVER_MAX;
    public  $HAVE_SAME_TYPE_TEMPLATE_SEAL;
    public  $CLOUD_FILE_OVER_LENGTH;
    public  $GET_OSS_FILE_FAILED;
    public  $CERT_BASE64_NULLPOINTER;
    public  $CERT_IS_EXIST;
    public  $CERT_SN_NULLPOINTER;
    public  $CERT_NOT_BELONG_ACCOUNT;
    public  $SEAL_BASE64_NULLPOINTER;
    public  $SEAL_IS_EXIST;
    public  $SEAL_IS_NOTEXIST;
    public  $INVALIDATE_CERT_BASE64;
    public  $DISK_UPLOAD_FILE_ERROR;
    public  $SIGN_PDF_ERR;
    public  $SEAL_TEMP_NOT_MATCH;
    public  $CERT_REPLACE_FAILED;
    public  $NOTIFY_SIGN_OPRATE_TYPE_NO;
    public  $NOTIFY_SIGN_SIGN_CUSTOMNUM_NO;
    public  $NOTIFY_URL_NO;
    public  $NOTIFY_UPDATE_STATUS_FAIL;
    public  $AUDIT_EXIST_SAME_APPLICATION;
    public  $TEMPLATE_NAME_NULLPOINTER;
    public  $TEMPLATE_NOT_EXIST;
    public  $TEMPLATE_ID_NULLPOINTER;
    public  $TEMPLATE_DATA_NULLPOINTER;
    public  $TEMPLATE_CONVERT_ERR;
    public  $TOKEN_NULLPOINTER;
    public  $PROJECT_ID_NULLPOINTER;
    public  $PROJECT_SECRET_NULLPOINTER;
    public  $TOKEN_IS_NOTEXIST;
    public  $TOKEN_EXPIRED;
    public  $ACCESS_DENIED;
    public  $PROJECT_INVALIDATE;
    public  $EQUIPID_NULLPOINTER;
    public  $OSS_APPLY_NULLPOINTER;
    public  $PAY_ACCOUNTID_NOT_EXIST;
    public  $PAY_ACCOUNTID_TYPE_ERR;
    public  $CHARGING_FAILED;
    public  $PROJECT_NO_WHITE_IP_CONFIG;
    public  $GET_CLIENT_IP_FAILED;
    public  $CLIENT_IP_ILLEGAL;
    public  $ERRREFLOG_NOT_EXIT;
    public  $ACCOUNTREF_IS_EXIT;
    public  $ERROR_OSS_KEY;
    public  $ERR_UPDATE_PROJECTID;
    public  $ERR_EMAIL_FORMAT;
    public  $ERR_IDNO_FORMAT;
    public  $ERR_ORGANIZE_FORMAT;
    public  $ERR_VIPACCOUNT_NOT_EXIST;
    public  $ERR_DELETE_ACCOUNT_PROJECT_REF;
    public  $ERR_SAVE_TEMPLATE;
    public  $ERR_OSS_UPLOAD_TEMPLATE;
    public  $ERR_DELETE_TEMPLATE;
    public  $ERR_PARAM_TEMPLATE;
    public  $ERR_GET_OSS_TEMP_DOWNURL;
    public  $ERR_DATEBASE_FAILED;
    public  $ERR_SAVE_TAG;
    public  $ERR_NO_TAG;
    public  $ADDACCOUNT_SUCC_ADDTAGREF_ERR;
    public  $ERR_QUERYPARAM_NULLPOINTER;
    public  $ERR_NO_SELECT_TAG;
    public  $ERR_PARAM_DELETE;
    public  $ERR_DELETE_TAGREF;
    public  $ERR_PARAM_ADD_TAGNAME;
    public  $ERR_PARAM_ADD_TAGNAME_ALREADY;
    public  $ERR_DELETE_ERRREFLOG;
    public  $ERR_PARAM_SAVE_TAGSEAL;
    public  $QUERY_TAGSEAL_FAIL;
    public  $PDF_HASH_NULLPOINTER;
    public  $ADMIN_NOT_EXIST;
    public  $CUSTOMFLAG_NULLPOINTER;
    public  $SIGNDATA_NULLPOINTER;
    public  $NOT_SUPPORT_SEPARATE_SIGN;
    public  $ADD_RECIEVER_FAILED;
    public  $DEVID_NULLPOINTER;
    public  $SEALCOLOR_NULLPOINTER;
    public  $SIGNTYPE_NULLPOINTER;
    public  $STREAM_NULLPOINTER;
    public  $SIGNRESULT_NULLPOINTER;
    public  $OVER_MAX_ACCESS_COUNT;
    public  $EPRWEB_SERVICE_FAILED;
    public  $EPRWEB_LOGIN_MISMATCH;
    public  $EPRWEB_LOGIN_RNSTATUS;
    public  $SING_FILE_NOT_EXISTS;
    public  $SIGNED_FILE_NOT_EXISTS;
    public  $SIGNER_NOT_EXISTS;


    function __construct(){
        $this->SUCCESS = new MyErrorException(0, "成功");
        $this->EMAIL_NULLPOINTER = new MyErrorException(1001, "邮箱必填");
        $this->CODE_NULLPOINTER = new MyErrorException(1002, "验证码必填");
        $this->SEND_EMAIL_ERR = new MyErrorException(1004, "发送邮件失败");
        $this->ACCOUNT_IS_EXIST = new MyErrorException(1006, "账户已经存在");
        $this->ACCOUNT_NOT_EXIST = new MyErrorException(1007, "账户不存在");
        $this->LOGIN_PWD_NULLPOINTER = new MyErrorException(1008, "登录密码必填");
        $this->PERSON_NAME_NULLPOINTER = new MyErrorException(1009, "姓名必填");
        $this->ORGANIZE_NAME_NULLPOINTER = new MyErrorException(1010, "机构名称必填");
        $this->PERSON_IDNO_NULLPOINTER = new MyErrorException(1011, "身份证号或护照号必填");
        $this->ORGAN_CODE_NULLPOINTER = new MyErrorException(1012, "组织机构代码号必填");
        $this->DEFAULT_APPLICATION_NOT_EXIST = new MyErrorException(1013, "默认服务器证书不存在");
        $this->SAVE_ACCOUNT_ERR = new MyErrorException(1014, "保存账户信息失败");
        $this->EMAIL_IS_NOTEXIST = new MyErrorException(1015, "邮箱不存在");
        $this->PARAM_INVALIDATE = new MyErrorException(1016, "参数错误");
        $this->CODE_INVALIDATE = new MyErrorException(1017, "验证码错误");
        $this->MOBILE_CODE_NOTEXIST = new MyErrorException(1018, "手机验证码不存在");
        $this->NEW_PASSWORD_NULLPOINTER = new MyErrorException(1019, "新密码必填");
        $this->SESSION_IS_NOTEXIST = new MyErrorException(1020, "session不存在");
        $this->MOBILE_NULLPOINTER = new MyErrorException(1021, "手机号必填");
        $this->SEND_MOBILE_ERR = new MyErrorException(1022, "发送短信失败");
        $this->PASSWORD_INVALIDATE = new MyErrorException(1023, "口令错误");
        $this->UPDATE_FIELDS_NULLPOINTER = new MyErrorException(1024, "更新字段必填");
        $this->ACCOUNT_NULLPOINTER = new MyErrorException(1025, "账户必填");
        $this->PERSON_NULLPOINTER = new MyErrorException(1026, "个人信息必填");
        $this->ORGANIZE_NULLPOINTER = new MyErrorException(1027, "企业信息必填");
        $this->DOC_NAME_NULLPOINTER = new MyErrorException(1030, "文档名必填");
        $this->DOC_TYPE_INVALIDATE = new MyErrorException(1031, "文档类型不支持");
        $this->OSS_UPLOAD_FILE_ERROR = new MyErrorException(1032, "文档上传oss失败");
        $this->RECEIVER_SAME_AS_SENDER = new MyErrorException(1033, "接收者不能与发送者相同");
        $this->RECEIVER_IS_EXIST = new MyErrorException(1034, "接收者已经存在");
        $this->PAGENUM_NULLPOINTER = new MyErrorException(1035, "页码必填");
        $this->FILE_ANALYSE_ERR = new MyErrorException(1036, "文档解析错误");
        $this->IMAGE_DATA_ERR = new MyErrorException(1037, "图片数据错误");
        $this->ONLU_REALNAME_ACCOUNT_SIGN = new MyErrorException(1038, "只允许实名用户签署");
        $this->EMAIL_IS_EXIST = new MyErrorException(1039, "邮箱已经存在");
        $this->MOBILE_IS_EXIST = new MyErrorException(1040, "该手机号已绑定e签宝");
        $this->PASSWORD_LENGTH_INVALIDATE = new MyErrorException(1041, "口令长度错误，至少6位");
        $this->PWDANSWER_NULLPOINTER = new MyErrorException(1042, "答案不能为空");
        $this->PWDANSWER_WRONG = new MyErrorException(1043, "安全问题回答错误");
        $this->PWDREQUEST_SAME = new MyErrorException(1044, "安全问题不能相同");
        $this->REAL_NAME_INVALIDATE = new MyErrorException(1045, "实名状态无效");
        $this->PRICE_INVALIDATE = new MyErrorException(1046, "金额验证不通过");
        $this->ORGAN_TYPE_INVALIDATE = new MyErrorException(1047, "单位类型无效");
        $this->ORGAN_REG_TYPE_INVALIDATE = new MyErrorException(1048, "单位注册类型无效");
        $this->LEGAL_AREA_INVALIDATE = new MyErrorException(1049, "法人归属地无效");
        $this->DELETE_ACCOUNT_FAIL = new MyErrorException(1050, "关联账户删除失败");
        $this->EMAIL_IS_EXIST_ACCOUNT = new MyErrorException(1051, "该邮箱已绑定e签宝");
        $this->NAME_LENGTH_INVALID = new MyErrorException(1052, "用户名允许的长度范围为2到4个字符");
        $this->PERSON_AREA_ILLEGAL = new MyErrorException(1059, "用户或企业法人归属地非法");
        $this->ORGAN_CODE_ERROR = new MyErrorException(1060, "组织机构代码号格式错误");
        $this->IDCARD_ERROR = new MyErrorException(1061, "身份证格式错误");
        $this->LEGAL_IDCARD_ERROR = new MyErrorException(1062, "法人身份证格式错误");
        $this->LEGAL_NAME_NULL = new MyErrorException(1063, "法人姓名不能为空");
        $this->LEGAL_IDCARD_NULL = new MyErrorException(1064, "法人身份证不能为空");
        $this->AGENT_IDCARD_ERROR = new MyErrorException(1065, "代理人身份证格式错误");
        $this->AGENT_NAME_NULL = new MyErrorException(1066, "代理人姓名不能为空");
        $this->AGENT_IDCARD_NULL = new MyErrorException(1067, "代理人身份证不能为空");
        $this->INVALID_MOBILE = new MyErrorException(1068, "手机号格式不正确");
        $this->ACCOUNTUID_EMPTY = new MyErrorException(1069, "参数accountUid未填");
        $this->SESEALID_NULLPOINTER = new MyErrorException(2001, "印章标识必填");
        $this->DOCID_NULLPOINTER = new MyErrorException(2002, "签署文档标识必填");
        $this->KEY_NULLPOINTER = new MyErrorException(2003, "关键字签章需要关键字");
        $this->RECEIVER_IS_NOTEXIST = new MyErrorException(2004, "未指定接收者");
        $this->FILE_NOT_EXIST = new MyErrorException(2005, "文件不存在");
        $this->SAVE_FILE_ERR = new MyErrorException(2006, "存储文件失败");
        $this->FILE_OVER_LENGTH = new MyErrorException(2007, "文件超出最大限制");
        $this->PARAM_NULLPOINTER = new MyErrorException(2008, "参数必填");
        $this->SIGNER_HAVE_SIGNED = new MyErrorException(2009, "该用户已经签署过此文件");
        $this->SIGNPWD_ERROR = new MyErrorException(2010, "签署口令错误");
        $this->CERT_NOT_EXIST = new MyErrorException(2011, "证书不存在");
        $this->NOT_FILE = new MyErrorException(2012, "不是有效的文件信息");
        $this->SERVER_CERT_SIGN_ERR = new MyErrorException(2013, "印章签名失败");
        $this->INFO_NOT_ENOUGH = new MyErrorException(2014, "用户信息不全，请补全后再操作");
        $this->MODEL_SEAL_FILED = new MyErrorException(2015, "模板印章生成失败");
        $this->ONLY_ONE_SEAL = new MyErrorException(2016, "当前只剩下一个印章，无法删除");
        $this->ONLY_CLOUD_OR_DRAFT = new MyErrorException(2017, "只能删除云文件或草稿文件");
        $this->NOT_OWN_DOC = new MyErrorException(2018, "该文件不属于你，无权操作");
        $this->ONLY_DRAFT = new MyErrorException(2019, "只能操作草稿文件");
        $this->NO_CERT = new MyErrorException(2020, "您还没有任何证书");
        $this->NO_SEAL = new MyErrorException(2021, "该证书没有任何印章");
        $this->EQUIPID_INVALIDATE = new MyErrorException(2022, "设备无效");
        $this->DOC_NOT_EXIST = new MyErrorException(2023, "签署文件不存在");
        $this->SESEAL_NOT_EXIST = new MyErrorException(2024, "签署印章不存在");
        $this->NO_SIGN_LEVEL = new MyErrorException(2037, "无权签署");
        $this->Doc_SVAEPATH_NULLPOINTER = new MyErrorException(2025, "文档保存路径为空");
        $this->CREATE_CERT_ERR = new MyErrorException(2026, "创建证书失败");
        $this->SIGNPOS_NULLPOINTER = new MyErrorException(2038, "签署位置为空");
        $this->DOC_NO_FLOW = new MyErrorException(2027, "无签署流程文档不能添加接收者");
        $this->CONNECT_PDF_TO_IMAGE_SERVER_FAILED = new MyErrorException(2028, "pdf转图片服务器连接异常");
        $this->MODEL_SEAL_NAME_LESS_MIN = new MyErrorException(2029, "不支持两个字以下的模板印章");
        $this->MODEL_SEAL_NAME_OVER_MAX = new MyErrorException(2030, "不支持十八个字以上的模板印章");
        $this->MODEL_SQUARE_SEAL_FILED = new MyErrorException(2031, "不支持该名字长度的方形模板印章");
        $this->MODEL_SEAL_NUMBER_OVER_MAX = new MyErrorException(2032, "印章数量过多");
        $this->MODEL_SEAL_PENDING_OVER_MAX = new MyErrorException(2033, "待审核印章数量过多");
        $this->HAVE_SAME_TYPE_TEMPLATE_SEAL = new MyErrorException(2034, "该类型印章已存在");
        $this->CLOUD_FILE_OVER_LENGTH = new MyErrorException(2035, "个人云文件空间不足");
        $this->GET_OSS_FILE_FAILED = new MyErrorException(2036, "从OSS上下载文件失败，请检查osskey是否有效");
        $this->CERT_BASE64_NULLPOINTER = new MyErrorException(3001, "证书Base64必填");
        $this->CERT_IS_EXIST = new MyErrorException(3002, "证书已经存在");
        $this->CERT_SN_NULLPOINTER = new MyErrorException(3003, "证书序列号必填");
        $this->CERT_NOT_BELONG_ACCOUNT = new MyErrorException(3004, "证书不属于此账户");
        $this->SEAL_BASE64_NULLPOINTER = new MyErrorException(3005, "印章图片Base64必填");
        $this->SEAL_IS_EXIST = new MyErrorException(3006, "印章已经存在");
        $this->SEAL_IS_NOTEXIST = new MyErrorException(3007, "印章不存在");
        $this->INVALIDATE_CERT_BASE64 = new MyErrorException(3008, "证书Base64无效");
        $this->DISK_UPLOAD_FILE_ERROR = new MyErrorException(3009, "文档上传文件系統失败");
        $this->SIGN_PDF_ERR = new MyErrorException(3010, "文档签署失败，请检查传参");
        $this->SEAL_TEMP_NOT_MATCH = new MyErrorException(3013, "印章模板类型和用户类型不匹配");
        $this->CERT_REPLACE_FAILED = new MyErrorException(3014, "替换证书失败");
        $this->NOTIFY_SIGN_OPRATE_TYPE_NO = new MyErrorException(3021, "未知的操作类型");
        $this->NOTIFY_SIGN_SIGN_CUSTOMNUM_NO = new MyErrorException(3022, "未知的客户自定义标识");
        $this->NOTIFY_URL_NO = new MyErrorException(3023, "异步通知地址未知");
        $this->NOTIFY_UPDATE_STATUS_FAIL = new MyErrorException(3024, "修改通知状态失败");
        $this->AUDIT_EXIST_SAME_APPLICATION = new MyErrorException(3501, "已存在相同类型的申请");
        $this->TEMPLATE_NAME_NULLPOINTER = new MyErrorException(4001, "模板名称必填");
        $this->TEMPLATE_NOT_EXIST = new MyErrorException(4002, "模板不存在");
        $this->TEMPLATE_ID_NULLPOINTER = new MyErrorException(4003, "模板ID必填");
        $this->TEMPLATE_DATA_NULLPOINTER = new MyErrorException(4004, "模板数据必填");
        $this->TEMPLATE_CONVERT_ERR = new MyErrorException(4005, "模板创建PDF文件失败");
        $this->TOKEN_NULLPOINTER = new MyErrorException(5001, "token必填");
        $this->PROJECT_ID_NULLPOINTER = new MyErrorException(5002, "项目编号必填");
        $this->PROJECT_SECRET_NULLPOINTER = new MyErrorException(5003, "项目验证码必填");
        $this->TOKEN_IS_NOTEXIST = new MyErrorException(5004, "token不存在");
        $this->TOKEN_EXPIRED = new MyErrorException(5005, "token已超时");
        $this->ACCESS_DENIED = new MyErrorException(5006, "无权访问");
        $this->PROJECT_INVALIDATE = new MyErrorException(5007, "接口调用者身份验证失败");
        $this->EQUIPID_NULLPOINTER = new MyErrorException(5008, "设备标识必填");
        $this->OSS_APPLY_NULLPOINTER = new MyErrorException(5009, "oss对象标识必填");
        $this->PAY_ACCOUNTID_NOT_EXIST = new MyErrorException(5010, "账户信息不存在");
        $this->PAY_ACCOUNTID_TYPE_ERR = new MyErrorException(5011, "支付实名认证方式仅支持个人账户");
        $this->CHARGING_FAILED = new MyErrorException(5012, "任务失败");
        $this->PROJECT_NO_WHITE_IP_CONFIG = new MyErrorException(5013, "接口调用方尚未配置ip白名单，请联系e签宝管理员配置");
        $this->GET_CLIENT_IP_FAILED = new MyErrorException(5014, "无法获取客户端ip地址");
        $this->CLIENT_IP_ILLEGAL = new MyErrorException(5015, "客户端地址非法，被禁止访问");
        $this->ERRREFLOG_NOT_EXIT = new MyErrorException(6000, "失败日志记录查询失败！");
        $this->ACCOUNTREF_IS_EXIT = new MyErrorException(6001, "您已关联该用户，无须再次关联！");
        $this->ERROR_OSS_KEY = new MyErrorException(6002, "文件未找到！");
        $this->ERR_UPDATE_PROJECTID = new MyErrorException(6003, "更新项目标识失败！");
        $this->ERR_EMAIL_FORMAT = new MyErrorException(6004, "邮箱号码格式不正确！");
        $this->ERR_IDNO_FORMAT = new MyErrorException(6005, "身份证号码格式不正确！");
        $this->ERR_ORGANIZE_FORMAT = new MyErrorException(6006, "组织机构代码证/多证合一号码格式不正确！");
        $this->ERR_VIPACCOUNT_NOT_EXIST = new MyErrorException(6007, "vip账户不存在");
        $this->ERR_DELETE_ACCOUNT_PROJECT_REF = new MyErrorException(6008, "删除账户项目关联关系失败！");
        $this->ERR_SAVE_TEMPLATE = new MyErrorException(6100, "上传模板信息失败");
        $this->ERR_OSS_UPLOAD_TEMPLATE = new MyErrorException(6101, "模板上传失败");
        $this->ERR_DELETE_TEMPLATE = new MyErrorException(6102, "删除模板信息失败");
        $this->ERR_PARAM_TEMPLATE = new MyErrorException(6103, "参数异常，请联系管理员");
        $this->ERR_GET_OSS_TEMP_DOWNURL = new MyErrorException(6104, "获取osss上文件下载地址失败");
        $this->ERR_DATEBASE_FAILED = new MyErrorException(6105, "数据库连接失败");
        $this->ERR_SAVE_TAG = new MyErrorException(6201, "新增用户组失败");
        $this->ERR_NO_TAG = new MyErrorException(6202, "用户组不能为空");
        $this->ADDACCOUNT_SUCC_ADDTAGREF_ERR = new MyErrorException(6203, "创建用户成功，但添加用户至用户组失败！");
        $this->ERR_QUERYPARAM_NULLPOINTER = new MyErrorException(6204, "用户组信息查询参数异常");
        $this->ERR_NO_SELECT_TAG = new MyErrorException(6205, "导入时未选择用户组");
        $this->ERR_PARAM_DELETE = new MyErrorException(6206, "删除用户组下的用户参数有误");
        $this->ERR_DELETE_TAGREF = new MyErrorException(6207, "删除用户组失败");
        $this->ERR_PARAM_ADD_TAGNAME = new MyErrorException(6208, "用户组名称必填");
        $this->ERR_PARAM_ADD_TAGNAME_ALREADY = new MyErrorException(6209, "用户组名称已存在");
        $this->ERR_DELETE_ERRREFLOG = new MyErrorException(6210, "删除日志记录失败");
        $this->ERR_PARAM_SAVE_TAGSEAL = new MyErrorException(6300, "更新用户组账户印章参数异常！");
        $this->QUERY_TAGSEAL_FAIL = new MyErrorException(6301, "查询用户组账户印章参数异常！");
        $this->PDF_HASH_NULLPOINTER = new MyErrorException(6302, "原文必填");
        $this->ADMIN_NOT_EXIST = new MyErrorException(7001, "管理员账户不存在");
        $this->CUSTOMFLAG_NULLPOINTER = new MyErrorException(9000, "自定义标识必填");
        $this->SIGNDATA_NULLPOINTER = new MyErrorException(9001, "签名结果不能为空");
        $this->NOT_SUPPORT_SEPARATE_SIGN = new MyErrorException(9002, "SDK不支持单独签");
        $this->ADD_RECIEVER_FAILED = new MyErrorException(9003, "添加文档接收者失败");
        $this->DEVID_NULLPOINTER = new MyErrorException(9004, "开发者账户为空");
        $this->SEALCOLOR_NULLPOINTER = new MyErrorException(9005, "印章颜色为空");
        $this->SIGNTYPE_NULLPOINTER = new MyErrorException(9006, "签章类型必填");
        $this->STREAM_NULLPOINTER = new MyErrorException(9007, "文件流为空");
        $this->SIGNRESULT_NULLPOINTER = new MyErrorException(9008, "签署结果为空");
        $this->OVER_MAX_ACCESS_COUNT = new MyErrorException(9999, "系統繁忙，请稍候再试");
        $this->EPRWEB_SERVICE_FAILED = new MyErrorException(100000, "登录认证时发生内部错误");
        $this->EPRWEB_LOGIN_MISMATCH = new MyErrorException(100001, "您使用的登录用户与签署用户不一致");
        $this->EPRWEB_LOGIN_RNSTATUS = new MyErrorException(100002, "您当前的帐号的实名状态存在异常");
        $this->SING_FILE_NOT_EXISTS = new MyErrorException(10003, "待签署文档不存在");
        $this->SIGNED_FILE_NOT_EXISTS = new MyErrorException(10004, "签署后文档不存在");
        $this->SIGNER_NOT_EXISTS = new MyErrorException(10005, "签署者必填");
    }
  }
?>