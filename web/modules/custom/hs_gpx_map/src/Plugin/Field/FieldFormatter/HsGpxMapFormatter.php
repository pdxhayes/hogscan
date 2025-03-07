<?php

namespace Drupal\hs_gpx_map\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\file\Plugin\Field\FieldFormatter\DescriptionAwareFileFormatterBase;

/**
 * Plugin implementation of the 'GPX Map' formatter.
 *
 * @FieldFormatter(
 *   id = "hs_gpx_map",
 *   label = @Translation("GPX Map"),
 *   field_types = {
 *     "file"
 *   }
 * )
 */
class HsGpxMapFormatter extends DescriptionAwareFileFormatterBase
{

  /**
   * {@inheritdoc}
   */
  public static function defaultSettings()
  {
    return [
        'width' => '100%',
        'height' => '300px',
        'zoom' => '8',
        'min_zoom' => '0',
        'max_zoom' => '0',
        'controltype' => 'default',
        'mtc' => 'standard',
        'pancontrol' => 1,
        'maptype' => 'map',
        'baselayers_map' => 1,
        'baselayers_satellite' => 1,
        'scale' => 0,
        'overview' => 0,
        'overview_opened' => 0,
        'scrollwheel' => 0,
        'draggable' => 0,
        'streetview_show' => 0,
        'use_description_as_link_text' => true,
      ] + parent::defaultSettings();
  }

  public function settingsSummary()
  {
    $summary = [];
    $settings = $this->getSettings();

    if ($settings['width']) {
      $summary[] = $this->t('Width: @w', array('@w' => $settings['width']));
    }
    if ($settings['height']) {
      $summary[] = $this->t('Height: @h', array('@h' => $settings['height']));
    }
    if ($settings['zoom']) {
      $summary[] = $this->t('Zoom: @z', array('@z' => $settings['zoom']));
    }
    if ($settings['min_zoom']) {
      $summary[] = $this->t('Zoom minimum: @z', array('@z' => $settings['min_zoom']));
    }
    if ($settings['max_zoom']) {
      $summary[] = $this->t('Zoom maximum: @z', array('@z' => $settings['max_zoom']));
    }
    if ($settings['controltype']) {
      $summary[] = $this->t('Zoom Control Type: @z', array('@z' => $settings['controltype']));
    }
    if ($settings['mtc']) {
      $summary[] = $this->t('Map Control Type: @m', array('@m' => $settings['mtc']));
    }
    if ($settings['pancontrol']) {
      $summary[] = $this->t('Show Pan Control: @yn', array('@yn' => ($settings['pancontrol'] ? 'Yes' : 'No')));
    }
    if ($settings['maptype']) {
      $summary[] = $this->t('Default Map Type: @m', array('@m' => $settings['maptype']));
    }
    if ($settings['scale']) {
      $summary[] = $this->t('Show Scale: @yn', array('@yn' => ($settings['scale'] ? 'Yes' : 'No')));
    }
    if ($settings['overview']) {
      $summary[] = $this->t('Overview Map: @yn', array('@yn' => ($settings['overview'] ? 'Yes' : 'No')));
    }
    if ($settings['scrollwheel']) {
      $summary[] = $this->t('Scrollwheel: @yn', array('@yn' => ($settings['scrollwheel'] ? 'Yes' : 'No')));
    }
    if ($settings['draggable']) {
      $summary[] = $this->t('Draggable: @yn', array('@yn' => ($settings['draggable'] ? 'Yes' : 'No')));
    }
    if ($settings['streetview_show']) {
      $summary[] = $this->t('Show streetview button: @yn', array('@yn' => ($settings['streetview_show'] ? 'Yes' : 'No')));
    }
    if ($this->getSetting('use_description_as_link_text')) {
      $summary[] = $this->t('Use description as link text');
    }
    return $summary;
  }

  public function settingsForm(array $form, FormStateInterface $form_state)
  {
    define('MAX_ZOOM', 22);
    $form['width'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Map width'),
      '#default_value' => $this->getSetting('width'),
      '#size' => 25,
      '#maxlength' => 25,
      '#description' => $this->t('The default width of a Google map, as a CSS length or percentage. Examples: <em>50px</em>, <em>5em</em>, <em>2.5in</em>, <em>95%</em>'),
      '#required' => TRUE,
    );
    $form['height'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Map height'),
      '#default_value' => $this->getSetting('height'),
      '#size' => 25,
      '#maxlength' => 25,
      '#description' => $this->t('The default height of a Google map, as a CSS length or percentage. Examples: <em>50px</em>, <em>5em</em>, <em>2.5in</em>, <em>95%</em>'),
      '#required' => TRUE,
    );
    $form['zoom'] = array(
      '#type' => 'select',
      '#title' => $this->t('Zoom'),
      '#default_value' => $this->getSetting('zoom') ? $this->getSetting('zoom') : min(8, MAX_ZOOM),
      '#options' => range(0, MAX_ZOOM),
      '#description' => $this->t('The default zoom level of a Google map.'),
    );
    $form['min_zoom'] = array(
      '#type' => 'select',
      '#title' => $this->t('Zoom minimum'),
      '#default_value' => $this->getSetting('min_zoom') ? $this->getSetting('min_zoom') : 0,
      '#options' => range(0, MAX_ZOOM),
      '#description' => $this->t('The minimum zoom level of a Google map.'),
    );
    $form['max_zoom'] = array(
      '#type' => 'select',
      '#title' => $this->t('Zoom maximum'),
      '#default_value' => $this->getSetting('max_zoom') ? $this->getSetting('max_zoom') : 0,
      '#options' => range(0, MAX_ZOOM),
      '#description' => $this->t('The maximum zoom level of a Google map. Set to 0 to ignore limit.'),
    );
    $form['controltype'] = array(
      '#type' => 'select',
      '#title' => $this->t('Zoom Control Type'),
      '#options' => array(
        'none' => $this->t('None'),
        'default' => $this->t('Default'),
        'small' => $this->t('Small'),
        'large' => $this->t('Large'),
      ),
      '#default_value' => $this->getSetting('controltype'),
    );
    $form['mtc'] = array(
      '#type' => 'select',
      '#title' => $this->t('Map Control Type'),
      '#options' => array(
        'none' => $this->t('None'),
        'standard' => $this->t('Horizontal bar'),
        'menu' => $this->t('Dropdown'),
      ),
      '#default_value' => $this->getSetting('mtc'),
    );
    $form['pancontrol'] = array(
      '#type' => 'checkbox',
      '#title' => $this->t('Show Pan control'),
      '#default_value' => $this->getSetting('pancontrol'),
      '#return_value' => 1,
    );

    $mapopts = array('map' => $this->t('Standard street map'));
    if ($this->getSetting('baselayers_satellite')) {
      $mapopts['satellite'] = $this->t('Standard satellite map');
    }
    $form['maptype'] = array(
      '#type' => 'select',
      '#title' => $this->t('Default Map Type'),
      '#default_value' => $this->getSetting('maptype'),
      '#options' => array(
        'map' => $this->t('Standard street map'),
        'satellite' => $this->t('Standard satellite map'),
      ),
    );
    $form['scale'] = array(
      '#type' => 'checkbox',
      '#title' => $this->t('Scale'),
      '#description' => $this->t('Show scale'),
      '#default_value' => $this->getSetting('scale'),
      '#return_value' => 1,
    );
    $form['overview'] = array(
      '#type' => 'checkbox',
      '#title' => $this->t('Overview map'),
      '#description' => $this->t('Show overview map'),
      '#default_value' => $this->getSetting('overview'),
      '#return_value' => 1,
    );

    $form['overview_opened'] = array(
      '#type' => 'checkbox',
      '#title' => $this->t('Overview map state'),
      '#description' => $this->t('Show overview map as open by default'),
      '#default_value' => $this->getSetting('overview_opened'),
      '#return_value' => 1,
    );
    $form['scrollwheel'] = array(
      '#type' => 'checkbox',
      '#title' => $this->t('Scrollwheel'),
      '#description' => $this->t('Enable scrollwheel zooming'),
      '#default_value' => $this->getSetting('scrollwheel'),
      '#return_value' => 1,
    );
    $form['draggable'] = array(
      '#type' => 'checkbox',
      '#title' => $this->t('Draggable'),
      '#description' => $this->t('Enable dragging on the map'),
      '#default_value' => $this->getSetting('draggable'),
      '#return_value' => 1,
    );
    $form['streetview_show'] = array(
      '#type' => 'checkbox',
      '#title' => $this->t('Show streetview button'),
      '#default_value' => $this->getSetting('streetview_show'),
      '#return_value' => 1,
    );
    $form['use_description_as_link_text'] = [
      '#title' => $this->t('Use description as link text'),
      '#description' => $this->t('Replace the file name by its description when available'),
      '#type' => 'checkbox',
      '#default_value' => $this->getSetting('use_description_as_link_text'),
    ];

    return $form;
  }


  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode)
  {
    $elements = [];
    foreach ($this->getEntitiesToView($items, $langcode) as $delta => $file) {
      $item = $file->_referringItem;

      $elements[$delta] = [
        '#theme' => 'gpx_map',
        '#file' => $file,
        '#description' => $this->getSetting('use_description_as_link_text') ? $item->description : NULL,
        '#settings' => $this->getSettings(),
        '#id' => str_replace('_', '-', $items->getName()),
        '#key' => $delta,
        '#cache' => [
          'tags' => $file->getCacheTags(),
        ],
      ];
      // Pass field item attributes to the theme function.
      if (isset($item->_attributes)) {
        $elements[$delta] += ['#attributes' => []];
        $elements[$delta]['#attributes'] += $item->_attributes;
        unset($item->_attributes);
      }
    }

    return $elements;
  }

}
