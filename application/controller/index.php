<?php
class index extends application {
	function __construct(){}
    function index() {
        $data['judul'] = "Selamat Datang";
        $this->loadView('index/index',$data);
    }
}