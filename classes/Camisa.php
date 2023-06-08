<?php
include_once 'Roupa.php';

class Camisa extends Roupa
{

    public function __construct($cor, $tamanho, $modelo)
    {
        parent::__construct($cor, $tamanho, $modelo);
    }

    public function ageitarAGola()
    {
        if($this->modelo == 'Polo' or $this->modelo == 'Social') {
            echo 'Ta lindo';
            return;
        }

        echo 'Não tem gola seu imbecil';
    }

    public function dobrar() 
    {
        echo 'Vira pra baixo e vira pra cima';
    }
}