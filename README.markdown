Provides use reCaptcha as form field.

## Installation

### Add RecaptchaBundle to your src/Bundle dir

You can download it from here http://whiteowluk.github.com/RecaptchaBundle

### Add RecaptchaBundle to your application kernel:

    // app/AppKernel.php
    public function registerBundles()
    {
        return array(
            // ...
            new Bundle\RecaptchaBundle\RecaptchaBundle(),
            // ...
        );
    }

### Add your private and public key for reCaptcha in configuration file
If you use secure url for reCaptcha put true in secure.

    // app/config/config.php
    $container->loadFromExtension('recaptcha', 'config', array(
    	'pbkey' => 'here_is_your_publick_key',
    	'prkey' => 'here_is_your_private_key',
    	'secure' => false
    ));

## Use in forms

### In your form class add following lines

    use Bundle\RecaptchaBundle\Field\Recaptcha,
        Bundle\RecaptchaBundle\Validator\Recaptcha as RecaptchaCheck;

When you create form (if you create it in separated class not in the controller) you need pass container into the method that prepare the from.
Let's see how it works.

In the controller we have some action. In this action we try to create the form. For now it's User form.

    public function someAction(){
	$user = new User();
	//now we need to declare container variable
	$container = $this->countainer;
	$user->prepareFrom($container);
    }

In the User form class

    public function prepareForm($container){
	$form = new Form( ... );
	...
	$recaptcha = new Recaptcha('captcha');
	$recaptcha->setSrcAttribute($container);
	$form->add($recaptcha);
	...
	return $form;
    }

That's all. Now go to the view section.

## Use in view

In template add following lines

    <?php echo $form['captcha']->errors(); ?>
    <?php echo $form['captcha']->widget(array(), 'RecaptchaBundle:Field:recaptcha_field.php'); ?>

That's all.
