<?php
include_once("src/requiredMethod.php");

define("GET_REQUESTED_PAGE", "page");

if(!isset($_GET[GET_REQUESTED_PAGE])) {
    header("Location: index.php?page=login.php");
    exit();
}
$requestedPage = $_GET[GET_REQUESTED_PAGE];
RequiredMethod::requireOnce($requestedPage);