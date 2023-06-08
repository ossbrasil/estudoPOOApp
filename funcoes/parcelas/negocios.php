<?php

function limiteDeMesses($data)
{
    $hoje = new DateTime();
    $vencimento = new DateTime($data);
    
    $interval = $hoje->diff($vencimento);

    if($vencimento < $hoje and $interval->m > 3) {
        return false;
    }

    return true;

}