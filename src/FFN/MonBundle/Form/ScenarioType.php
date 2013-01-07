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
class ScenarioType extends AbstractType{
  
  private $translator;
  
  public function __construct(Translator $translator) {
    $this->setTranslator($translator);
  }
  
  public function buildForm(FormBuilderInterface $builder, array $options) {
    //parent::buildForm($builder, $options);
    $builder
      ->add('name', 'text', array('label' => $this->getTranslator()->trans('mon_scenario_name')))
      ->add('frequency', 'text', array('label' => $this->getTranslator()->trans('mon_scenario_frequency')))
      ->add('enabled', 'checkbox', array('label' => $this->getTranslator()->trans('mon_scenario_enabled')))
    ;
  }
  
  public function getName(){
    return 'scenario';
  }
  
  public function getTranslator() {
    return $this->translator;
  }

  public function setTranslator($translator) {
    $this->translator = $translator;
  }


  
}
