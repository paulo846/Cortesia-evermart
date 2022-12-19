<?php

/**
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

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

    //array para salvar as linhas extraídas
    $csvArr = [];

    echo "<pre>";

    //Extrai linha por linha até mil
    while (($filedata = fgetcsv($list, 150, ",")) !== FALSE) {

        //numero de linhas
        $num = count($filedata);

        //remove pelo menos a primeira linha que o titulo
        if ($i > 0 && $num == $numberOfFields) {
            
            //salva os dados extraídos em um array
            $csvArr[$i]['nome']  = $filedata[0];
            $csvArr[$i]['email'] = $filedata[1];

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
