<?php 
    Class PlayerControl
    {

        private $playerhp;
        private $playerattack;
        private $playername;
        private $msg;
        private $attack;
        private $model;

        public function __construct($playerhp,$playerattack,$playername)
		{
            $this->playerhp=$playerhp;
            $this->playerattack=$playerattack;
            $this->playername=$playername;
        }
        
        public function attack($monsterhp)
        {
            $aftermonsterhp=$monsterhp-$this->attackrange();
            if($aftermonsterhp<=0){
                $aftermonsterhp=0;
            }
            return $aftermonsterhp;
        }

        public function getattack(){
            return $this->attack;
        }

        private function attackrange()
        {

            $rangetake= range(1,$this->playerattack);
            $rand=array_rand($rangetake,1);
            $this->attack=$rangetake[$rand];
            return $this->attack;

        }

        public function getAttributes(){

            $data['playerhp']=$this->playerhp;
            $data['playername']=$this->playername;
            $data['playerattack']=$this->playerattack;
            
            return $data;
            
        }

        public function getmsg($monstername,$record=FALSE,$sg_id=''){

            $this->msg=$this->playername.': 攻擊 '.$monstername.' 造成傷害 '.$this->attack;
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