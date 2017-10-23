(function($) {
	var imageNum = 100;
	if ($("#maxImageNum").val() != null) {
		imageNum = $("#maxImageNum").val();
	}
	var _static_ = static;//"http://static.dhgj.com:8081";
    var delParent;
	var fileType='';
	var delImg ;
    var is_delete = function(obj)
    {
    	var data = {};
		$(data).attr("type",obj.next(".up-img").attr("type"));
		$(data).attr("name",obj.next(".up-img").attr("up_name"));
		$(data).attr("oid",$("input[name='oid']").val());
		//console.log(data);
		$.post("/order/delete_img.html",data,function(F){
			//console.log(F);
            if(F.status==1)
            {   
				 //$(".works-mask").show();
				 delParent = obj.parent();
				 var numUp = delParent.siblings(".up-section").length;
				 if (numUp < imageNum + 1) 
				 {
					delParent.parent().find(".z_file").show();
				 }
				 
				 delParent.remove();

            }else{
            	jdbox.alert(0,F.info);
            }
		},'json')
    }
	$.fn.extend({
				takungaeImgup : function(opt, serverCallBack) {
					if (typeof opt != "object") {
						alert('参数错误!');
						return false;
					}
                    //alert(opt.display);
					var $fileInput = $(this);
					var $fileInputId = $fileInput.attr('id');

					var defaults = {
						fileType : [ "jpg", "png", "bmp", "jpeg","JPG","PNG","JPEG","BMP" ], // 上传文件的类型
						fileSize : 1024 * 1024 * 10, // 上传文件的大小 10M
						count : 0
					// 计数器
					};

					// 组装参数;

					if (opt.success) {
						var successCallBack = opt.success;
						delete opt.success;
					}

					if (opt.error) {
						var errorCallBack = opt.error;
						delete opt.error;
					}

					/* 点击图片的文本框 */
					$(this).change(function() {
						var reader = new FileReader(); // 新建一个FileReader();
						var idFile = $(this).attr("id");
						var file = document.getElementById(idFile);
						var imgContainer = $(this).parents(".z_photo"); // 存放图片的父亲元素
						var fileList = file.files; // 获取的图片文件
						var input = $(this).parent();// 文本框的父亲元素
						var imgArr = [];
						fileType = $(this).attr("name");						
						if((fileType=='user_pic')||(fileType=='certi_pic')||(fileType=='certiBack_pic'))
						{
							input.hide();
						}
						// 遍历得到的图片文件
						var numUp = imgContainer.find(".up-section").length;
						var totalNum = fileList.length; // 总的数量
						if (fileList.length > 10) {
							jdbox.alert(0,"一次上传图片数目不可以超过10个<br/>请重新选择"); // 一次选择上传超过5个
							// 或者是已经上传和这次上传的到的总数也不可以超过5个
						} else if (numUp < imageNum) {
							fileList = validateUp(fileList,defaults);
							for ( var i = 0; i < fileList.length; i++) {
								var imgUrl = window.URL.createObjectURL(fileList[i]);
								imgArr.push(imgUrl);
								var $section = $("<section class='up-section fl loading ml'>");
								imgContainer.children(".z_file").before($section);
								var $span = $("<span class='up-span'>");
								$span.appendTo($section);
								var $img0 = $("<img class='close-upimg'>");
								// .on("click",function(event){
								// 	            alert(1);
								// 	            eval(is_delete(event,$(this)));
								// 			    event.preventDefault();
								// 				event.stopPropagation();
								// 				$(".works-mask").show();
								// 				delParent = $(this).parent();
								// });
								$img0.attr("src",_static_+"/2015/uploadpicture/img/a7.png").appendTo($section);
								var $img = $("<img class='up-img up-opcity'>");
								$img.attr("src", imgArr[i]);
								$img.appendTo($section);
								var $p = $("<p class='img-name-p'>");
								$p.html(fileList[i].name).appendTo($section);
                                var $input = $("<input id='taglocation' name='taglocation' value='' type='hidden'>");
						        $input.appendTo($section);
						        var $input2 = $("<input id='tags' name='tags' value='' type='hidden'/>");
						        $input2.appendTo($section); 
								uploadImg(opt, fileList[i],$section,$img,input);
							}
							;
						}
						
						numUp = imgContainer.find(".up-section").length;
						// if (numUp >= imageNum) {
						// 	$(this).parent().hide();
						// };
						//input内容清空
						$(this).val("");
					});

					
					$(".z_photo").delegate(".close-upimg", "click", function(event) {
						//eval(is_delete(event,$(this)));
						 delImg = $(this);
						 event.preventDefault();
						 event.stopPropagation();
						 $(".works-mask").show();
						 //delParent = $(this).parent();
						 //delParent.remove();
					});

					$(".wsdel-ok").click(function(event) {
						event.preventDefault();
						event.stopPropagation();
						$(".works-mask").hide();
						eval(is_delete(delImg));
						// var numUp = delParent.siblings(".up-section").length;
						// if (numUp < imageNum + 1) {
						// 	delParent.parent().find(".z_file").show();
						// }
						
					});

					$(".wsdel-no").click(function() {
						$(".works-mask").hide();
					});

					// 验证文件的合法性
					function validateUp(files, defaults) {
						var arrFiles = [];// 替换的文件数组
						for ( var i = 0, file; file = files[i]; i++) {
							// 获取文件上传的后缀名
							var newStr = file.name.split("").reverse().join("");
							if (newStr.split(".")[0] != null) {
								var type = newStr.split(".")[0].split("")
										.reverse().join("");
								console.log(type + "===type===");
								if (jQuery.inArray(type, defaults.fileType) > -1) {
									// 类型符合，可以上传
									if (file.size >= defaults.fileSize) {
										alert('文件大小"' + file.name + '"超出10M限制！');
									} else {
										arrFiles.push(file);
									}
								} else {
									alert('您上传的"' + file.name + '"不符合上传类型');
								}
							} else {
								alert('您上传的"' + file.name + '"无法识别类型');
							}
						}
						return arrFiles;
					}
					;

					function uploadImg(opt, file, obj,$img,input) {
						$("#imguploadFinish").val(false);
						// 验证通过图片异步上传
						var url = opt.url;
						var data = new FormData();
						data.append("type", fileType);
						data.append("oid", opt.formData.oid);
						data.append("file", file);//alert(opt.formData.path);
					    //console.log(data);
						$.ajax({
							type : 'POST',
							url : url,
							data : data,
							processData : false,
							contentType : false,
							dataType : 'json',
							success : function(data) {
								//console.log(data);
								// obj.remove();
								// 上传成功
								if (data.status == 1) {
									$(".up-section").removeClass("loading");
									$(".up-img").removeClass("up-opcity");
									$("#imguploadFinish").val(true);
									var htmlStr = "<input type='text' style='display:none;' name='"
											+ opt.formData.name
											+ "' value='"
											+ data.url
											+ "'>";
									$img.attr("src",data.data.src);
									$img.attr("up_name",data.data.name);
									$img.attr("type",fileType);
									obj.append(htmlStr);
									if (successCallBack) {
										successCallBack(data);
							        }
							        //jdbox.alert(1,data.info);
								}
								if (data.status == 0) {
									obj.remove();
									$("#imguploadFinish").val(false);
									input.show();
									if(data.data.is_alert && data.data.is_alert==1)
									{
										jdbox.alert(0,data.data.info);
									}
									if (errorCallBack) {
										errorCallBack(data.url);
									}
								}
							},
							error : function(e) {
								obj.remove();
								var err = "上传失败，请联系管理员！";
								$("#imguploadFinish").val(false);
								if (errorCallBack) {
									errorCallBack(err);
								}
							}
						});
					}

				}
			});

})(jQuery);