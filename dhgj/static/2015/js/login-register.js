//注册、登录
(function($) {
	var loginUrl = '/member/login',
		registerUrl = '/member/register',
		uniqueUrl = '/member/checkUnique',
		smscodeUrl = '/public/smsajaxcheck.html';
	    checkinvUrl = '/member/checkinvitecode.html';
        verifycodeUrl = '/public/verifyajaxcheck.html';
	var _unique = true,
		_sending = false;
	var _username = function(val, n) {
			if ($(n).attr('rel') == val || val == '') {
				_error(val, n);
				return false;
			}
			if (val.length < 2) {
				_error('长度不能小于两个字符', n);
				return false;
			}
			if (_unique) {
				_wait(n);
				$.post(uniqueUrl, {
					'type': 'username',
					'username': val
				}, function(_result) {
					if (!_result.status) {
						return _error(_result.info, n);
					} else {
						return _success(n,_result.info);
					}
				}, 'json');
			} else {
				_success(n);
				return true;
			}
		}
	var _url = function(val, n) {
		_success(n);
		return true;
	}
	var _password = function(val, n) {
			if ($(n).attr('rel') == val || val == '') {
				_error(val, n);
				return false;
			}
			if (val.length < 6) {
				return _error('密码格式不正确', n);
			}
			_success(n);
			return true;
		}
	var _re_password = function(val, n) {
			if ($(n).attr('rel') == val || val == '') {
				_error(val, n);
				return false;
			}
			if (val.length < 6 || val !== $("input[name='password']").val()) {
				return _error('密码格式不正确或两次输入不一致', n);
			}
			_success(n);
			return true;
		}
	var _mobile = function(val, n) {

			if ($(n).attr('rel') == val || val == '') {
				_error(val, n);
				return false;
			}
			if (val.length != 11 || val.indexOf(1) > 0) {
				_error('手机号码格式不正确', n);
				return false;
			}
			if (_unique) {
				//_wait(n);
				$.post(uniqueUrl, {
					'type': 'mobile',
					'mobile': val
				}, function(_result) {
					if (!_result.status) {
						return _error(_result.info, n);
					} else {
						return _success(n);
					}
				}, 'json');
			} 
            else {
				_success(n);
				return true;
			}
		}
	var _smscode = function(val, n) {
			if ($(n).attr('rel') == val || val == '') {
				_error(val, n);
				return false;
			}
			if (val.length != 6) {
				_error('验证码格式不正确2', n);
				return false;
			}
			_wait(n);
			$.post(smscodeUrl, {
				'smscode': val
			}, function(_result) {
				if (!_result.status) {
					return _error(_result.info, n);
				} else {
					return _success(n,_result.info);
				}
			}, 'json');
		}
	var _bindsso = function(val,n){
		return true;
		}
	var _rigisteragree = function(val,n){
		return true;
		}
	var _error = function(val, n) {
			$(n).attr('valid', 0);
			$(n).parent().siblings('label.reg_input_r').removeClass('valid').addClass('error').html('<i class="ui-icon fail"></i>'+ val);
			return false;
		}
	var _success = function(n,val){
            val = val?val:'';
			$(n).parent().siblings('label.reg_input_r').removeClass('error').addClass('valid').html('<i class="ui-icon success">√</i>'+val);
			return false;
		}
	var _wait = function(n) {
			//var img = $('<img />');
			//img.attr('src', STATIC + '/box/ajaxloading_01.gif').css({'position':'absolute','top':'3px','left':'5px'});
			$(n).parent().siblings('label.reg_input_r').removeClass('error').addClass('valid').html('数据提交中...');
			return true;
		}
	var _clear = function(n) {
			$(n).parent().siblings('label.reg_input_r').html('');
			return true;
		}

    var _verify_code = function(val, n) {

        if ($(n).attr('rel') == val || val == '') {
            _error(val, n);
            return false;
        }
        if (val.length != 6) {
            _error('请输入六位验证码', n);
            return false;
        }
       return true;
        
    }

	var _invitecode = function(val,n){
		if(val){
			$.post(checkinvUrl, {
                'type':'invitecode',
				'invitecode': val
			}, function(_result) {
				if(!_result.status){
					return _error(_result.info, n);
				} else {
					return _success(n,_result.info);
				}
			}, 'json');
		}
	}

    $.fn.extend({
		constract: function(unique) {
			_unique = unique || false;
			$(this).find('input').each(function(i, n) {
				var _this = $(this);
				_this.focus(function() {
					_clear(n);
				}).blur(function() {
					if (_this.val() != '' && _this.attr('type')!="checkbox") {
						_this.attr('valid', '1');
						var fun = '_' + $(this).attr('name') + "($(this).val(),n)";
						eval(fun);
					}
				});
			});
		},
		submitfrom: function(ulobj) {
			url = ( _unique == true ) ? registerUrl : loginUrl;
			$(this).click(function() {
                if(_sending){
                    return false;
                }

                if(_unique == true){
                    if(!$("input[name='rigisteragree']:checked").val()){
                        $("input[name='rigisteragree']").parent().siblings('label.reg_input_r').removeClass('error').addClass('valid').html('请先同意注册条款！');
                        return false;
                    }
                }

				var _submit = true;
				var _data = {};
				ulobj.find('input.login-reg-input').each(function(i, n) {
					var _this = $(this);
					if (_this.attr('type')!='hidden' && parseInt(_this.attr('valid')) != 1) {
						if(_this.attr('allownull')==1 || _this.val()!=""){
							$(_data).attr($(this).attr('name'), $(this).val());
						}else{
                            _submit = false;
                            _error(_this.attr('rel'),n);
						}
					}
					$(_data).attr($(this).attr('name'), $(this).val());
				});
				if (!_submit) {
					return false;
				}
				jdbox.alert(2);
				_sending = true;
				var redirecturl = $("input[name='redirecturl']").val()?$("input[name='redirecturl']").val():'';
				if(redirecturl != '')$(_data).attr("redirecturl",redirecturl);
				$.post(url, _data, function(_result) {
					console.log(_result);
					_sending = false;
					if (!_result.status) {
						jdbox.alert(_result.status, _result.info);
					} else {

						if(_result.data.syn){
							var jsobj = $(_result.data.syn),mvl = document.createElement('script');
							mvl.type = 'text/javascript';
							mvl.async = true;
							mvl.src = jsobj.attr('src');
							var oHead = document.getElementsByTagName('HEAD').item(0);
							oHead.appendChild(mvl);
							jdbox.alert(1,_result.data.info);
                            location.href = _result.data.url; 
                        }

                        if(_result.info.url){
                            //jdbox.alert(1, _result.info.msg,"location.href ='"+ _result.info.url+"'");
                            location.href =  _result.info.url;
                        }
					}
				}, 'json');
			})
		}
	});
}(jQuery))