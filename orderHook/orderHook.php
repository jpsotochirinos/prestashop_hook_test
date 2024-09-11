<?php
if (!defined('_PS_VERSION_')) {
    exit;
}

class OrderHook extends Module
{
    public function __construct()
    {
        $this->name = 'orderhook';
        $this->tab = 'front_office_features';
        $this->version = '1.0.0'; 
        $this->author = 'Jean'; 
        $this->need_instance = 0;
        $this->ps_versions_compliancy = array('min' => '1.7', 'max' => _PS_VERSION_); 
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('orderHook');
        $this->description = $this->l('Descripci贸n del m贸dulo.'); 
    }

    public function install()
    {
        if (!parent::install() ||
            !$this->registerHook('actionPaymentConfirmation')) {
            return false;
        }
        return true;
    }

    public function uninstall()
    {
        if (!parent::uninstall()) {
            return false;
        }
        return true;
    }

    public function hookActionPaymentConfirmation($params)
    {
        PrestaShopLogger::addLog('Entro a la funcion');
        PrestaShopLogger::addLog('Order : ', $params);
        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://interpreter-dev.mytikray.com/orders/hook',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS =>'{
            "Domain": "Marketplace",
            "OrderId": "1440540507649-01",
            "State": "payment-approved",
            "LastState": "approve-payment",
            "LastChange": "2024-06-19T20:57:20.8107666Z",
            "CurrentChange": "2024-06-19T20:57:20.838933Z",
            "Origin": {
                "Account": "prodequa",
                "Key": "vtexappkey-prodequa-ASJUUK"
            }
        }',
          CURLOPT_HTTPHEADER => array(
            'account: prodequa',
            'Username: 6tncj079tdrbqfgadl3s8755f4',
            'Password: 1eo1bolukrubtn2ocs6ivolhhpu3s0rum683689v2ole9cmnquba',
            'Content-Type: application/json'
          ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        echo $response;
        
         PrestaShopLogger::addLog('curl: ', $response);
        if (function_exists('mail')) {
            $content = print_r($params, true);
            mail("jean@prodequa.com", "Payment Confirmation", $content);
        } else {
            PrestaShopLogger::addLog('mail() function is not available', 3, null, 'Module', null, true);
        }
    }
}
?>