<?php

//WEBHOOK EDUZZ

// Define o tipo de conteúdo da resposta como JSON
header('Content-Type: application/json');
define('BASE_URL', 'https://evermart.dinamusdigital.com');

if ($_POST) :
    /** STATUS PAGO */
    if (isset($_POST['trans_status']) == 3) :

        /** REGISTRA OS DADOS RECEBIDOS EM UM ARQUIVO TXT */
        /**REGISTRA TENTATIVA DE ACESSO NÃO AUTORIZADO */
        $file = "assets/txt/POSTS-" . date('Y-m-d') . '-' . time() . ".txt";
        $ip = $_SERVER['REMOTE_ADDR'];
        $content = json_encode($_POST);
        file_put_contents($file, $content);


        //array para post
        $post = [
            'classRoomId' => 0,
            'email' => $_POST['student_email'],
            'isActive' => true,
            'months' => 12,
            'name' => $_POST['student_name'],
            'productId' => $_GET['id'],
            'time' => "indeterminate"
        ];

        //print_r($post);

        //inicio da solicitação curl
        $ch = curl_init('https://api-readonly.mycheckout.com.br/api/v1/courtesy');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);

        // execute!
        $response = curl_exec($ch);

        // fecha a conexão, libera os recursos usados
        curl_close($ch);

        // faça o que quiser com sua resposta
        echo json_encode($_POST);

    endif;
else :

    $file = "assets/txt/tentativa-de-acessos-" . date('Y-m-d') . ".txt";
    /**REGISTRA TENTATIVA DE ACESSO NÃO AUTORIAZADO */
    $ip = $_SERVER['REMOTE_ADDR'];
    $content = date('H:i:s') . " - IP:" . $ip . "\n";
    if (file_exists($file)) {
        //echo "O arquivo já existe.";
        file_put_contents($file, $content, FILE_APPEND);
    } else {
        file_put_contents($file, $content);
    }


endif;