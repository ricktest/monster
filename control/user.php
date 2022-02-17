<?php 

	class user extends  Control{

		private $startgame;
		private $playermodel;
		private $monstermodel;
		private $msgmodel;
		private $register;
		private $arms;
		private $playersave;
		private $monstersave;

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
			$this->register=$this->Model('register');
			$this->arms=$this->Model('arms');

			$this->playersave=$this->Model('playersave');
			$this->monstersave=$this->Model('monstersave');

		}

		function content(){
			
			
			$content=$this->msgmodel->where(['msg_sg_id'=>$_SESSION['sg_id']])->DESC('msg_id')->Selectdata();
			
			echo json_encode($content);
			return TRUE;

			
		}
		
		public function login(){
			if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == "POST"){

				if(empty($_POST['rg_acount']) || empty($_POST['rg_pwd']) ){
					$msgdata['msg']='請輸入帳號、密碼';
					echo  json_encode($msgdata);
					return;
				}

				$users=$this->register->where(['rg_acount'=>$_POST['rg_acount'],'rg_pwd'=>$_POST['rg_pwd']])->SelectData();
				
				if(empty($users)){
					$msgdata['msg']='帳號、密碼錯誤';
					echo  json_encode($msgdata);
					return;
				}

				$_SESSION['user']=$users;
				$arrData=$this->startgame->where(['sg_status'=>'0','sg_rg_id'=>$_SESSION['user'][0]['rg_id']])
							->DESC('sg_id')
							->Limit(1)
							->SelectData();
				if(count($arrData)>0){
					$_SESSION['sg_id']=$arrData[0]['sg_id'];
				}else{
					$_SESSION['sg_id']='';
				}				
				$msgdata['link']='./?c=user&m=index';
				$msgdata['msg']='登入成功';
				echo  json_encode($msgdata);
				return;
			}else{
				
				$this->view('login');
			}
		}

		public function register(){
			
			if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == "POST"){
				
				if(empty($_POST['rg_name'])){
					$msgdata['msg']='請輸入名字';
					echo  json_encode($msgdata);
					return;
				}

				if($_POST['rg_acount']==''){
					$msgdata['msg']='請輸入帳號';
					echo  json_encode($msgdata);
					return;
				}

				if($_POST['rg_pwd']==''){
					$msgdata['msg']='請輸入密碼';
					echo  json_encode($msgdata);
					return;
				}

				if(strlen ($_POST['rg_name']) >=20 || strlen ($_POST['rg_acount'])>=20 || strlen ($_POST['rg_pwd'])>=20 ){
					$msgdata['msg']='名字、帳號、密碼不得超過20字';
					echo  json_encode($msgdata);
					return;
				}

				$users=$this->register->where(['rg_acount'=>$_POST['rg_acount']])->SelectData();
				if(count($users)>0){
					$msgdata['msg']='帳號已重複請重新輸入';
					echo  json_encode($msgdata);
					return;
				}

				$data['rg_name']=$_POST['rg_name'];
				$data['rg_acount']=$_POST['rg_acount'];
				$data['rg_pwd']=$_POST['rg_pwd'];
				$data['rg_date']=date('Y-m-d');
				$this->register->Create($data);
				$msgdata['msg']='註冊成功';
				$msgdata['link']='./?c=user&m=login';
				echo  json_encode($msgdata);
				return;

			}else{
				
				$this->view('register');
			}
			
				
			
		}

		function index(){
			//print_r($_SESSION);
			$monster=$this->monstermodel->SelectData();
			$this->view('default',$monster);
		}

		public function load()
		{

			$monstersave=$this->monstersave->where(['ms_sg_id'=>$_SESSION['sg_id']])->Selectdata();
			$playersave=$this->playersave->where(['ps_sg_id'=>$_SESSION['sg_id']])->Selectdata();
			
			$msgdata['monster']=json_decode($monstersave[0]['ms_json']);
			$msgdata['player']=json_decode($playersave[0]['ps_json']);
			$msgdata['action']=1;
			echo json_encode($msgdata);
			return ;
		}

		public function logout(){

			unset($_SESSION["user"]);
			unset($_SESSION["sg_id"]);
			$msgdata['link']='./?c=user&m=login';
			echo json_encode($msgdata);

			return TRUE;
			//header("Location: ./?c=user&m=login"); 
		}

		public function startgame()
		{
			
			$this->singlemonster=$this->randommonster($this->monstermodel->SelectData());
			$this->player=$this->playermodel->SelectData();
			$MonsterControl=new MonsterControl($this->singlemonster['ms_hp'],$this->singlemonster['ms_attack'],$this->singlemonster['ms_name']);
			
			$PlayerControl=new PlayerControl($this->player[0]['py_hp'],$this->player[0]['py_attack'],$this->player[0]['py_name']);

			if(empty($_SESSION['sg_id']))
			{

				$data['sg_player_id']=$this->player[0]['py_id'];
				$data['sg_date']=date('Y-m-d');
				$data['sg_rg_id']=$_SESSION['user'][0]['rg_id'];
				
				$this->startgame->Create($data);
				$msgdata['sg_id']=$this->startgame->GetMaxId('sg_id');

				$_SESSION['sg_id']=$msgdata['sg_id'];
				$MonsterControl->setid($this->singlemonster['ms_id']);
				$msgdata['monster']=$MonsterControl->getAttributes();
				$msgdata['player']=$PlayerControl->getAttributes();
				
				
				$monstersave['ms_sg_id']=$_SESSION['sg_id'];
				$monstersave['ms_json']=json_encode($msgdata['monster']);
				$this->monstersave->Create($monstersave);
				$playersave['ps_sg_id']=$_SESSION['sg_id'];
				$playersave['ps_json']=json_encode($msgdata['player']);
				$this->playersave->Create($playersave);

				
				$msgdata['msg']='遊戲開始';
				$msgdata['action']='1';
				echo json_encode($msgdata);

				return TRUE;
			}
			$msgdata['action']='0';
			$msgdata['msg']='遊戲已開始';
			echo json_encode($msgdata);
			return TRUE;
			
		}

		public function resetmonster(){
			$this->singlemonster=$this->randommonster($this->monstermodel->SelectData());
			$this->player=$this->playermodel->SelectData();
			$MonsterControl=new MonsterControl($this->singlemonster['ms_hp'],$this->singlemonster['ms_attack'],$this->singlemonster['ms_name']);
			
			$PlayerControl=new PlayerControl($this->player[0]['py_hp'],$this->player[0]['py_attack'],$this->player[0]['py_name']);
			$MonsterControl->setid($this->singlemonster['ms_id']);
			$msgdata['monster']=$MonsterControl->getAttributes();
			$msgdata['player']=$PlayerControl->getAttributes();
			echo json_encode($msgdata);
			return TRUE;
		}

		public function playerattack()
		{

			$this->singlemonster=$this->monstermodel
										->where(['ms_id'=>$_POST['ms_id']])
										->SelectData();

			$this->player=$this->playermodel->SelectData();
			$PlayerControl=new PlayerControl($this->player[0]['py_hp'],$this->player[0]['py_attack'],$this->player[0]['py_name']);
    		$PlayerControl->setmodel($this->msgmodel);
			
			$HP=$PlayerControl->attack(new $_POST['skill']($this->player[0]['py_attack'],$_POST['ms_hp']));
			$MonsterControl=new MonsterControl($HP,$this->singlemonster[0]['ms_attack'],$this->singlemonster[0]['ms_name']);
			$arms=$this->arms->where(['as_id'=>$this->singlemonster[0]['ms_as_id']])->Selectdata();
			
			if($HP<=0)
			{
				
				$PlayerControl->getmsg($this->singlemonster[0]['ms_name'],TRUE,$_SESSION['sg_id']);
				$msgdata['msg']=$PlayerControl->getvictorymsg($this->singlemonster[0]['ms_name'],TRUE,$_SESSION['sg_id']);
				$msgdata['monster']=$MonsterControl->getAttributes();
				
				$ArmsControl=new ArmsControl();
				$msgdata['arms']=$ArmsControl->getarms($arms[0]['as_name']);

				echo json_encode($msgdata);
				return FALSE;
			}
				
			$msgdata['monster']=$MonsterControl->getAttributes();
			$msgdata['msg']=$PlayerControl->getmsg($this->singlemonster[0]['ms_name'],TRUE,$_SESSION['sg_id']);

			
			echo json_encode($msgdata);
			return TRUE;
			
		}

		function monsterattack()
		{

			$this->singlemonster=$this->monstermodel
										->where(['ms_id'=>$_POST['ms_id']])
										->SelectData();
			$this->player=$this->playermodel->SelectData();
			
			$MonsterControl=new MonsterControl($this->singlemonster[0]['ms_hp'],$this->singlemonster[0]['ms_attack'],$this->singlemonster[0]['ms_name']);
			$MonsterControl->setmodel($this->msgmodel);
			$HP=$MonsterControl->attack($_POST['py_hp']);
			$PlayerControl=new PlayerControl($HP,$this->player[0]['py_attack'],$this->player[0]['py_name']);

			if($HP<=0)
			{
				
				$msgdata['msg']=$MonsterControl->getmsg($this->player[0]['py_name'],TRUE,$_SESSION['sg_id']);
				$MonsterControl->getvictorymsg($this->player[0]['py_name'],TRUE,$_SESSION['sg_id']);
				$msgdata['player']=$PlayerControl->getAttributes();
				echo json_encode($msgdata);
				return FALSE;
			}

			$msgdata['msg']=$MonsterControl->getmsg($this->player[0]['py_name'],TRUE,$_SESSION['sg_id']);
			$msgdata['player']=$PlayerControl->getAttributes();
			echo json_encode($msgdata);
			return TRUE;
			
		}

		public function endgame(){
			//unset($_SESSION["sg_id"]);
			$data['sg_status']=1;
			$this->startgame->set($data)->where(['sg_id'=>$_SESSION["sg_id"]])->Update();
			$_SESSION["sg_id"]='';
			$msgdata['msg']='遊戲結束';
			
			echo json_encode($msgdata);
			//echo 'sadsa';
		}

		function randommonster($monster){

			$rand=array_rand($monster,1);
			$mt=$monster[$rand];
		
			if(count($mt)>0){
				return $mt;
			}else{
				return FALSE;
			}
			
		}

		function reset(){

			$this->singlemonster=$this->randommonster($this->monstermodel->SelectData());
			$this->player=$this->playermodel->SelectData();
			$MonsterControl=new MonsterControl($this->singlemonster['ms_hp'],$this->singlemonster['ms_attack'],$this->singlemonster['ms_name']);
			$PlayerControl=new PlayerControl($this->player[0]['py_hp'],$this->player[0]['py_attack'],$this->player[0]['py_name']);
			$MonsterControl->setid($this->singlemonster['ms_id']);
			$msgdata['monster']=$MonsterControl->getAttributes();
			$msgdata['player']=$PlayerControl->getAttributes();
		
			echo json_encode($msgdata);
			return;

		}
		
	}

?>