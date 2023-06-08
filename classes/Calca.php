<?php
include_once 'Roupa.php';
class Calca extends Roupa
{

    public function __construct($cor, $tamanho, $modelo)
    {
        parent::__construct($cor, $tamanho, $modelo);
    }

    public function dobrar() 
    {
        echo 'Vira pra esquerda e vira pra direita';
    }

}