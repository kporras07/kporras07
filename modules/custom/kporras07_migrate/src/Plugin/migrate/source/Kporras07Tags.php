<?php

/**
 * @file
 * Contains \Drupal\kporras07_migrate\Plugin\migrate\source\Kporras07Tags.
 */

namespace Drupal\kporras07_migrate\Plugin\migrate\source;

use Drupal\migrate\Plugin\migrate\source\SqlBase;

/**
 * Drupal 8 tag from database.
 *
 * @MigrateSource(
 *   id = "kporras07_tags"
 * )
 */
class Kporras07Tags extends SqlBase {

  /**
   * {@inheritdoc}
   */
  public function query() {
    $query = $this->select('kporras07_terms', 't')
      ->fields('t', array(
        'name',
      ))
      ->condition('type', 'tags');
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
