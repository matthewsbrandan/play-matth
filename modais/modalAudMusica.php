<button type="button" class="d-none" data-toggle="modal" data-target="#modalAudMusica" id="modalAudMusicaClick"></button>
<div class="modal fade" id="modalAudMusica" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content bg-dark text-light">
            <div class="modal-header">
                <h5 class="modal-title">Música</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body bg-dark" style='max-height: 420px; overflow: auto;'>
                <div class="alert alert-info text-center" role="alert">Selecione o Aúdio para esta Música.</div>
                <ul class="list-group text-dark">
                    <li class="list-group-item bg-danger text-light font-weight-bold text-center pointer" onclick="selAudio('')">Remover Aúdio</li>
                <?php
                    $path = userPath("audio/");

                    $dirAudio = dir($path);
                    $enDir = 0;
                    while($audio = $dirAudio->read()){ $enDir++;  if($audio!='.'&&$audio!='..'){
                ?>
                    <li class="list-group-item">
                        <div class="row">
                            <div class="col-10 text-truncate" title="<?php echo $audio; ?>">
                                <div onclick="selAudio('<?php echo $audio; ?>')"><?php echo $audio; ?></div>
                                <div class="collapse" id="collapseRenameAud<?php echo $enDir; ?>">
                                    <div class="input-group mt-1">
                                        <input type="text" class="form-control" placeholder="Digite o novo nome com a Extensão..." value="<?php echo $audio; ?>" id="newname<?php echo $enDir; ?>">
                                        <div class="input-group-append">
                                            <span class="input-group-text bg-dark btn-dark text-light" onclick="formDir('renameAud',['<?php echo $audio; ?>',$('#newname<?php echo $enDir; ?>').val()])">Renomear</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-2 text-right pr-1 dropdown-left">
                                <span class="material-icons" id="dropMaisAud" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">more_vert</span>
                                <div class="dropdown-menu" aria-labelledby="dropMaisAud">
                                    <a class="dropdown-item" onclick="reproduzirEmDir('<?php echo userPath().$audio; ?>');">Reproduzir</a>
                                    <a class="dropdown-item" data-toggle="collapse" href="#collapseRenameAud<?php echo $enDir; ?>" role="button" aria-expanded="false" aria-controls="collapseRenameAud<?php echo $enDir; ?>">Renomear</a>
                                    <a class="dropdown-item" onclick="formDir('deleteAud','<?php echo $audio; ?>')">Excluir</a>
                                </div>
                            </div>
                        </div>
                    </li>
                <?php }} if($enDir<=2) echo "<p class='text-danger text-center p-1 border rounded-bottom' style='background: rgba(255,255,255,.85);'>Não existem Aúdios cadastrados. Faça o Upload!</p>"; ?>
                </ul>
            </div>                    
            <div class="modal-footer d-block">
                <button class="btn btn-info btn-block btn-sm mb-2" type="button" data-toggle="collapse" data-target="#uploadAudioCollapse" aria-expanded="false" aria-controls="uploadAudioCollapse">Novo</button>
                <div class="collapse" id="uploadAudioCollapse">
                    <form method="POST" action="back/back.php?file=mus" enctype="multipart/form-data">
                        <div class="custom-file mb-2">
                            <input type="file" accept="audio/*" class="custom-file-input" id="uploadAudio" name="uploadAudio" title="Procurar" required>
                            <label class="custom-file-label" for="uploadAudio" data-browse="Procurar">Upload do Aúdio:</label>
                        </div>
                        <input type="hidden" id="musEmAlt" name="musEmAlt" value="<?php echo isset($_POST['fgmId'])?$_POST['fgmId']:0; ?>">
                        <button type="submit" class="btn btn-sm btn-danger btn-block">UPLOAD</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>