<?php

require 'Slim/Autoloader.php';
\Slim\Autoloader::register();

require 'vendor/autoload.php';
$app = new \Slim\App();

/**
 * Step 3: Define the Slim application routes
 * Here we define several Slim application routes that respond
 * to appropriate HTTP request methods. In this example, the second
 * argument for `Slim::get`, `Slim::post`, `Slim::put`, `Slim::patch`, and `Slim::delete`
 * is an anonymous function.
 */

$validator = new GUMP();

$rules = array(
    'missing'       => 'required',
    'email'         => 'valid_email',
    // 'max_len'       => 'max_len,1',
    // 'min_len'       => 'min_len,4',
    // 'exact_len'     => 'exact_len,10',
    // 'alpha'         => 'alpha',
    // 'alpha_numeric' => 'alpha_numeric',
    // 'alpha_dash'    => 'alpha_dash',
    // 'alpha_space'   => 'alpha_space',   
    // 'numeric'       => 'numeric',
    // 'integer'       => 'integer',
    // 'boolean'       => 'boolean',
    // 'float'         => 'float',
    // 'valid_url'     => 'valid_url',
    // 'url_exists'    => 'url_exists',
    // 'valid_ip'      => 'valid_ip',
    // 'valid_ipv4'    => 'valid_ipv4',
    // 'valid_ipv6'    => 'valid_ipv6',
    // 'valid_name'    => 'valid_name',
    'contains'      => 'contains,free pro basic'
);

$invalid_data = array(
    'missing'       => '',
    'email'         => "not a valid email\r\n",
    // 'max_len'       => "1234567890",
    // 'min_len'       => "1",
    // 'exact_len'     => "123456",
    // 'alpha'         => "*(^*^*&",
    // 'alpha_numeric' => "abcdefg12345+\r\n\r\n\r\n",
    // 'alpha_dash'    => "ab<script>alert(1);</script>cdefg12345-_+",
    // 'alpha_space'   => 'abcdefg12345_$^%%&TGY', 
    // 'numeric'       => "one, two\r\n",
    // 'integer'       => "1,003\r\n\r\n\r\n\r\n",
    // 'boolean'       => "this is not a boolean\r\n\r\n\r\n\r\n",
    // 'float'         => "not a float\r\n",
    // 'valid_url'     => "\r\n\r\nhttp://add",
    // 'url_exists'    => "http://asdasdasd354.gov",
    // 'valid_ip'      => "google.com",
    // 'valid_ipv4'    => "google.com",
    // 'valid_ipv6'    => "google.com",
    // 'valid_name'    => '*&((*S))(*09890uiadaiusyd)',
    'contains'      => 'premium'
);

$valid_data = array(
    'missing'       => 'This is not missing',
    'email'         => 'sean@wixel.net',
    'max_len'       => '1',
    'min_len'       => '1234',
    'exact_len'     => '1234567890',
    'alpha'         => 'ÈÉÊËÌÍÎÏÒÓÔasdasdasd',
    'alpha_numeric' => 'abcdefg12345',
    'alpha_dash'    => 'abcdefg12345-_',
    'alpha_space'   => 'abcdefg12345 ',
    'numeric'       => 2.00,
    'integer'       => 3,
    'boolean'       => FALSE,
    'float'         => 10.10,
    'valid_url'     => 'http://wixel.net',
    'url_exists'    => 'http://wixel.net',
    'valid_ip'      => '69.163.138.23',
    'valid_ipv4'    => "255.255.255.255",
    'valid_ipv6'    => "2001:0db8:85a3:08d3:1319:8a2e:0370:7334",
    'valid_name'    => 'Sean Nieuwoudt',
    'contains'      => 'free'
);


// echo "\nAFTER SANITIZE:\n\n";
// print_r($validator->sanitize($invalid_data));

echo json_encode($validator->validate($invalid_data, $rules));

exit();

$app->get('/','getEmployee');
$app->get('/index/:id','getEmployeeDetail');

function getEmployee() {
    $sql = "select * FROM employee";
    try {
        $db = getConnection();
        $stmt = $db->query($sql);
        $wines = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        echo '{"data": ' . json_encode($wines) . '}';
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}

function getEmployeeDetail($id) {
    $sql = "select * FROM employee where id=".$id;
    try {
        $db = getConnection();
        $stmt = $db->query($sql);
        $wines = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        echo '{"data": ' . json_encode($wines) . '}';
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}

function getConnection() {
    $dbhost="localhost";
    $dbuser="root";
    $dbpass="";
    $dbname="2015_slim_employee";
    $dbh = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $dbh;
}
$app->run();
