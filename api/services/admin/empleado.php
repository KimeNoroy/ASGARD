<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = htmlspecialchars($_POST['nombre']);
    $apellido = htmlspecialchars($_POST['apellido']);
    $dui = htmlspecialchars($_POST['dui']);
    $telefono = htmlspecialchars($_POST['telefono']);
    $correo = htmlspecialchars($_POST['correo']);
    $nit = htmlspecialchars($_POST['nit']);

    // Aquí puedes procesar los datos recibidos, por ejemplo, guardarlos en una base de datos

    // Ejemplo de cómo guardar los datos en una base de datos MySQL
    $servername = "localhost";
    $username = "tu_usuario";
    $password = "tu_contraseña";
    $dbname = "tu_base_de_datos";

    // Crear conexión
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Verificar conexión
    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }

    $sql = "INSERT INTO empleados (nombre, apellido, dui, telefono, correo, nit) VALUES ('$nombre', '$apellido', '$dui', '$telefono', '$correo', '$nit')";

    if ($conn->query($sql) === TRUE) {
        echo "Nuevo registro creado exitosamente";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>