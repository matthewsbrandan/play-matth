<button type="button" class="d-none" data-toggle="modal" data-target="#modalCapaAlbum" id="modalCapaAlbumClick"></button>
<div class="modal fade" id="modalCapaAlbum" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content bg-dark">
            <form method="POST" action="">
                <div class="modal-header bg-light text-dark">
                    <h5 class="modal-title">Capa do Albúm</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body bg-dark">
                    <div class="alert alert-info" role="alert">
                        Selecione a imagem para a capa do Albúm. O Formato padrão é de <a class="alert-link">350x204</a> pixels.
                    </div>
                    <div class="text-center">
                    <?php
                        $path = userPath("img/");
                        $dirCapa = dir($path);
                        $enDir = 0;
                        while($imgCapa = $dirCapa->read()){ $enDir++; 
                            if($imgCapa!='.'&&$imgCapa!='..'){                                
                                $wh = explode("\"",getimagesize(userPath('img/').$imgCapa)[3]);
                    ?>
                        <figure 
                            class="figure bg-light text-center rounded d-inline-block pointer" 
                            style="max-width: 150px;" 
                            onclick="selImg('<?php echo $imgCapa; ?>')"
                        >
                            <figcapition 
                                class="figcaption" 
                            ><span class="px-1 d-block text-truncate"><?php echo $imgCapa; ?></span> </figcapition>
                            <img 
                                src='<?php echo userPath('img/').$imgCapa; ?>'
                                title='<?php echo $imgCapa; ?>'
                                class='figure-img img-fluid rounded my-0 shadow'
                            >
                            <figcapition class='figcaption'><?php echo $wh[1]."x".$wh[3]; ?></figcapition>
                        </figure>
                    <?php
                            }
                        }
                    ?>
                    </div>
                </div>
            </form>
            <div class="modal-footer d-block">
                <button class="btn btn-info btn-block btn-sm mb-2" type="button" data-toggle="collapse" data-target="#uploadCapaAlbumCollapse" aria-expanded="false" aria-controls="uploadCapaAlbumCollapse">Novo</button>
                <div class="collapse" id="uploadCapaAlbumCollapse">
                    <form method="POST" action="back/back.php?file=alb" enctype="multipart/form-data">
                        <div class="custom-file mb-2">
                            <input type="file" accept="image/*" class="custom-file-input" id="uploadCapaAlbum" name="uploadCapaAlbum" title="Procurar" required>
                            <label class="custom-file-label" for="uploadCapaAlbum" data-browse="Procurar">Upload do Aúdio:</label>
                        </div>
                        <input type="hidden" id="albEmAlt" name="albEmAlt" value="<?php echo isset($_POST['fgaId'])?$_POST['fgaId']:0; ?>">
                        <button type="submit" class="btn btn-sm btn-danger btn-block">UPLOAD</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>