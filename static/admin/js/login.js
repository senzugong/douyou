$(function(){
	$("#login-btn").click(function(){
		
		$("#login-form").submit();
    });
	$("#is_login").click(function(){
		window.location.href="/sign/vcard.html";
	});
	var demo=$("#login-form").Validform({
		tiptype:3,
		label:".label",
		showAllError:true,
		ajaxPost:true,
		
		callback:function(data){
			
			if(data.status=="1"){ 
				
				var source_url=$("#source_url").val();
				//location.href = source_url;
				if(source_url=="http://www.bangtoutiao.com/user/login.html"||source_url==""){
					location.href= "/";
				}else{
					location.href= source_url;
				}
				
			}else{
				
				$.Hidemsg(); 
				layer.msg(data.info);
				
			}

		}
	});
	demo.addRule([{
		ele:"#mobile",
		datatype:"m",
		nullmsg:"手机号不能为空",
		errormsg:"手机号格式错误"
	},
	{
		ele:"#user_pwd",
		datatype:"*4-20",
		nullmsg:"密码不能为空",
		errormsg:"密码格式错误"
	}]);
});