<?php 
    Class Skill1 implements Skill
    {
        private $attack,$hp;

        public function __construct($attack,$hp)
		{

            $this->attack=$attack;
            $this->hp=$hp;
           
        }

        public function gethp()
        {

            return $this->hp-$this->attack;

        }

       
        
        public function getmsg()
        {
            return '使用技能1';
        }

        public function getattack()
        {

            return $this->attack;
        }

    }

?>