<?php
include '../includes/header.php';
include '../funcoes/parcelas/negocios.php';
$pdo = conexao();

if(count($_POST) > 0) {
    

    if(isset($_POST['salvar'])) {

        if(!limiteDeMesses($_POST['data_vencimento_comparacao'])) {
            $error = 'Seu pagamento não pode ser negociado. Venciemnto maior que 3 Meses';
        } else {
            $sql = "UPDATE parcelas 
                SET data_vencimento='$_POST[data_vencimento]'
                WHERE id='$_GET[id]'
                ";
            $update = $pdo->exec($sql);
    
            if($update === false) {
                $error = 'Erro ao tentar alterar vencimento da parcela';
            } else {
                $sucesso = 'Parcela alterada com sucesso';
            }
        }

    } else if (isset($_POST['realizar_pagamento'])) {

        $dentro_do_limite = limiteDeMesses($_POST['data_vencimento']);

        if(!$dentro_do_limite) {
            $error = 'Seu pagamento não pode ser negociado. Venciemnto maior que 3 Meses';
        } else {
            $sql = "UPDATE parcelas 
                SET data_pagamento='$_POST[data_pagamento]', valor_pagamento='$_POST[valor_pagamento]'
                WHERE id='$_GET[id]'
                ";
            $update = $pdo->exec($sql);

            if($update === false) {
                $error = 'Erro ao tentar realizar pagamento da parcela';
            } else {
                $sucesso = 'Parcela alterada com sucesso';
            }

        }
    }
}

?>

<div class="container-fluid">

    <?php
        include '../includes/mensagens.php';

        $sql = "
            SELECT p.*
                FROM parcelas AS p
                WHERE id = '$_GET[id]'  
        ";
        $preparacao  = $pdo->query($sql);
        $result = $preparacao->fetch(PDO::FETCH_ASSOC);

        $data_vencimento = formatar_data($result['data_vencimento']);
        $valor_parcela = formatar_decimal($result['valor_parcela']);

        if($result['data_pagamento']) {
            $status = 'Pago';
        } else {
            $data = strtotime('now');
            $data_vencimento_verificacao = strtotime($result['data_vencimento']);

            if($data_vencimento_verificacao < $data) {
                $status = 'Inadinplente';
            } else {
                $status = 'pendente';
            }
        }

        $data_pagamento =  $result['data_pagamento'] ? formatar_data($result['data_pagamento']) : '-';

        $disable_form = $status == 'Pago' ? 'disabled' : '';
    ?>

    <div class="row">
        <div class="col-12">
            <div class="row">
                <div class="col-3">
                    <strong>Status: </strong> <?php echo $status ?>
                </div>
                <div class="col-3">
                    <strong>Data de pagamento</strong> <?php echo $data_pagamento ?>
                </div>
                <div class="col-3">
                    <strong>Valor Pagamento</strong> <?php echo formatar_decimal($result['valor_pagamento']) ?>
                </div>
            </div>  
        </div>
        <div class="col-12">
            <hr>
        </div>

        <div class="col-12">
            <form class="form-group" method="post" action="/view/formulario_parcela.php?id=<?php echo $_GET['id'] ?>">
                <div class='row'>
                <div class="col-3">
                    <label for="data_vencimento">Data Vencimento</label>
                    <input <?php echo $disable_form ?> type="date" class="form-control" value="<?php echo $result['data_vencimento'] ?>" name="data_vencimento" id="" placeholder="">
                    <input type="hidden" value="<?php echo $result['data_vencimento'] ?>" name="data_vencimento_comparacao">
                </div>
                </div>
                <div class="col-12 mt-3">
                <button <?php echo $disable_form ?> class="btn btn-success" name="salvar">Salvar</button>
                </div>
            </form>
        </div>

        <div class="col-12">
            <form  <?php echo $disable_form ?> class="form-group" method="post" action="/view/formulario_parcela.php?id=<?php echo $_GET['id'] ?>">
                <div class='row'>
                <div class="col-3">
                    <label for="data_pagamento">Data Pagemmnto</label>
                    <input <?php echo $disable_form ?> type="date" class="form-control" value="" name="data_pagamento" id="" placeholder="">
                    <input type='hidden' value="<?php echo $result['data_vencimento'] ?>" name="data_vencimento">
                </div>
                <div class="col-3">
                    <label for="valor_pagamento">Valor Pagamento</label>
                    <input <?php echo $disable_form ?> type="text" class="form-control" name="valor_pagamento" value="">
                </div>
                </div>
                <div class="col-12 mt-3">
                <button <?php echo $disable_form ?> class="btn btn-success" name="realizar_pagamento">Realizar Pagamento</button>
                </div>
            </form>

        </div>
    </div>    
</div>

<?php include '../includes/footer.php';