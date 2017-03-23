<?php


/**
 * Classe de perfil de pagamento representa um cartão de crédito ou uma conta bancária armazenada na plataforma Vindi.
 *
 * @copyright  2016 Kambo Tecnologia LTDA
 * @license    http://www.kambotecnologia.com.br
 *
 * @version    Release: @package_version@
 *
 * @link       http://kambotecnologia.com.br
 * @since      Class available since Release 1.0.0
 */
class payment_profiles
{
    //public variables
    public $_espaco = '%20'; //separator params

    /**
     * cadastra um novo perfil de pagamento para um cliente existente, $data (array): Receberá os parametros abaixo.
     *
     * @param holder_name (string): Nome do titular/portador do perfil de pagamento
     * @param registry_code (string, opcional): CPF ou CNPJ do titular/portador
     * @param bank_branch (string, opcional): Agência da conta bancária
     * @param bank_account (string, opcional): Número da conta bancária
     * @param card_expiration (string): Validade do cartão de crédito no formato MM/AA
     * @param card_number (string): Número completo do cartão de crédito
     * @param card_cvv (string): Código de segurança do cartão de crédito com 3 ou 4 dígitos
     * @param payment_method_code (string): Código do método de pagamento(credit_card, bank_debit, bank_slip)
     * @param payment_company_code (string, opcional): Código do banco ou bandeira(visa, mastercard)
     * @param customer_id (integer): ID do cliente associado ao perfil de pagamento
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
          CURLOPT_URL => 'https://app.vindi.com.br/api/v1/payment_profiles',
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
        $code = curl_getinfo($curl);

        curl_close($curl);

        if ($err) {
            $resultado = '{ "status": "error",  "message": "'.$err.'" }';
        }
        if ($code['http_code'] == 422) { //Parâmetros inválidos. Verificar erro na resposta.
            $resultado = '{ "status": "error",  "message": "Dados do cartão está incorreto ou/e bandeira incompatível das autorizadas. Por favor tente realizar o agendamento novamente." }';
        } else {
            $resultado = $response;
        }

        return $resultado;
    }// end function add payment_profiles

    /**
     * pesquisa na lista perfil de pagamento(ativo e cartão de crédito) e retorna apenas 1 e último perfil de pagamento, $id (integer): Receberá o código do cliente.
     *
     * @param id (string):  ID do cliente
     *
     * @author Jaisson Santos <jaissonssantos@gmail.com>
     *
     * @return JSON
     */
    public function getPayActive($id)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://app.vindi.com.br/api/v1/payment_profiles?query=customer_id='.$id.$_espaco.'status=active'.$_espaco.'type=PaymentProfile::CreditCard', //search params
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
}
