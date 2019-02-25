<?php
// Here you can initialize variables that will be available to your tests
date_default_timezone_set('UTC');

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
