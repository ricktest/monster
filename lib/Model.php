<?php 
    Class Model{

        private $db;
        protected $table;
        protected $allowedFields=array();
        protected $primaryKey;
        private  $sql_str='';
        function __construct(){
           
            $this->db=new database;
            
        }

        public function create($data){

            foreach($data as $k=>$v){
                $data[$k]=$this->db->replacedata($v);
            }
            
            $sql="INSERT INTO `".$this->table."` (`".implode('`,`', array_keys($data))."`) VALUES ('".implode("','", $data)."')";
            //file_put_contents(time().'.txt',$sql);
            $bool=$this->db->sqlquery($sql);
            return $bool;
        
        }

        public function leftjoin($data){
            $arr_data=array();
            foreach($data['ON'] as $k=>$v){
                $v=$this->db->replacedata($v);
                $arr_data[]=$k.'.'.$v;
            }
            $sql="Select * From `".$this->table."` Left JOIN ".$data['left']." ON ".implode('=',$arr_data);
            $obj=$this->db->sqlquery($sql);
            return $this->db->resultarray($obj);
        }
        
        public function where($data){
            
            $arr_data=array();
            if(count($data)>0){
                foreach($data as $k=>$v){
                    $v=$this->db->replacedata($v);
                    $arr_data[]=" AND `".$k."`='".$v."'";
                }
            }
            
            //$this->sql_str.=" where 1";
            $this->sql_str.=implode(' ',$arr_data);
            return $this;
        }

        public function desc($desc){
            $this->sql_str.=' ORDER BY `'.$desc.'` DESC';
            return $this;
        }

        public function limit($LIMIT){
            $this->sql_str.=' LIMIT '.$LIMIT;
            return $this;
        }

        public function delete($id,$debug=false){
            $id=$this->db->replacedata($id);
            $sql="DELETE FROM `".$this->table."` where " .$this->primaryKey."='".$id."' ".$this->sql_str;
            if($debug){
                echo $sql;
                file_put_contents('./sql.txt',$sql);
                exit;
            }
            return $this->db->sqlquery($sql);
        }

        public function set($data){
            $arr_data=array();
            foreach($data as $k=>$v){
                $v=$this->db->replacedata($v);
                $arr_data[]="`".$k."`='".$v."'";
            }
            $this->sql_str.=" SET ";
            $this->sql_str.=implode(',',$arr_data);
            $this->sql_str.=" where 1 ";
            return $this;
        }

        public function update($id='',$data=array(),$debug=false){

            foreach($data as $k=>$v){
                $data[$k]=$this->db->replacedata($v);
            }

            if(count($data)>0){
                $this->set($data);
            }
           
            $sql="UPDATE `".$this->table."`  ".$this->sql_str."";
            if($debug){
                echo $sql;
                exit;
            }     
            $this->sql_str='';
             return $this->db->sqlquery($sql);
        }

        public function selectData($data=array()){

            $sql='Select * From `'.$this->table.'` where 1 '.$this->sql_str;
            $obj=$this->db->sqlquery($sql);
            file_put_contents('./sql.txt',$sql);
            $this->sql_str='';
            return $this->db->resultarray($obj);

        }
       
    }

?>