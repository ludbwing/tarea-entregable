<?php
class tareaController
{

    private $db;
    private $tarea;


    public function __construct()
    {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type, Authorization");
        $datebase = new Database();
        $this->db = $datebase->connect();
        $this->tarea = new Tarea($this->db);
    }


    public function index()
    {
        include 'views/layouts/header.php'; //Siempre
        include 'views/tarea/index.php';
        include 'views/layouts/footer.php'; //Siempre
    }

    public function obtenerTarea()
    {
        header('Content-Type: application/json');
        try {
            $resultado = $this->tarea->obtenerTarea();
            $tareas = $resultado->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode([
                'status' => 'success',
                'data' => $tareas
            ]);
        } catch (Exception $e) {
            echo json_encode([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }














    public function guardarTarea(){
        header('Content-Type: application/json');

        try {
            // $data = json_decode(file_get_contents("php://input"));
            // var_dump($_POST);
            if (
                empty($_POST['titulo']) ||
                empty($_POST['descripcion']) ||
                empty($_POST['estado']) ||
                empty($_POST['prioridad']) ||
                empty($_POST['fecha_vencimiento'])
            ) {
                throw new Exception('Los campos son requeridos');
            }
            
            $this->tarea->titulo = $_POST['titulo'];
            $this->tarea->descripcion = $_POST['descripcion'];
            $this->tarea->estado = $_POST['estado'];
            $this->tarea->prioridad = $_POST['prioridad'];
            $this->tarea->fecha_vencimiento = $_POST['fecha_vencimiento'];

            // Manejo de imagen
            if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === 0) {
                $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
                $maxSize = 5 * 1024 * 1024; // 5MB
                
                // var_dump($_FILES['imagen']['type']);
                if (!in_array($_FILES['imagen']['type'], $allowedTypes)) {
                    throw new Exception('Tipo de archivo no permitido. Solo se permiten JPG, PNG y GIF.');
                }

                if ($_FILES['imagen']['size'] > $maxSize) {
                    throw new Exception('El archivo es demasiado grande. Máximo 5MB.');
                }

                $fileName = time() . '_' . basename($_FILES['imagen']['name']);
                $filePath = UPLOAD_PATH . $fileName;

                if (!is_dir(UPLOAD_PATH)) {
                    mkdir(UPLOAD_PATH, 0777, true);
                }

                if (!move_uploaded_file($_FILES['imagen']['tmp_name'], $filePath)) {
                    throw new Exception('Error al subir la imagen');
                }

                $this->tarea->imagen = $fileName;
            }






            if($this->tarea->crearTarea()){
                echo json_encode([
                    'status' => 'success',
                    'message' => 'Tarea registrada correctamente',
                ]);
            }else{
                throw new Exception('Error al registrar la Tarea');
            };
        } catch (Exception $e) {
            echo json_encode([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }







    public function actualizarTarea(){
        header('Content-Type: application/json');

        try {
            if (
                empty($_POST['titulo']) ||
                empty($_POST['descripcion']) ||
                empty($_POST['estado']) ||
                empty($_POST['prioridad'])
            ) {
                throw new Exception('Los campos son requeridos');
            }
            
            $this->tarea->id_tarea = $_POST['id_tarea'];
            $this->tarea->titulo = $_POST['titulo'];
            $this->tarea->descripcion = $_POST['descripcion'];
            $this->tarea->estado = $_POST['estado'];
            $this->tarea->prioridad = $_POST['prioridad'];
            $this->tarea->fecha_vencimiento = $_POST['fecha_vencimiento'];

            // Manejo de imagen
            if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === 0) {
                $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
                $maxSize = 5 * 1024 * 1024; // 5MB
                
                // var_dump($_FILES['imagen']['type']);
                if (!in_array($_FILES['imagen']['type'], $allowedTypes)) {
                    throw new Exception('Tipo de archivo no permitido. Solo se permiten JPG, PNG y GIF.');
                }

                if ($_FILES['imagen']['size'] > $maxSize) {
                    throw new Exception('El archivo es demasiado grande. Máximo 5MB.');
                }

                $fileName = time() . '_' . basename($_FILES['imagen']['name']);
                $filePath = UPLOAD_PATH . $fileName;

                if (!is_dir(UPLOAD_PATH)) {
                    mkdir(UPLOAD_PATH, 0777, true);
                }

                if (!move_uploaded_file($_FILES['imagen']['tmp_name'], $filePath)) {
                    throw new Exception('Error al subir la imagen');
                }

                $this->tarea->imagen = $fileName;
            }

















            if($this->tarea->actualizarTarea()){
                echo json_encode([
                    'status' => 'success',
                    'message' => 'Tarea actualizada correctamente',
                ]);
            }else{
                throw new Exception('Error al actualizar la Tarea');
            };
        } catch (Exception $e) {
            echo json_encode([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }














    public function eliminarTarea(){
        header('Content-Type: application/json');
        try {
            $data = json_decode(file_get_contents("php://input")); // capturar data del front

            $this->tarea->id_tarea = $data->id_tarea;

            if($this->tarea->eliminarTarea()){
                echo json_encode([
                    'status' => 'success',
                    'message' => 'Tarea eliminada correctamente',
                ]);
            }else{
                throw new Exception('Error al elimiar la Tarea');
            };
        } catch (Exception $e) {
            echo json_encode([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }










    
    public function buscarTarea(){
    }
}