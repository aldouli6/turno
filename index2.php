<!-- /*$apikey = "j7NBFEWD3unnhWN5Dh8iJPkCV8";
$mercid = "763083";
$accid = "769668";
$urlpag = "https://checkout.payulatam.com/ppp-web-gateway-payu";
/*
//TEST______
$apikey = "4Vj8eK4rloUd272L48hsrarnUA";
$mercid = "508029";
$accid = "512324";
$urlpag = "https://sandbox.checkout.payulatam.com/ppp-web-gateway-payu";
*/ -->

<!-- <form method="post" action="<?php echo $urlpag; ?>">
  <input name="merchantId"    type="hidden"  value="<?php echo $mercid; ?>"   >
  <input name="accountId"     type="hidden"  value="<?php echo $accid; ?>" >
  <input name="description"   type="hidden"  value="Saldo Vencido"  >____
  <input name="referenceCode" type="hidden"  value="<?php echo $refcode;?>" >paquet-Iddemongo____
  <input name="amount"        type="hidden"  value="<?php echo $result->saldovencido;?>"  tabulador >
  <input name="tax"           type="hidden"  value="0"  >
  <input name="taxReturnBase" type="hidden"  value="0" >
  <input name="currency"      type="hidden"  value="MXN" >
  <input name="signature"     type="hidden"  value="<?php echo hash("SHA256",$apikey . "~" . $mercid . "~" . $refcode . "~" . $result->saldovencido . "~MXN"); ?>"  >
  <input name="buyerEmail"    type="hidden"  value="<?php echo $user->email;?>" >
  <input name="buyerFullName"    type="hidden"  value="<?php echo $user->name;?>" >
  <input name="shippingAddress"    type="hidden"  value="<?php echo $datos->domicilio;?>" >
  <input name="shippingCity"    type="hidden"  value="Queretaro" >
  <input name="shippingCountry"    type="hidden"  value="MEX" >
  <input name="telephone"    type="hidden"  value="<?php echo $datos->telefono;?>" vacio >
  <input name="responseUrl"    type="hidden"  value="" >
  <input name="algorithmSignature"    type="hidden"  value="SHA256" >
  <input name="confirmationUrl"    type="hidden"  value="https://www.quattrocom.mx/gpag.html?tmpl=cron" >
  //<?php if($result->saldovencido > 0):?>
  <input name="Submit"    class="button"    type="submit"  value="Pagar Saldo Vencido" >
  <?php endif;?>
</form> -->

<?php
$user = 'root';
$password = 'root';
$db = 'turno';
$host = 'localhost';
$port = 8889;

$link = mysqli_init();
$success = mysqli_real_connect(
   $link,
   $host,
   $user,
   $password,
   $db,
   $port
);

echo "conectado";
print_r($success);
print_r($link);
?>