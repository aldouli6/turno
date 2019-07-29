<?php
ini_set('display_errors',1);
ini_set("sendmail_from", "admin@gardeapp.com");

if(function_exists('mail')) {
  echo 'mail() is enabled';
} else {
  echo 'mail() is not enabled';
}  

mail('caesar.arg@gmail.com','Test mail','The mail function is working!');
echo 'Mail sent!';

$varUbicacion = 'securezone';
include_once '../class/class.conexion.php';
require '../lib/PHPMailer/PHPMailerAutoload.php';
$mail = new PHPMailer;

$database = new db();

//$email = 'prueba2@debug.com';

$email = $_POST['remail'];


if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
     $error[] = 'Please enter a valid email address';
     echo $error;
}

$query0="SELECT UsuarioNickName FROM usuario WHERE UsuarioNickName=?";

$get0 = $database->getRows($query0, array($email));

//Cheks if the user is really registered on the system
if ($get0 != true) {
echo  'Sorry, we cannot find your account details please try another email address. ' . $email;
}
else
{
  //if (!$error) {

    //$query1="SELECT UsuarioNombreCompleto, UsuarioEmail, UsuarioTelefono FROM usuarioColono WHERE UsuarioEmail='".$email."'";

    $query1="SELECT UsuarioNombre, UsuarioNickName FROM usuario WHERE UsuarioNickName='".$email."'";


    $get1 = $database->getRows($query1, array());

    $a=$get1[0]['UsuarioNombre'];
    $b=$get1[0]['UsuarioNickName'];

    //$c=$get1[0][UsuarioTelefono];

    $password = substr(sha1(uniqid(rand(),1)),3,8);
    $pass = strtoupper(sha1($password));

    //var_dump($password);
    //$query2="UPDATE usuarioColono set UsuarioPassword=? WHERE UsuarioEmail=?";

    $query2="UPDATE usuario set UsuarioPassword=? WHERE UsuarioNickName=?";

    $get2 = $database->updateRow($query2, array($pass, $email));
    //var_dump($get2);
    
    /*$mail = new PHPMailer;
    $mail->isSMTP();
    $mail->SMTPDebug = 2;
    //Set the hostname of the mail server
    //$mail->Host = 'smtp.gmail.com';
    
    $mail->Port = 587;
    $mail->SMTPSecure = 'tls';
    $mail->SMTPAuth = true;

    //$mail->Host = gethostbyname('smtp.gmail.com');

    $mail->Host = 'tls://smtp.gmail.com:587';

    $mail->Username = "vzert.pruebas@gmail.com";
    $mail->Password = "tT908266";

    $mail->setFrom('pruebaspatito@gmail.com', 'Administrador de sistema');
    $mail->addAddress($email, 'Usuario BotonDePanico');
    $mail->Subject  = utf8_decode('Recuperación de contraseña');
    $mail->Body     = utf8_decode("Hola ".$a.",\r\n\ntú o alguien más solicitó detalles de tu cuenta.\r\nAquí se te envían los detalles solicitados, por favor guarde este mensaje ya que podría necesitarlo en un futuro.\r\nTu usuario es: ".$b." y tu nueva contraseña es: '$password' (Omitiendo las comillas).\r\nTu contraseña ha sido reseteada exitosamente.\r\n\nSaludos.");
    //var_dump($mail);
    if(!$mail->send()) {
      echo '<br><br>No se ha podido enviar el mensaje.<br><br>' . $mail->ErrorInfo;
      //var_dump($mail);
    } else {
      echo 'Un mensaje ha sido enviado exitosamente. Revise su correo electrónico, incluso la carpeta de spam.';
    } */

    $to      = $email;
    $subject = 'Recuperacion de contraseña';
    $message = "Hola ".$a.",\r\n\nTú o alguien más solicitó detalles de tu cuenta.\r\nAquí se te envían los detalles solicitados, por favor guarde este mensaje ya que podría necesitarlo en un futuro.\r\nTu usuario es: ".$b." y tu nueva contraseña es: '$password' (Omitiendo las comillas).\r\nTu contraseña ha sido reseteada exitosamente.\r\n\nSaludos.";
    $headers = 'From: admin@gardeapp.com' . "\r\n" .
        'Reply-To: admin@gardeapp.com' . "\r\n" .
        'Cc: admin@gardeapp.com' . "\r\n" .
        'X-Mailer: PHP/' . phpversion();

    $mail = mail($to, $subject, $message, $headers);

    if($mail){
      echo "Thank you for using our mail form";
    }else{
      echo "Mail sending failed."; 
      print_r(error_get_last());
    }

  //}
}
?>