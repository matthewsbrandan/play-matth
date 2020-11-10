<div class="modal fade" id="modalNovoAlbum" tabindex="-1" role="dialog" aria-labelledby="nomeNovoAlbum" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="POST" action="back/back.php?novoAlbum">
                <div class="modal-header">
                    <h5 class="modal-title">Novo Álbum</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="form-group my-0">
                        <label class="p-0 m-0 small text-center d-block" for="nomeNovaMusica">Albúm:</label>
                        <input type="text" class="form-control" id="nomeNovoAlbum" name="nomeNovoAlbum" placeholder="Digite o Nome do Álbum..." required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-danger btn-block">Registrar</button>
                </div>
            </form>
        </div>
    </div>
</div>