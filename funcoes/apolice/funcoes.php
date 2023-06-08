<?php

function listaDeApolices(Array $where = [])
{
    $sqlWhere = ' 1 ';
    
    if(count($where) > 0) {
        
        if(isset($where['id'])) {
            $sqlWhere .= "AND a.id = $where[id]";
        }
    }

    $sql = "
        SELECT a.*, u.nome AS nome_usuario, sa.nome AS status, c.nome AS titular, c.cpf AS titular_cpf
            FROM apolices AS a
            JOIN usuarios AS u ON a.usuario_cadastro = u.id
            JOIN contemplados AS c ON c.id_apolice = a.id AND c.id_tipo_contemplado IN (1, 4)
            JOIN status_apolice AS sa ON sa.id = a.id_status
            WHERE {$sqlWhere}
    ";
    

    $pdo = conexao();
    $preparacao  = $pdo->query($sql);
    $result = $preparacao->fetchAll(PDO::FETCH_ASSOC);
   
    return count($result) > 1 ? $result : $result[0];
}


function atualizarApolice(Int $id, Array $sets)
{
    
    foreach ($sets as $atributo => $valor) {
        $atributos[] = "$atributo = '$valor'";
    }
    
    $set = implode(', ', $atributos);
    
    $sql = "UPDATE apolices SET $set WHERE id='$id'";

    $pdo = conexao();
    return $pdo->exec($sql);
}