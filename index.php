<?php 
  
    date_default_timezone_set("Asia/Taipei");
    spl_autoload_register(function($className){
        require_once 'lib/' . $className . '.php';

    });
    session_start();
    if(empty($_GET['c'])){
        header("location: ./?c=user&m=index");
    }
	$control='';
	if(isset($_GET['c']))
	{
		$control=$_GET['c'];
	}
    if(file_exists('./control/' . $control . '.php')){
        
        require_once './control/' . $control . '.php';
        $control=new $_GET['c'];
        $m='';
        if(isset($_GET['m'])){
            $m=$_GET['m'];
        }

        if(method_exists($control,$m)){
            $control->$m();
        }else{
            die('method does not exist');
        }

    }else{
        die('Control does not exist');
    }
   
   
    

?>