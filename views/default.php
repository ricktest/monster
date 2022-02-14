
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
	/* http://meyerweb.com/eric/tools/css/reset/ 
   v2.0 | 20110126
   License: none (public domain)
*/

html, body, div, span, applet, object, iframe,
h1, h2, h3, h4, h5, h6, p, blockquote, pre,
a, abbr, acronym, address, big, cite, code,
del, dfn, em, img, ins, kbd, q, s, samp,
small, strike, strong, sub, sup, tt, var,
b, u, i, center,
dl, dt, dd, ol, ul, li,
fieldset, form, label, legend,
table, caption, tbody, tfoot, thead, tr, th, td,
article, aside, canvas, details, embed, 
figure, figcaption, footer, header, hgroup, 
menu, nav, output, ruby, section, summary,
time, mark, audio, video {
	margin: 0;
	padding: 0;
	border: 0;
	font-size: 100%;
	font: inherit;
	vertical-align: baseline;
}
/* HTML5 display-role reset for older browsers */
article, aside, details, figcaption, figure, 
footer, header, hgroup, menu, nav, section {
	display: block;
}
body {
	line-height: 1;
}
ol, ul {
	list-style: none;
}
blockquote, q {
	quotes: none;
}
blockquote:before, blockquote:after,
q:before, q:after {
	content: '';
	content: none;
}
table {
	border-collapse: collapse;
	border-spacing: 0;
}
	</style>
	<link href="./bootstrap-5.1.3-dist/css/bootstrap.min.css" rel="stylesheet" >
    
	<script src="./js/jquery-3.6.0.js"></script>
	<script>
        $(function(){ 

			var content=function(response) {

				$.each($.parseJSON(response), function (item, value) {
					$('#content').append("</p>"+value.msg_content+"</p>");
				});
				
			};
			loaddata();
			function loaddata(){
				//alert(localStorage.getItem("sg_id"));
				if(localStorage.getItem("sg_id")!=null){
					$(".gamecontent").show();
					$('#monstername').text(localStorage.getItem("monstername"));
                	$('#monsterhp').text(localStorage.getItem("monsterhp"));
					$('#playername').text(localStorage.getItem("playername"));
					$('#playerhp').text(localStorage.getItem("playerhp"));
					doAjax('./?c=user&m=content',{ sg_id: localStorage.getItem("sg_id") },content );
					//localStorage.removeItem("sg_id");
					if(localStorage.getItem("playerhp")<=0){
						$("#attack").hide();
						$("#reset").show();
					}
				}
			
			}

		

			var startgame = function(response) {
				//alert(response)
				var obj = jQuery.parseJSON(response);
				setloacl(response);
				showtext(response);
				$(".gamecontent").show();
				if(obj.action==1){
					$('#content').text("");
				}
				
				alert(obj.msg);
				
			};

			var resetmonster= function(response) {
				
				var obj = jQuery.parseJSON(response);
				localStorage.setItem('ms_id',obj.monsterid );
				setloacl(response);
				showtext(response);
				
			}
			var reset= function(response) {

				var obj = jQuery.parseJSON(response);
				$("#attack").show();
				$("#reset").hide();
				setloacl(response);
				showtext(response);
				localStorage.setItem('ms_id',obj.monsterid );
				history.go(0);

			}
			var monsterattack=function(response){
				//alert(response);
				//alert(response);
				setloacl(response);
				showtext(response);
				var obj = jQuery.parseJSON(response);
				if(obj.playerhp<=0){
					$("#attack").hide();
					$("#reset").show();
					//alert(obj.msg);
				}
				history.go(0);
			}
			var attack = function(response) {
				
				var obj = jQuery.parseJSON(response);
				setloacl(response);
				showtext(response);
				
				if(obj.monsterhp<=0){
					var result = confirm("怪獸已擊敗，是否還要繼續打怪?");
					if (result) {
						doAjax('./?c=user&m=resetmonster',{ resetmonster: "1" },resetmonster );
					} else {
						$(".gamecontent").hide();
						localStorage.removeItem("sg_id");
						alert('遊戲結束');
					}
				}else{
					doAjax('./?c=user&m=monsterattack',{ py_hp: localStorage.getItem("playerhp"),ms_id:localStorage.getItem("ms_id") },monsterattack );
				}
				//history.go(0);
			}
			
			function setloacl(result){
				$.each($.parseJSON(result), function (item, value) {
					
					localStorage.setItem(item,value );
				});
			}

			function showtext(result){
				var obj = jQuery.parseJSON(result);
				$('#monstername').text(obj.monstername);
                $('#monsterhp').text(obj.monsterhp);
				$('#playername').text(obj.playername);
				$('#playerhp').text(obj.playerhp);
			}

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
        
    
            $( "#attack" ).click(function() {
				
                doAjax('./?c=user&m=playerattack',{ ms_hp: localStorage.getItem("monsterhp"),ms_id:localStorage.getItem("ms_id") },attack);
            });
			$( "#reset" ).click(function() {
				localStorage.removeItem("sg_id");
                doAjax('./?c=user&m=reset',$('form').serialize(),reset );
            });
			$( "#startgame" ).click(function() {
                doAjax('./?c=user&m=startgame',{ sg_id: localStorage.getItem("sg_id") },startgame );
            });
            
        }); 
    </script>
	<style>
		#startgame{
			margin-top:50px;
		}
		#reset{
			margin-left:43%;
			display:none;
		}
		.player{
			width:35%;
			border:1px black solid;
			height:400px;
			margin-top:50px;
			
			float:left;
		}
		.recorde{
			width:30%;
			border:1px black solid;
			height:400px;
			margin-top:50px;
			overflow: auto;
			float:left;
		}
		.monster{
			width:35%;
			border:1px black solid;
			height:400px;
			margin-top:50px;
			float:left;
			
		}
		.gamecontent h1{
			text-align:center;
		}
		.gamecontent p{
			text-align:center;
		}
		#attack{
			margin-left:43%;
		}
		.text{
			display: flex;
			justify-content: center; 
			align-items: center; 
		}
	</style>
</head>
<body>
<div class="container-lg">
	<button id="startgame" type="button" class="btn btn-primary">開始遊戲</button>
	
	<div class="gamecontent"  style="display:none;">
		<div class="player" >
			<h1>玩家</h1>
			<div class="text" >名字:<span id="playername" ></span></div>
			<div class="text" >血量:<span id="playerhp" ></span></div>
			<button style="" id="attack" type="button" class="btn btn-primary">攻擊</button>
			<button style="" id="reset" type="button" class="btn btn-primary">重新玩</button>
		</div>
		<div class="recorde ">
			<h1>紀錄</h1>
			<div id="content" style="text-align:center;">
			</div>
		</div>
		<div class="monster">
			<h1>怪物</h1>
			<div class="text" >名字:<span id="monstername" ><span></div>
			<div class="text" >血量:<span id="monsterhp" ></span></div>
		</div>
	</div>
	
  <div class="row">
	<div class="col-12">

		
		<div id="msgcontent">

		</div>
		
	</div>
  </div>
</div>
</body>
</html>


