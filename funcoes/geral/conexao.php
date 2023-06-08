<?php
$nomeConexao;
function conexao()
{
    $pdo = new PDO('mysql:host=10.0.43.3;dbname=oss_poo', 'root', 'secret', [ PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"]);
    return $pdo;
}
