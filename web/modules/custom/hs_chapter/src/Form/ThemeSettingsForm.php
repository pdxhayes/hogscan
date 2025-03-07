<?php

namespace Drupal\hs_chapter\Form;

use Drupal;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Extension\ExtensionPathResolver;


/**
 *
 * Chapter Settings Form
 *
 */
class ThemeSettingsForm extends ConfigFormBase {

  const SETTINGS = 'hs_chapter.settings';

  public function getFormId(): string {
    return 'theme_settings_form';
  }

  protected function getEditableConfigNames(): array {
    return [
      static::SETTINGS,
    ];
  }

  public function buildForm(array $form, FormStateInterface $form_state): array {

    // $google_fonts = json_decode(file_get_contents(drupal_get_path('module','hs_chapter') . '/misc/js/font_names.json'), TRUE);

    // attach javascript
    $form['#attached']['library'][] = 'hs_chapter/hs_theme_settings_form';
    $form['#attributes'] = array('autocomplete'=>'off');

    //$config = $this->config(static::SETTINGS);
    $theme_settings = Drupal::config('hs_chapter.settings')->get('theme_settings');

    $form['colors'] = array(
      '#type' => 'fieldset',
      '#title' => t('Colors'),
      '#collapsible' => TRUE, // Added
      '#collapsed' => FALSE,  // Added
    );

    $form['colors']['background_color'] = array(
      '#type' => 'textfield',
      '#title' => t('Background Color: '),
      '#attributes' => array('id' => 'background_color', ' type' => 'text', ' class' => 'colorwell'),
      '#default_value' => $theme_settings['background_color'] ?? '#000000',
      '#prefix' => '<div id="picker"></div>',
    );

    $form['colors']['text_color'] = array(
      '#type' => 'textfield',
      '#title' => t('Text Color: '),
      '#attributes' => array('id' => 'text_color', ' type' => 'text', ' class' => 'colorwell'),
      '#default_value' => $theme_settings['text_color'] ?? '#000000',
    );

    $form['colors']['button_background'] = array(
      '#type' => 'textfield',
      '#title' => t('Button Background Color: '),
      '#attributes' => array('id' => 'button_background', ' type' => 'text', ' class' => 'colorwell'),
      '#default_value' => $theme_settings['button_background'] ?? '#000000',
    );

    $form['colors']['button_text'] = array(
      '#type' => 'textfield',
      '#title' => t('Button Text Color: '),
      '#attributes' => array('id' => 'button_text', ' type' => 'text', ' class' => 'colorwell'),
      '#default_value' => $theme_settings['button_text'] ?? '#000000',
    );

    $form['colors']['link_color'] = array(
      '#type' => 'textfield',
      '#title' => t('Link Color: '),
      '#attributes' => array('id' => 'link_color', ' type' => 'text', ' class' => 'colorwell'),
      '#default_value' => $theme_settings['link_color'] ?? '#000000',
    );

    $form['fonts'] = array(
      '#type' => 'fieldset',
      '#title' => t('Fonts'),
      '#collapsible' => TRUE, // Added
      '#collapsed' => FALSE,  // Added
    );

    $form['fonts']['body'] = array(
      '#type' => 'fieldset',
      '#title' => t('Body'),
      '#collapsible' => FALSE, // Added
      '#collapsed' => FALSE,  // Added
    );

    $form['fonts']['body']['family'] = array(
      '#type' => 'textfield',
      '#title' => t('Body Font: '),
      '#attributes' => array('id' => 'fonts_body_family', ' type' => 'text', ' class' => 'font-select'),
      '#default_value' => $theme_settings['fonts']['body']['family'] ?? 'Raleway',
      '#prefix' => '<div><div class="autocomplete" style="width: 300px;">',
      '#suffix' => '</div></div>',
    );

    $form['fonts']['body']['body_font_variant'] = array(
      '#type' => 'select',
      '#title' => t('Body Font Variant Font Variant: '),
      '#attributes' => array('id' => 'body_font_variant', ' type' => 'select', ' class' => 'select font-variant'),
      '#options' => [
        'select variant',
        'regular',
        'bold',
      ],
      '#default_value' => $theme_settings['h1_font_variant'] ?? 'regular',
    );

    $form['fonts']['body']['body_font_size'] = array(
      '#type' => 'textfield',
      '#title' => t('Body Font Size: '),
      '#attributes' => array('id' => 'body_font_size', ' type' => 'number', ' class' => ' font-size'),
      '#default_value' => $theme_settings['h1_font_size'] ?? '14',
    );

    $form['fonts']['h1_font'] = array(
      '#type' => 'textfield',
      '#title' => t('H1 Font: '),
      '#attributes' => array('id' => 'h1_font', ' type' => 'text', ' class' => ' font-select'),
      '#default_value' => $theme_settings['h1_font'] ?? 'Permanent Marker',
      '#prefix' => '<div><div class="autocomplete" style="width: 300px;">',
      '#suffix' => '</div></div>',
    );

    $form['fonts']['h1_font_variant'] = array(
      '#type' => 'select',
      '#title' => t('H1 Font Variant: '),
      '#attributes' => array('id' => 'h1_font_variant', ' type' => 'select', ' class' => ' select font-variant'),
      '#options' => [
        'select variant',
        'regular',
        'bold',
      ],
      '#default_value' => $theme_settings['h1_font_variant'] ?? 'regular',
    );

    $form['fonts']['h1_font_size'] = array(
      '#type' => 'number',
      '#title' => t('H1 Font Size: '),
      '#attributes' => array('id' => 'h1_font_size', ' type' => 'select', ' class' => ' select font-variant'),
      '#options' => [
        'select variant',
        'regular',
        'bold',
      ],
      '#default_value' => $theme_settings['h1_font_size'] ?? '0',
    );

    $form['fonts']['h2_font'] = array(
      '#type' => 'textfield',
      '#title' => t('H2 Font: '),
      '#attributes' => array('id' => 'h2_font', ' type' => 'text', ' class' => ' font-select'),
      '#default_value' => $theme_settings['h2_font'] ?? 'Bangers',
      '#prefix' => '<div><div class="autocomplete" style="width: 300px;">',
      '#suffix' => '</div></div>',
    );

    $form['fonts']['h2_font_variant'] = array(
      '#type' => 'select',
      '#title' => t('H2 Font Variant: '),
      '#attributes' => array('id' => 'h2_font_variant', ' type' => 'select', ' class' => ' select font-variant'),
      '#options' => [

      ],
      '#default_value' => $theme_settings['h2_font_variant'] ?? '0',
    );

    $form['fonts']['h2_font_variant'] = array(
      '#type' => 'number',
      '#title' => t('H2 Font Size: '),
      '#attributes' => array('id' => 'h2_font_variant', ' type' => 'select', ' class' => ' select font-variant'),
      '#options' => [
        'N/A',
      ],
      '#default_value' => $theme_settings['h2_font_variant'] ?? '0',
    );
    return parent::buildForm($form, $form_state);


  }

  // public function validateForm(array &$form, FormStateInterface $form_state) {
  //
  // }

  /**
   * Chapter Settings form submit handler.
   *
   * @param array $form
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *
   * @return void
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

    $theme_settings = array();
    $theme_settings['background_color'] = $form_state->getValue('background_color');
    $theme_settings['text_color'] = $form_state->getValue('text_color');
    $theme_settings['button_background'] = $form_state->getValue('button_background');
    $theme_settings['button_text'] = $form_state->getValue('button_text');
    $theme_settings['link_color'] = $form_state->getValue('link_color');
//    $theme_settings['body_font'] = $form_state->getValue('body_font');
//    $theme_settings['body_font_variant'] = $form_state->getValue('body_font_variant');
//    $theme_settings['body_font_size'] = $form_state->getValue('body_font_size');
//    $theme_settings['h1_font'] = $form_state->getValue('h1_font');
//    $theme_settings['h1_font_variant'] = $form_state->getValue('h1_font_variant');
//    $theme_settings['h1_font_size'] = $form_state->getValue('h1_font_size');
//    $theme_settings['h2_font'] = $form_state->getValue('h2_font');
//    $theme_settings['h2_font_variant'] = $form_state->getValue('h1_font_variant');
//    $theme_settings['h2_font_size'] = $form_state->getValue('h1_font_size');

    $this->configFactory->getEditable(static::SETTINGS)
      ->set('theme_settings', $theme_settings)
      ->save();

    parent::submitForm($form, $form_state);


  }





}
