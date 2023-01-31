if (isset($_FILES['arquivo'])) {

    $file = $_FILES['arquivo'];
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);

    if ($extension != "csv") {
        echo "O arquivo enviado não é um arquivo CSV";
        exit;
    }

    $list = fopen($file['tmp_name'], "r");
    $i = 0;
    $numberOfFields = 2;

    echo "<pre>";

    while (($filedata = fgetcsv($list, 500, ",")) !== FALSE) {
        $num = count($filedata);

        if ($i > 0 && $num == $numberOfFields) {
            $post = [
                'classRoomId' => 0,
                'email' => $filedata[1],
                'isActive' => true,
                'months' => 12,
                'name' => $filedata[0],
                'productId' => $_POST['id'],
                'time' => "indeterminate"
            ];

            $ch = curl_init('https://api-readonly.mycheckout.com.br/api/v1/courtesy');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post);

            $response = curl_exec($ch);

            if (curl_error($ch)) {
                echo "Erro na solicitação curl: " . curl_error($ch);
                exit;
            }

            curl_close($ch);

            var_dump($response);
        }
        $i++;
    }

    echo "</pre>";
    fclose($list);
}
