<?php
session_start();
if (!isset($_SESSION["usuario_id"])) {
  header("Location: ../public/login.html");
  exit;
}
?>