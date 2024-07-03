<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = htmlspecialchars($_POST['nombre_cliente']);
    $apellido = htmlspecialchars($_POST['apellido_cliente']);
    $correo = htmlspecialchars($_POST['email_cliente']);
    $clave = htmlspecialchars($_POST['contraseña_cliente']);
    $telefono = htmlspecialchars($_POST['telefono']);
    $dui = htmlspecialchars($_POST['dui_cliente']);
    $nit = htmlspecialchars($_POST['nit_cliente']);

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

    $sql = "INSERT INTO tb_clientes(nombre_cliente, apellido_cliente, email_cliente, contraseña_cliente, telefono, dui_cliente, nit_cliente)
    VALUES ('$nombre', '$apellido', '$correo', '$clave', '$telefono', '$dui', '$nit')";

    if ($conn->query($sql) === TRUE) {
        echo "Nuevo cliente registrado exitosamente";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>