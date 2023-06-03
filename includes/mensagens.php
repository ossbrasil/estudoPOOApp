<?php if(isset($error)) : ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
    <strong><?php echo $error ?></strong> 
    </div>
<?php endif;
    if(isset($sucesso)) : 
?>
    
    <div class="alert alert-success alert-dismissible fade show" role="alert">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
    <strong><?php echo $sucesso ?></strong> 
    </div>

<?php endif;