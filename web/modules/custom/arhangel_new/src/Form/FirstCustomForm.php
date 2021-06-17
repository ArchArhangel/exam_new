<?php

/**
 * @file
 * Contains \Drupal\arhangel_new\Form\FirstCustomForm.
 */
namespace Drupal\arhangel_new\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\RedirectCommand;
use Drupal\Core\Url;
use Drupal\Core\Ajax\OpenModalDialogCommand;
use Drupal\Core\Ajax\HtmlCommand;
use Drupal\file\Entity\File;

/**
 * Our first custom form class.
 * Class FirstCustomForm
 *
 * @package Drupal\arhangel_new\Form
 */
class FirstCustomForm extends FormBase {

  protected int $id;

  /**
   * {@inheritDoc}
   * @return string|void
   */
  public function getFormId() {
    return "arhangel_new_first_custom_form";
    // TODO: Implement getFormId() method.
  }

  /**
   * {@inheritDoc}
   * @param array $form
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *
   * @return array|void
   */
  public function buildForm(array $form, FormStateInterface $form_state, int $id = NULL) {

    $this->id = 0;

    $elements = NULL;
    if (!is_null($id)) {
      $query = \Drupal::database()->select('guest_book_form', 'ss');
      $query->fields('ss', ['name', 'email', 'phone', 'feedback', 'avatar', 'images', 'timestamp']);
      $query->condition('id', $id, '=');
      $elements = $query->execute()->fetch();
      $this->id = $id;
    }

    $form['message'] = [
      '#type' => 'markup',
      '#markup' => '<div class="form-message"></div>',
    ];

    $form['name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Name:'),
//      '#size' => '60',
      '#maxlength' => '100',
      '#required' => TRUE,
      '#default_value' => ($elements) ? $elements->name : '',
      '#ajax' => [
        'callback' => '::validateNameAjax',
        'event' => 'change',
        'progress' => [
          'type' => 'throbber',
          'message' => @$this->t('Verifying name...'),
        ],
      ],
      '#suffix' => '<div class="name-validation-message"></div>'
    ];

    $form['email'] = [
      '#type' => 'email',
      '#title' => $this->t('Email:'),
      '#required' => TRUE,
      '#default_value' => ($elements) ? $elements->email : '',
      '#ajax' => [
        'callback' => '::validateEmailAjax',
        'event' => 'change',
        'progress' => [
          'type' => 'throbber',
          'message' => @$this->t('Verifying email...'),
        ],
      ],
      '#suffix' => '<div class="email-validation-message"></div>'
    ];

    $form['phone'] = [
      '#type' => 'tel',
      '#title' => $this->t('Phone:'),
      '#maxlength' => '12',
      '#required' => TRUE,
      '#default_value' => ($elements) ? $elements->phone : '',
      '#ajax' => [
        'callback' => '::validatePhoneAjax',
        'event' => 'change',
        'progress' => [
          'type' => 'throbber',
          'message' => @$this->t('Verifying phone...'),
        ],
      ],
      '#suffix' => '<div class="phone-validation-message"></div>'
    ];

    $form['feedback'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Your feedback:'),
      '#required' => TRUE,
      '#default_value' => ($elements) ? $elements->feedback : '',
    ];

    $form['avatar'] = [
      '#type' => 'managed_file',
      '#title' => 'avatar',
      '#description' => 'Your avatar',
      '#upload_location' => 'public://guest_book/avatars',
      '#default_value' => ($elements) ? [$elements->avatar] : [],
      '#upload_validators' => [
        'file_validate_is_image' => [],
        'file_validate_extensions' => ['jpg jpeg png gif'],
        'file_validate_size' => [2 * 1024 * 1024],
      ],
//      '#theme' => 'image_widget',
//      '#preview_image_style' => 'medium',
//      '#progress_indicator' => "bar",
    ];

    $form['images'] = [
      '#type' => 'managed_file',
      '#title' => 'images',
      '#description' => 'Your image',
      '#upload_location' => 'public://guest_book/images',
      '#default_value' => ($elements) ? [$elements->images] : [],
      '#upload_validators' => [
        'file_validate_is_image' => [],
        'file_validate_extensions' => ['jpg jpeg png gif'],
        'file_validate_size' => [5 * 1024 * 1024],
      ],
    ];

    /*
    $form['other'] = array(
    '#type' => 'fieldset',
    '#title' => 'Дополнительные настройки',
    '#weight' => 5,
    '#collapsible' => TRUE,
    '#collapsed' => FALSE,
    );
    */

    if ($this->id !== 0) {
      $form['remove'] = [
        '#type' => 'button',
        '#value' => $this->t('Delete form'),
        '#ajax' => [
          'callback' => '::ajaxDeleteFormCallback',
          'event' => 'click',
        ],
      ];
    }

  if ($this->id == 0) {
    $form['reset'] = [
      '#type' => 'button',
      '#button_type' => 'reset',
      '#value' => $this->t('Reset form'),
      '#attributes' => [
        'onclick' => 'this.form.reset(); return false;',
      ],
    ];
  }

    $form['submit'] = [
      '#type' => 'submit',
      '#value' => ($this->id !== 0) ? $this->t('Save form') : $this->t('Send form'),
      '#ajax' => [
        'callback' => '::ajaxSendFormCallback',
        'event' => 'click',
        'progress' => [
          'type' => 'throbber',
        ],
      ],
    ];

    return $form;
    // TODO: Implement buildForm() method.
  }

  /**
   * Our form validation.
   * {@inheritDoc}
   * @param array $form
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {

    /*
    if (strlen($form_state->getValue('name')) < 2 || strlen($form_state->getValue('name')) > 100) {
      $form_state->setErrorByName('name', $this->t('Your name must be between 2 and 100 characters!'));
    }
    */
    parent::validateForm($form, $form_state); // TODO: Change the autogenerated stub

  }

  /**
   * Our name validation.
   * @param array $form
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *
   * @return \Drupal\Core\Ajax\AjaxResponse
   */
  public function validateNameAjax (array &$form, FormStateInterface $form_state) {
    $response = new AjaxResponse();
    if (strlen($form_state->getValue('name')) < 2 || strlen($form_state->getValue('name')) > 100) {
      $response->addCommand(new HtmlCommand('.name-validation-message', 'Your name must be between 2 and 100 characters!'));
    }
    else {
      $response->addCommand(new HtmlCommand('.name-validation-message', ''));
    }
    return $response;
  }

  /**
   * Our email validation.
   * @param array $form
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *
   * @return \Drupal\Core\Ajax\AjaxResponse
   */
  public function validateEmailAjax(array &$form, FormStateInterface $form_state) {
    $response = new AjaxResponse();
    if (!\Drupal::service('email.validator')->isValid($form_state->getValue('email'))) {
//    if (substr($form_state->getValue('email'), -11) == 'example.com' || strpos($form_state->getValue('email'), '@mail.com') === FALSE) {
      $response->addCommand(new HtmlCommand('.email-validation-message', 'Your email is not valid, it must be like name@mail.com'));
    }
    else {
      $response->addCommand(new HtmlCommand('.email-validation-message', ''));
    }
    return $response;
  }

  /**
   * Our phone validation.
   * @param array $form
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *
   * @return \Drupal\Core\Ajax\AjaxResponse
   */
  public function validatePhoneAjax (array &$form, FormStateInterface $form_state) {
    $response = new AjaxResponse();
    if (!is_numeric($form_state->getValue('phone')) || strlen($form_state->getValue('phone')) < 12) {
      $response->addCommand(new HtmlCommand('.phone-validation-message', 'The phone format must be 380 123 456 789'));
    }
    else {
      $response->addCommand(new HtmlCommand('.phone-validation-message', ''));
    }
    return $response;
  }

  /**
   * Our ajax submit.
   * @param array $form
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *
   * @return \Drupal\Core\Ajax\AjaxResponse
   */

  public function ajaxSendFormCallback(array &$form, FormStateInterface &$form_state): AjaxResponse {
    $response = new AjaxResponse();

    $url = Url::fromRoute('arhangel_new.page');
    $command = new RedirectCommand($url->toString());
    $response->addCommand($command);
    \Drupal::messenger()->addMessage(t('Comment sent!'));
//    $form_state->setRedirectUrl($url);

    return $response;
  }

  public  function  ajaxDeleteFormCallback (array &$form, FormStateInterface $form_state) {
    $response = new AjaxResponse();

    $query = \Drupal::database()->select('guest_book_form', 'ss');
    $query->fields('ss', ['name', 'email', 'phone', 'feedback', 'avatar', 'images', 'timestamp']);
    $query->condition('id', $this->id, '=');
    $elements = $query->execute()->fetch();

    $file = File::load($elements->avatar);
    if (!empty($file)) {
      $file->delete();
    }

    $file = File::load($elements->images);
    if (!empty($file)) {
      $file->delete();
    }

    $query = \Drupal::database()->delete('guest_book_form');
    $query->condition('id', $this->id, '=');
    $query->execute();

    $url = Url::fromRoute('arhangel_new.page');
    $command = new RedirectCommand($url->toString());
    $response->addCommand($command);
    \Drupal::messenger()->addMessage(t('Comment delete!'));

    return $response;
  }

  /*
  public function ajaxModal(array &$form, FormStateInterface $form_state) {
    $content['#markup'] = $form_state->getValue('text');
    $content['#attached']['library'][] = 'core/drupal.dialog.ajax';
    $title = 'Here is your content in modal';
    $response = new AjaxResponse();
    $response->addCommand(new OpenModalDialogCommand($title, $content, ['width' => '400', 'height' => '400']));
    return $response;
  }
  */

  /**
   * Our submit button.
   * {@inheritDoc}
   * @param array $form
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

    $avatar = 0;
    $item = $form_state->getValue('avatar');
    $file = File::load($item[0]);
    if (!empty($file)) {
//      $file->status = FILE_STATUS_PERMANENT;
      $file->setPermanent();
      $file->save();
      $avatar = $item[0];
    }

    $images = 0;
    $item = $form_state->getValue('images');
    $file = File::load($item[0]);
    if (!empty($file)) {
      $file->setPermanent();
      $file->save();
      $images = $item[0];
    }

    if ($this->id === 0) {
      $query = \Drupal::database()->insert('guest_book_form');
      $query->fields([
        'name' => $form_state->getValue('name'),
        'email' => $form_state->getValue('email'),
        'phone' => $form_state->getValue('phone'),
        'feedback' => $form_state->getValue('feedback'),
        'avatar' => $avatar,
        'images' => $images,
        'timestamp' => time(),
      ]);
      $query->execute();
    }
    else {
      $query = \Drupal::database()->upsert('guest_book_form');
      $query->fields([
        'id' => $this->id,
        'name' => $form_state->getValue('name'),
        'email' => $form_state->getValue('email'),
        'phone' => $form_state->getValue('phone'),
        'feedback' => $form_state->getValue('feedback'),
        'avatar' => $avatar,
        'images' => $images,
        'timestamp' => time(),
      ]);
      $query->key('id');
      $query->execute();
    }
    // TODO: Implement submitForm() method.
  }

}
