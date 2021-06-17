<?php

namespace Drupal\arhangel_new\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\file\Entity\File;
use Drupal\Core\Url;
use Drupal\Core\Form\FormBuilderInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * This is our controller.
 * Class GuestBookController
 *
 * @package Drupal\arhangel_new\Controller
 */
class GuestBookController extends ControllerBase {

  /**
   * @return string[]
   */
  public function custom_page() {
    $query = \Drupal::database()->select('guest_book_form', 'ss');
    $query->fields('ss', ['id', 'name', 'email', 'phone', 'feedback', 'avatar', 'images', 'timestamp']);
    $query->orderBy('id', 'DESC');
    $elements = $query->execute()->fetchAll();

    foreach ($elements as &$element) {
      $element = json_decode(json_encode($element), TRUE);
      $file = File::load($element['avatar']);
      if ($file != NULL) {
        $uri = $file->url();
//        $url = file_create_url($file->getFileUri());
        $url = Url::fromUri(file_create_url($uri))->toString();
        $element['avatar'] = $url;
      }
      else {
        $url = '/modules/custom/arhangel_new/img/default_avatar.jpg';
        $element['avatar'] = $url;
      }
    }

    foreach ($elements as &$element) {
      $element = json_decode(json_encode($element), TRUE);
      $file = File::load($element['images']);
      if ($file != NULL) {
        $uri = $file->url();
        $url = Url::fromUri(file_create_url($uri))->toString();
        $element['images'] = $url;
      }
    }

//    $form_state = new FormState();
//    $form_state->setRebuild();
//    $form = \Drupal::formBuilder()->buildForm('\Drupal\arhangel_new\Form\FirstCustomForm', $form_state);

    $form = \Drupal::formBuilder()->getForm('\Drupal\arhangel_new\Form\FirstCustomForm');

    $user = \Drupal::currentUser();
    $users = $user->hasPermission('administer comments');

//    $user_roles = $user->getRoles();
//    $roles_permissions = user_role_permissions($user_roles);

    return [
        '#theme' => 'guest_book',
        '#fields' => $elements,
        '#form' => $form,
        '#users' => $users,
    ];
  }
}
