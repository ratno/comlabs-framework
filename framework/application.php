<?php

/**
 * @author Ratno Putro Sulistiyono, ratno@comlabs.itb.ac.id
 * Basis kelas untuk framework sederhana yang digunakan untuk pelatihan di Comlabs USDI ITB
 */
if (!defined('BASEURL'))
  die('Authorized Personnel Only!!!');

class application {

  var $uri;
  var $model;
  var $db;
  var $tabel;
  var $pk;

  function __construct($uri) {
    $this->uri = $uri;
  }
  
  function preLoad(){
    $this->css("bootstrap.css");
    $this->css("sticky.css");
    $this->css("bootstrap-responsive.css");
    $this->css("main.css");
    $this->css("jquery.dataTables.css");
    $this->js("bootstrap.js");
    $this->js("jquery.dataTables.js");
    $this->script('$(".table").dataTable();');
  }

  function db() {
    $this->db = mysqli_connect(SERVER, USERNAME, PASSWORD, DATABASE);
  }

  function query($sql, $single=false) {
    $hasil = mysqli_query($this->db, $sql);
    if ($hasil === true) {
      return mysqli_affected_rows($this->db);
    } elseif ($hasil === false) {
      return false;
    } else {
      $out = array();
      $rows = mysqli_num_rows($hasil);
      if ($rows > 1) {
        while ($data = mysqli_fetch_assoc($hasil)) {
          $out[] = $data;
        }
      } else {
        $out = mysqli_fetch_assoc($hasil);
      }

      if ($single) {
        return $out[0];
      } else {
        return $out;
      }
    }
  }

  function insert($data, $execute=true, $process_file=true, $tabel="") {

    $kolom = $isi = array();
    foreach ($data as $key => $value) {
      if(preg_match("/^(inputtgl_)/", $key)){
        $key = substr($key, 9);
        $value = tanggal($value);
      }
      $kolom[] = $key;
      $isi[] = $this->escape($value);
    }

    if ($process_file && isset($_FILES)) {
      foreach ($_FILES as $file_form => $file) {
        if (is_array($file) && $file['tmp_name'] != "" && !empty($file['tmp_name'])) {
          $nama_file = date('Y.m.d.h.i.s.') . $file['name'];
          move_uploaded_file($file['tmp_name'], FILES . $nama_file);
          $kolom[] = $file_form;
          $isi[] = $this->escape($nama_file);
        }
      }
    }

    if (!$tabel)
      $tabel = $this->tabel;

    $sql = "INSERT INTO " . $tabel;
    $sql .= " (" . implode(",", $kolom) . ")";
    $sql .= " VALUES (" . implode(",", $isi) . ")";
    if ($execute) {
      return mysqli_query($this->db, $sql);
    } else {
      return $sql;
    }
  }

  function update($data, $id) {
    $update = array();
    foreach ($data as $key => $value) {
      if(preg_match("/^(inputtgl_)/", $key)){
        $key = substr($key, 9);
        $value = tanggal($value);
      }
      $update[] = $key . "=" . $this->escape($value);
    }

    if (isset($_FILES)) {
      foreach ($_FILES as $file_form => $file) {
        if (is_array($file) && $file['tmp_name'] != "" && !empty($file['tmp_name'])) {
          $nama_file = date('Y.m.d.h.i.s.') . $file['name'];
          move_uploaded_file($file['tmp_name'], FILES . $nama_file);
          $update[] = $file_form . "=" . $this->escape($nama_file);
        }
      }
    }

    $sql = "UPDATE " . $this->tabel . " SET " . implode(",", $update) . " WHERE " . $this->pk . "=" . $this->escape($id);
//    die($sql);
    return mysqli_query($this->db, $sql);
  }

  function delete($id) {
    $sql = "DELETE FROM " . $this->tabel . " WHERE " . $this->pk . " = " . $this->escape($id);
    return mysqli_query($this->db, $sql);
  }

  function escape($val) {
    $val = trim($val);
    if ($val == "" || empty($val) || is_null($val) || $val == 'null') {
      return 'null';
    }
    return is_numeric($val) ? "'$val'" : "'" . mysqli_escape_string($this->db, $val) . "'";
  }

  function init(){
    $this->db();
    $sql = "SELECT * FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA='".DB_NAME."'";
    $result = mysqli_query($this->db,$sql);
    $create_user_flag = true;
    $create_page_flag = true;

    $template_controller = CONTROLLER."nama_tabel.php";
    $template_model = MODEL."model_nama_tabel.php";
    $template_daftar = VIEW."nama_tabel/daftar.php";
    $template_form = VIEW."nama_tabel/form.php";
    $template_detil = VIEW."nama_tabel/view.php";
    $template_cari = VIEW."nama_tabel/cari.php";

    while($item = mysqli_fetch_assoc($result)) {
      $table_name = $item['TABLE_NAME'];
      // cek file exists
      $controller = CONTROLLER . $table_name . ".php";
      $model = MODEL . "model_" . $table_name . ".php";
      $view_path = VIEW . $table_name;
      $view_daftar = $view_path . "/daftar.php";
      $view_form = $view_path . "/form.php";
      $view_detil = $view_path . "/view.php";
      $view_cari = $view_path . "/cari.php";

      $this->create_file($controller, $template_controller, array($table_name));
      $this->create_file($model, $template_model, array($table_name));

      if (!file_exists($view_path)) {
        mkdir($view_path);
        chmod($view_path, 0775);

        $fields_result = mysqli_query($this->db, "SELECT * FROM $table_name");
        $kolom_daftar = array();
        $kolom_form = array();
        $kolom_view = array();
        $kolom_cari = array();
        while ($field = mysqli_fetch_field($fields_result)) {
          $field_name = $field->name;
          $field_judul = ucwords(str_replace("_", " ", $field_name));
          if ($field_name == 'id') {
            // skip
          } else {
            $kolom_daftar[] = '"' . $field_judul . '"=>\'$item["' . $field_name . '"]\',';
            $kolom_form[] = '
    <tr>
      <td>' . $field_judul . '</td>
      <td><?php input("' . $field_name . '", $data) ?></td>
    </tr>';
            $kolom_view[] = '
  <tr>
    <td>' . $field_judul . '</td>
    <td><?php echo $data["' . $field_name . '"] ?></td>
  </tr>';
            $kolom_cari[] = '
    <tr>
			<td>' . $field_judul . '</td>
      <td><?php input("' . $field_name . '", $data) ?></td>
		</tr>';
          }
        }

        $this->create_file($view_daftar, $template_daftar, array($table_name, implode("\n", $kolom_daftar)),
          array("nama_tabel", "/*#kolom#*/"));
        $this->create_file($view_form, $template_form, array($table_name, implode("\n", $kolom_form)),
          array("nama_tabel", "/*#kolom#*/"));
        $this->create_file($view_detil, $template_detil, array($table_name, implode("\n", $kolom_view)),
          array("nama_tabel", "/*#kolom#*/"));
        $this->create_file($view_cari, $template_cari, array($table_name, implode("\n", $kolom_cari)),
          array("nama_tabel", "/*#kolom#*/"));
      }

      if ($table_name == "user") {
        $create_user_flag = false;
      }
      if ($table_name == "page") {
        $create_page_flag = false;
      }
    }

    if($create_user_flag && $create_page_flag) {
      mysqli_query($this->db, "DROP TABLE IF EXISTS user");
      mysqli_query($this->db,"
        CREATE TABLE user (
          id int primary key auto_increment,
          nama varchar(100),
          email varchar(100) unique,
          hp varchar(30),
          username varchar(20) unique,
          password varchar(128),
          role varchar(30),
          tgl_diangkat date
        )
      ");

      mysqli_query($this->db,'INSERT INTO user (nama, email, hp, username, password, role) VALUES ("Ratno Putro Sulistiyono [Admin]","ratno@comlabs.itb.ac.id","0817201101","ratno","ratno","admin")');
      mysqli_query($this->db,'INSERT INTO user (nama, email, username, password, role) VALUES ("Ratno [User]","ratno@knoqdown.com","user","user","user")');

      mysqli_query($this->db, "DROP TABLE IF EXISTS page");
      mysqli_query($this->db,"
        CREATE TABLE page (
          id int primary key auto_increment,
          kode varchar(100) unique,
          nama varchar(255),
          isi text,
          akses varchar(255)
        )
      ");
    }
  }

  function create_file($target,$template,$replace,$search=array("nama_tabel")){
    if(!file_exists($target)) {
      $f = fopen($target,"w+");
      fwrite($f,str_replace($search,$replace,file_get_contents($template)));
      fclose($f);
      chmod($target,0775);
    }
  }

  function controller($class) {
    $file = CONTROLLER . $this->uri['controller'] . ".php";

    if (!file_exists($file))
      header("location: " . WEB_URL);

    require_once($file);

    $controller = new $class($this->uri);
    $controller->preLoad();

    if (method_exists($controller, $this->uri['method'])) {
      $controller->{$this->uri['method']}($this->uri['var']);
    } else {
      $controller->index($this->uri['var']);
    }
  }

  var $layout = "layout";
  var $js;
  var $script;
  var $css;
  
  function js($js_file) {
    // ambil dari application->js
    if(is_array($js_file) && count($js_file)>0){
      foreach ($js_file as $item_js_file) {
        $this->js[$item_js_file] = $item_js_file;
      }
    } else {
      $this->js[$js_file] = $js_file;
    }
  }
  
  function script($script) {
    // ambil dari application->script
    $this->script[] = $script;
  }
  
  function css($css_file){
    // ambil dari application->css
    if(is_array($css_file) && count($css_file)>0){
      foreach ($css_file as $item_css_file) {
        $this->css[$item_css_file] = $item_css_file;
      }
    } else {
      $this->css[$css_file] = $css_file;
    }
  }
  
  function populateGlobals(){
    // ambil dari global variabel
    if(key_exists('js', $GLOBALS)){
      if(is_array($GLOBALS['js']) && count($GLOBALS['js'])>0){
        foreach ($GLOBALS['js'] as $item_js_file_global) {
          $this->js[$item_js_file_global] = $item_js_file_global;
        }
      } else {
        if(is_string($GLOBALS['js'])){
          $this->js[$GLOBALS['js']] = $GLOBALS['js'];
        }
      }
      unset($GLOBALS['js']);
    }
    // ambil dari global variabel
    if(key_exists('css', $GLOBALS)){
      if(is_array($GLOBALS['css']) && count($GLOBALS['css'])>0){
        foreach ($GLOBALS['css'] as $item_css_file_global) {
          $this->css[$item_css_file_global] = $item_css_file_global;
        }
      } else {
        if(is_string($GLOBALS['css'])){
          $this->css[$GLOBALS['css']] = $GLOBALS['css'];
        }
      }
      unset($GLOBALS['css']);
    }
    // ambil dari global variabel
    if(key_exists('script',$GLOBALS) && is_array($GLOBALS['script']) && count($GLOBALS['script'])>0){
      foreach ($GLOBALS['script'] as $item_script_global)
      $this->script[] = $item_script_global;
      unset($GLOBALS['script']);
    } 
  }
  
  /*
   * usage:
   *  -> $this->ikat("selectbox1", "selectbox2", url('user','ambil_opsi'));
   * pada class user, function ambil_opsi, jalankan buat_opsi($data_array);
   */
  function ikat($selectbox_from,$selectbox_to,$url_data){
    $this->js('opsi_berantai.js');
    $this->script("opsi_berantai('selectbox_$selectbox_from','selectbox_$selectbox_to','$url_data');");
  }
  
  function loadJQueryUI() {
    $this->js(JQUERY_UI);
    $this->css(JQUERY_UI_CSS);
  }
 
  function view($view="", $vars="", $echo=true, $return="all") {
    if (is_array($vars) && count($vars) > 0)
      extract($vars, EXTR_PREFIX_SAME, "wddx");
    
    // read the layout, to capture any globals
    ob_start();
    $readlayout = VIEW . $this->layout . '.php';
    if (file_exists($readlayout)) require($readlayout);
    ob_end_clean();
    
    ob_start();
    $template_file = VIEW . $view . '.php';
    if(file_exists($template_file)) {
      require($template_file);
    } else {
      echo $view;
    }
    $main_content = ob_get_clean();
    
    //populate globals variabels
    $this->populateGlobals();
    
    $html5shiv = "<!-- HTML5 shim, for IE6-8 support of HTML5 elements -->";
    $html5shiv .= "<!--[if lt IE 9]>";
    $html5shiv .= "<script type='text/javascript' src='".JS_URL."html5shiv.js'></script>";
    $html5shiv .= "<![endif]-->";
    $jquery = "<script type='text/javascript' src='".JS_URL.JQUERY."'></script>";
    
    $js = "";
    if(is_array($this->js) && count($this->js)>0){
      foreach ($this->js as $item_js){
        $js .= "<script type='text/javascript' src='".JS_URL."$item_js'></script>\n";
      }
    }
    
    $script = "";
    if(is_array($this->script) && count($this->script)>0){
      $script .= "<script type='text/javascript'>\n";
      $script .= '$(function(){'."\n";
      $script .= "\tweb_url = '".WEB_URL."';\n";
      $script .= "\tjs_url = '".JS_URL."';\n";
      $script .= "\tcss_url = '".CSS_URL."';\n";
      foreach ($this->script as $item_script){
        $script .= "\t".$item_script."\n";
      }
      $script .= '});'."\n";
      $script .= "</script>\n";
    }
    
    $css = "";
    if(is_array($this->css) && count($this->css)>0){
      foreach ($this->css as $item_css){
        $css .= "<link rel='stylesheet' type='text/css' href='".WEB_URL."css.php?f=$item_css' />\n";
      }
    }

    $arr_menu = Spyc::YAMLLoad(APPDIR."/menu.yml");
    if ($user = $_SESSION['data_user']) {
      $menu = $arr_menu[$user['role']];
    } else {
      $menu = $arr_menu['public'];
    }
    
    $appfooter = APPFOOTER;
    
    ob_start();
    $file = VIEW . $this->layout . '.php';
    if (file_exists($file)) {
      require($file);
    } else {
      echo $main_content;
    }
    $out = ob_get_clean();
    
    clear_msg();
    if ($echo) {
      echo $out;
    } else {
      if ($return == "main") {
        return $main_content;
      } else {
        return $out;
      }
    }
  }

  function model($model,$alias="") {
    require_once(MODEL . $model . '.php');
    if($alias) {
      $this->$alias = new $model;
    } else {
      $this->$model = new $model;
    }
  }

  function impor($controller, $tabel) {
    $data['judul'] = "Impor Data XLS";
    $data['controller'] = $controller;
    $data['tabel'] = $tabel;
    $data['aksi'] = "impor_preview";
    $this->view("general/impor", $data);
  }

  function impor_preview() {
    if (!key_exists('controller', $_POST)) {
      header("location: " . url('index'));
      die();
    }
    $xls = new Spreadsheet_Excel_Reader($_FILES['file_csv']['tmp_name']);

    $sheet_num = $_POST['sheet_num'] - 1;
    $header_row = $_POST['header_row'];
    $first_row = $_POST['first_row'];
    $last_row = ($_POST['last_row']) ? ($_POST['last_row']) : count($xls->rowInfo[$sheet_num]);

    $controller = $_POST['controller'];
    $tabel = $_POST['tabel'];

    $cells = $xls->sheets[$sheet_num]['cells'];

    if (isset($_POST['columns']) && !empty($_POST['columns']) && $_POST['columns'] != '') {
      $columns = explode(",", $_POST['columns']);
    } else {
      $columns = $cells[$header_row];
    }

    $col_num = count($cells[$header_row]);

    $inserts = array();

    // judul
    $tbl_header = "";
    $tbl_header .= "<tr>";
    foreach ($cells[$header_row] as $col) {
      $tbl_header .= "<td>" . $col . "</td>";
    }
    $tbl_header .= "</tr>";

    // content
    $tbl_content = "";
    for ($i = $first_row; $i <= $last_row; $i++) {
      $tbl_content .= "<tr>";
      $row = array();
      for ($c = 1; $c <= $col_num; $c++) {
        $tbl_content .= "<td>" . $cells[$i][$c] . "</td>";

        $row[$columns[$c]] = $cells[$i][$c];
      }

      $inserts[] = $this->insert($row, false, false, $tabel);
      $tbl_content .= "</tr>";
    }

    $tbl = "<table border='1' cellpadding='5' cellspacing='0'>";
    $tbl .= $tbl_header;
    $tbl .= $tbl_content;
    $tbl .= "</table>";

    $data['judul'] = "Preview Data Hasil Impor";
    $data['controller'] = $controller;
    $data['aksi'] = "impor_status";
    $data['data'] = implode("||", $inserts);
    $data['tabel'] = $tbl;
    $this->view("general/impor_preview", $data);
  }

  function impor_status() {
    if (!key_exists('insert', $_POST)) {
      header("location: " . url('index'));
      die();
    }
    $this->db();
    $insert = explode('||', $_POST['insert']);
    $data['judul'] = "Status Simpan Impor Data";
    $data['hasil'] = "";
    foreach ($insert as $sql) {
      $sql = str_replace("\\'", "'", $sql);
      $hasil = $this->query($sql);
      if ($hasil) {
        $status = "sukses";
      } else {
        $status = "gagal";
      }
      $data['hasil'] .= "[$status] $sql<br />\n";
    }
    $this->view('general/impor_status', $data);
  }

}