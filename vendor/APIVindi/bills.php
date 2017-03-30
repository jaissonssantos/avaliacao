<?php


/**
 * Classe para transação avulsa na plataforma vindi.
 *
 * @copyright  2016 Kambo Tecnologia LTDA
 * @license    http://www.kambotecnologia.com.br
 *
 * @version    Release: @package_version@
 *
 * @link       http://kambotecnologia.com.br
 * @since      Class available since Release 1.0.0
 */
class bills
{
    /**
     * criar uma nova transação avulsa, $data (array): Receberá os parametros abaixo.
     *
     * @param customer_id (integer):  ID do cliente
     * @param code (string, opcional): Código externo para referência via API
     * @param installments (integer, opcional): Número de parcelas. Se não informado, o valor '1' será utilizado
     * @param payment_method_code (string): Código do método de pagamento(credit_card)
     * @param billing_at (string, opcional): Data opcional de emissão da cobrança no formato ISO 8601. Se não informada, a cobrança será imediata
     * @param due_at (string, opcional):  Data opcional de vencimento da cobrança no formato ISO 8601. Se não informada, o vencimento padrão será utilizado
     * @param bill_items (array): Lista de itens da fatura
     * @param bill_items[ product_id ] (integer, opcional): ID do produto associado ao item da fatura
     * @param bill_items[ product_code ] (string, opcional): Código do produto associado ao item da fatura
     * @param bill_items[ amount ] (integer): Valor do item da fatura
     * @param bill_items[ description ] (string, opcional): Descrição opcional do item da fatura
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
          CURLOPT_URL => 'https://app.vindi.com.br/api/v1/bills',
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
}
