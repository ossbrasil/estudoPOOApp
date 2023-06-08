<?php
include 'classes/db/DaoApolice.php';

$id = $_GET['id'];
            
$daoApolice = new DaoApolice;

$dados = $daoApolice->select()->where('a.id', $id)->get();


var_dump($dados);




