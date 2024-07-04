<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = htmlspecialchars($_POST['nombres_empleado']);
    $apellido = htmlspecialchars($_POST['apellidos_empleado']);
    $dui = htmlspecialchars($_POST['dui_empleado']);
    $email = htmlspecialchars($_POST['email_empleado']);
    $clave = htmlspecialchars($_POST['contrasena']);

    // Aquí puedes procesar los datos recibidos, por ejemplo, guardarlos en una base de datos

    // Ejemplo de cómo guardar los datos en una base de datos MySQL
    $servername = "localhost";
    $username = "tu_usuario";
    $email = "tu_email";
    $password = "tu_contraseña";
    $dbname = "tu_base_de_datos";

    // Crear conexión
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Verificar conexión
    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }

    $sql = "INSERT INTO tb_empleados(nombres_empleado, apellidos_empleado, dui_empleado, email_empleados) VALUES ('$nombre', '$apellido', '$dui', '$email')";

    if ($conn->query($sql) === TRUE) {
        echo "Nuevo registro creado exitosamente";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>