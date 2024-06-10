<?php
require_once(__DIR__.'/../vendor/autoload.php');

use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;
use \Vendor\Whatsappweb\cargarVariables;
use \Vendor\Whatsappweb\DBConnection;

class LoginControl{
    private $connection;

    public function __construct(DBConnection $dbConnection) {
        $this->connection = $dbConnection->getConnection();
    }

    public function indexLogin($user, $pass) {
        // Verificar si el usuario existe en la base de datos
        $sql = "SELECT id, password, hashed_password FROM users WHERE user = ?";
        $stmt = $this->connection->prepare($sql);
        $stmt->bind_param("s", $user);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
    
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $stored_password = $row['password'];
            $stored_hashed_password = $row['hashed_password'];
    
            if ($stored_hashed_password === null && $stored_password === $pass) {
                // La contraseña no está hashada en la base de datos, pero coincide con la contraseña original
                // Actualizar la contraseña con su versión hashada
                $new_hashed_password = password_hash($pass, PASSWORD_DEFAULT);
                $sql_update = "UPDATE login_users SET hashed_password = ? WHERE id = ?";
                $stmt_update = $this->connection->prepare($sql_update);
                $stmt_update->bind_param("si", $new_hashed_password, $row['id']);
                $stmt_update->execute();
                $stmt_update->close();
                
                return array("success" => true, "message" => "Inicio de sesión exitoso (y contraseña actualizada)", "userId" => $row['id']);
            } elseif ($stored_hashed_password !== null && password_verify($pass, $stored_hashed_password)) {
                // La contraseña está hashada en la base de datos y coincide con la contraseña proporcionada
                return array("success" => true, "message" => "Inicio de sesión exitoso", "userId" => $row['id']);
            } else {
                // La contraseña no coincide
                return array("success" => false, "message" => "Contraseña incorrecta");
            }
        } else {
            // El usuario no se encontró en la base de datos
            return array("success" => false, "message" => "Usuario no encontrado");
        }
    }
    ///////////////////////////////////////////////////////////////////////////////////////////////
    public function logout() {
        // Eliminar la sesión y la cookie
        session_start();
        session_destroy();
        setcookie("auth", "", time() - 3600, "/", "", 1, 1);
    }

    public function VerifySession($jwt){
        cargarVariablesEnv();
        $secret_key = $_ENV['KEY'];
        // Verificar si hay una sesión de PHP iniciada y si hay una cookie con el JWT
        if(isset($_SESSION['userId']) && isset($_COOKIE['auth'])){
            try {
                // Decodificar el JWT proporcionado en la cookie
                $decoded = JWT::decode($jwt, new Key($secret_key, 'HS256'));      
                // Verificar si el JWT decodificado coincide con la sesión de PHP
                if($_SESSION['userId'] == $decoded->userId) {
                    return array("success" => true, "message" => "Sesión válida", "userId" => $decoded->userId);
                } else {
                    return array("success" => false, "message" => "Sesión inválida");
                }
            } catch (Exception $e) {
                return array("success" => false, "message" => "Sesión inválida");
            }
        }
    
        // Si no se encontró una sesión de PHP y un JWT válido simultáneamente, devolver un error
        return array("success" => false, "message" => "Sesión inválidaaaa");
    }

    /*public function VerifySessionCookie($jwt){
        cargarVariablesEnv();
        $secret_key = $_ENV['KEY'];
        // Verificar si hay una cookie con el JWT
        if(isset($_COOKIE['auth'])){
            try {
                // Decodificar el JWT proporcionado en la cookie
                $decoded = JWT::decode($jwt, new Key($secret_key, 'HS256'));
                return array("status" => "success", "message" => "Sesión válida", "user_id" => $decoded->user_id);
            } catch (Exception $e) {
                return array("status" => "error", "message" => "Sesión inválida");
            }
        } else {
            return array("status" => "error", "message" => "Sesión inválida");
        }
    }*/

    /*public function VerifyCurrentUserPassword($user_id, $password) {
        // Verificar si la contraseña proporcionada coincide con la contraseña almacenada en la base de datos
        $sql = "SELECT password, hashed_password FROM login_users WHERE id = ?";
        $stmt = $this->con->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
    
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $stored_password = $row['password'];
            $stored_hashed_password = $row['hashed_password'];
    
            if ($stored_hashed_password === null && $stored_password === $password) {
                // La contraseña no está hashada en la base de datos, pero coincide con la contraseña original
                // Actualizar la contraseña con su versión hashada
                $new_hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $sql_update = "UPDATE login_users SET hashed_password = ? WHERE id = ?";
                $stmt_update = $this->con->prepare($sql_update);
                $stmt_update->bind_param("si", $new_hashed_password, $user_id);
                $stmt_update->execute();
                $stmt_update->close();
                
                return array("status" => "success", "message" => "Contraseña actualizada");
            } elseif ($stored_hashed_password !== null && password_verify($password, $stored_hashed_password)) {
                // La contraseña está hashada en la base de datos y coincide con la contraseña proporcionada
                return array("status" => "success", "message" => "Contraseña válida");
            } else {
                // La contraseña no coincide
                return array("status" => "error", "message" => "Contraseña incorrecta");
            }
        } else {
            // El usuario no se encontró en la base de datos
            return array("status" => "error", "message" => "Usuario no encontrado");
        }
    }*/
    
}

class UsersControl{
    private $con;

    public function __construct($con){
        $this->con = $con;
    }

    public function GetCurrentUserData($userId) {
        // Obtener los datos del usuario actual
        $sql = "SELECT * FROM data_users WHERE id = ?";
        $stmt = $this->con->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
    
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return array("success" => true, "userName" => $row['nombre'], "email" => $row['email'], "phone" => $row['telefono']);
        } else {
            return array("success" => false, "message" => "Usuario no encontrado");
        }
    }
}

class ProductsControl{
    private $con;

    public function __construct($con){
        $this->con = $con;
    }

    public function getProducts() {
        //verificar si hay una sesion valida con el cookie  
        if(isset($_COOKIE['auth'])){
            cargarVariablesEnv();
            $secret_key = $_ENV['KEY'];
            $jwt = $_COOKIE['auth'];
            $decoded = JWT::decode($jwt, new Key($secret_key, 'HS256'));
            $user_id = $decoded->user_id;
            $control = new UsersControl($this->con);
            $user = $control -> GetCurrentUserData($user_id);

            if($user['status'] === 'success'){
                $sql = "SELECT id_facturapi, sku, nombre, price, unit_name FROM products";
                $result = $this->con->query($sql);

                if (!$result) {
                    return array("status" => "error", "message" => "Error en la consulta de la base de datos");
                } else {
                    $products = array();
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            // Añadir cada fila de la tabla como un array asociativo
                            $products[] = array(
                                "id_facturapi" => $row['id_facturapi'],
                                "sku" => $row['sku'],
                                "nombre" => $row['nombre'],
                                "price" => $row['price'],
                                "unit_name" => $row['unit_name'],                                
                            );
                        }
                    } else {
                        return array("status" => "error", "message" => "No se encontraron productos");
                    }
                    
                    // Cerrar la conexión a la base de datos
                    $this->con->close();
                    
                    // Devolver los datos en el formato esperado por DataTables
                    return $products;
                }
            }else{
                return array("status" => "error", "message" => "Sesión inválida");
            }
        }else{
            return array("status" => "error", "message" => "Sesión inválida");
        }
        
    }
    

    public function addProduct($data) {

        if($data['sku'] !== ' ' || $data['sku'] !== ''){
            $sku = $data['sku'];
        }else{
            $sku = ' ';
        }
        
        // Agregar un producto a la base de datos
        $sql = "INSERT INTO products (id_facturapi, sku, nombre, price, unit_name) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->con->prepare($sql);
        $stmt->bind_param("sssis", $data['id_facturapi'], $sku, $data['product_name'], $data['price'], $data['unit_name']);
        $stmt->execute();
        $rowsAffected = $stmt->affected_rows;
        $stmt->close();

        if ($rowsAffected > 0) {
            return array("status" => "success", "message" => "Producto agregado");
        } else {
            return array("status" => "error", "message" => "Error al agregar el producto");
        }

    }

    public function deleteProduct($id_facturapi) {
        // Eliminar un producto de la base de datos
        $sql = "DELETE FROM products WHERE id_facturapi = ?";
        $stmt = $this->con->prepare($sql);
        $stmt->bind_param("s", $id_facturapi);
        $stmt->execute();
        $rowsAffected = $stmt->affected_rows;
        $stmt->close();

        if ($rowsAffected > 0) {
            return array("status" => "success", "message" => "Producto eliminado de la BD");
        } else {
            return array("status" => "error", "message" => "Error al eliminar el producto");
        }
    }

    public function GetPriceProduct($id_facturapi){
        //verificar si hay una sesion valida con la funcion VerifySessionCookie 
        $control = new LoginControl($this->con);
        $result = $control -> VerifySessionCookie($_COOKIE['auth']);
        
        if($result['status'] === 'success'){

            $factur_api = new FacturapiServices();
            $result_facturapi = $factur_api -> getProduct($id_facturapi);

            if(isset($result_facturapi->data->id)){
                $tax_include = $result_facturapi->data->tax_included;
                $taxability = $result_facturapi->data->taxability;

                $sql = "SELECT nombre, price FROM products WHERE id_facturapi = ?";
                $stmt = $this->con->prepare($sql);
                $stmt->bind_param("s", $id_facturapi);
                $stmt->execute();
                $result = $stmt->get_result();
                $stmt->close();
            
                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    return array("status" => "success", "price" => $row['price'], "product_name" => $row['nombre'], "tax_include" => $tax_include, "taxability" => $taxability);
                } else {
                    return array("status" => "error", "message" => "Producto no encontrado");
                }
            }else{
                return array("status" => "error", "message" => "Producto no encontrado en Facturapi");
            }

            
        }else{
            return $result;
        }
        
    }

    public function GetProduct($id_facturapi){
        //verificar si hay una sesion valida con la funcion VerifySessionCookie 
        $control = new LoginControl($this->con);
        $result = $control -> VerifySessionCookie($_COOKIE['auth']);
        
        if($result['status'] === 'success'){
            $sql = "SELECT id FROM products WHERE id_facturapi = ?";
            $stmt = $this->con->prepare($sql);
            $stmt->bind_param("s", $id_facturapi);
            $stmt->execute();
            $result = $stmt->get_result();
            $stmt->close();
        
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                return array("status" => "success", "id_db" => $row['id']);
            } else {
                return array("status" => "error", "message" => "Producto no encontrado");
            }
        }else{
            return $result;
        }
        
    }

    public function getDataEditProduct($id_facturapi){
        //verificar si hay una sesion valida con la funcion VerifySessionCookie 
        $control = new LoginControl($this->con);
        $result = $control -> VerifySessionCookie($_COOKIE['auth']);
        
        if($result['status'] === 'success'){
            $sql = "SELECT id_facturapi, sku, nombre, price FROM products WHERE id_facturapi = ?";
            $stmt = $this->con->prepare($sql);
            $stmt->bind_param("s", $id_facturapi);
            $stmt->execute();
            $result = $stmt->get_result();
            $stmt->close();
        
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                return array("status" => "success", "id_facturapi" => $row['id_facturapi'], "sku" => $row['sku'], "nombre" => $row['nombre'], "price" => $row['price']);
            } else {
                return array("status" => "error", "message" => "Producto no encontrado");
            }
        }else{
            return $result;
        }
        
    }

    public function editProduct($data){
        //verificar si hay una sesion valida con la funcion VerifySessionCookie 
        $control = new LoginControl($this->con);
        $result = $control -> VerifySessionCookie($_COOKIE['auth']);
        
        if($result['status'] === 'success'){
            
            // Editar un producto en la base de datos
            $sql = "UPDATE products SET sku = ?, nombre = ?, price = ? WHERE id_facturapi = ?";
            $stmt = $this->con->prepare($sql);
            $stmt->bind_param("ssis", $data['sku_edit'], $data['product_name_edit'], $data['product_price_edit'], $data['id_facturapi']);
            $stmt->execute();
            $rowsAffected = $stmt->affected_rows;
            $stmt->close();
    
            if ($rowsAffected > 0) {
                return array("status" => "success", "message" => "Producto editado");
            } else {
                return array("status" => "error", "message" => "Error al editar el producto");
            }
        }else{
            return $result;
        }
        
    }

    
}

class ClientsControl{
    private $con;

    public function __construct($con){
        $this->con = $con;
    }

    public function getClients() {
        $control = new LoginControl($this->con);
        $result = $control -> VerifySessionCookie($_COOKIE['auth']);
        
        if($result['status'] === 'success'){
            $sql = "SELECT * FROM costumers";
            $stmt = $this->con->prepare($sql);            
            $stmt->execute();
    
            $result = $stmt->get_result();
            $stmt->close();

            if (!$result) {
                return array("status" => "error", "message" => "Error en la consulta de la base de datos");
            } else {
                $clients = array();
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        // Añadir cada fila de la tabla como un array asociativo
                        $clients[] = array(
                            "id_db" => $row['id'],
                            "id_facturapi" => $row['id_facturapi'],
                            "name" => $row['name'],
                            "email" => $row['email'],
                            "phone" => $row['phone']
                        );
                    }
                } else {
                    return array("status" => "error", "message" => "No se encontraron clientes");
                }
                
                // Cerrar la conexión a la base de datos
                $this->con->close();
                
                // Devolver los datos en el formato esperado por DataTables
                return $clients;
            }
        }else{
            return $result;
        }
        
    }

    public function GetNameCostumers(){
        $control = new LoginControl($this->con);
        $result = $control -> VerifySessionCookie($_COOKIE['auth']);
        
        if($result['status'] === 'success'){
            $stmt = $this->con->prepare("SELECT id, name FROM costumers");
            $stmt->execute();
            $result = $stmt->get_result();
            $clients = array();
            if ($result->num_rows > 0) {

                while($row=$result->fetch_array()){
                    $names=($row['name']);
                    $id=($row['id']);
                    $clients[] = array('label' => $names, 'value' => $names, 'id' => $id);
                   }
                
            } else {
                return array("status" => "error", "message" => "No se encontraron clientes");
            }
            
            // Devolver los datos en el formato esperado por DataTables
            return $clients;
        }else{
            return $result;
        }
    }
    
}