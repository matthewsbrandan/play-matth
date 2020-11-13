<?php
  session_start();
  include('../conn/function.php');
  $pmatth = 5;
  if(isset($_SESSION['user_mtworld'])&&$_SESSION['user_mtworld']>0){
    $sql="select count(*) qtd from usuario u inner join user_sites us on u.id=us.usuario_id where u.id='{$_SESSION['user_mtworld']}' and us.sites_id='$pmatth' and status='ativo';";
    if(((enviarComand($sql,'bd_mtworld'))->fetch_assoc())['qtd']==0){
        header('Location: ../');
    }
  }
  else{
    if(isset($_COOKIE['mtworldPass'])&&isset($_COOKIE['mtworldKey'])){
        $sql="select u.id,u.nome,u.email from usuario u inner join user_sites us on u.id=us.usuario_id where u.email='{$_COOKIE['mtworldPass']}' and u.senha='{$_COOKIE['mtworldKey']}' and us.sites_id='$pmatth' and status='ativo';";
        if($linha = (enviarComand($sql,'bd_mtworld'))->fetch_assoc()){
            $_SESSION['user_mtworld'] = $linha['id'];
            $_SESSION['user_mtworld_nome'] = $linha['nome'];
            $_SESSION['user_mtworld_email'] = $linha['email'];
        } 
    }else header('Location: ../');
  }

  function userPath($folder=''){
    $path = $folder.($_SESSION['user_mtworld']+1000)."/";
    if($folder!==''){
        if(!is_dir($path)) mkdir($path);
    }
    return $path;
  }
  function hiddenPath($file){
      $file = explode("/",$file);

      if(count($file)==2) $file = $file[1];
      else $file = '';

      return $file;
  }
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <title>PlayMatth</title>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="css/bootstrap-grid.css">
    <link rel="stylesheet" type="text/css" href="css/bootstrap-grid.min.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <style>
        html,body{ background-image: linear-gradient(to bottom, #020202, #080808, #302020, #402020, #502020, #402020, #302020); min-height: 100%; }
        .light     { background: #DFEDF2; } .txt-light      { color: #DFEDF2; }
        .semi-light{ background: #517C8C; } .txt-semi-light { color: #517C8C; }
        .neuter    { background: #2A4359; } .txt-neuter     { color: #2A4359; }
        .semi-dark { background: #011C40; } .txt-semi-dark  { color: #011C40; }
        .dark      { background: #011126; } .txt-dark       { color: #011126; }
        .list-group-item{ cursor: pointer; transition: .6s background;  }
        .list-group-item:hover{ background: #2A4359; color: #DFEDF2;}
        .pointer { cursor: pointer; }
        .bg-cifra{ background: #ffa500;}
        .text-cifra{ color: #ffa500; }
        .text-cifra-bold{ color: #ffa500; font-weight: 550; }
        .transparente{ color: transparent; }
        html { font-size: 1rem; }
        @media (min-width: 576px) { html { font-size: 1.6rem; } }
        @media (min-width: 768px) { html { font-size: 1.4rem; } }
        @media (min-width: 992px) { html { font-size: 1rem; } }
    </style>
    <script src="js/jquery/jquery-3.4.1.min.js"></script>
    <!--Scroll Personalizado-->
    <style>
        ::-webkit-scrollbar { width: 7px; height: 7px; background-image: linear-gradient(to bottom, #020202, #080808, #302020, #402020, #502020); }
        ::-webkit-scrollbar-track { background-image: linear-gradient(to bottom, #020202, #080808, #302020, #402020, #502020); border-radius: 5px; } 
        ::-webkit-scrollbar-thumb { background: #444; border-radius: 5px; }
        ::-webkit-scrollbar-thumb:hover { background: #555; }
    </style>
    <script>
       var timerScroll;
       function reload(){ location.reload(); }
       function carrega(){ $('#divAlbum').removeClass('d-none'); }
       function carregaAlb(){ $('#divAlbSel').removeClass('d-none'); }
       function chamaGerencia(){ $('#modalGerenciarClick').click(); }
       function todasMusicas(){
            $('#divMusica').removeClass('d-none');
            $('#navOptions li:nth-child(1)').removeClass('active');
            $('#navOptions li:nth-child(2)').addClass('active');
       }
       function reduzLista(arr){
           novo = new Array(); c=0;
           for(i=0;i<arr.length;i++){ 
                if(arr[i][1].length>0){ novo[c] = arr[i]; c++; }
           }
           return novo;
       }
       function reproduzirA(arr,t){
           arr = reduzLista(arr);
           $('.aSpan').html('playlist_play');
           $('#'+t).html('equalizer');
           $('#reprodutorFixo').show('slow');
           $('#reprodutorTitle').html(arr[0][0]);
           $('#reprodutorAudio').attr('src','audio/'+arr[0][1]).attr('title','1');
           $('#reprodutorAudio')[0].play();
           concat = "";
           for(i=0;i<arr.length;i++){ 
               concat+= "<li class='list-group-item' onclick='playNext("+i+")'>"+arr[i][0]+"</li>";
           }
           $('#reprodutorList').html(concat).removeClass('d-none');
           $('#reprodutorList li:first').addClass('active');
           $('#reprodutorAudio')[0].onended = function(){ playList(arr); }
       }
       function playList(arr){
           v = $('#reprodutorAudio').attr('title');
           if(v>=arr.length || v==0){
              $('#reprodutorTitle').html(arr[0][0]);
              $('#reprodutorAudio').attr('src','audio/'+arr[0][1]).attr('title',1);
              $('#reprodutorAudio')[0].play();
              $('#reprodutorList li').removeClass('active');
              $('#reprodutorList li:nth-child(1)').addClass('active');
           }else{
              $('#reprodutorTitle').html(arr[v][0]);
              $('#reprodutorAudio').attr('src','audio/'+arr[v][1]).attr('title',parseInt(v)+1);
              $('#reprodutorAudio')[0].play();
              $('#reprodutorList li').removeClass('active'); v++;
              $('#reprodutorList li:nth-child('+v+')').addClass('active');
           }
       }
       function playNext(p){
           $('#reprodutorAudio').attr('title',p);
           $("#reprodutorAudio")[0].currentTime = $("#reprodutorAudio")[0].duration;
       }
       function reproduzir(p,t,title){
           alert(p);
           $('.rSpan').html('play_arrow');
           $('#'+t).html('equalizer');
           $('#reprodutorFixo').show('slow');
           $('#reprodutorTitle').html(title);
           $('#reprodutorAudio').attr('src','audio/'+p);
           $('#reprodutorAudio')[0].play();
       }
       function reproduzirEmDir(p){
           $('#reprodutorFixo').show('slow');
           $('#reprodutorTitle').html('Aúdio > '+p);
           $('#reprodutorAudio').attr('src','audio/'+p);
           $('#reprodutorAudio')[0].play();
       }
       function fechaAudio(){
           $('#reprodutorFixo').hide('slow');
           $('.rSpan').html('play_arrow');
           $('.aSpan').html('playlist_play');
           $('#reprodutorAudio')[0].pause();
       }
       function selAlbum(p){
           $('#albId').val(p);
           $('#formAlb').submit();
       }
       function selFormMusica(p){           
           $('#albMusica').val(p);
           $('#formMusica').submit();
       }
       function alterarMusica(p){
           $('#fgmId').val(p);
           $('#formGerenciarMusica').submit();
       }
       function alterarAlbum(p){
           $('#fgaId').val(p);
           $('#formGerenciarAlbum').submit();
       }
       function finalizaMusica(p){
           $('#deleteMusica').val(p).attr('name','altId');
           if(!($('#collapseAltAudio').is(":visible"))) $('#altAudio').attr('name','');
           if(!($('#collapseAltHistoria').is(":visible"))) $('#altHistoria').attr('name','');
           $('#formGerenciarMusica').attr('action','back/back.php?updateMus');
           $('#formGerenciarMusica').submit();
       }
       function finalizaAlbum(p){
           $('#deleteAlbum').val(p).attr('name','altId');
           $('#formGerenciarAlbum').attr('action','back/back.php?updateAlb');
           $('#formGerenciarAlbum').submit();
       }
       function revelaCifra(c,l){
           arrC = c.split('<br>');
           arrL = l.split('<br>');
           arr = "<strong class='mr-2 collapse collapseCifra'>Introdução: </strong><span class='text-cifra-bold collapse collapseCifra'>" + arrC[0] + "<br></span>";
           for(i=0;i<arrL.length;i++){
               if(arrC.length>(i+1))
               { if(arrC[i+1].length!=0) arr += "<span class='text-cifra-bold collapse collapseCifra'>" + arrC[i+1] + "<br></span>"; }
               arr += arrL[i] + "<br>";
           }
           return ((arr.split("_").join("<span class='transparente'>_</span>")).split("[").join("<span class='text-dark '>[")).split("]").join("]</span>");
       }
       function selMusica(p){
           btn = "";
           if($('#albIndHistoria'+p).html().length>0){ 
               btn="<button type='button' class='btn btn-outline-dark btn-sm ml-2' data-toggle='collapse' data-target='#collapseAlbHistoria' aria-expanded='false' aria-controls='collapseAlbHistoria' id='btnCollapseAlbHistoria' onclick='if($(\"#btnCollapseAlbHistoria\").hasClass(\"active\")){ $(\"#btnCollapseAlbHistoria\").removeClass(\"active\"); }else{ $(\"#btnCollapseAlbHistoria\").addClass(\"active\"); }'>História</button>";
               $('#collapseAlbHistoria').html($('#albIndHistoria'+p).html());    
           }
           if($('#albIndCifra'+p).html().length>0){
               btn+="<button class='btn btn-outline-dark btn-sm ml-2' data-toggle='collapse' data-target='.collapseCifra' aria-expanded='false' aria-controls='collapseCifra' id='btnCollapseCifra' onclick='if($(\"#btnCollapseCifra\").hasClass(\"bg-cifra text-light\")){ $(\"#btnCollapseCifra\").removeClass(\"bg-cifra text-light\").addClass(\"btn-outline-dark\"); }else{ $(\"#btnCollapseCifra\").addClass(\"bg-cifra text-light\").removeClass(\"btn-outline-dark\"); }'>Cifra</button>";
               $('#albIndDivLetra').html(revelaCifra($('#albIndCifra'+p).html(),$('#albIndLetra'+p).html()));
           }
           else{ $('#albIndDivLetra').html($('#albIndLetra'+p).html()); }
           if($('#albIndAudio'+p).val().length>0){   
                btn+="<span class='material-icons align-middle pointer rSpan' onclick='reproduzir(\""+$('#albIndAudio'+p).val()+"\",this.id,\""+$('#albIndNome'+p).html()+" | "+$('#albIndTitle').attr('title')+"\");' id='albIndPlay'>play_arrow</span>";
           } 
           $('#albIndSubTitle').html($('#albIndTitle').attr('title'));
           $('#albIndTitle').html($('#albIndNome'+p).html()+btn+"<span class='material-icons align-middle pointer' id='iflow' onclick='autoScroll()'>unfold_more</span>").addClass('mb-0').removeClass('mb-1');
           $('#albIndLista').addClass('d-none');
           v = "<a class='dropdown-item' data-toggle='modal' data-target='#modalNovaMusica'>Nova Música</a><a class='dropdown-item' onclick='alterarMusica("+$('#albIndLista li:nth-child('+p+') a').attr('id').substr(3)+")'>Gerenciar Música</a>";
           v+="<a class='dropdown-item' onclick='selAlbum("+$('#albSelId').val()+")'>Voltar para o Albúm</a>";
           v+="<div class='dropdown-divider'></div><div style='max-height: 200px; overflow: auto;'>";
           for(i=1;i<=$('#albIndLista li').length;i++){ v+="<a class='dropdown-item' onclick='selMusica("+i+")'>"+$('#albIndNome'+i).html()+"</a>"; }
           $('#dropdownMenu').html(v+"</div>");
       }
       function deletarAlbum(p){
           $('#deleteAlbum').val(p);
           $('#formGerenciarAlbum').attr('action','back/back.php?delAlb');
           $('#formGerenciarAlbum').submit();
       }
       function deletarMusica(p){
           $('#deleteMusica').val(p);
           $('#formGerenciarMusica').attr('action','back/back.php?delMus');
           $('#formGerenciarMusica').submit();
       }
       function selImg(p){
           $('#modalCapaAlbum').modal('hide');
           $('#altAlbumCapaImg').attr('src','img/'+p).attr('style','height: 204px');
           $('#altAlbumCapaCaption').html(p);
           $('#altAlbumCapa').val(p);
       }
       function selAudio(p){
           $('#modalAudMusica').modal('hide');
           $('#altAudio').val(p);
           $('#collapseAltAudio').show();
       }
       function formDir(acao,valor){
           retorno = false;
           if(acao=="renameAud"||acao=="renameImg"){
               if(valor[0].length>0&&valor[1].length>0){
                   conteudo = "<input type='hidden' name='oldname' value='"+valor[0]+"'><input type='hidden' name='newname' value='"+valor[1]+"'>";
                   retorno = true;
               }
           }else if(acao=="deleteAud"||acao=="deleteImg"){
               if(valor){
                   conteudo = "<input type='hidden' name='oldname' value='"+valor+"'>";
                   retorno = true;
               }
           }
           if(retorno){
               $('body').append("<form method='POST' action='back/back.php?"+acao+"' id='formDir'>"+conteudo+"</form>");
               $('#formDir').submit();
           }else msg(0,['Houve um erro ao executar esta função!']);
           
       }
       function autoScroll(){
           if($('#iflow').hasClass('text-danger')) breakFlow();
           else{
               $('#flowFixo').show('slow');
               $('#iflow').addClass('text-danger');
               var rolagem = 0;
               timerScroll = setInterval(function(){
                   if(rolagem<$('body').height()){ rolagem+=10; }else{ clearInterval(timerScroll); }
                   $('html, body').animate({ scrollTop: rolagem }, 1000); },1000);
           }
           
       }
       function breakFlow(){
           $('#flowFixo').hide('slow');
           $('#iflow').removeClass('text-danger');
           clearInterval(timerScroll);
       }
       $(function(){
           <?php if(isset($_GET['all'])){ ?> todasMusicas();
           <?php } else if(isset($_GET['alb'])){ if(isset($_POST['albMusica'])){ echo " $('#idM{$_POST['albMusica']}').click(); "; }?> carregaAlb();
           <?php } else{ ?> carrega(); <?php } ?>
           <?php if(isset($_GET['gerenciar'])){ ?> chamaGerencia();
           <?php if(isset($_POST['fgmId'])){ 
                $data = enviarComand("select musica_album_id from musica where musica_id='{$_POST['fgmId']}' and musica_user_id='{$_SESSION['user_mtworld']}';",'bd_pmatth');
                if($res = $data->fetch_assoc()){
           ?>
           $('#altAlbum').val('<?php echo $res['musica_album_id']; ?>');
           <?php }}else if(isset($_POST['fgaId'])){ ?> $('#myTab li:nth-child(2) a').click(); 
           <?php }} if(isset($_GET['nAlb'])){ echo " alterarAlbum({$_GET['nAlb']}); "; }?> 
           <?php if(isset($_GET['nMus'])){ echo " alterarMusica({$_GET['nMus']}); "; }?>
           <?php if(isset($_GET['uMus'])){ echo " selFormMusica({$_GET['uMus']}); "; }?>
           <?php if(isset($_GET['uAlb'])){ echo " selAlbum({$_GET['uAlb']}); "; }?>
       });
    </script>
    <!--MSG-->
    <script>
        function msg(p,arr){
            if(arr[p]){
                $('#modalMsgBody').html(arr[p]);
                $('#modalMsgAutoClick').click();
            }
        }
        //Vetor de Tratamento de Erros
        var error = ["Erro ao Registrar nova Música",
                     "Erro ao Registrar o Album",
                     "Esse Albúm está vinculado á algumas Músicas. Desvincular Músicas depois faça a exclusão novamente.",
                     "Houve um erro ao tentar deletar está Música.",
                     "Houve um erro na alteração da Música",
                     "Problema com o Preenchimento dos Campos, tente novamente.",
                     "Houve um Erro ao Atualizar este Albúm.",
                     "Houve um Erro ao Preencher os Campos.",
                     "Houve eu erro no carregamento do Arquivo",
                     "Nenhum arquivo foi selecionado",
                     "Houve eu erro no carregamento do Arquivo",
                     "Nenhum arquivo foi selecionado",
                     "Houve um Erro ao Registrar a Cifra",
                     "Preencha os campos para Alterar o nome do Arquivo",
                     "Arquivo renomeado com Sucesso!",
                     "Houve um erro ao tentar deletar este Arquivo",
                     "Arquivo deletado com Sucesso!"];
        $(function(){
           <?php if(isset($_GET['dAlb'])){ echo " msg(0,['Albúm Deletado com Sucesso!']); "; }?> 
           <?php if(isset($_GET['dMus'])){ echo " msg(0,['Música Deletada com Sucesso!']); "; }?>
           <?php if(isset($_GET['error'])){ echo " msg({$_GET['error']},error); "; }?>
        });
    </script>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top" style="background-color: #020202;">
      <a class="navbar-brand text-danger font-weight-bold" href="index.php">Play Matth</a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"> <span class="navbar-toggler-icon"></span> </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto" id="navOptions">
          <li class="nav-item active">
            <a class="nav-link" href="index.php">Albúm <span class="sr-only">(current)</span></a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="index.php?all">Todas as Músicas</a>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              Adicionar
            </a>
            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
              <a class="dropdown-item" data-toggle="modal" data-target="#modalNovaMusica">Nova Música</a>
              <a class="dropdown-item" data-toggle="modal" data-target="#modalNovoAlbum">Novo Albúm</a>
              <div class="dropdown-divider"></div>
              <a class="dropdown-item" onclick="chamaGerencia()">Gerenciar</a>
            </div>
          </li>
          <li class="nav-item">
            <!-- FAZER LINK FUNCIONAR -->
            <a class="nav-link" href="../" style="opacity: .6">Sair</a>
          </li>
        </ul>
        <!--Search-->
        <form class="form-inline my-2 my-lg-0" method="POST" action="index.php?alb" id="formMusica">
          <?php if(isset($_SESSION['user_mtworld'])&&$_SESSION['user_mtworld']>0){ ?>
            <button type="button" class="btn btn-outline-secondary my-2 my-sm-0 mr-2" style="opacity: .9" id="aMatthNavigate" onclick="$('#matthNavigate').modal('show');">
                <span class="material-icons align-middle px-1">ac_unit</span>
            </button>
          <?php } ?>
          <select class="custom-select mr-2 text-truncate text-muted" id="albNovaMusica" name="albNovaMusica" style="max-width: 220px;">
                <option selected>Pesquisar Música...</option>
                <?php
                    $data = enviarComand("select * from musica where musica_user_id='{$_SESSION['user_mtworld']}' order by musica_nome;",'bd_pmatth');
                    while($res = $data->fetch_assoc()){
                ?>
                <option value="<?php echo $res['musica_id']; ?>"><?php echo $res['musica_nome']; ?></option>
                <?php } ?>
          </select>
          <button class="btn btn-outline-danger my-2 my-sm-0" onclick="selFormMusica($('#albNovaMusica').val());">
            <span class="material-icons" style="vertical-align: -8px">search</span>
          </button>
          <input type="hidden" name="albMusica" id="albMusica">
        </form>
      </div>
    </nav>
    <!--Conteúdo-->
    <div class="container-fluid mt-5 py-3 d-block">
        <!--Albuns-->
        <form method="POST" action="index.php?alb" id="formAlb">
            <input type="hidden" name="albId" id="albId">
            <div class="row row-cols-lg-3 row-cols-md-1 row-cols-sm-1 row-cols-xs-1 d-none" id="divAlbum">
                <?php
                    $res = enviarComand("select count(*) qtd,album_nome,album_img,album_id from musica inner join album on musica_album_id=album_id where musica_user_id='{$_SESSION['user_mtworld']}' group by album_id order by album_id desc;",'bd_pmatth'); $entrou = 0;
                    while($data = $res->fetch_assoc()){ $entrou++;
                ?>
                <div class='col mb-4 shadow'>
                    <div class='card'>
                        <img src='img/<?php echo $data['album_img']; ?>' class='card-img-top' alt='...'>
                        <div class='card-body'>                        
                            <h5 class='card-title pointer' onclick='selAlbum(<?php echo $data['album_id']; ?>)'><?php echo $data['album_nome']." ({$data['qtd']})"; ?></h5>
                            <!--PlayList-->
                            <script>
                            <?php
                                $temAudio = false;
                                echo "var playAlb".$entrou." = [";
                                $d = enviarComand("select musica_nome, musica_audio from musica where musica_album_id='{$data['album_id']}' and musica_user_id='{$_SESSION['user_mtworld']}';",'bd_pmatth');
                                while($r = $d->fetch_assoc()){
                                    echo "['".$r['musica_nome']." | ".$data['album_nome']."','".$r['musica_audio']."'],";
                                    if(strlen($r['musica_audio'])>0) $temAudio = true;
                                }
                                echo "['','']]; playAlb".$entrou.".pop();";
                            ?>
                            </script>
                            <?php if($temAudio) {?>
                            <span class="material-icons float-right pointer aSpan" onclick="reproduzirA(playAlb<?php echo $entrou; ?>,this.id)" id="spanA<?php echo $entrou; ?>">playlist_play</span>
                            <?php } ?>
                            <p class='card-text'>Descrição do Albúm.</p>
                        </div>
                    </div>
                </div>
                <?php } ?>
            </div>
        </form>
        <!--Músicas-->
        <div class="row row-cols-lg-3 row-cols-md-1 row-cols-sm-1 d-none" id="divMusica">
            <ul class='list-group mx-auto'>
                <li class='list-group-item row bg-dark text-light'>
                    <div class="col-5">
                        Albúns
                        <span class='badge badge-danger mr-2'>
                        <?php echo enviarComand("select count(*) from album where album_user_id='{$_SESSION['user_mtworld']}';","bd_pmatth")->fetch_assoc()['count(*)']; ?>
                        </span>
                    </div>
                    <div class="col-5">
                        Músicas 
                        <span class='badge badge-danger mr-2'>
                        <?php echo enviarComand("select count(*) from musica where musica_user_id='{$_SESSION['user_mtworld']}';","bd_pmatth")->fetch_assoc()['count(*)']; ?>
                        </span>
                    </div>
                    <div class="col-2">
                        <!--PlayList-->
                        <script>
                        <?php
                            echo "var playMus = [";
                            $dAll = enviarComand("select musica_nome, musica_audio, album_nome from musica inner join album on musica_album_id = album_id where musica_user_id='{$_SESSION['user_mtworld']}' order by musica_id desc;",'bd_pmatth');
                            while($rAll = $dAll->fetch_assoc()){
                                echo "['".$rAll['musica_nome']." | ".$rAll['album_nome']."','".$rAll['musica_audio']."'],";
                            }
                            echo "['','']]; playMus.pop();";
                        ?>
                        </script>
                        <span class="material-icons d-block text-right pointer aSpan" id="spanMus" onclick="reproduzirA(playMus,this.id);">playlist_play</span>
                    </div>
                </li>
                <?php
                    $data = enviarComand("select * from musica inner join album on musica_album_id=album_id where musica_user_id='{$_SESSION['user_mtworld']}' order by musica_id desc;",'bd_pmatth');
                    $entrou = 0;
                    while($res = $data->fetch_assoc()){ $entrou++;
                ?>
                <li class='list-group-item row'>
                    <div class="text-truncate col-8"> <?php echo $res['musica_nome'].' | '.$res['album_nome']; ?> </div>
                    <div class="col-4 text-right">
                        <a onclick="selFormMusica(<?php echo $res['musica_id']; ?>)"><span class="material-icons">subject</span></a>
                        <?php if(strlen($res['musica_audio'])>0){?>
                        <span class="material-icons rSpan" onclick="reproduzir('<?php echo $res['musica_audio']; ?>',this.id,'<?php echo $res['musica_nome'].' | '.$res['album_nome']; ?>');" id="spanR<?php echo $entrou; ?>">play_arrow</span>
                        <?php }else{ ?><span class="material-icons" title="Não Possui Aúdio Cadastrado" onclick="msg(0,['O Aúdio ainda não foi Cadastrado!']);">music_off</span> <?php } ?>
                    </div>
                </li>
                <?php } ?>
            </ul>
        </div>
        <!--Album Selecionado-->
        <div class="row d-none" id="divAlbSel">
            <?php
                if(isset($_POST['albMusica'])){
                    $d = enviarComand("select musica_album_id from musica where musica_id='{$_POST['albMusica']}' and musica_user_id='{$_SESSION['user_mtworld']}';",'bd_pmatth');
                    if($r = $d->fetch_assoc()){ $_POST['albId'] = $r['musica_album_id']; }
                }
                $data = enviarComand("select count(*) qtd,album_nome,album_img,album_id from musica inner join album on musica_album_id=album_id where album_id='{$_POST['albId']}' and musica_user_id='{$_SESSION['user_mtworld']}' group by album_id;",'bd_pmatth'); $nao = false;
                if($res = $data->fetch_assoc()){ if(isset($_POST['albMusica'])){ $_POST['albId'] = $res['album_id'];}
            ?>
            <div class="col-12">
                <div class="card mb-3">
                  <div style="background: url('img/<?php echo $res['album_img']; ?>'); height: 250px;"></div>
                  <div class="card-body">
                    <!--Header-->
                    <div>
                        <div class="dropleft float-right pointer">
                          <span class="material-icons" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">more_vert</span>
                          <div class="dropdown-menu" aria-labelledby="dropdownMenuButton" id="dropdownMenu">
                            <a class="dropdown-item" data-toggle="modal" data-target="#modalNovaMusica">Nova Música</a>
                            <a class="dropdown-item" data-toggle="modal" data-target="#modalGerenciar">Gerenciar</a>
                            <div class="dropdown-divider"></div>
                            <div style='max-height: 200px; overflow: auto;'>
                            <?php 
                                $res1 = enviarComand("select album_nome,album_id from musica inner join album on musica_album_id=album_id where musica_user_id='{$_SESSION['user_mtworld']}' group by album_id order by album_id desc;",'bd_pmatth');
                                while($data = $res1->fetch_assoc()){
                            ?>
                            <a class="dropdown-item" onclick="selAlbum(<?php echo $data['album_id']; ?>)"><?php echo $data['album_nome']; ?></a>
                            <?php } ?>
                            </div>
                          </div>
                        </div>
                        <!--PlayList-->
                        <script>
                        <?php
                            $temAudio = false;
                            echo "var playAlbInd = [";
                            $dAlb = enviarComand("select musica_nome, musica_audio from musica where musica_album_id='{$res['album_id']}' and musica_user_id='{$_SESSION['user_mtworld']}';",'bd_pmatth');
                            while($rAlb = $dAlb->fetch_assoc()){
                                echo "['".$rAlb['musica_nome']." | ".$res['album_nome']."','".$rAlb['musica_audio']."'],";
                                if(strlen($rAlb['musica_audio'])>0) $temAudio = true;
                            }
                            echo "['','']]; playAlbInd.pop();";
                        ?>
                        </script>
                        <?php if($temAudio) {?>
                        <span class="material-icons float-right pointer aSpan" id="spanInd" onclick="reproduzirA(playAlbInd,this.id);">playlist_play</span>
                        <?php } ?>
                        <h5 class="card-title mb-1" id="albIndTitle" title="<?php echo $res['album_nome']; ?>"><?php echo $res['album_nome'].' ('.$res['qtd'].')'; ?></h5>
                        <p class="card-text mb-2 font-weight-800 font-italic" id="albIndSubTitle">Descrição do Albúm.</p>
                    </div>
                    <input type="hidden" id="albSelId" value="<?php echo $res['album_id']; ?>">
                    <div class="collapse bg-dark text-secondary rounded p-3 mb-1" id="collapseAlbHistoria"></div>
                    <div id="albIndDivLetra"></div>
                    <ul class="list-group" id="albIndLista">
                    <!--Albúm Vazio-->
                <?php }else{ $nao = true; ?>
                    <div class="alert alert-danger mx-2" role="alert">
                        <h4 class="alert-heading">Não há Músicas Cadastradas neste Albúm!</h4>
                        <p>Adicione uma Música a este Albúm ou se quiser você pode excluí-lo em:<br/><strong>Adicionar -> Gerenciar -> Albúm.</strong></p>
                    </div>
                <?php
                    }
                    if(!$nao){
                    $data = enviarComand("select * from musica inner join album on musica_album_id=album_id where album_id='{$_POST['albId']}' and musica_user_id='{$_SESSION['user_mtworld']}';",'bd_pmatth');
                    $entrou = 0;
                    while($res = $data->fetch_assoc()){ $entrou++;
                ?>
                        <li class="list-group-item">
                            <span id="albIndNome<?php echo $entrou; ?>"><?php echo $res['musica_nome']; ?></span>
                            <div class=" float-right">
                                <a onclick="selMusica(<?php echo $entrou; ?>)" id="idM<?php echo $res['musica_id']; ?>"><span class="material-icons">subject</span></a>
                                <?php if(strlen($res['musica_audio'])>0){?>
                                <span class="material-icons rSpan" onclick="reproduzir('<?php echo $res['musica_audio']; ?>',this.id,'<?php echo $res['musica_nome']." | '+$('#albIndTitle').attr('title')+'"; ?>');" id="spanR<?php echo $entrou; ?>">play_arrow</span>
                                <?php }else{ ?><span class="material-icons" title="Não Possui Aúdio Cadastrado" onclick="msg(0,['O Aúdio ainda não foi Cadastrado!']);">music_off</span> <?php } ?>
                            </div>
                            <input type="hidden" id="albIndAudio<?php echo $entrou; ?>" value="<?php echo nl2br($res['musica_audio']); ?>">
                            <div class="d-none" id="albIndLetra<?php echo $entrou; ?>"><?php echo nl2br($res['musica_letra']); ?></div>
                            <div class="d-none" id="albIndHistoria<?php echo $entrou; ?>"><?php echo nl2br($res['musica_historia']); ?></div>
                            <div class="d-none" id="albIndCifra<?php echo $entrou; ?>"><?php echo nl2br($res['musica_cifra']); ?></div>
                        </li>
                <?php }} ?>
                    </ul>   
                  </div>
                </div>
            </div>
        </div>
    </div>
    <!--Modais-->
    <?php 
        include('modais/modalNovaMusica.php'); 
        include('modais/modalNovoAlbum.php');
        include('modais/modalGerenciar.php');
        include('modais/modalCapaAlbum.php');
        include('modais/modalAudMusica.php');
        include('modais/modalCifra.php');
    ?>
    <!--AutoScroll-->
    <div class="bg-light fixed-bottom rounded p-1 pb-0" tabindex="-2" style="right: auto; left: auto; display: none; " id="flowFixo">
        <button class="btn btn-danger btn-sm btn-block active px-1" type="button" onclick="breakFlow();"><span class="material-icons px-2 align-middle">stop</span></button>
    </div>
    <!--Reprodutor Fixo-->
    <div class="bg-light fixed-bottom rounded p-2 pb-0" tabindex="-2" style="right: 0px; left: auto;display: none;max-height: 80%;overflow: auto;" id="reprodutorFixo">
        <button class="btn btn-danger btn-sm btn-block active px-5" type="button" data-toggle="collapse" data-target="#reprodutorCollapse" aria-expanded="false" aria-controls="reprodutorCollapse"><span class="material-icons px-5">equalizer</span></button>
        <div class="collapse pt-2" id="reprodutorCollapse">
            <h6 class="text-center text-truncate" id="reprodutorTitle">Música | Albúm</h6>
            <audio id="reprodutorAudio"  class="mx-auto d-block" controls></audio>
            <ul class="list-group my-2 text-center d-none" id="reprodutorList"></ul>
            <div class="d-block text-center mb-0">
                <button type="button" class="btn btn-dark btn-sm mx-auto" onclick="fechaAudio();">&times;</button>
            </div>
        </div>
    </div>
    <?php include('../function/global.php'); ?>
</body>
</html>
<script src="js/popper.min.js"></script>
<script src="js/bootstrap.js"></script>
<script src="js/bootstrap.bundle.js"></script>
<script src="js/bootstrap.bundle.min.js"></script>