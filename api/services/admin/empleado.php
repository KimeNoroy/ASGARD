<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = htmlspecialchars($_POST['nombres_empleado']);
    $apellido = htmlspecialchars($_POST['apellidos_empleado']);
    $dui = htmlspecialchars($_POST['dui_empleado']);
    $clave = htmlspecialchars($_POST['contrasena']);

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

    $sql = "INSERT INTO tb_empleados(nombres_empleado, apellidos_empleado, dui_empleado) VALUES ('$nombre', '$apellido', '$dui')";

    if ($conn->query($sql) === TRUE) {
        echo "Nuevo registro creado exitosamente";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>