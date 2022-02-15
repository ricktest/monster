
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
	<link href="./bootstrap-5.1.3-dist/css/bootstrap.min.css" rel="stylesheet" >
	<script src="./js/jquery-3.6.0.js"></script>
	<script>
        $(function(){ 

            function doAjax(url,data,response){
						$.ajax({
						type: "POST",
						url: url,
						data : data, 
						success:response,
						error: function (thrownError) {
						console.log(thrownError);
						}
				});
			}

            var register=function(response){
                //alert(response);
                var obj = jQuery.parseJSON(response);
                if(obj.link!=undefined){
                    document.location.href=obj.link;
                }
                alert(obj.msg);
            }

			$( ".btnregister" ).click(function() {
                doAjax('./?c=user&m=register',$('form').serialize(),register );
            });
            
        }); 
    </script>
</head>
<body>
<div class="container-lg">
	 <form action="" method="post" class="form" >
        <span>名字</span><input type="text" name="rg_name" value="">
        <span>帳號</span><input type="text" name="rg_acount" value="">
        <span>密碼</span><input type="text" name="rg_pwd" value="" >
        <input type="button" value="註冊" class="btnregister">
        <a href="./?c=user&m=login"><input type="button" value="登入"></a>
    </form>
</div>
</body>
</html>


