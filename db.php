<?php
$mysqli = new mysqli("127.0.0.1", "root", "", "master");
if ($mysqli->connect_errno) {
    echo "�� ������� ������������ � MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
}

?>