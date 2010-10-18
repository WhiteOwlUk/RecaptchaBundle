<?php
namespace Bundle\RecaptchaBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class RecaptchaExtension extends Extension{

    public function configLoad($config, ContainerBuilder $container){
	if(!empty ($config)){
	    $container->setParameter('recaptcha.config.prkey', $config['prkey']);
	    $container->setParameter('recaptcha.config.pbkey', $config['pbkey']);
	    $container->setParameter('recaptcha.config.secure', $config['secure']);
	}
	return $container;
    }
    /**
     * Returns the namespace to be used for this extension (XML namespace).
     *
     * @return string The XML namespace
     */
    public function getNamespace(){
        return 'http://www.example.com/symfony/schema/';
    }
    /**
     * Returns the recommanded alias to use in XML.
     *
     * This alias is also the mandatory prefix to use when using YAML.
     *
     * @return string The alias
     */
    public function getAlias(){
        return 'recaptcha';
    }
    /**
     * Returns the base path for the XSD files.
     *
     * @access public
     * @return string The XSD base path
     */
    public function getXsdValidationBasePath()
    {
        return __DIR__.'/../Resources/config/';
    }
}
