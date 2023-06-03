<?php include '../includes/header.php' ?>

<div class="container-fluid">
    <table class="table mt-3">
    <thead>
        <tr>
            <th>Numero da apolice</th>
            <th>Status da Apolice</th>
            <th>Usuario Cadastrado</th>
            <th>Data inicial</th>
        </tr>
    </thead>
    <?php
        $pdo = new PDO('mysql:host=10.0.43.3;dbname=oss_poo', 'root', 'secret');
        $sql = '
            SELECT a.*, u.nome AS nome_usuario, sa.nome AS status 
                FROM apolices AS a
                JOIN usuarios AS u ON a.usuario_cadastro = u.id
                JOIN status_apolice AS sa ON sa.id = a.id_status
        ';
        $preparacao  = $pdo->query($sql);
        $result = $preparacao->fetchAll(PDO::FETCH_ASSOC);
    ?>
    <tbody>
        <?php
            foreach($result as $apolice) {
                $data_inicio = date('d/m/Y', strtotime($apolice['data_inicio']));
                    
        ?>
        <tr>
            <td><a href='/view/formulario.php?id=<?php echo $apolice['id'] ?>'><?php echo $apolice['id'] ?></td>
            <td><?php echo $apolice['status'] ?></td>
            <td><?php echo $apolice['nome_usuario'] ?></td>
            <td><?php echo $data_inicio?></td>
        </tr>
        <?php
            } 
        ?>
    </tbody>
    </table>
</div>

<?php include '../includes/footer.php' ?>






