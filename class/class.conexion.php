<?php
/*
 * CREATOR: VELEZOFT
 * DEVELOPER: ALDO ULISES CORNEJO VELEZ
 * DATE: 24/03/19
 * PROJECT: turno
 *
 * DESCRIPTION: MANEJO DE DATOS A LA BASE DE DATOS
 *
 */
class db{
    public $isConnected;
    private $datab;
    private $bd = "turno";
    private $host = "localhost";
    private $user = "root";
    private $pass = "root";
    private $options = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8');
    public function __construct(){
        $this->isConnected = true;
        try {
            $this->datab = new PDO("mysql:host=".$this->host.";dbname=".$this->bd.";charset=utf8", $this->user , $this->pass, $this->options);
            $this->datab->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->datab->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        }
        catch(PDOException $e) {
            $this->isConnected = false;
            throw new Exception($e->getMessage());
        }
    }



    public function beginTransactionDB(){
        $stmt=$this->datab->beginTransaction();
    }

    public function commitDB(){
    	$stmt=$this->datab->commit();
    }


    public function rollBackDB(){
    	$stmt=$this->datab->rollback();
    }

    public function Disconnect(){
        $this->datab = null;
        $this->isConnected = false;
    }
    public function getRow($query, $params=array()){
        try{
            $stmt = $this->datab->prepare($query);
            $stmt->execute($params);
            return $stmt->fetch();
        }catch(PDOException $e){
            throw new Exception($e->getMessage());
            return false;
        }
    }
    public function getRows($query, $params=array()){
        try{
            $stmt = $this->datab->prepare($query);
            $stmt->execute($params);
            return $stmt->fetchAll();
        }catch(PDOException $e){
            throw new Exception($e->getMessage());
            return false;
        }
    }
    public function insertRow($query, $params=array()){
        try{
            $stmt = $this->datab->prepare($query);
            $stmt->execute($params);
            return true;
        }catch(PDOException $e){
            throw new Exception($e->getMessage());
            return false;
        }
    }
    public function updateRow($query, $params){
        return $this->insertRow($query, $params);
    }
    public function deleteRow($query, $params){
        return $this->insertRow($query, $params);
    }


    public function lastIdDB(){
       //Se obtiene el último ID del último insert efectuado por la instancia solicitada
        return $stmt=$this->datab->lastInsertId();
    }



    public function securequery($params){

		$action = $params['action'];
        $table = $params['table'];
        $parameters = $params['parameters'];
        switch ($action){
            case 'insert':
                try{
                    $countParams = count($parameters);
                    $vararray = 0;
                    $i = 1;
                    $query = "INSERT INTO ".$table." (";
                    foreach ($parameters as $columna => $valor) {
                        $query .= $columna.",";
                        $vararray++;
                    }
					$query = trim($query, ',');
                    $query .= ") VALUES (".str_repeat("?,", $countParams);
					$query = trim($query, ',');
					$query .= ")";
					$stmt = $this->datab->prepare($query);
                    foreach ($parameters as $param => &$value) {
						if (is_int($value)){
							$stmt->bindParam($i, $value, PDO::PARAM_INT);
						}else{
							$stmt->bindParam($i, $value, PDO::PARAM_STR);
						}
						$i++;
					}
                    $stmt->execute();
                }catch (PDOException $e){
                    throw new Exception($e->getMessage());
                }
                break;
            case 'update':
				try{
                    $countParams = count($parameters);
                    $vararray = 0;
                    $i = 1;
					$idrow = null;
                    $query = "UPDATE ".$table." SET ";
                    foreach ($parameters as $columna => $valor) {
                        if($columna == 'id'){
							$idrow = $valor;
						}else{
							$query .= $columna." = ?, ";
                        	$vararray++;
						}
                    }

					$query = substr ($query, 0, -2);
					echo $query .= " WHERE ".$table."id = ?";
					$stmt = $this->datab->prepare($query);
                    foreach ($parameters as $param => &$value) {
						if($param != 'id'){
							if (is_int($value)){
								$stmt->bindParam($i, $value, PDO::PARAM_INT);
							}else{
								$stmt->bindParam($i, $value, PDO::PARAM_STR);
							}
							$i++;
						}
					}
					//ID valid only integer
					$stmt->bindParam($i++, $idrow, PDO::PARAM_INT);
                    $stmt->execute();
                }catch (PDOException $e){
                    throw new Exception($e->getMessage());
                }
                break;
            default:
                return 'Err:11';
        }

    }
}
//COMO USAR
/*
    Connecting to DataBase
    $database = new db();

    Getting row
    $getrow = $database->getRow("SELECT email, username FROM users WHERE username =?", array("yusaf"));

    Getting multiple rows
    $getrows = $database->getRows("SELECT id, username FROM users");

    Inserting a row
    $insertrow = $database ->insertRow("INSERT INTO users (username, email) VALUES (?, ?)", array("yusaf", "yusaf@email.com"));

    Updating existing row
    $updaterow = $database->updateRow("UPDATE users SET username = ?, email = ? WHERE id = ?", array("yusafk", "yusafk@email.com", "1"));

    Delete a row
    $deleterow = $database->deleteRow("DELETE FROM users WHERE id = ?", array("1"));
    disconnecting from database
    $database->Disconnect();

	SECURE FOR INSERT
	$securequery = array('action'=>'insert', 'table'=>'test', 'parameters'=>array('email'=>'sasl@hodfsdftmail.com', 'nombre'=>'sasl','edad'=> 49));
	SECURE For UPDATE
	ID necesario para la transaccion e indicado como id en el arreglo, nombre real en la base de datos=> nombretabla + id ej. clienteid
	$securequery = array('action'=>'update', 'table'=>'test', 'parameters'=>array('id=>1','email'=>'sasl@hodfsdftmail.com', 'nombre'=>'sasl','edad'=> 49));

    Checking connection
    if($database->isConnected){
    	echo "connected";
    }else{
    	echo "not connected";
    }

*/
