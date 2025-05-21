<?php
$plain = "5678"; // what you typed into login form
$hash = '$2y$10$eLT5kbVC7H4A...FAdbYyOc6e'; // paste from database

if (password_verify($plain, $hash)) {
    echo "Match!";
} else {
    echo "No match.";
}
