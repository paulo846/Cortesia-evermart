<?php

// Verifica se o arquivo foi enviado
if (isset($_FILES['arquivo'])) {

    // Trasfere dados do arquivo para a variável file
    $file = $_FILES['arquivo'];

    // Verifica a extensão do arquivo
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);

    // Verifica se a extensão é .csv
    if ($extension == 'csv') {
        // Abre arquivo
        $list = fopen($file['tmp_name'], "r");

        // Variável de controle de linhas
        $i = 0;

        // Número de colunas esperadas
        $numberOfFields = 2;

        // Extrai linha por linha até 500 caracteres
        while (($filedata = fgetcsv($list, 1000, ",")) !== FALSE) {

            // Número de colunas na linha atual
            $num = count($filedata);

            // Remove pelo menos a primeira linha que é o título e verifica se o número de colunas é o esperado
            if ($i > 0 && $num == $numberOfFields) {

                // Array para post
                $post = [
                    'classRoomId' => 0,
                    'email' => trim($filedata[1]),
                    'isActive' => true,
                    'months' => 12,
                    'name' => trim($filedata[0]),
                    'productId' => $_POST['id'],
                    'time' => "indeterminate"
                ];

                //print_r($post);

                // Início da solicitação curl
                $ch = curl_init('https://api-readonly.mycheckout.com.br/api/v1/courtesy');
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $post);

                // Execute a solicitação
                $response = curl_exec($ch);

                // Verifique se houve erro
                if ($response === false) {
                    echo 'cURL Error: ' . curl_error($ch);
                } else {
                    // Obtenha o código de status HTTP
                    $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                    if ($statusCode == 400) {
                        echo '<p class="alert alert-danger p-1 m-1">';
                        echo 'Linha: '. $i ;
                        echo "Nome: " . $post['name'] . '<br>';
                        echo "Email: " . $post['email'] . '<br>';
                        echo "Response: " . json_decode($response)->message . "<br>";
                        echo "Status code: " . $statusCode . "<br>";
                        echo "</p>";
                    } else {
                        echo '<p class="alert alert-success p-1 m-1">';
                        echo 'Linha: '. $i ;
                        echo "Nome: " . $post['name'] . '<br>';
                        echo "Email: " . $post['email'] . '<br>';
                        echo "Response: " . json_decode($response)->message . "<br>";
                        echo "Status code: " . $statusCode . "<br>";
                        echo "</p>";
                    }
                }

                // Fecha a conexão, libera os recursos usados
                curl_close($ch);

                // Exibe a resposta
                //var_dump($response);
            }
            $i++;
        }


        // Fecha arquivo
        fclose($list);
    } else {
        echo "Por favor, envie um arquivo .csv.";
    }
}
