<?php 

	class user extends  Control{

		private $startgame;
		private $playermodel;
		private $monstermodel;
		private $msgmodel;

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
			
			$this->startgame=$this->Model('startgame');
			$this->playermodel=$this->Model('player');
			$this->monstermodel=$this->Model('monster');
			$this->msgmodel=$this->Model('msg');
		}
		function content(){
			
			if(isset($_POST['sg_id'])){
				$content=$this->msgmodel->where(['msg_sg_id'=>$_POST['sg_id']])->DESC('msg_id')->Selectdata();
				$_SESSION['sg_id']=$_POST['sg_id'];
				echo json_encode($content);
				return TRUE;

			}
		}
		function index(){
			$this->view('default');
		}

		public function startgame(){
			//setcookie( "temp", "", time()-3600);
			$this->singlemonster=$this->randommonster($this->monstermodel->SelectData());
			$this->player=$this->playermodel->SelectData();
			if(empty($_POST['sg_id'])){
				$data['sg_player_id']=$this->player[0]['py_id'];
				$data['sg_date']=date('Y-m-d');
				$this->startgame->Create($data);
				$msgdata['ms_id']=$this->singlemonster['ms_id'];
				$msgdata['monstername']=$this->singlemonster['ms_name'];
				$msgdata['monsterhp']=$this->singlemonster['ms_hp'];
				$msgdata['playerhp']=$this->player[0]['py_hp'];
				$msgdata['playername']=$this->player[0]['py_name'];
				$msgdata['sg_id']=$this->startgame->GetMaxId('sg_id');
				$_SESSION['sg_id']=$msgdata['sg_id'];
				$msgdata['msg']='遊戲開始';
				$msgdata['action']='1';
				echo json_encode($msgdata);
				return TRUE;
			}else{
				$msgdata['msg']='遊戲已開始';
				
				echo json_encode($msgdata);
				return TRUE;
			}
		}

		public function resetmonster(){
			$this->singlemonster=$this->randommonster($this->monstermodel->SelectData());
			$this->player=$this->playermodel->SelectData();
			if(!$this->singlemonster){
				$msgdata['action']='99';
				$msgdata['msg']='所有怪獸已擊敗，遊戲結束';
				echo json_encode($msgdata);
				return TRUE;
			}

			$msgdata['monsterid']=$this->singlemonster['ms_id'];
			$msgdata['monstername']=$this->singlemonster['ms_name'];
			$msgdata['monsterhp']=$this->singlemonster['ms_hp'];
			$msgdata['playerhp']=$this->player[0]['py_hp'];
			$msgdata['playername']=$this->player[0]['py_name'];
			$msgdata['msg']='遊戲開始';
			echo json_encode($msgdata);
			return TRUE;
		}

		public function playerattack(){
			
			$this->singlemonster=$this->monstermodel
										->where(['ms_id'=>$_POST['ms_id']])
										->SelectData();
			$this->player=$this->playermodel->SelectData();
			$HP=$_POST['ms_hp']-$this->player[0]['py_attack'];
			
			if($HP<=0){
				$msgdata['msg']=$this->singlemonster[0]['ms_name'].'已擊敗，玩家贏得勝利';
				$msgdata['monstername']=$this->singlemonster[0]['ms_name'];
				$msgdata['monsterhp']=$HP;
				$msgdata['playerhp']=$this->player[0]['py_hp'];
				$msgdata['playername']=$this->player[0]['py_name'];

				$content['msg_sg_id']=$_SESSION['sg_id'];
				$content['msg_content']=$this->player[0]['py_name'].': 攻擊 '.$this->singlemonster[0]['ms_name'].' 造成傷害 '.$this->player[0]['py_attack'];
				$this->msgmodel->Create($content);
				
				$content['msg_content']=$msgdata['msg'];
				$this->msgmodel->Create($content);
				echo json_encode($msgdata);
				return FALSE;
			}else{

				$msgdata['msg']=$this->player[0]['py_name'].': 攻擊 '.$this->singlemonster[0]['ms_name'].' 造成傷害 '.$this->player[0]['py_attack'];
				$msgdata['monsterhp']=$HP;
				$msgdata['monstername']=$this->singlemonster[0]['ms_name'];
				//$msgdata['playerhp']=$this->player[0]['py_hp'];
				//$msgdata['playername']=$this->player[0]['py_name'];

				$content['msg_sg_id']=$_SESSION['sg_id'];
				$content['msg_content']=$msgdata['msg'];
				$this->msgmodel->Create($content);
				echo json_encode($msgdata);

				return TRUE;
			}
		}

		function monsterattack(){
			$this->singlemonster=$this->monstermodel
										->where(['ms_id'=>$_POST['ms_id']])
										->SelectData();
			$this->player=$this->playermodel->SelectData();
			$HP=$_POST['py_hp']-$this->singlemonster[0]['ms_attack'];
			//print_r($_POST);
			/*$this->player=unserialize($_COOKIE["player"]);
			$this->singlemonster=unserialize($_COOKIE["singlemonster"]);
			$HP=$this->player[0]['HP']=$this->player[0]['HP']-$this->singlemonster[0]['attack'];
			setcookie( "player", serialize($this->player)); */
			
			if($HP<=0){

				$msgdata['msg']=$this->singlemonster[0]['ms_name'].':'.' 攻擊 '.$this->player[0]['py_name'].' 造成傷害 '.$this->singlemonster[0]['ms_attack'].'</br>你已死亡，遊戲結束';
				
				$msgdata['playerhp']=$HP;
				$content['msg_sg_id']=$_SESSION['sg_id'];
				$content['msg_content']=$msgdata['msg'];
				$this->msgmodel->Create($content);

				echo json_encode($msgdata);
				return FALSE;
			}else{
				$msgdata['msg']=$this->singlemonster[0]['ms_name'].':'.' 攻擊 '.$this->player[0]['py_name'].' 造成傷害 '.$this->singlemonster[0]['ms_attack'];
				$msgdata['playername']=$this->player[0]['py_name'];
				$msgdata['playerhp']=$HP;
				//$msgdata['monsterhp']=$this->singlemonster[0]['ms_hp'];
				//$msgdata['monstername']=$this->singlemonster[0]['ms_name'];

				$content['msg_sg_id']=$_SESSION['sg_id'];
				$content['msg_content']=$msgdata['msg'];
				$this->msgmodel->Create($content);

				echo json_encode($msgdata);
				return TRUE;
			}

		}

		function randommonster($monster){
			$rand=array_rand($monster,1);
			$mt=$monster[$rand];
			/*if(isset($_COOKIE["temp"])){
				$this->temp=unserialize($_COOKIE["temp"]);
			}
			$mt=[];
			while(count($this->temp)<10){
				$rand=array_rand($monster,1);
				
				if(!in_array($monster[$rand]['ms_name'], $this->temp)){
					$this->temp[] = $monster[$rand]['ms_name'];
					setcookie( "temp", serialize($this->temp)); 
					$mt=$monster[$rand];
					break;
				}
			}*/
			
			if(count($mt)>0){
				return $mt;
			}else{
				return FALSE;
			}
			
		}

		function reset(){
			$this->singlemonster=$this->randommonster($this->monstermodel->SelectData());
			$this->player=$this->playermodel->SelectData();
			$data['sg_player_id']=$this->player[0]['py_id'];
			$data['sg_date']=date('Y-m-d');
			$this->startgame->Create($data);
			$msgdata['monsterid']=$this->singlemonster['ms_id'];
			$msgdata['monstername']=$this->singlemonster['ms_name'];
			$msgdata['monsterhp']=$this->singlemonster['ms_hp'];
			$msgdata['playerhp']=$this->player[0]['py_hp'];
			$msgdata['playername']=$this->player[0]['py_name'];
			$msgdata['sg_id']=$this->startgame->GetMaxId('sg_id');
			$_SESSION['sg_id']=$msgdata['sg_id'];
			//setcookie( "temp", "", time()-3600);
			//$msgdata['msg']='重新開始';
			echo json_encode($msgdata);
			return;

		}
		
	}

?>