<?php

namespace Drupal\hs_chapter\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Config\ConfigFactoryInterface;

use Drupal\hs\Services\HsHelpers; // for HS Helpers

/**
* demo data form
*
*/
class PreLaunchChecklist extends ConfigFormBase {
  
  const SETTINGS = 'hs_chapter.checklist';
  
  public function getFormId() {
    return 'prelaunch_checklist_form';
  }
  
  protected function getEditableConfigNames() {
    return [
      static::SETTINGS,
    ];
  }
  
  public function buildForm(array $form, FormStateInterface $form_state) {
    
    
    
    $config = $this->config(static::SETTINGS);
  
    
      $form['chapter_settings']['sample_test_value'] = array(
        '#type' => 'textfield',
        '#title' => t('Sample Test Value'),
        '#attributes' => array('id' => 'sample_test_value', ' type' => 'text'),
        '#default_value' => \Drupal::config('hs_chapter.settings')->get('sample_test_value'),
      );
    
    
    return parent::buildForm($form, $form_state);
    
  
  }
  
  // public function validateForm(array &$form, FormStateInterface $form_state) {
  //   
  // }
  
  public function submitForm(array &$form, FormStateInterface $form_state) {
    
    $this->configFactory->getEditable(static::SETTINGS)
      ->set('sample_test_value', $form_state->getValue('sample_test_value'))
      ->save();
    
    
    parent::submitForm($form, $form_state);
      
    // $message = "Your settings have been saved.";
    // \Drupal::messenger()->addStatus($message);
    
    
  }
  
  
  
 
  
}