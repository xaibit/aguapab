<?php
$dir_subida = 'files/';
$fichero_subido = $dir_subida . basename($_FILES['clientes']['name']);

unlink($fichero_subido);
echo '<pre>';
if (move_uploaded_file($_FILES['clientes']['tmp_name'], $fichero_subido)) {
    echo "El fichero es válido y se subió con éxito.\n";
	header("Location: ../upload/parser.php?f=$fichero_subido");
    echo "El fichero fué procesado con éxito.\n";
} else {
    echo "¡Posible ataque de subida de ficheros!\n";
}
echo '<a href="../upload">Volver</a><br>';
echo '<a href="../upload/example.php">Procesar</a>';
echo '</pre>';
?>