<?php 
    include '../includes/header.php';
?>

    <div class="container-fluid">
        <table class="table mt-3">
        <thead>
            <tr>
                <th>id</th>
                <th>Forma de pagamento</th>
                <th>Nº Parcela</th>
                <th>Código do documento</th>
                <th>Valor</th>
                <th>Vencimento</th>
                <th>Status</th>
            </tr>
        </thead>
        <?php
            $pdo = new PDO('mysql:host=10.0.43.3;dbname=oss_poo', 'root', 'secret', ["SET NAMES utf8"]);
            $sql = "
                SELECT p.*, fp.tipo_pagamento
                    FROM parcelas AS p
                    JOIN forma_pagamentos AS fp ON fp.id = p.id_forma_pagamento
                    WHERE id_apolice = '$_GET[apolice]'
                    
            ";
            $preparacao  = $pdo->query($sql);
            $result = $preparacao->fetchAll(PDO::FETCH_ASSOC);
        ?>
        <tbody>
            <?php
                foreach($result as $parcelas) {
                    $data_vencimento = date('d/m/Y', strtotime($parcelas['data_vencimento']));
                    $valor_parcela = number_format($parcelas['valor_parcela'], 2, ',', '.');

                    if($parcelas['data_pagamento']) {
                        $status = 'Pago';
                    } else {
                        $data = strtotime('now');
                        $data_vencimento_verificacao = strtotime($parcelas['data_vencimento']);

                        if($data_vencimento_verificacao < $data) {
                            $status = 'Inadinplente';
                        } else {
                            $status = 'pendente';
                        }
                    }
            ?>
            <tr>
                <td><a href='/view/formulario_parcela.php?id=<?php echo $parcelas['id'] ?>'><?php echo $parcelas['id'] ?></td>
                <td><?php echo $parcelas['tipo_pagamento'] ?></td>
                <td><?php echo $parcelas['numero_parcela'] ?></td>
                <td><?php echo $parcelas['codigo_documento'] ?></td>
                <td><?php echo $valor_parcela ?></td>
                <td><?php echo $data_vencimento ?></td>
                <td><?php echo $status ?></td>
            </tr>
            <?php
                } 
            ?>
        </tbody>
        </table>
    </div>
    
<?php include '../includes/footer.php';





