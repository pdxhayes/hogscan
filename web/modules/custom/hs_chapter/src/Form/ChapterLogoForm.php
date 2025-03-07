<?php

namespace Drupal\hs_chapter\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\File\FileSystemInterface;


/**
* demo data form
*
*/
class ChapterLogoForm extends FormBase {

  const SETTINGS = 'hs_chapter.settings';

  public function getFormId() {
    return 'chapter_logo_form';
  }

  protected function getEditableConfigNames() {
    return [
      static::SETTINGS,
    ];
  }

  public function buildForm(array $form, FormStateInterface $form_state) {

    $config = $this->config(static::SETTINGS);

    $builder_settings = \Drupal::config('hs_chapter.settings')->get('logo_builder');

    // attach javascript
    $form['#attached']['library'][] = 'hs_chapter/hs_chapter_logo_form';


    $form['image'] = array(
      '#markup' => '<img id="generated-img" style="display:none" src="" />',
      '#allowed_tags' => ['img'],
    );
    $form['canvas'] = array(
      '#markup' => '<div class="row"></div><div id="logo-canvas-wrapper" class="col-lg-6"><canvas id="logo-canvas"></canvas></div>',
      '#allowed_tags' => ['div', 'canvas'],
    );
    $form['logo'] = array(
      '#type' => 'hidden',
      '#attributes' => array('id' => 'logo-img'),
    );

    $form['patch_style'] = array(
      '#prefix' => '<div class="col-lg-6">',
      '#type' =>'fieldset',
      '#title' => t('Patch Style'),
      '#collapsible' => FALSE,
      '#collapsed' => FALSE,
      '#tree' => TRUE,
    );



    $form['patch_style']['eagle_logo_url'] = array(
    //	  '#access' => FALSE,
      '#type' => 'hidden',
      '#attributes' => array('id' => 'eagle_logo_url', 'style' => 'width: 100%'),
      '#value' => '/'. Drupal::service('extension.list.module')->getPath('hs_chapter').'/misc/LOGO_BUILDER_EAGLE_455.png?t='.time(),
    );

    $form['patch_style']['skull_logo_url'] = array(
    //	  '#access' => FALSE,
      '#type' => 'hidden',
      '#attributes' => array('id' => 'skull_logo_url', 'style' => 'width: 100%'),
      '#value' => '/'. Drupal::service('extension.list.module')->getPath('hs_chapter').'/misc/LOGO_BUILDER_SKULL_455.png?t='.time(),
    );


    $form['type_options'] = array(
      '#type' => 'value',
      '#value' => array('EAGLE' => t('Eagle'),
                        'SKULL' => t('Skull')
                        )
    );

    $form['patch_style']['patch_selection'] = array(
      '#type' => 'select',
      '#title' => t('Patch'),
      '#options' => $form['type_options']['#value'],
      '#description' => t('Select the Eagle or Winged Skull patch style.'),
      '#attributes' => array('id' => 'patchselect'),
      '#default_value' => $builder_settings['type'],
    );

    $form['first_line'] = array(
      '#type' => 'fieldset',
      '#title' => t('First row'),
      '#collapsible' => FALSE,
      '#collapsed' => FALSE,
      '#tree' => TRUE,
    );

    $form['first_line']['text'] = array(
      '#type' => 'textfield',
      '#title' => t('Text'),
      '#attributes' => array(
                'id' => 'text1',
                'style' => 'text-transform:uppercase',
                'autocomplete' => 'off',
                ),
      '#default_value' => $builder_settings['first_line']['text'],
    );

    $form['first_line']['font_size'] = array(
      '#type' => 'textfield',
      '#title' => t('Font size'),
      '#attributes' => array('id' => 'font1', ' type' => 'number'),
      '#default_value' =>  $builder_settings['first_line']['size'],
    );

    $form['first_line']['offset'] = array(
      '#type' => 'textfield',
      '#title' => t('Top offset'),
      '#attributes' => array('id' => 'offset1', ' type' => 'number'),
      '#default_value' =>  $builder_settings['first_line']['offset'],
    );

    $form['second_line'] = array(
      '#type' => 'fieldset',
      '#title' => t('Second row'),
      '#collapsible' => FALSE,
      '#collapsed' => FALSE,
      '#tree' => TRUE,
    );

    $form['second_line']['text'] = array(
      '#type' => 'textfield',
      '#title' => t('Text'),
      '#attributes' => array(
                'id' => 'text2',
                'style' => 'text-transform:uppercase',
                'autocomplete' => 'off',
                ),
      '#default_value' =>  $builder_settings['second_line']['text'],
    );

    $form['second_line']['font_size'] = array(
      '#type' => 'textfield',
      '#title' => t('Font size'),
      '#attributes' => array('id' => 'font2', ' type' => 'number'),
      '#default_value' => $builder_settings['second_line']['size'],
    );

    $form['second_line']['offset'] = array(
      '#type' => 'textfield',
      '#title' => t('Top offset'),
      '#attributes' => array('id' => 'offset2', ' type' => 'number'),
      '#default_value' => $builder_settings['second_line']['offset'],
    );

    $form['submit'] = array(
      '#type' => 'submit',
      '#value' => t('Save logo'),
      '#attributes' => array('id' => 'create-logo'),
      '#suffix' => '</div></div>',
    );

    //$uploaded = variable_get('hog_uploaded_logo_file', '');
//    $form['custom_logo'] = array(
//      '#type' => 'fieldset',
//      '#title' => t('Custom logo'),
//      '#collapsible' => FALSE,
//      '#collapsed' => FALSE,
//    );
//    $form['custom_logo']['uploaded_logo'] = array(
//      '#title' => t('Image'),
//      '#type' => 'managed_file',
//      '#default_value' => '',
//      '#upload_location' => 'public://',
//    );
//    $form['custom_logo']['submit2'] = array(
//      '#type' => 'submit',
//      '#value' => t('Save uploaded logo'),
//    );




    return $form;


  }

  // public function validateForm(array &$form, FormStateInterface $form_state) {
  //
  // }

  public function submitForm(array &$form, FormStateInterface $form_state) {

    $logo_link = "";
    if ($form_state->getValue(['logo'])) {
      $data = str_replace('data:image/png;base64,', '', $form_state->getValue(['logo']));
      $data = str_replace(' ', '+', $data);
      $data = base64_decode($data);
      $file = file_save_data($data, 'public://created_chapter_logo.png', 1);

      $message = "The logo has been saved. You may need to clear your browser cache to see the updated logo.";
      \Drupal::messenger()->addStatus($message);


    }

    $builder_settings = array();
    $builder_settings['type'] = $form_state->getValue('patch_style')['patch_selection'];
    $builder_settings['first_line']['text'] = $form_state->getValue('first_line')['text'];
    $builder_settings['first_line']['size'] = $form_state->getValue('first_line')['font_size'];
    $builder_settings['first_line']['offset'] = $form_state->getValue('first_line')['offset'];
    $builder_settings['second_line']['text'] = $form_state->getValue('second_line')['text'];
    $builder_settings['second_line']['size'] = $form_state->getValue('second_line')['font_size'];
    $builder_settings['second_line']['offset'] = $form_state->getValue('second_line')['offset'];

    $this->configFactory->getEditable(static::SETTINGS)
      ->set('logo_builder', $builder_settings)
      ->save();

  }





}
