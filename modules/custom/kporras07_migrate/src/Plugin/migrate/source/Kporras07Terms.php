<?php

/**
 * @file
 * Contains \Drupal\kporras07_migrate\Plugin\migrate\source\Kporras07Terms.
 */

namespace Drupal\kporras07_migrate\Plugin\migrate\source;

use Drupal\migrate\Plugin\migrate\source\SqlBase;

/**
 * Drupal 8 term from database.
 *
 * @MigrateSource(
 *   id = "kporras07_terms"
 * )
 */
class Kporras07Terms extends SqlBase {

  /**
   * {@inheritdoc}
   */
  public function query() {
    $query = $this->select('kporras07_terms', 't')
      ->fields('t', array(
        'name',
        'type',
      ));
    return $query;
  }

  /**
   * {@inheritdoc}
   */
  public function fields() {
    $fields = array(
      'name' => $this->t('Name of the term'),
      'type' => $this->t('Vocabulary of the term'),
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
      'type' => array(
        'type' => 'string',
        'alias' => 't',
      ),
    );
  }

}
