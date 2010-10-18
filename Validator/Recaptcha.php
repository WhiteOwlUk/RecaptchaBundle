<?php
namespace Bundle\RecaptchaBundle\Validator;

class Recaptcha extends \Symfony\Component\Validator\Constraint{
    public $message = 'Enter a valid captcha';
    public $container_instance;

    public function defaultOption(){
	return 'container_instance';
    }
}
