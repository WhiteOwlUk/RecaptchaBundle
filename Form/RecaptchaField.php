<?php

namespace Bundle\RecaptchaBundle\Form;

use Symfony\Component\Form\Field;
use Symfony\Component\Form\Exception\FormException;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * A field for entering a recaptcha text.
 */
class RecaptchaField extends Field
{
    /**
     * The reCAPTCHA server URL's
     */
    const RECAPTCHA_API_SERVER        = 'http://www.google.com/recaptcha/api';
    const RECAPTCHA_API_SECURE_SERVER = 'https://www.google.com/recaptcha/api';

    /**
     * The javascript src attributes (challenge, noscript)
     *
     * @var string
     */
    protected $scrips;

    /**
     * Sets the Javascript source URLs.
     *
     * @param ContainerInterface $container An ContainerInterface instance
     */
    public function setScriptURLs(ContainerInterface $container)
    {
        $pubkey = $container->getParameter('recaptcha.pubkey');
        $useSSL = $container->getParameter('recaptcha.secure');

        if ($pubkey == null || $pubkey == '') {
            throw new \FormException('To use reCAPTCHA you must get an API key from <a href="https://www.google.com/recaptcha/admin/create">https://www.google.com/recaptcha/admin/create</a>');
        }

        if ($useSSL) {
            $server = self::RECAPTCHA_API_SECURE_SERVER;
        } else {
            $server = self::RECAPTCHA_API_SERVER;
        }

        $this->scrips = array(
            'challenge' => $server.'/challenge?k='.$pubkey,
            'noscript'  => $server.'/noscript?k='.$pubkey,
        );
    }

    /**
     * Gets the Javascript source URLs.
     *
     * @param string $key The script name
     *
     * @return string The javascript source URL
     */
    public function getScriptURL($key)
    {
        return isset($this->scrips[$key]) ? $this->scrips[$key] : null;
    }
}
