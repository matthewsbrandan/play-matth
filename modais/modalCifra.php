<button type="button" class="d-none" id="modalCifraClick" data-toggle="modal" data-target="#modalCifra"></button>
<div class="modal fade" tabindex="-1" role="dialog" id="modalCifra" aria-labelledby="#">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-cifra">Cifra <span class="text-dark">&#9835;</span></h5>
                <button type="button" class="close" aria-label="Close" data-dismiss="modal"> <span aria-hidden="true">&times;</span> </button>
            </div>
            <div class="modal-body pt-2">
                <form method="POST" action="back/back.php?cifra">
                    <?php
                        $d = enviarComand("select * from musica inner join album on musica_album_id=album_id where musica_id='{$_POST['fgmId']}' and musica_user_id='{$_SESSION['user_mtworld']}';",'bd_pmatth');
                        if($r = $d->fetch_assoc()){ 
                            if(strlen($r['musica_cifra'])>0){ $arrCifra = explode('<br/>',$r['musica_cifra']); }
                    ?>
                    <input type="hidden" id="cifraId" name="cifraId" value="<?php echo $r['musica_id']; ?>">
                    <h5><?php echo $r['musica_nome']; ?> | <small class="text-cifra-bold"><?php echo $r['album_nome']; ?></small></h5>
                    <div class="p-1 border rounded" style="max-height: 480px; overflow: auto;">
                        <input type="text" class="form-control pl-0 border-top-0 border-left-0 border-right-0 rounded-0 text-cifra-bold" id="cLinha0" name="cLinha0" value="<?php echo isset($arrCifra)?$arrCifra[0]:''; ?>"/>
                        <label class="text-center d-block small" for="cLinha0">Introdução &uarr;</label>
                        <?php 
                            $fracionado = explode("\n",nl2br($r['musica_letra']));
                            $linhas = 0;
                            foreach($fracionado as $value){ $linhas++;
                        ?>
                        <input type="text" class="form-control pl-0 border-top-0 border-left-0 border-right-0 rounded-0 text-cifra-bold<?php echo strlen(trim(str_replace('<br />','',$value)))==0?" d-none":"";?>" id="cLinha<?php echo $linhas; ?>" name="cLinha<?php echo $linhas; ?>" value="<?php echo isset($arrCifra)?$arrCifra[$linhas]:''; ?>"/>
                        <?php echo $value; } ?>
                    </div>
                    <?php } ?>
                    <button class="btn btn-block btn-success text-light mt-3">Salvar</button>
                </form>
            </div>
        </div>
    </div>
</div>