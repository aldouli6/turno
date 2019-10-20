<?php
$handle = new Upload($_FILES["image"]);
if ($handle->uploaded) {
$handle->Process("uploads/");
if ($handle->processed) {
// usamos la funcion insert_img de la libreria db.php
// insert_img("","uploads/",$handle->file_dst_name);
} else {
echo 'Error: ' . $handle->error;
}
} else {
echo 'Error: ' . $handle->error;
}
unset($handle);
// $tipo='usuarios';
// $perfil='19';
//     $file_ext = 'png';
//     $target_path = $_SERVER['DOCUMENT_ROOT']."/turno/assets/img/profiles/";
//     $target_path .= $tipo.'_'.$perfil.'.'.$file_ext; 
//     if(move_uploaded_file($_FILES['file']['tmp_name'], $target_path)) {
//         $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]/turno/assets/img/profiles/".$tipo."_".$perfil.".png";
//         echo $actual_link;
//     } else{
//         echo 0;
//     }
?>