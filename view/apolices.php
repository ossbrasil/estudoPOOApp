<?php include '../includes/header.php' ?>
<?php include '../funcoes/apolice/funcoes.php' ?>

<div class="container-fluid">
    <table class="table mt-3">
    <thead>
        <tr>
            <th>Numero da apolice</th>
            <th>Status da Apolice</th>
            <th>Usuario Cadastrado</th>
            <th>Titular</th>
            <th>Data inicial</th>
        </tr>
    </thead>
    <?php $result = listaDeApolices(); ?>
    <tbody>
        <?php 
            foreach($result as $apolice) :
                $data_inicio = date('d/m/Y', strtotime($apolice['data_inicio']));      
        ?>
        <tr>
            <td><a href='/view/formulario.php?id=<?php echo $apolice['id'] ?>'><?php echo $apolice['id'] ?></td>
            <td><?php echo $apolice['status'] ?></td>
            <td><?php echo $apolice['nome_usuario'] ?></td>
            <td><?php echo $apolice['titular'] ?></td>
            <td><?php echo $data_inicio ?></td>
        </tr>
        <?php
            endforeach;
        ?>
    </tbody>
    </table>
</div>

<?php include '../includes/footer.php' ?>






