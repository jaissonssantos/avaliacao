<?php


/**
 * Classe para adicionar, editar, atualizar e excluir o cliente na plataforma vindi.
 *
 * @copyright  2016 Kambo Tecnologia LTDA
 * @license    http://www.kambotecnologia.com.br
 *
 * @version    Release: @package_version@
 *
 * @link       http://kambotecnologia.com.br
 * @since      Class available since Release 1.0.0
 */
class costumers
{
    /**
     * cadastra um novo cliente, $data (array): Receberá os parametros abaixo.
     *
     * @param name (string):  Nome do cliente
     * @param email (string, opcional): E-mail do cliente
     * @param registry_code (string, opcional): CPF ou CNPJ do cliente
     * @param code (string, opcional): Código opcional para referência via API
     * @param notes (string, opcional): Observações adicionais internas sobre o cliente
     * @param metadata (array, opcional): Metadados do cliente
     * @param address (array, opcional): Endereço
     * @param address[ street ] (string, opcional): Endereço
     * @param address[ number ] (string, opcional): Número do endereço
     * @param address[ additional_details ] (string, opcional): Complemento
     * @param address[ zipcode ] (string, opcional): Código postal
     * @param address[ neighborhood ] (string, opcional): Bairro
     * @param address[ city ] (string, opcional): Cidade
     * @param address[ state ] (string, opcional): Código do estado no formato ISO 3166-2. Exemplo: SP
     * @param address[ country ] (string, opcional): Código do país no formato ISO 3166-1 alpha-2. Exemplo: BR
     * @param phones (array, opcional): Lista de números de telefone do cliente
     * @param phones [ phone_type ] (string) = ['mobile' ou 'landline']: Tipo
     * @param phones [ number ] (string): Número de telefone no formato E.164 incluindo código do país e código de área. Exemplo: 5511975416666
     * @param phones [ extension ] (string, opcional): Ramal com até 6 dígitos
     *
     * @author Jaisson Santos <jaissonssantos@gmail.com>
     *
     * @return JSON
     */
    public function add($data)
    {
        $curl = curl_init();
        $data = json_encode($data);

        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://app.vindi.com.br/api/v1/customers',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 60,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_2,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS => $data,
          CURLOPT_HTTPHEADER => array(
            'authorization: Basic '.API_VINDI,
            'cache-control: no-cache',
            'content-type: application/json',
          ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            $resultado = '{ "status": "error",  "message": "'.$err.'" }';
        } else {
            $resultado = $response;
        }

        return $resultado;
    }// end function add costumer

    /**
     * pesquisa um cliente, $code (integer): Receberá o código do cliente.
     *
     * @param code (string):  ID do cliente
     *
     * @author Jaisson Santos <jaissonssantos@gmail.com>
     *
     * @return JSON
     */
    public function getid($code)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://app.vindi.com.br/api/v1/customers?query=code='.$code, //search params
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 60,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_2,
          CURLOPT_CUSTOMREQUEST => 'GET',
          CURLOPT_HTTPHEADER => array(
            'authorization: Basic '.API_VINDI,
            'cache-control: no-cache',
            'content-type: application/json',
          ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            $resultado = '{ "status": "error",  "message": "'.$err.'" }';
        } else {
            $resultado = $response;
        }

        return $resultado;
    }//end get id costumer

    /**
     * atualizar um cliente, $data (array): Receberá os parametros abaixo e $id (integer) código do cliente na plataforma vindi.
     *
     * @param name (string):  Nome do cliente
     * @param email (string, opcional): E-mail do cliente
     * @param registry_code (string, opcional): CPF ou CNPJ do cliente
     * @param code (string, opcional): Código opcional para referência via API
     * @param notes (string, opcional): Observações adicionais internas sobre o cliente
     * @param metadata (array, opcional): Metadados do cliente
     * @param address (array, opcional): Endereço
     * @param address[ street ] (string, opcional): Endereço
     * @param address[ number ] (string, opcional): Número do endereço
     * @param address[ additional_details ] (string, opcional): Complemento
     * @param address[ zipcode ] (string, opcional): Código postal
     * @param address[ neighborhood ] (string, opcional): Bairro
     * @param address[ city ] (string, opcional): Cidade
     * @param address[ state ] (string, opcional): Código do estado no formato ISO 3166-2. Exemplo: SP
     * @param address[ country ] (string, opcional): Código do país no formato ISO 3166-1 alpha-2. Exemplo: BR
     * @param phones (array, opcional): Lista de números de telefone do cliente
     * @param phones [ phone_type ] (string) = ['mobile' ou 'landline']: Tipo
     * @param phones [ number ] (string): Número de telefone no formato E.164 incluindo código do país e código de área. Exemplo: 5511975416666
     * @param phones [ extension ] (string, opcional): Ramal com até 6 dígitos
     *
     * @author Jaisson Santos <jaissonssantos@gmail.com>
     *
     * @return JSON
     */
    public function update($data, $id)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://app.vindi.com.br/api/v1/customers/'.$id, //params id costumer
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 60,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_2,
          CURLOPT_CUSTOMREQUEST => 'PUT',
          CURLOPT_HTTPHEADER => array(
            'authorization: Basic '.API_VINDI,
            'cache-control: no-cache',
            'content-type: application/json',
          ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            $resultado = '{ "status": "error",  "message": "'.$err.'" }';
        } else {
            $resultado = $response;
        }

        return $resultado;
    }//end update costumer
}
