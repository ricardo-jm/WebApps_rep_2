
<?php

include '../src/DBconnect.php';

function escape($data) {
    $data = htmlspecialchars($data, ENT_QUOTES | ENT_SUBSTITUTE, "UTF-8");
    $data = trim($data);
    $data = stripslashes($data);
    return ($data);
}

function validateLogin($connection)
{
    try {
        $password = escape($_POST['Password']);
        $username = strtolower(escape($_POST['Username']));
        $sql = "SELECT * FROM user WHERE username = :username";
        $statement = $connection->prepare($sql);
        $statement->bindParam(':username', $username, PDO::FETCH_ASSOC);
        $statement->execute();
        $user = $statement->fetch(PDO::FETCH_ASSOC);
        #var_dump($user);

        if ($user && $statement->rowCount() > 0) {
            if (($user['username'] == $username) && ($user['pwd'] == $password)) {
                echo 'Username and Password are correct';

                $_SESSION['Username'] = $username; //store Username to the session
                $_SESSION['Active'] = true;
                header("location:index.php");
                exit; //we’ve just used header() to redirect to another page but we must terminate all current code so that it doesn’t run when we redirect
            } else echo 'Incorrect Username or Password';
        }else echo 'Incorrect Username or Password';
    } catch(PDOException $error) {
        //Commented so that an attacker wouldn't get information of the app in the error
        //echo $sql . "<br>" . $error->getMessage();
    }
}

function register($connection)
{
    try {
        echo "success";
        $new_user = array(
            "username" => strtolower(escape($_POST['username'])),
            "pwd" => escape($_POST['password']),
            "email" => escape($_POST['email']),
            "phone" => escape($_POST['phone'])
        );

        // SQL Injection vulnerable query
        //$sql = sprintf("INSERT INTO %s (%s) values (%s)", "user", implode(", ",
        //    array_keys($new_user)), ":" . implode(", :", array_keys($new_user)));

        $sql = "INSERT INTO user (username, password, email, phone) 
                VALUES (:username, :password, :email, :phone)";

        $statement->bindParam(':username', $new_user['username']);
        $statement->bindParam(':password', $new_user['pwd']);
        $statement->bindParam(':email', $new_user['email']);
        $statement->bindParam(':phone', $new_user['phone']);

        $statement = $connection->prepare($sql);
        $statement->execute($new_user);
    } catch (PDOException $error) {
        echo $sql . "<br>" . $error->getMessage();
    }
}

function search($connection)
{
    try {
        $sql = "SELECT * FROM product WHERE prodname = :prodname";
        $prodname = $_POST['prodname'];
        $statement = $connection->prepare($sql);
        $statement->bindParam(':prodname', $prodname, PDO::PARAM_STR);
        $statement->execute();
        $result = $statement->fetchAll();
    } catch(PDOException $error) {
        echo $sql . "<br>" . $error->getMessage();
    }
    return $result;
}

function listproducts($connection)
{
    try {
        $sql = "SELECT * FROM product";
        $statement = $connection->prepare($sql);
        $statement->execute();
        $result = $statement->fetchAll();
    } catch(PDOException $error) {
        echo $sql . "<br>" . $error->getMessage();
    }
    return $result;
}

function querySingleProduct($connection)
{
    try {
        $sql = "SELECT * FROM product WHERE id = :id";
        $statement = $connection->prepare($sql);
        $statement->bindParam(':id', $_GET["code"], PDO::PARAM_STR);
        $statement->execute();
        $productByCode = $statement->fetch(PDO::FETCH_ASSOC);
    } catch(PDOException $error) {
        echo $sql . "<br>" . $error->getMessage();
    }
    return $productByCode;
}

function addProduct($connection)
{
    try {
        $new_product = array(
            "prodname" => escape($_POST['prodname']),
            "category" => escape($_POST['category']),
            "proddescription" => escape($_POST['proddescription']),
            "price" => escape($_POST['price']),
            "image" => escape($_POST['image'])
        );

        $sql = sprintf( "INSERT INTO %s (%s) values (%s)", "product", implode(", ",
            array_keys($new_product)), ":" . implode(", :", array_keys($new_product)) );
        $statement = $connection->prepare($sql);
        $statement->execute($new_product);
    } catch(PDOException $error) {
        echo $sql . "<br>" . $error->getMessage();
    }
    if (isset($_POST['submit']) && $statement)
    {
        //Vulnerable to reflected XSS 
        //echo $new_product['prodname']. ' successfully added';
        echo escape($new_product['prodname']). ' successfully added';
    }
}

function deleteProduct($connection)
{
    try {
        require_once '../src/DBconnect.php';

        $id = $_GET["id"];

        $sql = "DELETE FROM product WHERE id = :id";

        $statement = $connection->prepare($sql);
        $statement->bindValue(':id', $id);
        $statement->execute();
        $success = "Product ". $id. " successfully deleted";
    } catch(PDOException $error) {
        echo $sql . "<br>" . $error->getMessage();
    }
}

function queryProductById($connection)
{
    try {
        $id = $_GET['id'];
        $sql = "SELECT * FROM product WHERE id = :id";
        $statement = $connection->prepare($sql);
        $statement->bindValue(':id', $id);
        $statement->execute();
        $product = $statement->fetch(PDO::FETCH_ASSOC);
    } catch(PDOException $error) {
        echo $sql . "<br>" . $error->getMessage();
    }
    return $product;
}

function updateProductById($connection)
{
    try {
        require_once '../src/DBconnect.php';
        $product =[
            "id" => escape($_POST['id']),
            "prodname" => escape($_POST['prodname']),
            "category" => escape($_POST['category']),
            "proddescription" => escape($_POST['proddescription']),
            "price" => escape($_POST['price']),
            "image" => escape($_POST['image'])
        ];
        $sql = "UPDATE product
                SET id = :id,
                prodname = :prodname,
                category = :category,
                proddescription = :proddescription,
                price = :price,
                image = :image
            WHERE id = :id";
        $statement = $connection->prepare($sql);
        $statement->execute($product);
    } catch(PDOException $error) {
        echo $sql . "<br>" . $error->getMessage();
    }
    return $statement;
}



function addItemToCart($connection)
{
    if(!empty($_POST["quantity"])) {
        $productByCode = querySingleProduct($connection);

        $itemArray = array($productByCode["id"] => array('name' => $productByCode["prodname"], 'code' => $productByCode["id"], 'proddescription' => $productByCode["proddescription"], 'quantity' => $_POST["quantity"], 'price' => $productByCode["price"], 'image' => $productByCode["image"]));

        if (!empty($_SESSION["cart_item"])) {
            if (in_array($productByCode["id"], array_keys($_SESSION["cart_item"]))) {
                foreach ($_SESSION["cart_item"] as $k => $v) {
                    if ($productByCode["id"] == $k) {
                        if (empty($_SESSION["cart_item"][$k]["quantity"])) {
                            $_SESSION["cart_item"][$k]["quantity"] = 0;
                        }
                        $_SESSION["cart_item"][$k]["quantity"] += $_POST["quantity"];
                    }
                }
            } else {
                $_SESSION["cart_item"] = array_merge($_SESSION["cart_item"], $itemArray);
            }
        } else {
            $_SESSION["cart_item"] = $itemArray;
        }
    }
}

function removeItemFromCart($connection)
{
    if(!empty($_SESSION["cart_item"])) {
        foreach($_SESSION["cart_item"] as $k => $v) {
            if($_GET["code"] == $v['code'])
                unset($_SESSION["cart_item"][$k]);
            if(empty($_SESSION["cart_item"]))
                unset($_SESSION["cart_item"]);
        }
    }
}

function changeCartQuantity($connection)
{
    $amount = filter_input(INPUT_POST, 'amount');
    if($amount == 'increase'){
        foreach($_SESSION["cart_item"] as $k => $v) {
            if($_GET["code"] == $v['code'])
                $_SESSION["cart_item"][$k]["quantity"] = $_SESSION["cart_item"][$k]["quantity"] + 1;
            if(empty($_SESSION["cart_item"]))
                unset($_SESSION["cart_item"]);
        }
    } else {
        foreach($_SESSION["cart_item"] as $k => $v) {
            if($_GET["code"] == $v['code']) {
                $_SESSION["cart_item"][$k]["quantity"] = $_SESSION["cart_item"][$k]["quantity"] - 1;
                if ($_SESSION["cart_item"][$k]["quantity"] == 0)
                    unset($_SESSION["cart_item"][$k]); //Completed
            }
            if(empty($_SESSION["cart_item"]))
                unset($_SESSION["cart_item"]);
        }
    }
}

