<?php
require_once __DIR__ . '/../client/classes/Database.php';
require_once __DIR__ . '/../client/classes/Offer.php';
require_once __DIR__ . '/../client/classes/Proposal.php';
require_once __DIR__ . '/../client/classes/User.php';
require_once __DIR__ . '/../client/classes/Category.php';
require_once __DIR__ . '/../client/classes/Subcategory.php';

$databaseObj= new Database();
$offerObj = new Offer();
$proposalObj = new Proposal();
$userObj = new User();
$categoryObj = new Category();
$subcategoryObj = new Subcategory();

$userObj->startSession();
?>
