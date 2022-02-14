<?php

    Class startgame extends Model{

        protected $table='startgame_tab';
        protected $allowedFields=['sg_player_id','sg_date'];

        public function GetMaxId($data){

            $ID=$this->DESC($data)
            ->Limit(1)
            ->SelectData();
            return $ID[0][$data];
        }

    }

?>