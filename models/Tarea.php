<?php
//models/Producto.php
class Tarea {
    private $conn;
    public $id_tarea;
    public $titulo;
    public $descripcion;
    public $estado;
    public $prioridad;
    public $imagen;
    public $fecha_vencimiento;

    public function __construct($db) {
        $this->conn = $db;
    }








    public function obtenerTarea() {
        $query = "SELECT * FROM tarea ORDER BY fecha_creacion DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    } //









    public function crearTarea() {
        $query = "INSERT INTO tarea  
                (titulo, descripcion, estado, prioridad, imagen, fecha_vencimiento) 
                VALUES (:titulo, :descripcion, :estado, :prioridad, :imagen, :fecha_vencimiento)";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':titulo', $this->titulo);
        $stmt->bindParam(':descripcion', $this->descripcion);
        $stmt->bindParam(':estado', $this->estado);
        $stmt->bindParam(':prioridad', $this->prioridad);
        $stmt->bindParam(':imagen', $this->imagen);
        $stmt->bindParam(':fecha_vencimiento', $this->fecha_vencimiento);
        if($stmt->execute()) {
            return true;
        }
        return false;
    }





    public function actualizarTarea() {
        $query = "UPDATE tarea
                SET titulo = :titulo, 
                    descripcion = :descripcion, 
                    estado = :estado, 
                    fecha_vencimiento = :fecha_vencimiento, 
                    prioridad = :prioridad" .
                    ($this->imagen ? ", imagen = :imagen" : "") .
                " WHERE id_tarea = :id_tarea";
        
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':titulo', $this->titulo);
        $stmt->bindParam(':descripcion', $this->descripcion);
        $stmt->bindParam(':estado', $this->estado);
        $stmt->bindParam(':prioridad', $this->prioridad);
        $stmt->bindParam(':id_tarea', $this->id_tarea);
        $stmt->bindParam(':fecha_vencimiento', $this->fecha_vencimiento);
        
        if($this->imagen) {
            $stmt->bindParam(':imagen', $this->imagen);
        }

        if($stmt->execute()) {
            return true;
        }
        return false;
    }












    public function eliminarTarea() {
        $query = "DELETE FROM tarea WHERE id_tarea = :id_tarea";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_tarea', $this->id_tarea);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }












    
    // public function search($term) {
    //     $query = "SELECT * FROM " . $this->table . " 
    //              WHERE name LIKE :term 
    //              ORDER BY name ASC";
        
    //     $term = "%{$term}%";
        
    //     $stmt = $this->conn->prepare($query);
    //     $stmt->bindParam(':term', $term);
        
    //     $stmt->execute();
    //     return $stmt;
    // }
}