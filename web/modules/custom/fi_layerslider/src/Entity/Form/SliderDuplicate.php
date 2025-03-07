<?php

/**
 * @file
 * Contains \Drupal\fi_layerslider\Entity\Form\SliderDuplicate.
 */

namespace Drupal\fi_layerslider\Entity\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\fi_layerslider\Entity\Slider;

/**
 * Provides a form for deleting Slider entities.
 *
 * @ingroup fi_layerslider
 */
class SliderDuplicate extends FormBase {
  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'fi_layerslider_slides_edit';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, $fi_slider = 0) {
    $slider = Slider::load($fi_slider);
    $form['name'] = array(
      '#title' => t('Name'),
      '#type' => 'textfield',
      '#description' => t('The name of the Slider entity.'),
      '#default_value' => 'Copy of ' . $slider->get('name')->getString(),
    );

    $form['slider_id'] = array(
      '#type' => 'hidden',
      '#default_value' => $fi_slider,
    );

    $form['actions'] = array(
      '#type' => 'actions',
    );

    $form['actions']['submit'] = array(
      '#type' => 'submit',
      '#default_value' => $this->t('Duplicate'),
      '#button_type' => 'primary',
    );
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $slider = Slider::load($form_state->getValue('slider_id'));
    $slider_new = $slider->createDuplicate();
    $slider_new->set('name', $form_state->getValue('name'));
    $slider_new->save();
    \Drupal::messenger()->addMessage('Slider has been duplicated.');
    $form_state->setRedirect('entity.fi_slider.collection');
  }

}
