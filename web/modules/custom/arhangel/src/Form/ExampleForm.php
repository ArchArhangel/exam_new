<?php

namespace Drupal\arhangel\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\CssCommand;

/**
 * Our example form.
 * Class ExampleForm
 *
 * @package Drupal\arhangel\Form
 */
class ExampleForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return "arhangel_exampleform";
    // TODO: Implement getFormId() method.
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $form['text'] = array(
      '#type' => 'textarea',
      '#title' => $this
        ->t('Text'),
      '#required' => TRUE,
      '#resizable' => 'none',
    );

    $form['copy'] = array(
      '#type' => 'checkbox',
      '#title' => $this
        ->t('Send me a copy'),
    );

    $form['settings']['active'] = array(
      '#type' => 'radios',
      '#title' => $this
        ->t('Poll status'),
      '#default_value' => 1,
      '#options' => array(
        0 => $this
          ->t('Closed'),
        1 => $this
          ->t('Active'),
      ),
    );

    $form['example_select'] = [
      '#type' => 'select',
      '#title' => $this
        ->t('Select element'),
      '#options' => [
        '1' => $this
          ->t('One'),
        '2' => [
          '2.1' => $this
            ->t('Two point one'),
          '2.2' => $this
            ->t('Two point two'),
        ],
        '3' => $this
          ->t('Three'),
      ],
    ];

    $form['expiration'] = array(
      '#type' => 'date',
      '#title' => $this
        ->t('Content expiration'),
      '#default_value' => array(
        'year' => 2020,
        'month' => 2,
        'day' => 15,
      ),
    );

    $form['select'] = [
      '#title' => 'Select some fruit',
      '#type' => 'select',
      '#options' => [
        'apple' => 'Apple',
        'banana' => 'Banana',
        'orange' => 'Orange',
      ],
      '#empty_option' => '- Select -',
      '#required' => TRUE,
      '#ajax' => [
        'callback' => '::validateFruitAjax',
        'event' => 'change',
      ],
      '#prefix' => '<div id="fruit-selector">',
      '#suffix' => '</div>',
    ];

    return $form;
    // TODO: Implement buildForm() method.
  }

  /**
   * {@inheritdoc}
   */
  public function validateFruitAjax(array &$form, FormStateInterface $form_state) {
    $response = new AjaxResponse();
    switch ($form_state->getValue('select')) {
      case 'apple':
        $style = ['border' => '2px solid green'];
        break;

      case 'banana':
        $style = ['border' => '2px solid yellow'];
        break;

      case 'orange':
        $style = ['border' => '2px solid orange'];
        break;

      default:
        $style = ['border' => '2px solid transparent'];
    }
    $response->addCommand(new CssCommand('#fruit-selector select', $style));
    return $response;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    drupal_set_message('Submitting our form...');
    // TODO: Implement submitForm() method.
  }

}
