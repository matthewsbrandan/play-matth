<?php session_start();
if(isset($_SESSION['user_mtworld'])&&$_SESSION['user_mtworld']>0){
    include('../../conn/function.php');
    
    function userPath($file,$folder=''){
        $path = $folder.($_SESSION['user_mtworld']+1000).'/';
        
        if($folder!=''){ if(!is_dir($path)) mkdir($path); }
        
        return $path.$file;
    }

    if(isset($_GET['novaMusica'])){
        if(isset($_POST['nomeNovaMusica'])&&isset($_POST['albumNovaMusica'])&&isset($_POST['letraNovaMusica'])){
            //Begin Slashes
            $_POST['nomeNovaMusica'] = addslashes($_POST['nomeNovaMusica']);
            $_POST['albumNovaMusica'] = addslashes($_POST['albumNovaMusica']);
            $_POST['letraNovaMusica'] = addslashes($_POST['letraNovaMusica']);
            //End Slaches
            $sql = "insert into musica(musica_nome,musica_album_id,musica_letra,musica_user_id) values ('{$_POST['nomeNovaMusica']}','{$_POST['albumNovaMusica']}','{$_POST['letraNovaMusica']}','{$_SESSION['user_mtworld']}');";
            if(enviarComand($sql,'bd_pmatth')){
                $sql = "select musica_id from musica where musica_nome='{$_POST['nomeNovaMusica']}' and musica_album_id='{$_POST['albumNovaMusica']}' and musica_letra='{$_POST['letraNovaMusica']}' and musica_user_id='{$_SESSION['user_mtworld']}' order by musica_id desc limit 1;";
                $d = enviarComand($sql,'bd_pmatth');
                if($f = $d->fetch_assoc()){ header('Location: ../index.php?nMus='.$f['musica_id']); }
            }else{ header('Location: ../index.php?error=0'); }
        }
    }else
    if(isset($_GET['novoAlbum'])){
        if(isset($_POST['nomeNovoAlbum'])){
            //Begin Slashes
            $_POST['nomeNovoAlbum'] = addslashes($_POST['nomeNovoAlbum']);
            //End Slashes
            $sql = "insert into album(album_nome,album_img,album_user_id) values ('{$_POST['nomeNovoAlbum']}','peq-escuro.png','{$_SESSION['user_mtworld']}');";
            if(enviarComand($sql,'bd_pmatth')){
                $sql = "select album_id from album where album_nome='{$_POST['nomeNovoAlbum']}' and album_user_id='{$_SESSION['user_mtworld']}' order by album_id desc limit 1;";
                $d = enviarComand($sql,'bd_pmatth');
                if($f = $d->fetch_assoc()){ header('Location: ../index.php?nAlb='.$f['album_id']); }
            }else{ header('Location: ../index.php?error=1'); }
        }
    }else
    if(isset($_GET['delAlb'])){
        $sql="select count(*) qtd from musica where musica_album_id='{$_POST['deleteAlbum']}' and musica_user_id='{$_SESSION['user_mtworld']}';";
        $d = enviarComand($sql,'bd_pmatth');
        if($d->fetch_assoc()['qtd']==0){
            $sql="delete from album where album_id='{$_POST['deleteAlbum']}' and album_user_id='{$_SESSION['user_mtworld']}';";
            if(enviarComand($sql,'bd_pmatth')){ header('Location: ../index.php?dAlb'); }
        }else{ header('Location: ../index.php?error=2'); }
    }else
    if(isset($_GET['delMus'])){
        $sql="delete from musica where musica_id='{$_POST['deleteMusica']}' and musica_user_id='{$_SESSION['user_mtworld']}';";
        if(enviarComand($sql,'bd_pmatth')){ header('Location: ../index.php?dMus'); }
        else{ header('Location: ../index.php?error=3'); }
    }else
    if(isset($_GET['updateMus'])){
        if(isset($_POST['altId'])&&isset($_POST['altMusica'])&&isset($_POST['altAlbum'])&&isset($_POST['altLetra'])){
            $_POST['altMusica'] = addslashes($_POST['altMusica']);
            $_POST['altLetra'] = addslashes($_POST['altLetra']);

            $comp = "";
            if(isset($_POST['altAudio'])&&!empty($_PSOT['altAudio'])){
                $_POST['altAudio'] = userPath($_POST['altAudio']);
                $comp .= ", musica_audio='{$_POST['altAudio']}'";
            }
            if(isset($_POST['altHistoria'])){
                $_POST['altHistoria'] = addslashes($_POST['altHistoria']);
                $comp .= ", musica_historia='{$_POST['altHistoria']}'";
            }

            $sql = "update musica set musica_nome='{$_POST['altMusica']}', musica_album_id='{$_POST['altAlbum']}', musica_letra='{$_POST['altLetra']}'$comp where musica_id='{$_POST['altId']}' and musica_user_id='{$_SESSION['user_mtworld']}';";
            
            if(enviarComand($sql,'bd_pmatth')){ header('Location: ../index.php?uMus='.$_POST['altId']); }
            else header('Location: ../index.php?error=4');
        }else header('Location: ../index.php?error=5');
    }else
    if(isset($_GET['updateAlb'])){
        if(isset($_POST['altId'])&&isset($_POST['altAlbumNome'])&&isset($_POST['altAlbumCapa'])){
            //Begin Slashes
            $_POST['altAlbumNome'] = addslashes($_POST['altAlbumNome']);
            $_POST['altAlbumCapa'] = userPath($_POST['altAlbumCapa']);
            //End Slashes
            $sql = "update album set album_nome='{$_POST['altAlbumNome']}', album_img='{$_POST['altAlbumCapa']}' where album_id='{$_POST['altId']}' and album_user_id='{$_SESSION['user_mtworld']}';";
            if(enviarComand($sql,'bd_pmatth')){ header('Location: ../index.php?nAlb='.$_POST['altId']); }
            else header('Location: ../index.php?error=6');
        }else header('Location: ../index.php?error=7');
    }else
    if(isset($_GET['file'])){
        if($_GET['file']=="mus"){
            $retorna = "";
            if(isset($_POST['musEmAlt'])&&$_POST['musEmAlt']>0) $retorna = "nMus=".$_POST['musEmAlt'];
            if($_FILES['uploadAudio']){
                if($_FILES['uploadAudio']['error']==0){
                    move_uploaded_file(
                        $_FILES['uploadAudio']['tmp_name'],
                        userPath($_FILES['uploadAudio']['name'],"../audio/")
                    );
                    header('Location: ../index.php?'.$retorna);
                }else header('Location: ../index.php?error=8');
            }else{ header('Location: ../index.php?error=9'); }
        }else
        if($_GET['file']=="alb"){
            $retorna = "";
            if(isset($_POST['albEmAlt'])&&$_POST['albEmAlt']>0) $retorna = "nAlb=".$_POST['albEmAlt'];
            if($_FILES['uploadCapaAlbum']){
                if($_FILES['uploadCapaAlbum']['error']==0){
                    move_uploaded_file(
                        $_FILES['uploadCapaAlbum']['tmp_name'],
                        userPath($_FILES['uploadCapaAlbum']['name'],"../img/")
                    );
                    header('Location: ../index.php?'.$retorna);
                }else header('Location: ../index.php?error=10');
            }else{  header('Location: ../index.php?error=11'); }
        }
    }else
    if(isset($_GET['cifra'])){
        $concat = "";
        for($i=0;$i<(count($_POST)-1);$i++){ $concat .= $_POST['cLinha'.$i]."<br/>"; }
        $sql="update musica set musica_cifra='$concat' where musica_id='{$_POST['cifraId']}' and musica_user_id='{$_SESSION['user_mtworld']}';";
        if(enviarComand($sql,'bd_pmatth')){ header('Location: ../index.php?uMus='.$_POST['cifraId']); }
        else header('Location: ../index.php?error=12');
    }else
    if(isset($_GET['renameAud'])||isset($_GET['renameImg'])){
        if(isset($_POST['oldname'])&&isset($_POST['newname'])){
            $pasta = "../".(isset($_GET['renameAud'])?'audio':'img')."/";
            rename(userPath($_POST['oldname'],$pasta),userPath($_POST['newname'],$pasta));
            header('Location: ../index.php?error=14');
        }else header('Location: ../index.php?error=13');
    }else
    if(isset($_GET['deleteAud'])||isset($_GET['deleteImg'])){
        if(isset($_POST['oldname'])){
            $pasta = "../".(isset($_GET['deleteAud'])?'audio':'img')."/";
            unlink($pasta.$_POST['oldname']);
            header('Location: ../index.php?error=16');
        }else header('Location: ../index.php?error=15');
    }
}else header('Location: ../');
?>