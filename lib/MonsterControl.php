<?php 
    Class MonsterControl
    {

        private $monsterhp;
        private $id;
        private $monsterattack;
        private $monstername;
        private $msg;
        private $attack;
        private $model;

        public function __construct($monsterhp,$monsterattack,$monstername)
		{
           
            $this->monsterhp=$monsterhp;
            $this->monsterattack=$monsterattack;
            $this->monstername=$monstername;
        }

        public function setid($id){
            $this->id=$id;
        }

        public function getid(){
            return $this->id;
        }

        public function attack($playerhp)
        {
            $playerhp=$playerhp-$this->attackrange();
            if($playerhp<=0){
                $playerhp=0;
            }
            return $playerhp;
        }

        public function getattack(){
            return $this->attack;
        }

        private function attackrange()
        {

            $rangetake= range(1,$this->monsterattack);
            $rand=array_rand($rangetake,1);
            $this->attack=$rangetake[$rand];
            return $this->attack;

        }

        public function getAttributes(){

            $data['monsterhp']=$this->monsterhp;
            $data['monstername']=$this->monstername;
            $data['monsterattack']=$this->monsterattack;
            $data['ms_id']=$this->getid();

            return $data;
            
        }

        public function getvictorymsg($playername,$record=FALSE,$sg_id=''){

            $this->msg=$playername.'，已死亡';
            if($record){
                $content['msg_sg_id']=$sg_id;
				$content['msg_content']=$this->msg;
                $this->model->Create($content);
            }
            return $this->msg;

        }

        public function getmsg($playername,$record=FALSE,$sg_id){

            $this->msg.=$this->monstername.': 攻擊 '.$playername.' 造成傷害 '.$this->attack;
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