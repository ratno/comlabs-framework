<?php
class index extends application {
    function index() {
        $data['judul'] = "Selamat Datang";
        $this->loadView('index/index',$data);
    }
}
?>