<?php

include_once 'Conexao.php';
include_once 'QueryBuild.php';

class DaoApolice extends QueryBuild
{
    public function __construct()
    {

    }

    public function select()
    {
        $this->sql = "SELECT a.*, u.nome AS nome_usuario, sa.nome AS status, c.nome AS titular, c.cpf AS titular_cpf
            FROM apolices a
            JOIN usuarios AS u ON a.usuario_cadastro = u.id
            JOIN contemplados AS c ON c.id_apolice = a.id AND c.id_tipo_contemplado IN (1, 4)
            JOIN status_apolice AS sa ON sa.id = a.id_status
            ";
            return $this;
    }

    public function first()
    {
    }

    public function last()
    {
    }

    public function get()
    {
        $this->constructSql();
        
        Conexao::open();
        $apolices = Conexao::query($this->sql, $this->valores);
        
        if(count($apolices) == 0) {
            return null;
        }
        
        return $apolices;
    }
}