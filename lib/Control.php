<?php 
class Control{

    private $contents;
    private $extends;

    function Model($model){
        require_once './models/' . $model . '.php';
        return new $model();
    }

    public function section()
    {
        ob_start();
    }

    public function endSection()
    {
        $this->contents =ob_get_clean();

    }

    public function renderSection()
    {
        echo $this->contents;
    }

    public function extends($str){
        $this->extends=$str;
    }

    private function getextend(){
        return $this->extends;
    }

    public  function View($view , $data=array()){
       
        if(file_exists('./views/' . $view . '.php')){
            //$this->section();
            require_once './views/' . $view . '.php';
            //$this->endSection();
            unset($_SESSION['erreo']);
        } else {
            die('View does not exist');
        }

       // require_once $this->getextend();
        

    }
    public  function redirect($lik,$msg=''){
        $str='<script>';
        if($msg){
            $str.='alert("'.$msg.'");';
        }
        $str.='document.location.href="'.$lik.'";</script>';
        echo $str;
        exit;
    }
}
?>
