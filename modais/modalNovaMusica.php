<div class="modal fade" id="modalNovaMusica" tabindex="-1" role="dialog" aria-labelledby="nomeNovaMusica" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="POST" action="back/back.php?novaMusica">
                <div class="modal-header">
                    <h5 class="modal-title">Nova Música</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="form-group my-0">
                        <label class="p-0 m-0 small text-center d-block" for="nomeNovaMusica">Música:</label>
                        <input type="text" class="form-control" id="nomeNovaMusica" name="nomeNovaMusica" placeholder="Digite o Nome da Música" required>
                    </div>
                    <div class="form-group my-0">
                        <label class="p-0 m-0 small text-center d-block" for="albumNovaMusica">Albúm:</label>
                        <select class="custom-select" id="albumNovaMusica" name="albumNovaMusica">
                            <?php
                                $data = enviarComand("select * from album where album_user_id='{$_SESSION['user_mtworld']}';",'bd_pmatth');
                                while($res = $data->fetch_assoc()){
                            ?>
                            <option value="<?php echo $res['album_id']; ?>"><?php echo $res['album_nome']; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group my-0">
                        <label class="p-0 m-0 small text-center d-block" for="letraNovaMusica">Letra:</label>
                        <textarea class="form-control" id="letraNovaMusica" name="letraNovaMusica" rows="8" placeholder="Letra da Música..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-danger btn-block">Registrar</button>
                </div>
            </form>
        </div>

    </div>
</div>