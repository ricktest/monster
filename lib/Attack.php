<?php 
    Class Attack implements Skill
    {
        private $attack,$hp;

        public function __construct($attack,$hp)
		{

            $this->attack=$attack;
            $this->hp=$hp;
           
        }

        public function gethp()
        {

            $aftermonsterhp=$this->hp-$this->attackrange();
            if($aftermonsterhp<=0){
                $aftermonsterhp=0;
            }
            return $aftermonsterhp;

        }

        private function attackrange()
        {

            $rangetake= range(1,$this->attack);
            $rand=array_rand($rangetake,1);
            $this->attack=$rangetake[$rand];
            return $this->attack;

        }
        
        public function getmsg()
        {
            return '普通攻擊';
        }

        public function getattack()
        {

            return $this->attack;
        }

    }

?>