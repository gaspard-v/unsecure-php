<?php
require_once("src/requiredMethod.php");
require_once("src/pageName.php");

define("GET_REQUESTED_PAGE", "page");

if (!isset($_GET[GET_REQUESTED_PAGE])) {
    header("Location: index.php?page=" . PAGE_CHECK_USER);
    exit();
}
$requestedPage = $_GET[GET_REQUESTED_PAGE];
RequiredMethod::requireOnce($requestedPage);