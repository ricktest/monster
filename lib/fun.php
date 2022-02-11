<?php 
Class fun{
    private $erreo;

    public function required($data,$msg='',$boo=false){

       

        if(empty($_POST[$data])){
            if($boo){
                $this->erreo=$msg;
                return true;
            }
            $this->erreo[$data]=$msg;
        }
        return false;
        //print_r($this->erreo);
    }

    public function max($data,$max,$msg='',$boo=false){

       
        if(isset($_POST[$data]) && strlen($_POST[$data]) > $max ){
            
            if($boo){
                $this->erreo=$msg;
                return true;
            }
    
            $this->erreo[$data]=$msg ;
           
        }
        return false;
        //print_r($this->erreo);
        //exit;
    }

    public function geterreos(){
        return $this->erreo;
    }

    public function run($data,$first=false){
        $array=$this->setData($data);
        foreach( $array as $k=>$v){
            foreach($v['rules'] as $k2=>$v2){
                if(strpos($v2,"[")){
                    $test=strpos($v2,"[");
                    $test2=strpos($v2,"]");
                    $str=substr($v2, $test+1, -1); 
                    $meoth=substr($v2,0,$test);
                    $boo=$this->$meoth($k,$str,$data[$k]['errors'][$meoth],$first);
                    if($first && $boo){
                        return;
                    }
                }else{
                    $boo=$this->$v2($k,$data[$k]['errors'][$v2],$first);
                    if($first && $boo){
                        return;
                    }
                    //print_r($data[$k]['errors'][$v2]);
                }
                
            }
        }
        //print_r($array);
        //$this->$m('');

    }

    private function setData($data){

        $arr_data=array();

        foreach($data as $k=>$v){
            $arr_data[$k]['rules']=explode('|',$v['rules']);
            
        }
        return $arr_data;
    }
}
?>