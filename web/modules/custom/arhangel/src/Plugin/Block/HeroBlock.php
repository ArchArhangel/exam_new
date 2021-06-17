<?php

namespace Drupal\arhangel\Plugin\Block;

use Drupal\Core\Block\Annotation\Block;
use Drupal\Core\Block\BlockBase;

/**
 * Class HeroBlock
 * Provides a block called "Example hero block".
 *
 * @Block(
 *   id="module_hero_hero",
 *   admin_label=@Translation ("Example hero block")
 * )
 * @package Drupal\arhangel\Plugin\Block
 */

class HeroBlock extends BlockBase {

  /**
   * {@inheritDoc}
   * @return array|void
   */
  public function build() {
    $heroes = [
    ['hero_name' => 'Thor', 'real_name' => 'David Banner'],
    ['hero_name' => 'Wolverine', 'real_name' => 'Thor Odinson'],
    ['hero_name' => 'Phoenix', 'real_name' => 'Tony Stark'],
    ['hero_name' => 'Batman', 'real_name' => 'Carl Lucas'],
    ['hero_name' => 'Superman', 'real_name' => 'Matthew Murdock'],
    ['hero_name' => 'Spider-Man', 'real_name' => 'Steven Rogers'],
    ['hero_name' => 'Wonder Woman', 'real_name' => 'Natalia Romanova'],
  ];

    $table = [
      '#type' => 'table',
      '#header' => [
        $this->t('Hero name'),
        $this->t('Real name'),
      ]
    ];

    foreach ($heroes as $hero) {
      $table[] = [
        'hero_name' => [
          '#type' => 'markup',
          '#markup' => $hero['hero_name'],
        ],
        'real_name' => [
          '#type' => 'markup',
          '#markup' => $hero['real_name'],
        ],
      ];
    }

    return $table;
    // TODO: Implement build() method.
  }

}
