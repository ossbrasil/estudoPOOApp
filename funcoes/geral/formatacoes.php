<?php

function formatar_decimal($numero, $tipo = 'brasileiro')
{
    if($tipo == 'computacional') {
        $retorno_formatado = str_replace('.', '', $numero);
        $retorno_formatado = str_replace(',', '.', $numero);
    } else {
        $retorno_formatado = number_format($numero, 2, ',', '.');
    }

    return $retorno_formatado;

}

function formatar_data($data = 'now', $tipo = 'brasileiro') 
{
    if($tipo == 'computacional') {
        $retorno_formatado = date('Y-m-d', strtotime($data));
    } else {
        $retorno_formatado = date('d/m/Y', strtotime($data));
    }

    return $retorno_formatado;
}