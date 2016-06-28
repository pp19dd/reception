<?php
if( !defined("MAP_API_KEY") ) {
    die("Error 211");
}

$db = new PDO(PDO_CONN, PDO_USER, PDO_PASS);

$st = $db->prepare(
    "insert into `entries` " .
    "(`stamp`, `reception`, `lat`, `lng`, `zoom`, `ip`) " .
    "values " .
    "(now(), :reception, :lat, :lng, :zoom, :ip)"
);

if( isset( $_SERVER['HTTP_X_FORWARDED_FOR']) ) {
    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
} else {
    $ip = $_SERVER['REMOTE_ADDR'];
}

$ret = array(
    "status" => "ok",
    "stamp" => date("r")
);

try {
    $st->execute(array(
        "reception" => $_POST['reception'],
        "lat" => $_POST['lat'],
        "lng" => $_POST['lng'],
        "zoom" => $_POST['zoom'],
        "ip" => $ip
    ));
} catch( Exception $e ) {
    $ret["status"] = "fail";
}

echo json_encode($ret);
