<?php
namespace Bundle\RecaptchaBundle\Field;

use Symfony\Component\Form\Field;

class Recaptcha extends Field{
    
    CONST RECAPTCHA_API_SERVER = 'http://www.google.com/recaptcha/api';
    CONST RECAPTCHA_API_SECURE_SERVER = 'https://www.google.com/recaptcha/api';
    CONST RECAPTCHA_VERIFY_SERVER = 'http://www.google.com/';

    private  $_src;

    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        parent::configure();
    }

    /**
     * {@inheritDoc}
     */
    public function getAttributes()
    {
        return array_merge(parent::getAttributes(), array(
            'type'        => 'text/javascript',
	    'src'	  => $this->_src
        ));
    }
    /**
     * Set serc attribute for script tag
     * @access public
     * @param object $container The container object with configuration
     * @return string
     */
    public function setSrcAttribute($container){
	if($container->getParameter('recaptcha.config.secure')){
	    $http = self::RECAPTCHA_API_SECURE_SERVER;
	}
	else{
	    $http = self::RECAPTCHA_API_SERVER;
	}
	return $this->_src = $http . '/challenge?k=' . $container->getParameter('recaptcha.config.pbkey');
    }
}