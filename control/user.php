<?php 

	class user extends  Control{
		
		private	$temp=[];

		private	$player =[
			'0'=>['name'=>'player','HP'=>'30','attack'=>'5','exp'=>'']
		];

		private $monster=[
			'0'=>['id'=>'0','name'=>'A','HP'=>'10','attack'=>'1'],
			'1'=>['id'=>'1','name'=>'B','HP'=>'15','attack'=>'3'],
			'2'=>['id'=>'2','name'=>'C','HP'=>'20','attack'=>'5'],
			'3'=>['id'=>'3','name'=>'D','HP'=>'25','attack'=>'7'],
			'4'=>['id'=>'4','name'=>'E','HP'=>'30','attack'=>'3'],
			'5'=>['id'=>'5','name'=>'F','HP'=>'35','attack'=>'4'],
			'6'=>['id'=>'6','name'=>'G','HP'=>'40','attack'=>'2'],
			'7'=>['id'=>'7','name'=>'H','HP'=>'45','attack'=>'3'],
			'8'=>['id'=>'8','name'=>'I','HP'=>'50','attack'=>'5'],
			'9'=>['id'=>'9','name'=>'J','HP'=>'55','attack'=>'4'],
		];

		private $singlemonster=[];

		public function __construct()
		{
			
		  
		}
		
		function index(){
			
			if(isset($_COOKIE["singlemonster"])){
				$this->singlemonster=unserialize($_COOKIE["singlemonster"]);
				$this->player=unserialize($_COOKIE["player"]);
				$data['player']=$this->player;
				$data['singlemonster']=$this->singlemonster;
				$this->view('default',$data);
				return;
			}
			
			//print_r(unserialize($_COOKIE["singlemonster"]));
			$this->view('default');
			
		}

		public function startgame(){

			if( (empty($_COOKIE["singlemonster"]) AND empty($_COOKIE["player"]))){

				$this->singlemonster[]=$this->randommonster($this->monster);
				setcookie( "singlemonster", serialize($this->singlemonster)); 
				setcookie( "player", serialize($this->player)); 
				//print_r($this->singlemonster[0]['name']);
				//echo '<p>'.$this->singlemonster[0]['name'].' 出現了!!!</p>';
				setcookie( "msg",'<p>'.$this->singlemonster[0]['name'].' 出現了!!!</p>'); 
				$msgdata['msg']='遊戲開始';
				//print_r($_COOKIE['msg']);
				//setcookie( "msg",$_COOKIE['msg'].'<p>'.$msgdata['msg'].'</p>'); 
				$msgdata['link']='./?c=user&m=index';
				echo json_encode($msgdata);
				return TRUE;
				//$data['player']=$this->player;
				//$data['singlemonster']=$this->singlemonster;

			}
		}

		public function resetmonster(){

			if(isset($_POST['resetmonster'])){

				$this->singlemonster[]=$this->randommonster($this->monster);
				
				if($this->singlemonster[0]){
					setcookie( "singlemonster", serialize($this->singlemonster)); 
					setcookie( "player", serialize($this->player)); 
					$msgdata['msg']=$this->singlemonster[0]['name'].' 出現了!!!</br>';
					setcookie( "msg",$_COOKIE['msg'].'<p>'.$msgdata['msg'].'</p>'); 
					$msgdata['monsterhp']='血量:'.$this->singlemonster[0]['HP'];
					$msgdata['monstername']='名字:'.$this->singlemonster[0]['name'];
					$msgdata['playerhp']='血量:'.$this->player[0]['HP'];
					$msgdata['playername']='名字:'.$this->player[0]['name'];
					echo json_encode($msgdata);
					return TRUE;
				}else{
					$msgdata['msg']='所有怪獸已擊敗遊戲結束';
					setcookie( "msg",$_COOKIE['msg'].'<p>'.$msgdata['msg'].'</p>'); 
					$msgdata['action']=3;
					echo json_encode($msgdata);
					return TRUE;
				}
				

			}

		}

		public function playerattack(){

		
			$this->singlemonster=unserialize($_COOKIE["singlemonster"]);
			$HP=$this->singlemonster[0]['HP']=$this->singlemonster[0]['HP']-$this->player[0]['attack'];
			setcookie( "singlemonster", serialize($this->singlemonster)); 

			
			if($HP<=0){
				$msgdata['msg']=$this->singlemonster[0]['name'].'已擊敗</br>玩家贏得勝利</br>';
				setcookie( "msg",$_COOKIE['msg'].'<p>'.$msgdata['msg'].'</p>'); 
				$msgdata['monsterhp']='血量:'.$HP;
				$msgdata['action']='2';
				echo json_encode($msgdata);
				return FALSE;
			}else{

				$msgdata['msg']=$this->player[0]['name'].': 攻擊 '.$this->singlemonster[0]['name'].' 造成傷害 '.$this->player[0]['attack'];
				setcookie( "msg",$_COOKIE['msg'].'<p>'.$msgdata['msg'].'</p>'); 
				$msgdata['monsterhp']='血量:'.$HP;
				$msgdata['action']='0';
				echo json_encode($msgdata);

				return TRUE;
			}
		}

		function monsterattack(){
			
			$this->player=unserialize($_COOKIE["player"]);
			$this->singlemonster=unserialize($_COOKIE["singlemonster"]);
			$HP=$this->player[0]['HP']=$this->player[0]['HP']-$this->singlemonster[0]['attack'];
			setcookie( "player", serialize($this->player)); 
			
			if($HP<=0){

				$msgdata['msg']=$this->singlemonster[0]['name'].':'.' 攻擊 '.$this->player[0]['name'].' 造成傷害 '.$this->singlemonster[0]['attack'].'</br>你已死亡，遊戲結束';
				setcookie( "msg",$_COOKIE['msg'].'<p>'.$msgdata['msg'].'</p>'); 
				$msgdata['playerhp']='血量:'.$HP;
				$msgdata['action']='4';
				echo json_encode($msgdata);
				return FALSE;
			}else{
				$msgdata['msg']=$this->singlemonster[0]['name'].':'.' 攻擊 '.$this->player[0]['name'].' 造成傷害 '.$this->singlemonster[0]['attack'];
				setcookie( "msg",$_COOKIE['msg'].'<p>'.$msgdata['msg'].'</p>'); 
				$msgdata['playerhp']='血量:'.$HP;
				$msgdata['action']='1';
				echo json_encode($msgdata);
				return TRUE;
			}

		}

		function randommonster($monster){

			if(isset($_COOKIE["temp"])){
				$this->temp=unserialize($_COOKIE["temp"]);
			}
			
			$mt=[];
			while(count($this->temp)<10){
				$rand=array_rand($monster,1);
				if(!in_array($monster[$rand]['name'], $this->temp)){
					$this->temp[] = $monster[$rand]['name'];
					setcookie( "temp", serialize($this->temp)); 
					$mt=$monster[$rand];
					break;
				}
			}
			if(count($mt)>0){
				return $mt;
			}else{
				//echo '所有怪獸已擊敗，遊戲結束';
				return FALSE;
			}
			
		}

		function reset(){
			setcookie( "singlemonster", "", time()-3600);
			setcookie( "player", "", time()-3600);
			setcookie( "temp", "", time()-3600);
			setcookie( "msg", "", time()-3600);
			$msgdata['msg']='重新開始';
			$msgdata['action']='5';
			echo json_encode($msgdata);
			return;
		}
		
	}

?>