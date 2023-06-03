<?php
// var_dump($_POST);
if(count($_POST) > 0) {
    $valor_plano =  $_POST['valor_plano'];

    $valor_plano = str_replace('.', '', $valor_plano);
    $valor_plano = str_replace(',', '.', $valor_plano);
    

    $pdo = new PDO('mysql:host=10.0.43.3;dbname=oss_poo', 'root', 'secret', [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    $sql = "UPDATE apolices 
        SET id_tipo_venda='$_POST[tipo_de_venda]', id_status='$_POST[status]', data_inicio='$_POST[data_inicio]', valor_plano='$valor_plano'
        WHERE id='$_GET[id]'
        ";
    $update = $pdo->exec($sql);
    
    if($update === false) {
        $error = 'Não foi possível slavar os dados';
    } else {

        $sucesso = 'Apolice slava com sucesso';
    }
}


include '../includes/header.php';

?>
<div class="container-fluid">
<?php
    include '../includes/mensagens.php';
    $pdo = new PDO('mysql:host=10.0.43.3;dbname=oss_poo', 'root', 'secret');
    $sql = "
        SELECT a.*, c.nome AS nome_contemplado, c.cpf, sa.nome AS status 
            FROM apolices AS a
            JOIN contemplados AS c ON c.id_apolice = a.id AND c.id_tipo_contemplado IN (1, 4)
            JOIN status_apolice AS sa ON sa.id = a.id_status
            WHERE a.id = '$_GET[id]' 
    ";
    $preparacao  = $pdo->query($sql);
    $result = $preparacao->fetch(PDO::FETCH_ASSOC);
    $valor_plano = number_format($result['valor_plano'], 2, ',', '.') ;
    // var_dump($result);
?>

    <div class="row">
        <div class="col-12">
            <div class="row">
                <div class="col-3">
                    <strong>nome do titular: </strong><?php echo $result['nome_contemplado'] ?>
                </div>
                <div class="col-3">
                    <strong>cpf do titular</strong> <?php echo $result['cpf'] ?>
                </div>
                <div class="col-3">
                    <strong>numero da apolice</strong> <?php echo $result['id'] ?>
                </div>
                <div class="col-3">
                    <strong>status da apolice</strong> <?php echo $result['status'] ?>
                </div>
            </div>  
        </div>
        <div class="col-12">
            <a href="/view/parcelas.php?apolice=<?php echo $result['id'] ?>">Parcelas</a>
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
                    <select class="form-control" name="status">
                        <?php
                            foreach($result2 as $status) {

                                $selected = $status['id'] == $result['id_status'] ? 'selected' : ''
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
                    
                    <select  class="form-control" name="tipo_de_venda">
                        <?php
                            foreach($result3 as $tipo_venda) {

                                $selected = $tipo_venda['id'] == $result['id_tipo_venda'] ? 'selected' : ''
                        ?>
                            <option <?php echo  $selected ?> value="<?php echo  $tipo_venda['id'] ?>"><?php echo $tipo_venda['nome'] ?></option>
                        <?php
                            } 
                        ?>
                    </select>
                </div>
                <div class="col-3">
                    <label for="data_inicio">Data Inicio</label>
                    <input type="date" class="form-control" value="<?php echo $result['data_inicio'] ?>" name="data_inicio" id="" placeholder="">
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