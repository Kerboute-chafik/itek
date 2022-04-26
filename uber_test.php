<?php
/**
* 2007-2022 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2022 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

if (!defined('_PS_VERSION_')) {
    exit;
}

class Uber_test extends Module
{
    protected $config_form = false;

    public function __construct()
    {
        $this->name = 'uber_test';
        $this->tab = 'front_office_features';
        $this->version = '1.0.0';
        $this->author = 'chafik';
        $this->need_instance = 1;

        /**
         * Set $this->bootstrap to true if your module is compliant with bootstrap (PrestaShop 1.6)
         */
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('uber_test');
        $this->description = $this->l('a module that logs u to uber');

        $this->confirmUninstall = $this->l('Are you sure to delete this module ?');

        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
    }

    /**
     * Don't forget to create update methods if needed:
     * http://doc.prestashop.com/display/PS16/Enabling+the+Auto-Update
     */
    public function install()
    {
        Configuration::updateValue('UBER_TEST_LIVE_MODE', false);

        include(dirname(__FILE__).'/sql/install.php');

        return parent::install() &&
            $this->registerHook('header') &&
            $this->registerHook('backOfficeHeader') &&
            $this->registerHook('displayHome');
    }

    public function uninstall()
    {
        Configuration::deleteByName('UBER_TEST_LIVE_MODE');

        include(dirname(__FILE__).'/sql/uninstall.php');

        return parent::uninstall();
    }

    /**
     * Load the configuration form
     */
    public function getContent()
    {
        /**
         * If values have been submitted in the form, process.
         */
        if (((bool)Tools::isSubmit('submitUber_testModule')) == true) {
            $this->postProcess();
        }

        $this->context->smarty->assign('module_dir', $this->_path);

        $output = $this->context->smarty->fetch($this->local_path.'views/templates/admin/configure.tpl');

        return $output.$this->renderForm();
    }

    /**
     * Create the form that will be displayed in the configuration of your module.
     */
    protected function renderForm()
    {
        $fields_1 = array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('TOKEN details'),
                    'icon'  => 'icon-envelope'
                ),
                'description' => $this->l('LOREMIPSUM').
                    '<br/>'.
                    $this->l('You can generate the token by following the link: ').' 
                                 <a href="https://developer.uber.com/dashboard/" target=_blank>'.
                    $this->l('Click here') .'</a><br/>'.
                    $this->l('Copy paste the generated token in the field below'),
                'input' => array(
                    array(
                        'type'     => 'text',
                        'label'    => $this->l('SERVER TOKEN'),
                        'name'     => 'SERVER_TOKEN',
                        'required' => true
                    ),
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                    'class' => 'btn btn-default pull-right',
                    'name'  => 'saveConfigurationTOKEN'
                )
            ),
        );
        $fields_2 = array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('Client id'),
                    'icon'  => 'icon-envelope'
                ),
                'description' => $this->l('LOREMIPSUM').
                    '<br/>'
                ,
                'input' => array(
                    array(
                        'type'     => 'text',
                        'label'    => $this->l('CLIENT ID'),
                        'name'     => 'CLIENT_ID',
                        'required' => true
                    ),
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                    'class' => 'btn btn-default pull-right',
                    'name'  => 'saveConfigurationID'
                )
            ),
        );
        $fields_3 = array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('CLIENT secret'),
                    'icon'  => 'icon-envelope'
                ),
                'description' => $this->l('LOREMIPSUM').
                    '<br/>',
                'input' => array(
                    array(
                        'type'     => 'text',
                        'label'    => $this->l('CLIENT_SECRET'),
                        'name'     => 'CLEINCT_SECRET',
                        'required' => true
                    ),
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                    'class' => 'btn btn-default pull-right',
                    'name'  => 'saveConfigurationSECRET'
                )
            ),
        );

        $helper = new HelperForm();
        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $helper->module = $this;
        $helper->default_form_language = $this->context->language->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG', 0);

        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitUber_testModule';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false)
            .'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');

        $helper->tpl_vars = array(
            'fields_value' => $this->getConfigFormValues(), /* Add values for your inputs */
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
        );

        return $helper->generateForm(array($fields_1,$fields_2,$fields_3));
    }


    /**
     * Set values for the inputs.
     */
    protected function getConfigFormValues()
    {
        return array(
            'UBER_TEST_LIVE_MODE' => Configuration::get('UBER_TEST_LIVE_MODE', true),
            'UBER_TEST_ACCOUNT_CLIENT_ID' => Tools::getValue('CLIENT_ID',Configuration::get('UBER_TEST_ACCOUNT_CLIENT_ID', null)),
            'UBER_TEST_ACCOUNT_CLIENT_SECRET' => Tools::getValue('CLIENT_SECRET',Configuration::get('UBER_TEST_ACCOUNT_CLIENT_SECRET', null)),
            'UBER_TEST_ACCOUNT_SERVER_TOKEN' => Tools::getValue('SERVER_TOKEN',Configuration::get('UBER_TEST_ACCOUNT_SERVER_TOKEN', null)),
        );
    }

    /**
     * Save form data.
     */
    protected function postProcess()
    {
        $form_values = $this->getConfigFormValues();

        foreach (array_keys($form_values) as $key) {
            Configuration::updateValue($key, Tools::getValue($key));
        }
        $this->_html .= $this->displayConfirmation($this->l('Settings updated'));
    }

    /**
    * Add the CSS & JavaScript files you want to be loaded in the BO.
    */
    public function hookBackOfficeHeader()
    {
        if (Tools::getValue('module_name') == $this->name) {
            $this->context->controller->addJS($this->_path.'views/js/back.js');
            $this->context->controller->addCSS($this->_path.'views/css/back.css');
        }
    }

    /**
     * Add the CSS & JavaScript files you want to be added on the FO.
     */
    public function hookHeader()
    {
        $this->context->controller->addJS($this->_path.'/views/js/front.js');
        $this->context->controller->addCSS($this->_path.'/views/css/front.css');
    }

    public function getUberData(){
        if (Configuration::get('SERVER_TOKEN')
            && Configuration::get('CLIENT_SECRET')
            && Configuration::get('CLIENT_ID')) {

            $parameters = array(
                'client_id' => Configuration::get('CLIENT_ID'),
                'client_secret' => Configuration::get('CLIENT_SECRET'),
                'grant_type' => 'authorization_code',
                'redirect_uri' => 'http://localhost/prestashop/prestashop/fr/',
                'scope' => 'profile',
                'code' => Configuration::get('SERVER_TOKEN')
            );

            UberApi::curlCall('token', $parameters);
        }

    }

    public function hookDisplayHome()
    {
        if(Tools::isSubmit('submit_uber')){
         $this->getUberData();
        }
        return '<input type="submit" name="submit_uber" class="btn btn-primary" value="AUTH TO UBER">';
    }
}
