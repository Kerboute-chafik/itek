<?php

use Symfony\Component\Translation\TranslatorInterface;

class ItekCheckoutStep extends AbstractCheckoutStep
{

    protected $itekdata;

    public function __construct(
        Context $context,
        TranslatorInterface $translator

    )
    {
        parent::__construct($context, $translator);
        $this->context = $context;
        $this->translator = $translator;
    }

    /**
     * Affichage de la step
     * @param array $extraParams
     * @return string
     */
    public function render(array $extraParams = [])
    {
        $defaultParams = 'hello world';
        $this->context->smarty->assign($defaultParams);
        return $this->module->display(
            _PS_MODULE_DIR_ . $this->module->name,
            'views/templates/hook/itekCheckoutStep.tpl'
        );
    }

    /**
     * @param array $requestParameters
     * @return $this
     */
    public function handleRequest(array $requestParameters = array())
    {
        if (isset($requestParameters['submitCustomStep'])) {
            $this->setComplete(true);
            if (version_compare(_PS_VERSION_, '1.7.6') > 0) {
                $this->setNextStepAsCurrent();
            } else {
                $this->setCurrent(false);
            }
        }

        return $this;
    }
}
