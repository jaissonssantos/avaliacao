<?php

require_once('lib/Iugu.php');

class IuguConnect
{

    public static function token($attributes = [])
    {

        Iugu::setApiKey(IUGU_KEY); // Ache sua chave API no Painel

        $token = Iugu_PaymentToken::create($attributes);

        if (isset($token['errors'])) {
            throw new Exception('Verifique as informações de cobrança e tente novamente');
        }
            
        return [
            'id'  => $token['id'],
            'method' => $token['method']
        ];

    }

    public static function charge($attributes = [])
    {
        Iugu::setApiKey(IUGU_KEY); // Ache sua chave API no Painel

        $charge = Iugu_Charge::create($attributes);

        if (isset($charge['errors'])) {
            throw new Exception('Verifique as informações de cobrança e tente novamente');
        }
            
        return [
            'success'  => $charge['success'],
            'message' => $charge['message'],
            'invoice_id' => $charge['invoice_id']
        ];
    }

    public static function invoice($email,$validade,$item,$quantidade,$value)
    {
        Iugu::setApiKey(IUGU_KEY); // Ache sua chave API no Painel

        $invoice = Iugu_Invoice::create(
            [
                'email'       => $email,
                'due_date'    => $validade,
                'items'       => [
                    [
                        'description' => $item,
                        'quantity'    => $quantidade,
                        'price_cents' => $value,
                    ],
                ]
            ]
        );

        if (isset($invoice['errors'])) {
            throw new Exception('Verifique as informações da fatura e tente novamente');
        }
            
        return [
            'iugu_id'  => $invoice['secure_id'],
            'redirect' => $invoice['secure_url']
        ];
    }

    public static function marketplaceCreate($attributes = [])
    {

        Iugu::setApiKey(IUGU_KEY); // Ache sua chave API no Painel

        $create = Iugu_Marketplace::create($attributes);

        if (isset($create['errors'])) {
            throw new IuguException();
            // throw new Exception('Verifique as informações da criação da sub-conta e tente novamente');
        }
            
        // return [
        //     'account_id'  => $subaccount['account_id'],
        //     'name' => $subaccount['name'],
        //     'live_api_token' => $subaccount['live_api_token'],
        //     'test_api_token' => $subaccount['test_api_token'],
        //     'user_token' => $subaccount['user_token']
        // ];
        return $create;

    }
}
