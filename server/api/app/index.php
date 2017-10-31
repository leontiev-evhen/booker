<?php
use libs\RestServer;
date_default_timezone_set('Europe/Kiev');

try
{
	header("Access-Control-Allow-Origin:*");
	header("Access-Control-Allow-Methods: POST, GET, PUT, DELETE");
	header("Access-Control-Allow-Headers: Authorization, Content-Type");
	
	require_once __DIR__.'/vendor/autoload.php';
    RestServer::run();
}
catch (Exception $e)
{
    echo $e->getMessage();
}



   



