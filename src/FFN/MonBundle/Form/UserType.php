<?php

namespace FFN\MonBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Translation\Translator;

/**
 * Description of UserType
 *
 * @author nsias
 */
class UserType extends AbstractType{
  
  private $translator;
  
  public function __construct(Translator $translator) {
    $this->setTranslator($translator);
  }
  
  public function buildForm(FormBuilderInterface $builder, array $options) {
    //parent::buildForm($builder, $options);
    $builder->add('username', 'text', array('label' => $this->getTranslator()->trans('mon_username')));
    $builder->add('password', 'password', array('label' => $this->getTranslator()->trans('mon_password')));
    $builder->add('email', 'email', array('label' => $this->getTranslator()->trans('mon_mail')));
  }
  
  public function getName(){
    return 'user';
  }
  
  public function getTranslator() {
    return $this->translator;
  }

  public function setTranslator($translator) {
    $this->translator = $translator;
  }


  
}
