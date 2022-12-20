<?php

//Verifica se o arquivo foi enviado
if (isset($_FILES['arquivo'])) {

    //Trasfere dados do arquivo para a variavel file
    $file = $_FILES['arquivo'];

    //Abre arquivo
    $list = fopen($file['tmp_name'], "r");

    //Linhas
    $i = 0;

    //Numero de colunas
    $numberOfFields = 2;

    echo "<pre>";

    //Extrai linha por linha até 500
    while (($filedata = fgetcsv($list, 500, ",")) !== FALSE) {

        //numero de linhas
        $num = count($filedata);

        //remove pelo menos a primeira linha que é o titulo
        if ($i > 0 && $num == $numberOfFields) {

            //array para post
            $post = [
                'classRoomId' => 0,
                'email' => $filedata[1],
                'isActive' => true,
                'months' => 12,
                'name' => $filedata[0],
                'productId' => $_POST['id'],
                'time' => "indeterminate"
            ];

            print_r($post);

            //inicio da solicitação curl
            $ch = curl_init('https://api-readonly.mycheckout.com.br/api/v1/courtesy');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post);

            // execute!
            $response = curl_exec($ch);

            // fecha a conexão, libera os recursos usados
            curl_close($ch);

            // faça o que quiser com sua resposta
            var_dump($response);
        }
        $i++;
    }

    echo "</pre>";

    //fecha arquivo
    fclose($list);
}
