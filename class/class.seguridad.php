<?php
/*
  * CREATOR: VELEZOFT
 * DEVELOPER: ALDO ULISES CORNEJO VELEZ
 * DATE: 24/03/19
 * PROJECT: turno
 *
 * Seguridad
 * DESCRIPTION: Controla la seguridad en los métodos de autenticación
 *
 */
class SeguridadSistema{
        public static function validaRequerido($valor){
            if(trim($valor) == ''){
               return false;
            }else{
               return true;
            }
        }
        public static function validarEntero($valor){
            if(empty($valor)){
                return false;
            }else{
                if(is_numeric($valor)){
                    return true;
                }else{
                    return false;
                }
            }
        }
        public static function validarIDUsuario($valor){
            if(filter_var($valor,FILTER_VALIDATE_INT, array("options"=>array("min_range"=>3195,"max_range"=>50000)) )){
                return true;
            }else{
                return false;
            }
        }
        public static function validaEmail($valor){
            if(filter_var($valor, FILTER_VALIDATE_EMAIL) === FALSE){
               return false;
            }else{
               return true;
            }
        }
        public static function validaalfabetico($valor){
            $permitidos = '/^[A-Z üÜáéíóúÁÉÍÓÚñÑ]{1,50}$/i';
            if(empty($valor)){
                return false;
            }else{
                if (preg_match($permitidos,$valor)){
                    return true;
                }else{
                    return false;
                }
            }
        }
        public static function validaclave($valor){
            $busqueda=false;
            $permitidos=array("_","."," ","!","#","$","%","&","=","?","¡","¿","?");
            if(empty($valor)){
                return false;
            }else{
                $tamanio=strlen($valor);
                for($j=0;$j<$tamanio;$j++){
                    if(in_array($valor{$j},$permitidos) || ctype_alnum($valor{$j})){
                        $busqueda=false;
                    }else{
                        $busqueda=true;
                        break;
                    }
                }
            }
            return $busqueda;
        }
        public static function validacaracteresespeciales($valor){
            $busqueda=false;
            $permitidos=array("_","."," ","$","%","!","#","?","¡","¿","?","&","á","é","í","ó","ú","Á","É","Í","Ó","Ú","Ñ","ñ",":");
            if(empty($valor)){
                return false;
            }else{
                $tamanio=strlen($valor);
                for($j=0;$j<$tamanio;$j++){
                    $caracter = utf8_decode($valor{$j});
                    if(in_array( $caracter,$permitidos) || ctype_alnum($valor{$j})){
                        $busqueda=false;
                    }else{
                        $busqueda=true;
                        break;
                    }
                }
            }
            return $busqueda;
        }
        public static function validafecha($date) {
           list($dd,$mm,$yy)=explode("-",$date);
           if (is_numeric($yy) && is_numeric($mm) && is_numeric($dd))
           {
               if(checkdate($mm,$dd,$yy)){
                    return true;
                }else{
                    return false;
                }
           }
           return false;
        }
        public static function validafechau($date) {
           list($yy,$mm,$dd)=explode("-",$date);
           if (is_numeric($yy) && is_numeric($mm) && is_numeric($dd))
           {
               if(checkdate($mm,$dd,$yy)){
                    return true;
                }else{
                    return false;
                }
           }
           return false;
        }
        public static function validanegativo($num) {
            if(is_numeric($num) and $num<0){
                return false;
            }else{
                return true;
            }
        }
        public static function limpiaCadena( $str = "" ){
            return str_replace(array('--','/*','*/','-\'','99999999999999999','%25','%20','\''), "", $str );
        }





        public static function generarClavePoliza($cantidadClavePoliza,$cantidadElementos){
                //Método que genera la clave de la póliza



                $clavePolizaResultado=null; // Variable que muestra la clave como resultado del valor de los parámetros
                $cantidadCeros="";//Controla la cantidad de ceros dependiendo de la cantidad de elementos


                //Se elije la cantidad de carácteres que compondrán la clave de la póliza




                      $sizeCantidadElementos=strlen($cantidadElementos);//Dependiendo de la cantidad de elementos se verifica por cuantos carácteres está compusto ese valor ya que influye en la cantidad de ceros a mostrar al usuario;


                       //En caso de  que los parámetros sean números se generará la clave de acuerdo al valor que traigan.
                       //De la cantidad elejida 4 carácteres serán asignados para el año y mes y los demás serán de acuerdo a los valores de los parámetros que re reciben

                     if($sizeCantidadElementos<$cantidadClavePoliza){
                         // la cantidad de carácteres que conforman el total de elementos debe ser menor a la cantidad de carácteres elegidos para la póliza

                             for($i=1;$i<=(($cantidadClavePoliza-4)-$sizeCantidadElementos);$i++){
                                  $cantidadCeros.="0";
                             }



                           $clavePolizaResultado="1606".$cantidadCeros.$cantidadElementos;

                       }




              return $clavePolizaResultado;




            }



            public static function generarClaveSiniestro($cantidadClaveSiniestro,$cantidadElementosSiniestro){

                   $claveSiniestroResultado=null;//Variable que muestra como resultado el valor de los parámetros
                   $cantidadCerosSiniestro="";

                   $sizeCantidadElementosSiniestro=strlen($cantidadElementosSiniestro);


                  if($sizeCantidadElementosSiniestro<$cantidadClaveSiniestro){

                         for($i=1;$i<=($cantidadClaveSiniestro-4)-$sizeCantidadElementosSiniestro;$i++){
                            $cantidadCerosSiniestro.="0";
                         }

                         $claveSiniestroResultado="FLDR".$cantidadCerosSiniestro.$cantidadElementosSiniestro;


                  }

                return $claveSiniestroResultado;

            }


            public static function generarClaveAsistenciaVial($cantidadClaveAsistenciaVial,$cantidadElementosAsistenciaVial){

                  $claveAsistenciaVialResultado=null;
                  $cantidadCerosAsistenciaVial="";

                  $sizeCantidadElementosAsistenciaVial=sizeof($cantidadElementosAsistenciaVial);

                  if($sizeCantidadElementosAsistenciaVial<$cantidadClaveAsistenciaVial){

                      for($i=1;$i<=($cantidadClaveAsistenciaVial-4)-$sizeCantidadElementosAsistenciaVial;$i++){
                          $cantidadCerosAsistenciaVial.="0";

                      }
                      $claveAsistenciaVialResultado="ASVL".$cantidadCerosAsistenciaVial.$cantidadElementosAsistenciaVial;

                  }

               return $claveAsistenciaVialResultado;
            }






}//Fin clase seguridad sistema
?>
