<?php
abstract class Roupa 
{
    public $cor;
    
    public $tamanho;
    
    public $modelo;

    public function __construct($cor, $tamanho, $modelo)
    {
        $this->cor = $cor;
        $this->tamanho = $tamanho;
        $this->modelo = $modelo;
    }

    public function desabotoar()
    {
        if($this->modelo == 'Polo' or $this->modelo == 'Social' or $this->modelo == 'Social') {
            echo 'ploc ploc';
            return;
        }

        echo 'Não tem botão seu imbecil';
    }

    abstract public function dobrar();
}