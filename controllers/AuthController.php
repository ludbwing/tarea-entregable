<?php
class AuthController {
    private $db;
    private $usuario;
    public function __construct() 
    {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type, Authorization");
      $datebase = new Database();
      $this->db = $datebase->connect();
      $this-> usuario = new Usuario($this->db); 
    }

    public function showLogin(){
        include 'views/auth/login.php';

    }

    public function showRegister(){
        include 'views/auth/register.php';
    }

    public function login(){
        header('Content-Type: application/json');
        try {
            ///throw new Exception('error josue');
            $data = json_decode(file_get_contents("php://input")); 
            if(empty($data->nombreUsuario) && empty($data->claveUsuario)){
                throw new Exception('usuario y contraseña son requeridos');
            }

            $usuario = $this->usuario->login($data->nombreUsuario, $data->claveUsuario);

            if($usuario){
                session_start();
                $_SESSION['id_usuario'] = $usuario['id_usuario'];
                $_SESSION['usuario'] = $usuario['nombre_usuario'];
                $_SESSION['rol'] = $usuario['rol'];
                $_SESSION['correo'] = $usuario['correo'];
                $_SESSION['nombre_completo'] = $usuario['nombre_completo'];

                echo json_encode([
                    'status' =>'success',
                    'message' =>'Login Exitoso',
                    'usuario' =>[
                        'nombre_usuario'=>$usuario['nombre_usuario'],
                        'rol'=>$usuario['rol'],
                        'nombre_completo'=>$usuario['nombre_completo'],
                    ]
                ]);
            }else{
                throw new Exception('Usuario o contraseña Incorrectos');
            }
        //    var_dump($usuario);

        } catch (Exception $e) {
            echo json_encode([
                'status'=> 'error', 
                'message' => $e->getMessage() 
            ]);
            
        }
    }

    public function register(){
        header('Content-Type: application/json');
        try {
            $data = json_decode(file_get_contents("php://input"));

              if(
                 empty($data->clave) || 
                 empty($data->confirmarClave) ||
                 empty($data->email) ||
                 empty($data->nombreCompleto) ||
                 empty($data->usuario) 
                 ){
                  throw new Exception('Los campos son requeridos son requeridos');
              }

              if ($data->clave !== $data->confirmarClave) {
                  throw new Exception('Las contraseñas no coinciden');
              }


              if($this->usuario->validaUsuario($data->usuario)){
                throw new Exception('El usuario ya existe');
              }

              if($this->usuario->validaEmail($data->email)){
                throw new Exception('El Email ya existe');
              }

            //   if (!preg_match('/[!@#$%^&*(),.?":{}|<>]/',$data->clave)) {
            //     throw new Exception('El Contraseña debe contener al menos un carácter especial');
            // }
            // if (strlen($data->clave) < 8) {
            //     throw new Exception('El Contraseña debe tener al menos 8 caracteres');
            // }

              $usuarioData = [
                "clave" => $data->clave,
                "email" => $data->email,
                "nombreCompleto" => $data->nombreCompleto,
                "usuario" => $data->usuario,
                "rol" => $data->rol,
              ];

              if($this->usuario->registrarUsuario($usuarioData)){
                echo json_encode([
                    'status'=> 'success', 
                    'message' => 'Uusuario registrado correctamente', 
                ]);
              }else{
                throw new Exception('Error al registrar usuario');
              }





        } catch (Exception $e) {
            echo json_encode([
                'status'=> 'error',
                'message' => $e->getMessage()
            ]);
        }
    }    
}