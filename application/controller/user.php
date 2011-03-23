<?php
class user extends application {
	function __construct(){
		$this->loadModel("model_user");
	}
	
	function redirect(){
		header("location: ".url('user'));
	}
	
	function index(){
		$data['judul'] = "Halaman User";
		$data['data'] = $this->model_user->ambil_data();
		$this->loadView("user/index",$data);
	}
	
	function tambah() {
        $data['judul'] = "Tambah User";
		$data['aksi'] = "simpan_tambah"; 
        $this->loadView('user/form',$data);
    }

    function simpan_tambah() {
        $this->model_user->insert($_POST);
        $this->redirect();
    }

    function ubah($var) {
        $data['judul'] = "Ubah User";
		$data['aksi'] = "simpan_ubah";
        $data['data'] = $this->model_user->ambil_berdasar_id($var['id']);
        $this->loadView('user/form',$data);
    }

    function simpan_ubah($var) {
        $this->model_user->update($_POST,$var['id']);
        $this->redirect();
    }

	function hapus($var) {
        $this->model_user->delete($var['id']);
        $this->redirect();
    }
}