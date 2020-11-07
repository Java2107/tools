<?php
$pages = array(
  "disabled","discord","invite"
);
if(isset($_GET["page"])) {
  $page = $_GET["page"];
  if($page[0] == "/") {
    $page = substr($page, 1);
  }
  if(in_array($page, $pages)) {

    include "includes/pages/$page.php";
  } else { 
    include "includes/pages/404.php";
  }
} else {
  include "includes/pages/home.php";
}
?>
