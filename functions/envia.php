<?php

/**
 * Verifica se existe o arquivo
 */

if (isset($_FILES['arquivo'])) {
    $file = $_FILES['arquivo'];
    echo "<pre>";
    //print_r($file);

    $list = fopen($file['tmp_name'], "r");

    $i = 0;
    $numberOfFields = 2;
    $csvArr = [];

    while (($filedata = fgetcsv($list, 1000, ",")) !== FALSE) {
        $num = count($filedata);
        if ($i > 0 && $num == $numberOfFields) {
            $csvArr[$i]['nome'] = $filedata[0];
            $csvArr[$i]['email'] = $filedata[1];
        }
        $i++;
    }
    fclose($list);

    foreach ($csvArr as $key => $client) {
        $post = [
            'classRoomId' => 0,
            'email' => $client['email'],
            'isActive' => true,
            'months' => 12,
            'name' => $client['nome'],
            'productId' => $_POST['id'],
            'time' => "indeterminate"
        ];

        print_r($post);

        $ch = curl_init('https://api-readonly.mycheckout.com.br/api/v1/courtesy');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);

        // execute!
        $response = curl_exec($ch);

        // close the connection, release resources used
        curl_close($ch);

        // do anything you want with your response
        var_dump($response);

        
    }
}


// set post fields
