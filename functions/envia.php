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

    //Extrai linha por linha até mil
    while (($filedata = fgetcsv($list, 1000, ",")) !== FALSE) {
        
        //numero de linhas
        $num = count($filedata);

        //remove pelo menos a primeira linha que o titulo
        if ($i > 0 && $num == $numberOfFields) {
            //salva os dados extraídos em um array
            $csvArr[$i]['nome'] = $filedata[0];
            $csvArr[$i]['email'] = $filedata[1];
        }
        $i++;
    }

    //fecha arquivo
    fclose($list);

    //retorno na tela
    echo "<pre>";

    //Lista as linhas extraídas que foram salvas no array $csvArr
    foreach ($csvArr as $key => $client) {
        
        //array para post
        $post = [
            'classRoomId' => 0,
            'email' => $client['email'],
            'isActive' => true,
            'months' => 12,
            'name' => $client['nome'],
            'productId' => $_POST['id'],
            'time' => "indeterminate"
        ];

        //Retorna dados criados para o post na tela
        //Esse dados podem ser usados caso o post falhe
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
}