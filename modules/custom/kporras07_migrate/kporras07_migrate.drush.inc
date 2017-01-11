<?php

/**
 * @file
 * Drush commands for kporras07_migrate.
 */

/**
 * Implements hook_drush_command().
 */
function kporras07_migrate_drush_command() {
  $items = array();
  $items['kporras07-premigrate'] = [
    'description' =>  'Premigrate stuff',
    'aliases' => ['premigrate'],
  ];

  return $items;
}

/**
 * Pre migrate command.
 */
function drush_kporras07_migrate_kporras07_premigrate() {
  kporras07_migrate_process_html('personal');
  kporras07_migrate_process_html('tech');
  /*
   *kporras07_migrate_process_md('personal');
   *kporras07_migrate_process_md('tech');
   */
}

/**
 * Process html files.
 *
 * @param string folder
 *   Folder to lookup for files.
 */
function kporras07_migrate_process_html($type) {
  require_once libraries_get_path('querypath') . '/src/qp.php';
  $folder = drupal_get_path('module', 'kporras07_migrate') . '/data/' . $type;
  $folder = \Drupal::service('file_system')->realpath($folder);
  $files = file_scan_directory($folder, '/\.html$/');
  $printed = FALSE;
  foreach ($files as $file) {
    $filename = $file->filename;
    $uri = $file->uri;
    drush_log('Processing file: ' . $uri);
    drush_print('Processing file: ' . $uri);
    $contents = file_get_contents($uri);

    // Clean files.
    $contents = str_replace('<meta http-equiv="Content-Type" content="text/html; charset=utf-8">', '', $contents);
    $contents = preg_replace('/<meta name=\"description\" content=\".*\">/', '', $contents);
    $contents = preg_replace('/<hr class=\"[A-Za-z-]*\">/', "$0</hr>", $contents);
    $contents = preg_replace('/<img [A-Za-z0-9=\"\-\s\*\_\.\:\/]*>/', "$0</img>", $contents);
    $contents = preg_replace('/<br>/', "$0</br>", $contents);
    $contents = preg_replace('/<style>.*<\/style>/s', '', $contents);
    $contents = preg_replace('/\n/s', '', $contents);

    $qp = qp($contents, 'html', array(
      'use_parser' => 'xml',
      'replace_entities' => TRUE,
    ));
    $title = $qp->find('head title')->innerHTML();
    $body = $qp->find('body article');
    $body->find('header')->remove();
    $footer = $body->find('footer');
    $body->find('footer')->remove();
    $body = $body->innerHTML();
    $permalink = '';
    foreach ($footer->find('a') as $a) {
      if (strpos($a->attr('href'), 'https://medium.com/p/') === 0) {
        $permalink = $a->attr('href');
      }
    }
    $date_parts = explode('-', $filename);
    $date_parts = array_slice($date_parts, 0, 3);
    $date_string = $date_parts[0] . '-' . $date_parts[1] . '-' . substr($date_parts[2], 0, 2);
    $date = strtotime($date_string);

    $html5qp = html5qp($permalink);
    $tags = array();
    foreach ($html5qp->find('.tags--postTags a') as $tag) {
      $tags[] = $tag->innerHtml();
    }
    $tags = implode(',', $tags);

    $record = array(
      'title' => $title,
      'date' => $date,
      'type' => $type,
      'categories' => '',
      'tags' => $tags,
      'permalink' => $permalink,
      'file' => $filename,
      'body' => $body,
      'id' => hash('md5', $filename)
    );

    \Drupal::database()->merge('kporras07_posts')
      ->key(array('id' => $record['id']))
      ->fields($record)
      ->execute();

  }
}
