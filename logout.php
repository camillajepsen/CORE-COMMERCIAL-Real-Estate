<?php

session_start();

// end the session to log the user out
session_unset();
session_destroy();

// send to index.php
header("Location: index.php");
exit();

