<?php

class VueGenerique{

    public function __construct(){
        ob_start();
    }

    public function getVueGenerique(){
        return ob_get_clean();
    }
}