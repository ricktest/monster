<?php 
    Class PlayerControl
    {

        private $playerhp;
        private $playerattack;
        private $playername;
        private $msg;
        private $attack;
        private $model;
        private $skillmsg;

        public function __construct($playerhp,$playerattack,$playername)
		{
            $this->playerhp=$playerhp;
            $this->playerattack=$playerattack;
            $this->playername=$playername;
        }
        
        public function attack(Skill $skill)
        {
            
            $hp=$skill->gethp();
            $this->attack=$skill->getattack();
            $this->skillmsg=$skill->getmsg();

            return $hp;
        }

        
        public function getAttributes(){

            $data['playerhp']=$this->playerhp;
            $data['playername']=$this->playername;
            $data['playerattack']=$this->playerattack;

            require_once './models/playersave.php';
            $datasave['ps_json']=json_encode($data);
            $playersave=new playersave();
            $playersave->set($datasave)->where(['ps_sg_id'=>$_SESSION['sg_id']])->Update();
            return $data;
            
        }

        public function getmsg($monstername,$record=FALSE,$sg_id=''){

            $this->msg='<span style="color:red;" >'.$this->playername.': '.$this->skillmsg.' '.$monstername.' 造成傷害 '.$this->attack.'</span>';

            if($record){

                $content['msg_sg_id']=$sg_id;
				$content['msg_content']=$this->msg;
                $this->model->Create($content);

            }

            return $this->msg;

        }

        public function getvictorymsg($monstername,$record=FALSE,$sg_id=''){

            $this->msg=$monstername.'已擊敗，玩家贏得勝利';
            if($record){
                $content['msg_sg_id']=$sg_id;
				$content['msg_content']=$this->msg;
                $this->model->Create($content);
            }
            return $this->msg;

        }

        public function setmodel($model){

            $this->model=$model;

        }

    }

?>