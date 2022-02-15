<?php 

    Class ArmsControl
    {
        private $as_id;
        private $as_name;
        private $model;

        public function getarms($name){
            $data['as_name']='';
            if($this->randomarms()){
                $data['as_name']=$name;
                return $data;
            }
            return FALSE;
        }

        private function randomarms()
        {
            $a = array_fill(0,50, 1);
            $b = array_fill(50,100, 0);
            $d = mt_rand(0,99);
            $arr=array_merge( $a,$b);

            return $arr[$d]; 
        }

        public function setmodel($model){

            $this->model=$model;

        }

    }

?>