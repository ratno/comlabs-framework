<?php

/**
 * @author Ratno Putro Sulistiyono, ratno@comlabs.itb.ac.id
 * Fungsi-fungsi untuk  mendukung framework sederhana yang digunakan untuk pelatihan di Comlabs USDI ITB
 */
function url($controller, $method=null, $var=null) {
  $out = BASEURL . SUBDIR . "/" . INDEX . "$controller";
  if ($method) {
    $out .= "/$method";
  }
  if (is_array($var)) {
    foreach ($var as $k => $c) {
      if ($k && $c) {
        $out .= "/$k:$c";
      }
    }
  } else {
    $out .= "/$var";
  }
  return $out;
}

function d($data, $die=false) { //debug
  echo "<pre>";
  echo "\n--debug start at " . date("d-m-Y h:i:s") . "--\n";
  print_r($data);
  echo "\n--end debug--\n";
  echo "</pre>";
  if ($die)
    die("-die-");
}

function selected($data, $pilihan) {
  if ($data == $pilihan)
    return " selected=selected";
  else
    return "";
}

function set_keamanan($data_user) {
  $_SESSION['identitas_login'] = '--B0lehMasukG4n--';
  $_SESSION['data_user'] = $data_user;
}

function cek_keamanan($roles) {
  if (in_array("public", $roles)) { // klo di roles dikasi public, ya langsung aja akses
    return true;
  } else { // klo ndak cek identitasnya
    // jika identitas_login sesuai dengan yang di set di set_keamanan berarti ok
    if ($_SESSION['identitas_login'] == '--B0lehMasukG4n--') {
      $user = $_SESSION['data_user'];
      if (in_array($user['role'], $roles)) {
        return $user; // user boleh akses, maka berikan info user
      } else {
        $out = "<script>";
        $out .= "alert('Maaf anda tidak memiliki privilege untuk mengakses halaman ini, anda akan dikembalikan ke halaman control panel.');";
        $out .= "location.href='" . url("index", "control_panel") . "';";
        $out .= "</script>";
        die($out);
//        header("Location: " . url("index", "control_panel")); // ga boleh akses, lemparkan ke halaman control panel
      }
    } else {
      header("Location: " . url("index", "login")); // belum punya login, lemparkan ke halaman login
    }
  }
}

function cek_role($role) {
  $user = $_SESSION['data_user'];
  if ($role == $user['role']) {
    return TRUE;
  } else {
    return FALSE;
  }
}

function angka($angka, $desimal=2) {
  if (is_numeric($angka)) {
    return number_format($angka, $desimal, ",", ".");
  } else {
    return $angka;
  }
}

function tabel($controller, $data, $kolom, $aksi=array(), $no_page=null, $jml_data_per_page=null, $total_data=null,$method=null, $aksi_header="Aksi", $blnReturn = false) {
  if (is_array($data) && count($data) > 0) {
    if(!is_empty($no_page)){
      $total_page = ceil($total_data/$jml_data_per_page);
      $paging = "";
      if ($no_page > 1)
        $paging .=  link_href(url($controller, $method, array("page" => $no_page-1)), "&lt;&lt; Prev");
      else
        $paging .=  "&lt;&lt; Prev";
      for($idx_no_page = 1; $idx_no_page <= $total_page; $idx_no_page++) {
        if ((($idx_no_page >= $no_page - 3) && ($idx_no_page <= $no_page + 3)) || ($idx_no_page == 1) || ($idx_no_page == $total_page)){
          if ($idx_no_page == $no_page)
            $paging .= " <b>[".$idx_no_page."]</b> ";
          else 
            $paging .=  " " . link_href(url($controller, $method, array("page" => $idx_no_page)), $idx_no_page) ." ";
        }
      }
      if ($no_page < $total_page) 
        $paging .=  link_href(url($controller, $method, array("page" => $no_page+1)), "Next &gt;&gt;");
      else
        $paging .= "Next &gt;&gt;";
    }
    
    $out = is_empty($no_page)?"":$paging;
    
    $out .= '<table border="0" cellpadding="5" cellspacing="0" class="grid">';
    // bikin baris header
    $out .= "<thead>";
    $out .= "<tr class='baris_judul'>";
    $out .= "<th>#</th>";
    foreach ($kolom as $header => $field) {
      $out .= "<th>" . $header . "</th>";
    }
    if (count($aksi) > 0) {
      $out .= "<th>" . $aksi_header . "</th>";
    }
    $out .= "</tr>";
    $out .= "</thead>";
    
    // bikin isi tabel
    // jika $data['id'] ada, maka datanya cuman satu
    $out .= "<tbody>";
    if (is_array($data)) {
      if (key_exists("id", $data)) {
        $data_process[0] = $data;
      } else {
        $data_process = $data;
      }
      if(is_empty($no_page)){
        $nomor = 1;
      } else {
        $nomor = (($no_page-1)*$jml_data_per_page)+1;
      }
      foreach ($data_process as $item) {
        $tr_class = ($nomor%2==0)?"baris_genap":"baris_ganjil";
        $out .= "<tr class='$tr_class'>";
        $out .= "<td style='text-align: right;'>".$nomor++."</td>";
        foreach ($kolom as $field) {
          $prop = "";
          if (preg_match('/^(angka)/', $field)) {
            $prop = 'style="text-align: right;"';
          }
          $out .= "<td $prop>";
          $out .= eval('return ' . $field . ';');
          $out .= "</td>";
        }
        if (count($aksi) > 0) {
          $out .= "<td>";
          foreach ($aksi as $method => $name) {
            $model_name = "model_".$controller;
            $out .= "[" . buat_link($name, $method, $controller, $item[$model_name::pk()]) . "]";
          }
          $out .= "</td>";
        }
        $out .= "</tr>";
      }
    }
    $out .= "</tbody>";
    $out .= "</table>";
    $out .= is_empty($no_page)?"":$paging;
  } else {
    $out = "<h3>Data Tidak Tersedia</h3>";
  }

  if($blnReturn){
    return $out;
  } else {
    echo $out;
  }
}

function link_href($link, $name="", $prefix="", $class="", $id="") {
  if ($link) {
    $name = ($name != "") ? $name : $link;
    $class = ($class != "") ? ' class="' . $class . '"' : "";
    $id = ($id != "") ? ' id="' . $id . '"' : "";
    return '<a' . $id . $class . ' href="' . $prefix . $link . '">' . $name . '</a>';
  } else {
    return "";
  }
}

function buat_link($nama, $action, $controller, $id) {
  return link_href(url($controller, $action, array("id" => $id)), ucwords($nama));
}

function link_tambah($nama, $teks="Tambah Data") {
  return '<a href="' . url($nama, "tambah") . '">' . $teks . '</a>';
}

function form_properties($controller, $aksi, $id=null, $file_upload=false, $blnReturn = false) {
  $out = 'action="' . url($controller, $aksi, array("id" => $id)) . '" method="POST"';
  if ($file_upload) {
    $out .= ' enctype="multipart/form-data"';
  }
  if($blnReturn){
    return $out;
  } else {
    echo $out;
  }
}

function input($name, $data="", $type="text", $file_location=FILES_URL,$blnReturn = false) {
  $blnFileAda = false;
  $file = "";
  if (is_array($data)) {
    if (key_exists($name, $data) && isset($data[$name])) {
      if($type == 'file'){
        $blnFileAda = true;
        $file = $data[$name];
      } elseif($type == 'tanggal' || $type == 'tgl'){
        $data[$name] = tanggal($data[$name]);
      }
      $value = ' value="' . $data[$name] . '"';
    } else {
      $value = "";
    }
  } else {
    if ($data) {
      if($type == 'file'){
        $blnFileAda = true;
        $file = $data;
      } elseif($type == 'tanggal' || $type == 'tgl'){
        $data = tanggal($data);
      }
      $value = ' value="' . $data . '"';
    } else {
      $value = "";
    }
  }

  $out = "";
  if($type== 'file' && $blnFileAda && $file){
    $out .= link_href($file_location.$file,$file) ."<br />";
    $id = "inputfile_".$name;
  } elseif($type=="tanggal" || $type=="tgl"){
    $id = "inputtgl_".$name;
    $name = $id;
    $GLOBALS['js'][] = JQUERY_UI;
    $GLOBALS['css'][] = JQUERY_UI_CSS;
    $GLOBALS['script'][] = '$("#'.$id.'").datepicker({ dateFormat:"dd-mm-yy", changeMonth: true, changeYear: true, yearRange:"-70:+1" });';
    $type = "text";
  } else {
    $id = "inputbox_".$name;
  }
  $out .= '<input type="' . $type . '" id="' . $id . '" name="' . $name . '"' . $value . ' />';
  if($blnReturn){
    return $out;
  } else {
    echo $out;
  }
}

function submit($value="kirim", $type="submit", $name="",$blnReturn = false) {
  if ($name != "") {
    $name_property = ' name="' . $name . '"';
  } else {
    $name_property = "";
  }

  $out = '<input type="' . $type . '" value="' . $value . '"' . $name . ' />';
  if($blnReturn){
    return $out;
  } else {
    echo $out;
  }
}

function pilihan($name, $opsi="", $data="", $blnReturn = false) {
  if (is_array($data) && isset($data[$name])) {
    $terpilih = $data[$name];
  } else {
    $terpilih = $data;
  }
  $out = '<select id="selectbox_' . $name . '"  name="' . $name . '">';
  $out .= buat_opsi($opsi,$terpilih, true);
  $out .= "</select>";
  if($blnReturn){
    return $out;
  } else {
    echo $out;
  }
}

/**
 * fungsi untuk membuat opsi yg akan digunakan oleh fungsi pilihan
 * 
 * @author Ratno Putro Sulistiyono
 * @param string $data_dari_database data yg diperoleh dari model/query
 * @param string $nama_pilihan nama kolom yg isinya akan dimunculkan sebagai opsi
 * @param string $pilihan biasanya id dari pilihan yg jika pilihan di-select maka data ini akan disimpan dalam tabel
 * @return array data opsi yg akan dilempar ke fungsi pilihan 
 * 
 * usage:
 * formatted options from array
 *  -> opsi($this->model_user->ambil_data(),array("nama","role","email","format"=>"%s [%s/%s]"));
 */
function opsi($data_dari_database="", $nama_pilihan="nama", $pilihan="id") {
  $out = array();
  if (is_array($data_dari_database) && count($data_dari_database) > 0) {
    // cek dulu apakah data dari db tsb cuman satu? dengan mengecek $nama_pilihan sebagai key
    if(is_array($nama_pilihan)){
        $cek_kolom = $nama_pilihan[0];
    } else {
        $cek_kolom = $nama_pilihan;
    }
    
    if(key_exists($cek_kolom, $data_dari_database)){
        // data tunggal, harus dimasukkan kedalam array lagi
        $temp = $data_dari_database;
        $data_dari_database = array();
        $data_dari_database[] = $temp;
        unset($temp);
    }
      
    $blnArrayNamaPilihan = false;
    if(is_array($nama_pilihan) && count($nama_pilihan) > 0){
      $blnArrayNamaPilihan = true;
      $format = "";
      $blnFormat = false;
      $delimiter = " ";

      if(key_exists("format", $nama_pilihan)){
        $blnFormat = true;
        $format = $nama_pilihan['format'];
        unset($nama_pilihan['format']);
      }

      if(key_exists("delimiter", $nama_pilihan)){
        $delimiter = $nama_pilihan['delimiter'];
        unset($nama_pilihan['delimiter']);
      }
    }
    
    foreach ($data_dari_database as $item) {
      if($blnArrayNamaPilihan){
        $arrData = array();
        foreach ($nama_pilihan as $kolom_nama_pilihan) {
          $arrData[$kolom_nama_pilihan] = $item[$kolom_nama_pilihan];
        }
        
        if($blnFormat) {
          $out[$item[$pilihan]] = vsprintf($format,$arrData);
        } else {
          $out[$item[$pilihan]] = implode($delimiter, $arrData);
        }
      } else {
        $out[$item[$pilihan]] = $item[$nama_pilihan];
      }
    }
  }
  return $out;
}

function buat_opsi($opsi, $data_terpilih="", $blnReturn=false){
  $blnReplicate = (key_exists('0', $opsi) && isset($opsi[0]))?true:false;
  $out = '<option value="null">- Pilih -</option>';
  if (is_array($opsi) && count($opsi) > 0) {
    foreach ($opsi as $pilihan => $nama_pilihan) {
      if($blnReplicate) $pilihan = $nama_pilihan;
      $out .= '<option value="' . $pilihan . '"' . selected($data_terpilih, $pilihan) . '>' . ucwords($nama_pilihan) . '</option>';
    }
  }
  
  if($blnReturn){
    return $out;
  } else {
    echo $out;
  }
}

function gambar($file, $path=IMAGES_URL, $width="", $height="") {
  if ($file != "") {
    $width = ($width != "") ? ' width="' . $width . '"' : "";
    $height = ($height != "") ? ' height="' . $height . '"' : "";
    return '<img' . $width . $height . ' src="' . $path . $file . '" />';
  } else {
    return "";
  }
}

function hapus_file($file, $path = FILES) {
  unlink($file, $path);
}

function tanggal($tgl,$delimiter = "-"){
  $tgl = str_replace(array("/","."), "-", $tgl);
  $array_tgl = explode("-", $tgl);
  $swap_tgl = array($array_tgl[2],$array_tgl[1],$array_tgl[0]);
  $out = implode($delimiter, $swap_tgl);
  return $out;
}

function is_empty($data) {
  $blnToReturn = false;
  if(empty($data) || $data=="" || is_null($data)){
    $blnToReturn = true;
  }
  
  return $blnToReturn;
}

function textarea($name,$data="", $class="simple",$blnReturn=false){
  if (is_array($data) && isset($data[$name])) {
    $isi = $data[$name];
  } else {
    $isi = $data;
  }
  
  if($class == 'simple' || $class == 'advanced'){
    $theme = $class;
    $class = 'tinymce';
    $GLOBALS['js'][] = 'tiny_mce/jquery.tinymce.js';
    $GLOBALS['script'][] = '
      $("textarea.tinymce").tinymce({
        script_url : js_url+"tiny_mce/tiny_mce.js",
        // General options
        theme : "'.$theme.'",
        plugins : "autolink,lists,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,advlist",

        // Theme options
        theme_advanced_buttons1 : "save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect",
        theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
        theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen",
        theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,pagebreak",
        theme_advanced_toolbar_location : "top",
        theme_advanced_toolbar_align : "left",
        theme_advanced_statusbar_location : "bottom",
        theme_advanced_resizing : true
      });';
  }
  $out = "<textarea id='inputbox_".$name."' name='".$name."' class='".$class."'>".$isi."</textarea>";
  if($blnReturn){
    return $out;
  } else {
    echo $out;
  }
}

function menu($menu, $blnReturn=false){
  $GLOBALS['css'][] = 'menu.css';
  $GLOBALS['js'][] = 'menu.js';
  $out ='<ul id="ldd_menu" class="ldd_menu">';
  foreach ($menu as $key => $value) {
    if(is_array($value) && count($value)>0){
      $out .=  "<li>";
      $out .=  "<span>$key</span>";
      $out .=  "<div class='ldd_submenu'>";
      $out .=  "<ul>";
      $foot = "";
      $blnFoot = false;
      foreach ($value as $subkey=>$subvalue){
        $link = BASEURL . SUBDIR . "/" .$subvalue;
        if(!$blnFoot && preg_match("/^(Tambah)/", $subkey)){
          $foot = "<a class='ldd_subfoot' href='$link'>+ $subkey</a>";
          $blnFoot = true;
        } else {
          $out .=  "<li><a href='$link'>$subkey</a></li>";
        }
      }
      $out .=  "</ul>";
      $out .=  $foot;
      $out .=  "</div>";
    } else {
      $link = BASEURL . SUBDIR . "/" .$value;
      $out .=  "<li><a href='$link'>$key</a></li>";
    }
  }
  $out .= '</ul>';
  if($blnReturn){
    return $out;
  } else {
    echo $out;
  }
}

function kolom($tbl,$kolom,$alias=null,$fungsi=null){
  if(is_empty($fungsi)) {
    $out = "$tbl.$kolom";
  } else {
    $out = "$fungsi($tbl.$kolom)";
  }
  if(!is_empty($alias)){
    $out .= " as $alias";
  }
  return $out;
}