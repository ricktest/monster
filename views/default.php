
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
        function doAjax(url,data,response =function (response) {
                    //alert(response);
                    var obj = jQuery.parseJSON(response);
					//alert(obj.msg);
                    if(obj.action==0){
						doAjax('./?c=user&m=monsterattack',$('form').serialize() );
                    }else if(obj.action==2){
						var result = confirm("怪獸已擊敗，是否還要繼續打怪?");
						if (result) {
							doAjax('./?c=user&m=resetmonster',{ resetmonster: "1" } );
						} else {
							alert('遊戲結束');
						}
					}else if(obj.action==3){
						$("#attack").hide();
						$("#reset").show();
						alert('所有怪獸已擊敗遊戲結束');

					}else if(obj.action==4){

						$("#attack").hide();
						$("#reset").show();
					}else if(obj.action==5){
						$( "#startgame" ).click();
					}
					$('#monstername').text(obj.monstername);
                    $('#monsterhp').text(obj.monsterhp);
					$('#playerhp').text(obj.playerhp);
					$('.recorde').append("<p>"+obj.msg+"</p>");
					
					if(obj.link!=undefined){
						
						document.location.href=obj.link;
					}
                }){
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
                doAjax('./?c=user&m=playerattack',$('form').serialize() );
            });
			$( "#reset" ).click(function() {
                doAjax('./?c=user&m=reset',$('form').serialize() );
            });
			$( "#startgame" ).click(function() {
                doAjax('./?c=user&m=startgame',$('form').serialize() );
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
	</style>
</head>
<body>
<div class="container-lg">
	<button id="startgame" type="button" class="btn btn-primary">開始遊戲</button>
	<?php if(isset($data['player'][0]['HP'])){?>
	<div class="gamecontent"  >
		<div class="player" >
			<h1>玩家</h1>
			<p>名字:<?=$data['player'][0]['name']?></p>
			<p id="playerhp" >血量:<?=$data['player'][0]['HP']?></p>
			<?php if(count(unserialize($_COOKIE["temp"]))!=10){?>
			<button style="<?=($data['player'][0]['HP']<=0)?'display:none;':'display:block;'?>" id="attack" type="button" class="btn btn-primary">攻擊</button>
			<button style="<?=($data['player'][0]['HP']<=0)?'display:block;':'display:none;'?>" id="reset" type="button" class="btn btn-primary">重新玩</button>
			<?php }else{?>
			<button style="display:block;" id="reset" type="button" class="btn btn-primary">重新玩</button>
			<?php }?>
		</div>
		<div class="recorde">
			<h1>紀錄</h1>
			
			<?php 
				if(isset($_COOKIE['msg'])){
					echo $_COOKIE['msg'];
				}
			?>
			
		</div>
		<div class="monster">
			<h1>怪物</h1>
			<p id="monstername" >名字:<?=$data['singlemonster'][0]['name']?><p>
			<p id="monsterhp" >血量:<?=$data['singlemonster'][0]['HP']?></p>
		</div>
	</div>
	<?php }?>
  <div class="row">
	<div class="col-12">

		
		<div id="msgcontent">

		</div>
		
	</div>
  </div>
</div>
</body>
</html>


