<?php

/**
 * @file
 * Contains \Drupal\kporras07_migrate\Plugin\migrate\source\Kporras07Categories.
 */

namespace Drupal\kporras07_migrate\Plugin\migrate\source;

use Drupal\migrate\Plugin\migrate\source\SqlBase;

/**
 * Drupal 8 category from database.
 *
 * @MigrateSource(
 *   id = "kporras07_categories"
 * )
 */
class Kporras07Categories extends SqlBase {

  /**
   * {@inheritdoc}
   */
  public function query() {
    $query = $this->select('kporras07_terms', 't')
      ->fields('t', array(
        'name',
      ))
      ->condition('type', 'category');
    return $query;
  }

  /**
   * {@inheritdoc}
   */
  public function fields() {
    $fields = array(
      'name' => $this->t('Name of the term'),
    );

    return $fields;
  }

  /**
   * {@inheritdoc}
   */
  public function getIds() {
    return array(
      'name' => array(
        'type' => 'string',
        'alias' => 't',
      ),
    );
  }

}
