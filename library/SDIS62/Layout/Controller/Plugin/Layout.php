<?php

class SDIS62_Layout_Controller_Plugin_Layout extends Zend_Layout_Controller_Plugin_Layout
{
    private $request;

    // Modular layout
    public function postDispatch(Zend_Controller_Request_Abstract $request)
    {
        $this->request = $request;

        $layout = $this->getLayout();

        // Récupération du bon layout en fonction du module
        if(null !== file_exists($this->getModulePath() . 'layouts' . DIRECTORY_SEPARATOR . 'scripts'))
        {
            $layout->setLayoutPath($this->getModulePath() . DIRECTORY_SEPARATOR . 'layouts' . DIRECTORY_SEPARATOR . 'scripts');
        }
        else
        {
            $layout->setLayoutPath(APPLICATION_PATH . DIRECTORY_SEPARATOR . 'layouts' . DIRECTORY_SEPARATOR . 'scripts');
        }

        parent::postDispatch($request);
    }

    // Récupère le lien vers la racine du module
    private function getModulePath() 
    {
        $module = $this->request->getModuleName();

        if($module == 'default') 
        {
            return APPLICATION_PATH;
        }
        else
        {
            return APPLICATION_PATH . DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR . $module;
        }
    }
}