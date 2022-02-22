<?php 
    interface Observable
    {
        // 新增/註冊觀察者
        public function attach(Observer $observer);
        // 刪除觀察者
        public function detach(Observer $observer);
        // 觸發通知
        public function notify();
    }

    interface Observer
    {
        // 接收到通知的處理方法
        public function update(Observable $observable);
    }

    Class PlayerControl implements Observable
    {

        private $playerhp;
        private $playerattack;
        private $playername;
        private $msg;
        private $attack;
        private $model;
        private $skillmsg;
        private $observers = array();

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
           

            return $data;
            
        }

        public function getSkill(){

            return $this->skillmsg;

        }

        public function getAttack(){

            return $this->attack;

        }

        public function getmsg(){

            return $this->msg;

        }


        public function setMsg(String $msg){

            $this->msg=$msg;

        }

        public function attach(Observer $observer)
        {
            $key = array_search($observer, $this->observers);
            if ($key === false) {
                $this->observers[] = $observer;
            }
        }

        // 移除觀察者
        public function detach(Observer $observer)
        {
            $key = array_search($observer, $this->observers);
            if ($key !== false) {
                unset($this->observers[$key]);
            }
        }

        // 遍歷呼叫觀察者的update()方法進行通知，不關心其具體實現方式
        public function notify()
        {
            foreach ($this->observers as $observer) {
                // 把本類物件傳給觀察者，以便觀察者獲取當前類物件的資訊
                $observer->update($this);
            }
        }

    }

    class playersLog implements Observer
    {
        private $model;

        public function __construct(Model $model)
        {
            $this->model=$model;
        }

        public function update(Observable $observable)
        {
            $data = $observable->getAttributes();
            if (!empty($data)) {
                $datasave['ps_json']=json_encode($data);
                $this->model->set($datasave)->where(['ps_sg_id'=>$_SESSION['sg_id']])->Update();

            } else {
                
            }
        }
    }

    class msgRecord implements Observer
    {
        private $model;
        private $name;
        private $type;

        public function __construct(Model $model)
        {
            $this->model=$model;
           
        }

        public function update(Observable $observable)
        {
            $data = $observable->getmsg();
            if (!empty($data)) {
                $content['msg_sg_id']=$_SESSION['sg_id'];
				$content['msg_content']=$data;
                $this->model->Create($content);
            } else {
                
            }
        }
    }

?>