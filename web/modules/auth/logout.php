<?php
require_once __DIR__ . '/../../core/Session.php';

use Web\Core\Session;

Session::destroy();
header("Location: ../../index.php");
exit;
