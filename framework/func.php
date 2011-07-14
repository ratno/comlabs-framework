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
        header("Location: " . url("index", "control_panel")); // ga boleh akses, lemparkan ke halaman control panel
      }
    } else {
      header("Location: " . url("index", "login")); // belum punya login, lemparkan ke halaman login
    }
  }
}

function angka($angka, $desimal=2) {
  if (is_numeric($angka)) {
    return number_format($angka, $desimal, ",", ".");
  } else {
    return $angka;
  }
}

function tabel($controller, $data, $kolom, $tampilkan_aksi=true) {
  $out = '<table border="1" cellspacing="0" cellpadding="5">';
  // bikin baris header
  $out .= "<tr>";
  foreach ($kolom as $header => $field) {
    $out .= "<th>" . $header . "</th>";
  }
  if($tampilkan_aksi){
    $out .= "<th>" . "Aksi" . "</th>";
  }
  $out .= "</tr>";

  // bikin isi tabel
  foreach ($data as $item) {
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
    if($tampilkan_aksi){
      $out .= "<td>";
      $out .= "[" . buat_link("Ubah","ubah", $controller, $item['id']) . "]";
      $out .= "[" . buat_link("Hapus","hapus", $controller, $item['id']) . "]";
      $out .= "</td>";
    }
    $out .= "</tr>";
  }

  $out .= "</table>";

  echo $out;
}

function buat_link($nama,$action, $controller, $id) {
  return '<a href="' . url($controller, $action, array("id" => $id)) . '">' . ucwords($nama) . '</a>';
}

function form_properties($controller, $aksi, $id, $file_upload=false) {
  $out = 'action="' . url($controller, $aksi, array("id" => $id)) . '" method="POST"';
  if ($file_upload) {
    $out .= ' enctype="multipart/form-data"';
  }
  echo $out;
}

function input($name, $data="", $type="text") {
  if (is_array($data) && isset($data[$name])) {
    $value = ' value="' . $data[$name] . '"';
  } else {
    if ($data) {
      $value = ' value="' . $data . '"';
    } else {
      $value = "";
    }
  }

  echo '<input type="' . $type . '" name="' . $name . '"' . $value . ' />';
}

function pilihan($name, $opsi, $data) {
  if (is_array($data) && isset($data[$name])) {
    $terpilih = $data[$name];
  } else {
    $terpilih = $data;
  }

  $out = '<select name="'.$name.'">';
  foreach ($opsi as $pilihan => $nama_pilihan) {
    $out .= '<option value="' . $pilihan . '"' . selected($terpilih, $pilihan) . '>' . ucwords($nama_pilihan) . '</option>';
  }
  $out .= "</select>";
  echo $out;
}

function opsi($data_dari_database,$nama_pilihan,$pilihan="id"){
  $out = array();
  foreach ($data_dari_database as $item){
    $out[$item[$pilihan]] = $item[$nama_pilihan];
  }
  return $out;
}