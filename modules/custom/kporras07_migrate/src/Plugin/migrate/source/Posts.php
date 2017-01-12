<?php

/**
 * @file
 * Contains \Drupal\kporras07_migrate\Plugin\migrate\source\Posts.
 */

namespace Drupal\kporras07_migrate\Plugin\migrate\source;

use Drupal\migrate\Plugin\migrate\source\SqlBase;
use Drupal\migrate\Row;

/**
 * Drupal 8 node from database.
 *
 * @MigrateSource(
 *   id = "posts"
 * )
 */
class Posts extends SqlBase {

  /**
   * {@inheritdoc}
   */
  public function query() {
    $query = $this->select('kporras07_posts', 'p')
      ->fields('p', array(
        'id',
        'title',
        'date',
        'type',
        'categories',
        'tags',
        'body',
      ));
    return $query;
  }

  /**
   * {@inheritdoc}
   */
  public function fields() {
    $fields = array(
      'id' => $this->t('ID in the intermediate table'),
      'title' => $this->t('Title of the content'),
      'body' => $this->t('Full text of the content'),
      'date' => $this->t('Post date'),
      'categories' => $this->t('Post categories'),
      'tags' => $this->t('Post tags'),
      'type' => $this->t('Post type'),
    );

    return $fields;
  }

  /**
   * {@inheritdoc}
   */
  public function getIds() {
    return array(
      'id' => array(
        'type' => 'string',
        'alias' => 'p',
      ),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function prepareRow(Row $row) {
    if (parent::prepareRow($row) === FALSE) {
      return FALSE;
    }

    $categories = $row->getSourceProperty('categories');
    if (!empty($categories)) {
      $categories = explode(',', $categories);
    }
    $row->setSourceProperty('categories', $categories);

    $tags = $row->getSourceProperty('tags');
    if (!empty($tags)) {
      $tags = explode(',', $tags);
    }
    $row->setSourceProperty('tags', $tags);
  }

}
