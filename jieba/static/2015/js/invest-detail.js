(function($) {
	var submitUrl = '/invest/tender.html',
		callbackUrl = '/invest/tenderCallback.html',
		gettimeUrl = '/index/getTimes.html?t=';
	$.fn.extend({
		showClock: function(timestamp, startTime) {
			if (timestamp <= 0) {
				return false;
			}
			var clock = {
				clockwp: null,
				startTime: null,
				nowMil: '',
				timeOffsetToServer: 0,
				now: function() {
					var _nowMil = this.nowMil || new Date().getTime();
					_nowMil = parseFloat(_nowMil) + this.timeOffsetToServer * 1000;
					return new Date(_nowMil);
				},
				updateNow: function(timeInMili) {
					this.nowMil = timeInMili;
					this.timeOffsetToServer = 0;
				},
				getTimeStamp: function() {
					var date = new Date();
					return date.getTime();
				},
				betweenTimes: function(time) {
					var nowTime = this.now();
					return (time.getTime() - nowTime.getTime());
				},
				synTimeFromServer: function() {
					/*
					var url = gettimeUrl + this.getTimeStamp();
					var _this = this;
					$.getJSON(url, function(response) {
						if (response.info != undefined) {
							_this.updateNow(response.info);
						}
					});
					*/
				},
				rightZeroStr: function(v) {
					if (v < 10) {
						return "<b>0</b><b>" + v + "</b>";
					}
					var b = parseInt(v / 10);
					return "<b>" + b + "</b><b>" + (v - b * 10) + "</b>";
				},
				timmerRun: function() {
					var _endTime = new Date(this.startTime);
					var timestamp = this.betweenTimes(_endTime);
					if (timestamp <= 0) {
						return false;
					} else if ((timestamp == 20 * 1000) || (this.timeOffsetToServer / (2 * 60) == 1)) {
						this.synTimeFromServer();
					}
					var _now = this.now();
					this.show_times(_now, _endTime);
				},
				show_times: function(fromDate, toDate) {
					var timeOffset = toDate.getTime() - fromDate.getTime();
					var dayOfMil = 24 * 60 * 60 * 1000;
					var hourOfMil = 60 * 60 * 1000;
					var minOfMil = 60 * 1000;
					var hourOffset = timeOffset % dayOfMil;
					var minuteOffset = hourOffset % hourOfMil;
					var seccondOffset = minuteOffset % minOfMil;
					var days = this.rightZeroStr(Math.floor(timeOffset / dayOfMil));
					var hours = this.rightZeroStr(Math.floor(hourOffset / hourOfMil));
					var minutes = this.rightZeroStr(Math.floor(minuteOffset / minOfMil));
					var secconds = this.rightZeroStr(Math.floor(seccondOffset / 1000));
					this.clockwp.find('span#ui-countdown-d').html(days);
					this.clockwp.find('span#ui-countdown-h').html(hours);
					this.clockwp.find('span#ui-countdown-m').html(minutes);
				}
			};
			clock.clockwp = $(this);
			clock.startTime = startTime;
			setInterval(function() {
				clock.timeOffsetToServer++;
				clock.timmerRun();
			}, 1000);
		},
		tenderLoan: function(options) {
			var _this = $(this),
				send = false;
			var calculator = {
				T: 0,
				//类型
				A: 0,
				//本金
				D: 0,
				//日
				M: 0,
				//月
				N: 0,
				//期次
				R: 0,
				//月利率
				RM: 0,
				//总利息
				AM: 0,
				//本息
				L: [],
				//分期信息
				//清除输出内容
				clear: function() {
					this.T = this.A = this.D = this.M = this.N = this.R = this.RM = this.AM = 0;
					this.L = [];
					options.ratemoneyObj.html('0');
				},
				//等额本息
				debx: function() {
					var C = 0,
						I = 0,
						D = 0;
					C = this.A * this.R * this.power(1 + this.R, this.N) / (this.power(1 + this.R, this.N) - 1);
					this.AM = C * this.N;
					for (var i = 0; i < this.N; i++) {
						D = i == 0 ? this.A : this.L[i - 1][3];
						I = D * this.R;
						this.RM += I;
						this.L.push([I, C - I, C, D - (C - I)]);
					}
				},
				//付息还本
				fxhb: function() {
					var I = this.A * this.R;
					for (var i = 0; i < this.N; i++) {
						this.RM += I;
						if (i < (this.N - 1)) {
							this.L.push([I, 0, I, this.A]);
						} else {
							this.L.push([I, this.A, this.A + I, 0]);
						}
					}
					this.AM = this.A + this.RM;
				},
				//本息到付
				bxdf: function() {
					/*var D = 0,
						F = 0;
					D = this.R * 12 / 360;
					this.RM = this.A * D * this.D;
					this.AM = this.RM + this.A;
					this.L.push([this.RM, this.A, this.AM, F]);*/
					var D = 0,
					F = 0;
					D = this.R * 12 / 360;
					this.RM = this.A * this.R * this.N;
					this.AM = this.RM + this.A;
					this.L.push([this.RM, this.A, this.AM, F]);
				},
				power: function(x, y) {
					var t = x;
					while (y-- > 1) {
						t *= x;
					}
					return t;
				},
				//显示输出
				show: function() {
					options.ratemoneyObj.html(this.format(this.RM.toFixed(2)));
				},
				format: function(s) {
					s = s.toString();
					s = s.replace(/^(\d*)$/, "$1.");
					s = (s + "00").replace(/(\d*\.\d\d)\d*/, "$1");
					s = s.replace(".", ",");
					var re = /(\d)(\d{3},)/;
					while (re.test(s)) {
						s = s.replace(re, "$1,$2");
					}
					s = s.replace(/,(\d\d)$/, ".$1");
					return s.replace(/^\./, "0.");
				},
				init: function(m) {
					this.clear();
					this.T = parseInt(loanJson.repayment);
					this.A = parseInt(m);
					this.R = parseFloat(loanJson.yearrate) / 100 / 12;
					this.N = parseInt(loanJson.loanmonth);
					this.D = parseInt(loanJson.loanday);

					if (this.T == 1) {
						this.debx();
					} else if (this.T == 2) {
						this.fxhb();
					} else if (this.T == 3) {
						this.bxdf();
					}
					this.show();
				}
			};
			if (!loanJson) {
				return jdbox.alert(0, '数据格式错误');
			}
			options.inputObj.keyup(function(e) {
				var event = e ? e : window.event;
				var key = event.keyCode ? event.keyCode : event.which;
				if(key == 8){
					//return false;
				}
				var val = parseInt($(this).val());
                var maxtendermoney = parseInt(loanJson.loanmoney - loanJson.tendermoney);
                if(parseInt(loanJson.maxtendermoney)){
                    maxtendermoney = maxtendermoney<parseInt(loanJson.maxtendermoney)?maxtendermoney:parseInt(loanJson.maxtendermoney);
                }
				if (isNaN(val)) {
					val = '';
					//val = loanJson.mintendermoney;
				} else if (val > maxtendermoney) {
					val = maxtendermoney;
				}
				$(this).val(val);
				calculator.init(val==''?0:val);
			});
			_this.click(function() {
				if (!memberJson.id) {
					return jdbox.alert(0, '请登录后进行投标',"location.href='/member/login'");
				}
                if(!$("input[name='agreement']:checked").val()){
                    return jdbox.alert(0, '请先同意借款协议');
                }

				if (isNaN(options.inputObj.val())) {
					return jdbox.alert(0, '请输入投资金额');
				}
				if (parseInt(memberJson.amount) < parseInt(options.inputObj.val())) {
                    return jdbox.alert(0, '账户余额不足',"location.href='/member/recharge'");
				}
                if(loanJson.category_admin==1){
                    if(loanJson.loanmoney!=parseInt(options.inputObj.val())){
                        return jdbox.alert(0, '投资金额有误');
                    }
                }
                /*if (parseInt(memberJson.amount) < parseInt(loanJson.mintendermoney)) {
					return jdbox.alert(0, '当前账户余额小于最小投资金额');
				}*/

				if (send) {
					return jdbox.alert(0, '订单提交中');
				}
				send = true;
				var data = {};
				$(data).attr('sn', loanJson.loansn);
				$(data).attr('money', options.inputObj.val());
				jdbox.alert(2,'数据提交中');
				$.post(submitUrl, data, function(result) {
					send = false;
					if (!result.status) {
						return jdbox.alert(0, result.info);
					} else {
						$(result.data.data).appendTo('body');
                        //location.href='/invest/payment';
						/*var note = '提交成功';
						if (options.successnote) note = options.successnote;
						return jdbox.alert(1, note ,'window.location.reload()');*/
					}
				}, 'json');
			});
		}
	})
}(jQuery))