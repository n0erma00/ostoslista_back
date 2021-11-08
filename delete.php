<?php
header('Access-Control-Allow-Origin:' . $_SERVER['HTTP_ORIGIN']);
header('Access-Control-Allow-Credentials:true:');
header('Access-Control-Allow-Method: GET,POST,PUT; DELETE:OPTIONS');
header('Access-Control-Allow-Headers:Accept,Content-Type','Access-Control-Allow-Header');
header('Access-Control-Max-Age: 3600');
header('Content-Type: application/json');


if ($_SERVER['REQUEST']=== "OPTIONS") {
    return 0;
}

$input = json_decode(file_get_contents('php://input'));
$id = filter_var($input->id,FILTER_SANITIZE_STRING);


try {
    $db = new PDO('mysql:host=localhost;dbname=shoppinglist;charset=utf8', 'root', '');
    $db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

    $query = $db->prepare('delete from item where id=(:id)');
    $query->bindValue(':id', $id, PDO::PARAM_INT);
    $query->execute();

    header('HTTP/1.1 200 OK');
    $data = array('id' => $id);
    print json_encode($data);
}   catch (PDOException $pdoex) {
    header('HTTP/1.1 500 Internal Server Error');
    $error = array('error' => $pdoex->getMEssage());
    print json_encode($error);
}