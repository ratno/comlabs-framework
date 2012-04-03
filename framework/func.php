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

function tabel($controller, $data, $kolom, $aksi=array(), $aksi_header="Aksi") {
  if (is_array($data) && count($data) > 0) {
    $out = '<table border="1" cellspacing="0" cellpadding="5">';
    // bikin baris header
    $out .= "<tr>";
    foreach ($kolom as $header => $field) {
      $out .= "<th>" . $header . "</th>";
    }
    if (count($aksi) > 0) {
      $out .= "<th>" . $aksi_header . "</th>";
    }
    $out .= "</tr>";

    // bikin isi tabel
    // jika $data['id'] ada, maka datanya cuman satu
    if (is_array($data)) {
      if (key_exists("id", $data)) {
        $data_process[0] = $data;
      } else {
        $data_process = $data;
      }

      foreach ($data_process as $item) {
        $out .= "<tr>";
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
            $out .= "[" . buat_link($name, $method, $controller, $item['id']) . "]";
          }
          $out .= "</td>";
        }
        $out .= "</tr>";
      }
    }


    $out .= "</table>";
  } else {
    $out = "<h3>Data Tidak Tersedia</h3>";
  }

  echo $out;
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

function form_properties($controller, $aksi, $id, $file_upload=false) {
  $out = 'action="' . url($controller, $aksi, array("id" => $id)) . '" method="POST"';
  if ($file_upload) {
    $out .= ' enctype="multipart/form-data"';
  }
  echo $out;
}

function input($name, $data="", $type="text", $file_location=FILES_URL) {
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
  } elseif($type=="tanggal" || $type=="tgl"){
    $name = "ztglz___" . $name;
  }
  $out .= '<input type="' . $type . '" id="inputbox_' . $name . '" name="' . $name . '"' . $value . ' />';
  echo $out;
}

function submit($value="kirim", $type="submit", $name="") {
  if ($name != "") {
    $name_property = ' name="' . $name . '"';
  } else {
    $name_property = "";
  }

  echo '<input type="' . $type . '" value="' . $value . '"' . $name . ' />';
}

function pilihan($name, $opsi, $data) {
  if (is_array($data) && isset($data[$name])) {
    $terpilih = $data[$name];
  } else {
    $terpilih = $data;
  }

  $out = '<select id="selectbox_' . $name . '"  name="' . $name . '">';
  $out .= '<option value="null">- Pilih -</option>';
  if (is_array($opsi) && count($opsi) > 0) {
    foreach ($opsi as $pilihan => $nama_pilihan) {
      $out .= '<option value="' . $pilihan . '"' . selected($terpilih, $pilihan) . '>' . ucwords($nama_pilihan) . '</option>';
    }
  }

  $out .= "</select>";
  echo $out;
}

/**
 * fungsi untuk membuat opsi yg akan digunakan oleh fungsi pilihan
 * 
 * @author Ratno Putro Sulistiyono
 * @param string $data_dari_database data yg diperoleh dari model/query
 * @param string $nama_pilihan nama kolom yg isinya akan dimunculkan sebagai opsi
 * @param string $pilihan biasanya id dari pilihan yg jika pilihan di-select maka data ini akan disimpan dalam tabel
 * @return array data opsi yg akan dilempar ke fungsi pilihan 
 */
function opsi($data_dari_database, $nama_pilihan="nama", $pilihan="id") {
  $out = array();
  if (is_array($data_dari_database) && count($data_dari_database) > 0) {
    foreach ($data_dari_database as $item) {
      $out[$item[$pilihan]] = $item[$nama_pilihan];
    }
  }
  return $out;
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