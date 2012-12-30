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
class ControlType extends AbstractType{
  
  private $translator;
  
  public function __construct(Translator $translator) {
    $this->setTranslator($translator);
  }
  
  public function buildForm(FormBuilderInterface $builder, array $options) {
    //parent::buildForm($builder, $options);
    $builder->add('name', 'text', array('label' => $this->getTranslator()->trans('mon_control_name')));
  }
  
  public function getName(){
    return 'control';
  }
  
  public function getTranslator() {
    return $this->translator;
  }

  public function setTranslator($translator) {
    $this->translator = $translator;
  }


  
}
