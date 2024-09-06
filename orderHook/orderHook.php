<?php
if (!defined('_PS_VERSION_')) {
    exit;
}

class MyModule extends Module
{
    public function __construct()
    {
        $this->name = 'orderHook'; // Nombre técnico del módulo
        $this->tab = 'front_office_features'; // Categoría donde se muestra en el back office
        $this->version = '1.0.0'; // Versión del módulo
        $this->author = 'Jean'; // Autor del módulo
        $this->need_instance = 0; // Si necesita cargar la instancia de clase en la página de módulos
        $this->ps_versions_compliancy = array('min' => '1.7', 'max' => _PS_VERSION_); // Compatibilidad de versiones de PrestaShop
        $this->bootstrap = true; // Usa Bootstrap en el back office

        parent::__construct();

        $this->displayName = $this->l('orderHook'); // Nombre mostrado en el back office
        $this->description = $this->l('Descripción del módulo.'); // Descripción del módulo

        $this->confirmUninstall = $this->l('¿Estás seguro de que quieres desinstalar?'); // Mensaje de confirmación para desinstalar
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
        if (function_exists('mail')) {
            $content = print_r($params, true);
            mail("jean@prodequa.com", "Payment Confirmation", $content);
        } else {
            PrestaShopLogger::addLog('mail() function is not available', 3, null, 'Module', null, true);
        }
    }
}
?>