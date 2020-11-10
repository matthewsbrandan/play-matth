<button type="button" class="d-none" data-toggle="modal" data-target="#modalGerenciar" id="modalGerenciarClick"></button>
<div class="modal fade" id="modalGerenciar" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Gerenciar</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <!--Nav-->
                <div class=" text-nowrap" style="overflow: auto;">
                    <ul class="nav nav-tabs flex-nowrap" id="myTab" role="tablist">
                        <li class="nav-item">
                        <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Música</a>
                        </li>
                        <li class="nav-item">
                        <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Albúm</a>
                        </li>
                        <li class="nav-item">
                        <a class="nav-link" id="general-tab" data-toggle="tab" href="#general" role="tab" aria-controls="general" aria-selected="false">Dados Gerais</a>
                        </li>
                        <li class="nav-item">
                        <a class="nav-link" id="diverge-tab" data-toggle="tab" href="#diverge" role="tab" aria-controls="diverge" aria-selected="false">Divergências</a>
                        </li>
                    </ul>
                </div>
                <div class="tab-content" id="myTabContent">
                    <!--Musica-->
                    <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                    <form method="POST" action="index.php?gerenciar" id="formGerenciarMusica">
                    <?php 
                        if(isset($_POST['fgmId'])){ 
                            $dt = enviarComand("select * from musica where musica_id='{$_POST['fgmId']}' ;",'bd_pmatth');
                            if($rs = $dt->fetch_assoc()){
                    ?>
                    <!--Música Selecionada-->
                    <div class="mb-0 text-center border bg-dark text-light rounded p-1">
                        <span class="material-icons px-1 align-middle pointer" title="Aúdio" data-toggle="collapse" href="#collapseAltAudio" role="button" aria-expanded="false" aria-controls="collapseAltAudio" onclick="if($(this).hasClass('text-danger')){ $(this).removeClass('text-danger'); }else{ $(this).addClass('text-danger'); }">settings_voice</span>
                        <span class="material-icons px-1 align-middle pointer" title="História" data-toggle="collapse" href="#collapseAltHistoria" role="button" aria-expanded="false" aria-controls="collapseAltHistoria" onclick="if($(this).hasClass('text-danger')){ $(this).removeClass('text-danger'); }else{ $(this).addClass('text-danger'); }">library_books</span>
                        <span class="material-icons px-1 align-middle pointer" title="Cifra" onclick="$('#modalCifraClick').click();">receipt</span>
                        <span class="material-icons px-1 align-middle pointer" title="Deletar" onclick="deletarMusica(<?php echo $_POST['fgmId']; ?>);">delete</span>
                        <input type="hidden" id="deleteMusica" name="deleteMusica" value="0">
                    </div>
                    <div class="form-group my-0">
                        <label class="p-0 m-0 small text-center d-block" for="altMusica">Música:</label>
                        <input type="text" class="form-control" id="altMusica" name="altMusica" value="<?php echo $rs['musica_nome']; ?>">
                    </div>
                    <div class="form-group my-0">
                        <label class="p-0 m-0 small text-center d-block" for="altAlbum">Albúm:</label>
                        <select class="custom-select" id="altAlbum" name="altAlbum">
                            <?php
                                $data = enviarComand("select * from album where album_user_id='{$_SESSION['user_mtworld']}';",'bd_pmatth');
                                while($res = $data->fetch_assoc()){
                            ?>
                            <option value="<?php echo $res['album_id']; ?>"><?php echo $res['album_nome']; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group my-1 collapse" id="collapseAltAudio">
                        <label class="p-0 m-0 small text-center d-block" for="altAudio">Aúdio:</label>
                        <div class="input-group">
                            <input type="text" class="form-control bg-light" id="altAudio" name="altAudio" value="<?php echo hiddenPath($rs['musica_audio']); ?>" readonly>
                            <div class="input-group-append"onclick="$('#modalAudMusicaClick').click();">
                                <span class="input-group-text material-icons">autorenew</span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group my-0 collapse" id="collapseAltHistoria">
                        <label class="p-0 m-0 small text-center d-block" for="altHistoria">História:</label>
                        <textarea class="form-control" id="altHistoria" name="altHistoria" rows="8"><?php echo $rs['musica_historia']; ?></textarea>
                    </div>
                    <div class="form-group my-0">
                        <label class="p-0 m-0 small text-center d-block" for="altLetra">Letra:</label>
                        <textarea class="form-control" id="altLetra" name="altLetra" rows="8"><?php echo $rs['musica_letra']; ?></textarea>
                    </div>
                    <div class="btn-group btn-block">
                        <button type="button" class="btn btn-danger btn-block btn-sm mt-3" onclick="selFormMusica(<?php echo $_POST['fgmId']; ?>);">Cancelar</button>
                        <button type="button" class="btn btn-primary btn-block btn-sm mt-3" onclick="finalizaMusica(<?php echo $_POST['fgmId']; ?>)">Finalizar</button>
                    </div>
                    <!--Selecionar a Música-->
                    <?php }} else{ ?>
                    <div class="input-group mt-3 mb-2">
                        <select class="custom-select" id="gerenciarMusica">
                            <?php
                                $data = enviarComand("select musica_id,musica_nome from musica where musica_user_id='{$_SESSION['user_mtworld']}' order by musica_nome;",'bd_pmatth');
                                while($res = $data->fetch_assoc()){
                            ?>
                            <option value="<?php echo $res['musica_id']; ?>"><?php echo $res['musica_nome']; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <button type="button" class="btn btn-danger btn-block btn-sm" onclick="alterarMusica($('#gerenciarMusica').val());">Alterar</button>
                    <input type="hidden" id="fgmId" name="fgmId">
                    <?php } ?>
                    </form>
                    </div>
                    <!--Album-->
                    <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                        <form method="POST" action="index.php?gerenciar" id="formGerenciarAlbum">
                        <?php
                            if(isset($_POST['fgaId'])){ 
                            $dt = enviarComand("select * from album where album_id='{$_POST['fgaId']}' and album_user_id='{$_SESSION['user_mtworld']}';",'bd_pmatth');
                            if($rs = $dt->fetch_assoc()){
                        ?>
                        <!--Album Selecionado-->
                        <div class="mb-0 text-center border bg-dark text-light rounded p-1">
                            <span class="material-icons px-1 align-middle pointer" title="Alterar Imagem" onclick="$('#modalCapaAlbumClick').click();">collections</span>
                            <span class="material-icons px-1 align-middle pointer" title="Deletar" onclick="deletarAlbum(<?php echo $_POST['fgaId']; ?>);">delete</span>
                            <input type="hidden" id="deleteAlbum" name="deleteAlbum" value="0">
                        </div>
                        <div class="form-group">
                            <label class="p-0 m-0 small text-center d-block" for="altMusica">Albúm:</label>
                            <input type="text" class="form-control" id="altAlbumNome" name="altAlbumNome" value="<?php echo $rs['album_nome']; ?>">
                        </div>
                        <figure class="figure">
                            <img src="img/<?php echo $rs['album_img']; ?>" class="figure-img img-fluid rounded" alt="..." id="altAlbumCapaImg">
                            <figcaption class="figure-caption" id="altAlbumCapaCaption">
                            <?php echo hiddenPath($rs['album_img']); ?>
                            </figcaption>
                            <input type="hidden" id="altAlbumCapa" name="altAlbumCapa" value="<?php echo $rs['album_img']; ?>">
                        </figure>
                        <div class="btn-group btn-block">
                            <button type="button" class="btn btn-danger btn-block btn-sm mt-3" onclick="selAlbum(<?php echo $_POST['fgaId']; ?>);">Cancelar</button>
                            <button type="button" class="btn btn-primary btn-block btn-sm mt-3" onclick="finalizaAlbum(<?php echo $_POST['fgaId']; ?>)">Finalizar</button>
                        </div>
                        <!--Selecionar Album-->
                        <?php }} else{ ?>
                        <div class="input-group mt-3 mb-2">
                            <select class="custom-select" id="gerenciarAlbum" name="gerenciarAlbum">
                                <?php
                                    $data = enviarComand("select album_id,album_nome from album where album_user_id='{$_SESSION['user_mtworld']}' order by album_nome;",'bd_pmatth');
                                    while($res = $data->fetch_assoc()){
                                ?>
                                <option value="<?php echo $res['album_id']; ?>"><?php echo $res['album_nome']; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <button type="button" class="btn btn-danger btn-block btn-sm" onclick="alterarAlbum($('#gerenciarAlbum').val());">Alterar</button>
                        <input type="hidden" id="fgaId" name="fgaId">
                        <?php } ?>
                        </form>
                    </div>
                    <!--Geral-->
                    <div class="tab-pane fade" id="general" role="tabpanel" aria-labelledby="general-tab">
                        <!--Geral/Albúm-->
                        <button type="button" class="btn btn-sm btn-block btn-dark mt-2" data-toggle="collapse" data-target="#collapseAlbum" aria-expanded="false" aria-controls="collapseAlbum">Albúm</button>
                        <div class="collapse" id="collapseAlbum">
                            <ul class="list-group mt-1" style="max-height: 340px; overflow: auto;">
                        <?php
                            $d = enviarComand("select * from album where album_user_id='{$_SESSION['user_mtworld']}';",'bd_pmatth');
                            while($r = $d->fetch_assoc()){
                        ?>
                            <li class="list-group-item py-1 pointer" onclick="alterarAlbum(<?php echo $r['album_id']; ?>)">
                            <?php 
                                if($f = enviarComand("select count(*) qtd from musica where musica_album_id={$r['album_id']} and musica_user_id='{$_SESSION['user_mtworld']}';","bd_pmatth")->fetch_assoc()){
                                    echo " <span class='badge badge-danger mr-2'>".$f['qtd']."</span>"; 
                                }
                                echo $r['album_nome'];
                            ?>
                                
                            </li>
                        <?php } ?>
                            </ul>
                        </div>
                        <!--Geral/Música-->
                        <button type="button" class="btn btn-sm btn-block btn-danger mt-2" data-toggle="collapse" data-target="#collapseMusica" aria-expanded="false" aria-controls="collapseMusica">Música</button>
                        <div class="collapse" id="collapseMusica">
                            <ul class="list-group mt-1" style="max-height: 340px; overflow: auto;">
                        <?php
                            $d = enviarComand("select * from musica where musica_user_id='{$_SESSION['user_mtworld']}';",'bd_pmatth');
                            while($r = $d->fetch_assoc()){
                        ?>
                            <li class="list-group-item py-1 pointer" onclick="alterarMusica(<?php echo $r['musica_id']; ?>)">
                            <?php 
                                echo "<span class='material-icons align-middle".(strlen($r['musica_audio'])>0?" text-info' title='Aúdio'>mic":"' title='Aúdio'>mic_off")."</span>";
                                echo "<span class='material-icons align-middle mr-2".(strlen($r['musica_cifra'])>0?" text-cifra' title='Cifra'>receipt":"' title='Cifra'>cancel_presentation")."</span>";
                                echo "<span class='material-icons align-middle mr-2".(strlen($r['musica_historia'])>0?" text-danger' title='História'>library_books":"' title='História'>backspace")."</span>";
                                echo $r['musica_nome'];
                            ?>
                            </li>
                        <?php } ?>
                            </ul>
                        </div>
                    </div>
                    <!--Divergências-->
                    <div class="tab-pane fade" id="diverge" role="tabpanel" aria-labelledby="diverge-tab">
                        <ul class="list-group mt-2">
                            <li class="list-group-item bg-dark text-light text-center text-truncate" data-toggle="collapse" href="#ancnd" role="button" aria-expanded="false" aria-controls="ancnd">
                                <span class='badge badge-danger mr-2' id="badgeAncnd"></span>Aúdios não Cadastrados no Diretório
                            </li>
                            <div class="collapse" id="ancnd">
                            <?php
                            $d = enviarComand("select musica_id,musica_nome,musica_audio from musica where musica_user_id='{$_SESSION['user_mtworld']}';",'bd_pmatth');
                            $count = 0;
                            while($r = $d->fetch_assoc()){
                                if(!(file_exists('audio/'.$r['musica_audio']))){
                                    echo "<li class='list-group-item text-truncate' onclick='alterarMusica(".$r['musica_id'].")'>".$r['musica_nome']." > ".$r['musica_audio']."</li>"; $count++; 
                                }
                            }
                            if($count>0){
                            ?><script>$(function(){ $('#badgeAncnd').html('<?php echo $count;?>'); });</script><?php } ?>
                            </div>
                        </ul>
                        <ul class="list-group mt-2">
                            <li class="list-group-item bg-dark text-light text-center text-truncate" data-toggle="collapse" href="#incnd" role="button" aria-expanded="false" aria-controls="incnd">
                                <span class='badge badge-danger mr-2' id="badgeIncnd"></span>Imagens não Cadastrados no Diretório
                            </li>
                            <div class="collapse" id="incnd">
                            <?php
                            $d = enviarComand("select album_img from album where album_user_id='{$_SESSION['user_mtworld']}';",'bd_pmatth');
                            $count = 0;
                            while($r = $d->fetch_assoc()){
                                if(!(file_exists('img/'.$r['album_img']))){
                                    echo "<li class='list-group-item'>".$r['album_img']."</li>"; $count++; 
                                }
                            }
                            if($count>0){
                            ?><script>$(function(){ $('#badgeIncnd').html('<?php echo $count;?>'); });</script><?php } ?>
                            </div>
                        </ul>
                        <ul class="list-group mt-2">
                            <li class="list-group-item bg-info text-light text-center">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="text-truncate">
                                        Aúdios no Diretório
                                        </div>
                                        <span class="d-block badge badge-light"><?php echo count(scandir("audio/"))-2; ?></span>
                                    </div>
                                    <div class="col-6">
                                        <div class="text-truncate">
                                        Aúdios no Banco de Dados
                                        </div>
                                        <span class="d-block badge badge-light"><?php echo enviarComand("select count(*) qtd from musica where musica_audio!='' and musica_user_id='{$_SESSION['user_mtworld']}';",'bd_pmatth')->fetch_assoc()['qtd']; ?></span>
                                    </div>
                                </div>
                            </li>
                        </ul>
                        <ul class="list-group mt-2">
                            <li class="list-group-item bg-cifra text-light text-center">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="text-truncate">
                                        Imagens no Diretório
                                        </div>
                                        <span class="d-block badge badge-light"><?php echo count(scandir("img/"))-2; ?></span>
                                    </div>
                                    <div class="col-6">
                                        <div class="text-truncate">
                                        Imagens no Banco de Dados
                                        </div>
                                        <span class="d-block badge badge-light"><?php echo enviarComand("select count(*) qtd from album where album_img!='' and album_user_id='{$_SESSION['user_mtworld']}';",'bd_pmatth')->fetch_assoc()['qtd']; ?></span>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>