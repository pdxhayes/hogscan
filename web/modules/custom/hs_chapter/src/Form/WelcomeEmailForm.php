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
class WelcomeEmailForm extends ConfigFormBase {
  
  const SETTINGS = 'hs_chapter.settings';
  
  public function getFormId() {
    return 'chapter_settings_form';
  }
  
  protected function getEditableConfigNames() {
    return [
      static::SETTINGS,
    ];
  }
  
  public function buildForm(array $form, FormStateInterface $form_state) {
    
    
    
    $config = $this->config(static::SETTINGS);
  
    $welcome_email_settings = \Drupal::config('hs_chapter.settings')->get('config')['welcome_email'];
    
    // print_r($welcome_email_settings);
    // exit;
    
      
      $form['#prefix'] = 'Use this form to configure the welcome email that will be sent to new members during creation or import.  This email may be the "First Impression" the Chapter gives the new member.  Put thought into a message that welcomes the new member.';
      
      
      $form['welcome_email']['from'] = array(
        '#type' => 'textfield',
        '#title' => t('From Address'),
        '#attributes' => array('id' => 'from_value', ' type' => 'text'),
        '#default_value' => $welcome_email_settings['from'],
      );
      
      $form['welcome_email']['subject'] = array(
        '#type' => 'textfield',
        '#title' => t('Subject'),
        '#attributes' => array('id' => 'subject_value', ' type' => 'text'),
        '#default_value' => $welcome_email_settings['subject'],
      );
      
      $form['welcome_email']['body'] = array(
        '#type' => 'text_format',
        '#title' => t('Body'),
        '#attributes' => array('id' => 'body_value', ' type' => 'text'),
        '#default_value' => $welcome_email_settings['body']['value'],
        '#format'=> $welcome_email_settings['body']['format'],
      );
      
      
    
    return parent::buildForm($form, $form_state);
    
  
  }
  
  // public function validateForm(array &$form, FormStateInterface $form_state) {
  //   
  // }
  
  public function submitForm(array &$form, FormStateInterface $form_state) {
    
  
    
    $config['welcome_email']['from'] = $form_state->getValue('from');
    $config['welcome_email']['subject'] = $form_state->getValue('subject');
    $config['welcome_email']['body'] = $form_state->getValue('body');
    
      
    $this->configFactory->getEditable(static::SETTINGS)
      ->set('config', $config)
      ->save();
      
    
    parent::submitForm($form, $form_state);
      
    // $message = "Your settings have been saved.";
    // \Drupal::messenger()->addStatus($message);
    
    
  }
  
  
  
 
  
}