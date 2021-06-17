<?php

namespace Drupal\arhangel\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\HtmlCommand;

/**
 * Our custom ajax form.
 * Class AjaxHeroForm
 */
class AjaxHeroForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return "arhangel_ajaxhero";
    // TODO: Implement getFormId() method.
  }

  /**
   * {@inheritDoc}
   * @param array $form
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *
   * @return array
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $form['message'] = [
      '#type' => 'markup',
      '#markup' => '<div class="result_message"></div>',
    ];

    $form['rival_1'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Rival one'),
    ];

    $form['rival_2'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Rival two'),
    ];

    $form['email'] = [
      '#title' => 'Email',
      '#type' => 'email',
      '#required' => TRUE,
      '#ajax' => [
        # Если валидация находится в другом классе, то необходимо указывать
        # в формате Drupal\modulename\ClassName::methodName.
        'callback' => '::validateEmailAjax',
        # Событие, на которое будет срабатывать наш AJAX.
        'event' => 'change',
        # Настройки прогресса. Будет показана гифка с анимацией загрузки.
        'progress' => array(
          'type' => 'throbber',
          'message' => t('Verifying email..'),
        ),
      ],
      # Элемент, в который мы будем писать результат в случае необходимости.
      '#suffix' => '<div class="email-validation-message"></div>'
    ];


    $form['submit'] = [
      '#type' => 'button',
      '#value' => $this->t('Who will win?'),
      '#ajax' => [
        'callback' => '::setMessage',
      ],
    ];

    return $form;
    // TODO: Implement buildForm() method.
  }

  /**
   * Our custom Ajax response.
   * @param array $form
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *
   * @return \Drupal\Core\Ajax\AjaxResponse
   */
  public function setMessage(array &$form, FormStateInterface $form_state) {
    $winner = rand(1, 2);
    $response = new AjaxResponse();
    $response->addCommand(
      new HtmlCommand(
        '.result_message',
        'The winner is ' . $form_state->getValue('rival_' . $winner)
      )
    );

    return $response;
  }

  public function submitForm(array &$form, FormStateInterface $form_state) {
    // TODO: Implement submitForm() method.
  }

}
