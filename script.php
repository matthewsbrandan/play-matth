<?php
    session_start();
    include('../conn/function.php');
    $start = false;
    if($start){
        $sql = "select * from album";
        $data = enviarComand($sql,'bd_pmatth');
        while($res = $data->fetch_assoc()){
            $sql = "update album set album_img='1002/{$res['album_img']}' where album_id='{$res['album_id']}';";
            if(enviarComand($sql,'bd_pmatth'))
                echo "Feito";
            else 
                echo "Erro";
            echo "<br/>";
        }

        $sql = "select * from musica";
        $data = enviarComand($sql,'bd_pmatth');
        while($res = $data->fetch_assoc()){
            if(!empty($res['musica_audio'])){
                $sql = "update musica set musica_audio='1002/{$res['musica_audio']}' where musica_id='{$res['musica_id']}';";
                if(enviarComand($sql,'bd_pmatth'))
                    echo "Feito";
                else 
                    echo "Erro";
                echo "<br/>";
            }
        }
    }