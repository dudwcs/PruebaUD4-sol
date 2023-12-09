<!DOCTYPE html>
<html lang="es" data-bs-theme="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>

    <title>Actualizar producto</title>
</head>

<body>
    <?php
    require_once 'conexion.php';
    $product_name = "";
    $price = 0;
    $exito = false;
    $producto = array();


    if (isset($_GET["prod_id"])) {
        $prod_id = $_GET["prod_id"];

        $producto = findProductById($prod_id);
        if ($producto === false) {

            echo "<div class=\"alert alert-danger\" role=\"alert\">
                    No se ha encontrado el producto </div>";
        } else { ?>

            <div class="container-fluid">
                <header class="mb-5">
                    <div class="p-5 text-center " style="margin-top: 58px;">
                        <h1 class="mb-3"> Actualizar producto </h1>

                    </div>
                </header>
                <form class='form-control ' method="post">
                    <div>
                        <label for="productName" class="form-label col-3">Nombre producto</label>
                        <input name="productName" type="text" class="form-control col-9" id="productName" pattern="^(?!\s*$).+" value="<?= $producto['ProductName'] ?>" required />
                    </div>
                    <div>
                        <label for="price" class="form-label col-3">Precio</label>
                        <input name="price" type="number" step="0.01" class="form-control col-9" id="price" required value="<?= $producto['Price'] ?>" />
                    </div>
                    <input type="hidden" value="<?= $producto['ProductID'] ?>" name="prod_id" />

                    <div class="row d-flex justify-content-center">
                        <button type="submit" class="btn btn-primary my-3 col-3">Actualizar producto</button>
                    </div>

                </form>
            </div>
    <?php }
    } else {
        echo "<div class=\"alert alert-info\" role=\"alert\">
           Añade el parámetro prod_id a la URL. Por ejemplo:
           http://localhost:3000/actualizar.php?prod_id=1 </div>";
    }
    ?>
    <?php

    if (isset($_POST["productName"])) {
        if (!empty($_POST["productName"])) {
            $product_name = $_POST["productName"];
        }
        if (isset($_POST["price"]) &&  !empty($_POST["price"])) {
            $price = $_POST["price"];
        }
        if (isset($_POST["prod_id"]) && !empty($_POST["prod_id"])) {
            $prod_id = $_POST["prod_id"];
        }
        $exito = update_product($product_name, $price, $prod_id);

        if ($exito) : ?>
            <div class="alert alert-success" role="alert">
                El producto se ha actualizado correctamente
            </div>
        <?php else : ?>
            <div class="alert alert-danger" role="alert">
                Ha ocurrido algún error y no ha podido actualizarse el producto
            </div>
    <?php
        endif;
    }




    function  findProductById($prod_id)
    {
        $conProyecto = getConnection();
        $pdostmt = $conProyecto->prepare("SELECT * FROM products WHERE ProductId = ?");
        $pdostmt->execute([$prod_id]);
        $array = $pdostmt->fetch(PDO::FETCH_ASSOC);
        return $array;
    }


    function update_product(
        string $product_name,
        float $price,
        int $product_id

    ): bool {
        $exito = false;

        try {
            $conProyecto = getConnection();
            $pdostmt = $conProyecto->prepare("UPDATE  products set ProductName = ?, Price = ?
                WHERE ProductId = ?");

            $pdostmt->bindValue(1, $product_name);
            $pdostmt->bindValue(2, $price);
            $pdostmt->bindValue(3, $product_id, PDO::PARAM_INT);
            $exito = $pdostmt->execute();

           // $pdostmt->debugDumpParams();
        } catch (Exception $ex) {
            $exito = false;

            echo "<div class=\"alert alert-danger\" role=\"alert\">
        Ha ocurrido una excepción durante la actualización: " . $ex->getMessage() . "</div>";
        }
        return $exito;
    }


    ?>



</body>

</html>