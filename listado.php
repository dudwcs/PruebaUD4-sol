<?php
require_once 'conexion.php';


$cod = null;


$productos_array = obtener_productos();
/**
 * obtener_productos
 * Consulta la tabla products para obtener los datos ordenados por ProductID descendentemente
 * @return array array con tantos registros como tuplas haya y por cada registro un array con tantas claves como columnas haya
 */
function obtener_productos(): array
{
  
    $productos_array = null;
    try {
        $conProyecto = getConnection();
        $consulta = "select * from products order by ProductID desc";
        $stmt = $conProyecto->prepare($consulta);

        $stmt->execute();
        $productos_array = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $ex) {
        die("Error al recuperar los productos " . $ex->getMessage());
    }

  
    return $productos_array;
}
/**
 * mostrar_productos
 * Crea una fila html mostrando en cada celda de datos ProductName y ProductID y Price. 
 * @param  array $productos_array un array asociativo con 3 claves: una por cada columna
 * @return void
 */
function mostrar_productos(array $productos_array)
{
    foreach ($productos_array as $fila) {
        echo "<tr class='text-center'><th scope='row'>";
     
        echo "<td>{$fila['ProductID']}</td>";
        echo "<td>{$fila['ProductName']}</td>";
        echo "<td>{$fila['Price']}</td>";
      
        echo "</tr>";
    }
}
?>
<!doctype html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-
scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!-- css para usar Bootstrap -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <title>Listado de productos</title>
</head>

<body>
    <h3 class="text-center mt-2 font-weight-bold">Listado de libros</h3>
    <div class="container mt-3">
        <a href="crear.php" class='btn btn-success mt-2 mb-2'>Crear</a>
        <table class="table table-striped table-light">
            <thead>
                <tr class="text-center">
                    <th scope="col"></th>
                    <th scope="col">ProductID</th>
                    <th scope="col">ProductName</th>
                    <th scope="col">Price</th>
                 
                </tr>
            </thead>
            <tbody>
                <?php
                mostrar_productos($productos_array);

                ?>
            </tbody>
        </table>

      
    </div>
</body>

</html>