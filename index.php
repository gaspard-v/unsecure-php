<?php
include_once("src/requiredMethod.php");

define("GET_PAGE_DEMANDEE", "page");

if(!isset($_GET[GET_PAGE_DEMANDEE])) {
    header("Location: index.php?page=connexion.php");
    exit();
}
$pageDemandee = $_GET[GET_PAGE_DEMANDEE];
RequiredMethod::requireOnce($pageDemandee);