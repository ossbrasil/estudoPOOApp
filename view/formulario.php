<?php
include '../includes/header.php';
include '../funcoes/apolice/funcoes.php';

$pdo = conexao();

if(count($_POST) > 0) {
    $valor_plano =  $_POST['valor_plano'];

    
    $_POST['valor_plano'] = formatar_decimal($valor_plano, 'computacional');
    
    $update = atualizarApolice($_GET['id'], $_POST);
    
    if($update === false) {
        $error = 'Não foi possível slavar os dados';
    } else {

        $sucesso = 'Apolice slava com sucesso';
    }
}

?>
<div class="container-fluid">
<?php
    include '../includes/mensagens.php';
    $apolice = listaDeApolices(['id' => $_GET['id']]);
    $valor_plano = formatar_decimal($apolice['valor_plano']) ;
?>

    <div class="row">
        <div class="col-12">
            <div class="row">
                <div class="col-3">
                    <strong>nome do titular: </strong><?php echo $apolice['titular'] ?>
                </div>
                <div class="col-3">
                    <strong>cpf do titular</strong> <?php echo $apolice['titular_cpf'] ?>
                </div>
                <div class="col-3">
                    <strong>numero da apolice</strong> <?php echo $apolice['id'] ?>
                </div>
                <div class="col-3">
                    <strong>status da apolice</strong> <?php echo $apolice['status'] ?>
                </div>
            </div>  
        </div>
        <div class="col-12">
            <a href="/view/parcelas.php?apolice=<?php echo $apolice['id'] ?>">Parcelas</a>
            <hr />
        </div>
        <div class="col-12">
            <form class="form-group" method="post" action="/view/formulario.php?id=<?php echo $_GET['id'] ?>">
                <div class='row'>
                <div class="col-3">
                    <label for="status">Status</label>
                    <?php 
                        
                        $sql2 = "SELECT * FROM status_apolice";

                        $preparacao2  = $pdo->query($sql2);
                        $result2 = $preparacao2->fetchAll(PDO::FETCH_ASSOC);
                    ?>
                    <select class="form-control" name="id_status">
                        <?php
                            foreach($result2 as $status) {

                                $selected = $status['id'] == $apolice['id_status'] ? 'selected' : ''
                        ?>
                            <option <?php echo  $selected ?> value="<?php echo  $status['id'] ?>"><?php echo $status['nome'] ?></option>
                        <?php
                            } 
                        ?>
                    </select>
                </div>
                <div class="col-3">
                    <label for="tipo_de_venda">Tipo de Venda</label>
                        <?php 
                            $sql3 = "SELECT * FROM tipo_vendas";
                            $preparacao3  = $pdo->query($sql3);
                            $result3 = $preparacao3->fetchAll(PDO::FETCH_ASSOC);
                        ?>
                    
                    <select  class="form-control" name="id_tipo_venda">
                        <?php
                            foreach($result3 as $tipo_venda) {

                                $selected = $tipo_venda['id'] == $apolice['id_tipo_venda'] ? 'selected' : ''
                        ?>
                            <option <?php echo  $selected ?> value="<?php echo  $tipo_venda['id'] ?>"><?php echo $tipo_venda['nome'] ?></option>
                        <?php
                            } 
                        ?>
                    </select>
                </div>
                <div class="col-3">
                    <label for="data_inicio">Data Inicio</label>
                    <input type="date" class="form-control" value="<?php echo $apolice['data_inicio'] ?>" name="data_inicio" id="" placeholder="">
                </div>
                <div class="col-3">
                    <label for="valor_plano">Valor Plano</label>
                    <input type="text" class="form-control" name="valor_plano" value="<?php echo $valor_plano ?>">
                </div>
                </div>
                <div class="col-12 mt-3">
                <button class="btn btn-success">Salvar</button>
                </div>
            </form>

        </div>
    </div>    
</div>
    
<?php include '../includes/footer.php';