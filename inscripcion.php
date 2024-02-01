<?php
// Conexión a la base de datos (reemplaza 'root' y '' con tu usuario y contraseña)
$conexion = new mysqli("localhost", "root", "", "escuela");

if ($conexion->connect_error) {
    die("Error de conexión a la base de datos: " . $conexion->connect_error);
}

// Obtener la capacidad total
$capacidad_total = 15;

// Obtener la cantidad de inscritos desde la base de datos
$result = $conexion->query("SELECT COUNT(*) as inscritos FROM alumnos WHERE salon_id = 1");
$fila = $result->fetch_assoc();
$inscritos = $fila['inscritos'];

// Calcular la capacidad restante
$capacidad_restante = $capacidad_total - $inscritos;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['inscribir'])) {
        // Obtener el nombre del formulario
        $nombre = $_POST['nombre'];

        // Insertar en la base de datos
        $sql = "INSERT INTO alumnos (nombre, salon_id) VALUES ('$nombre', 1)";

        if ($conexion->query($sql) === TRUE) {
            // Incrementar el número de inscritos
            $inscritos++;

            // Calcular la capacidad restante después de la inscripción
            $capacidad_restante = $capacidad_total - $inscritos;

            // Mostrar el mensaje
            echo "<p>¡Inscripción exitosa! Lugares disponibles: $capacidad_restante. Alumnos inscritos: $inscritos</p>";
        } else {
            echo "Error: " . $sql . "<br>" . $conexion->error;
        }
    }
}

// Mostrar el formulario si hay lugares disponibles
if ($capacidad_restante > 0) {
    ?>
    <form method="post">
        <label for="nombre">Nombre:</label>
        <input type="text" name="nombre" required>
        <input type="submit" name="inscribir" value="Inscribirse">
    </form>
    <?php
} else {
    // Mostrar un mensaje si no hay lugares disponibles
    echo "<p>No hay lugares disponibles en este salón.</p>";
}

// Cerrar la conexión a la base de datos
$conexion->close();
?>
