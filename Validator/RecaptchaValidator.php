<?php
namespace Bundle\RecaptchaBundle\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class RecaptchaValidator extends ConstraintValidator{
    
    public function isValid($value, Constraint $constraint){
	//define variable for recaptcha check answer
	$prkey = $constraint->container_instance->getParameter('recaptcha.config.prkey');
	$remoteip = $_SERVER['REMOTE_ADDR'];
	$challenge = $_POST["recaptcha_challenge_field"];
	$response = $_POST["recaptcha_response_field"];
	
        if (!$this->_recaptcha_check_answer($prkey, $remoteip, $challenge, $response)) {
            $this->setMessage($constraint->message, array('{{ value }}' => $value));
            return false;
        }
        return true;
    }

    /**
     * Calls an HTTP POST function to verify if the user's guess was correct
     * @param string $privkey
     * @param string $remoteip
     * @param string $challenge
     * @param string $response
     * @param array $extra_params an array of extra variables to post to the server
     * @return ReCaptchaResponse
    */
    private function _recaptcha_check_answer($privkey, $remoteip, $challenge, $response, $extra_params = array()){
	if ($privkey == null || $privkey == '') {
		die ("To use reCAPTCHA you must get an API key from <a href='https://www.google.com/recaptcha/admin/create'>https://www.google.com/recaptcha/admin/create</a>");
	}

	if ($remoteip == null || $remoteip == '') {
		die ("For security reasons, you must pass the remote ip to reCAPTCHA");
	}
	
	//discard spam submissions
        if ($challenge == null || strlen($challenge) == 0 || $response == null || strlen($response) == 0) {
                return false;
        }

	$response = $this->_recaptcha_http_post (RECAPTCHA_VERIFY_SERVER, "/recaptcha/api/verify",
                                          array (
                                                 'privatekey' => $privkey,
                                                 'remoteip' => $remoteip,
                                                 'challenge' => $challenge,
                                                 'response' => $response
                                                 ) + $extra_params
                                          );

        $answers = explode ("\n", $response [1]);

        if (trim ($answers [0]) == 'true') {
	    return true;
        }
        else {
	    return false;
        }
    }
    
    /**
     * Submits an HTTP POST to a reCAPTCHA server
     * @param string $host
     * @param string $path
     * @param array $data
     * @param int port
     * @return array response
     */
    private function _recaptcha_http_post($host, $path, $data, $port = 80) {

        $req = _recaptcha_qsencode ($data);

        $http_request  = "POST $path HTTP/1.0\r\n";
        $http_request .= "Host: $host\r\n";
        $http_request .= "Content-Type: application/x-www-form-urlencoded;\r\n";
        $http_request .= "Content-Length: " . strlen($req) . "\r\n";
        $http_request .= "User-Agent: reCAPTCHA/PHP\r\n";
        $http_request .= "\r\n";
        $http_request .= $req;

        $response = '';
        if( false == ( $fs = @fsockopen($host, $port, $errno, $errstr, 10) ) ) {
                die ('Could not open socket');
        }

        fwrite($fs, $http_request);

        while ( !feof($fs) )
                $response .= fgets($fs, 1160); // One TCP-IP packet
        fclose($fs);
        $response = explode("\r\n\r\n", $response, 2);

        return $response;
    }
}