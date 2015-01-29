<?php

class Player extends Eloquent {
    
    protected $table = 'players';

    public function isExpired() {
        return $this->expires >= time();
    }

}