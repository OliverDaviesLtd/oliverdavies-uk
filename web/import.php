<?php

use Drupal\node\Entity\Node;
use Drupal\taxonomy\Entity\Term;

$articles = json_decode(file_get_contents(__DIR__.'/export.json'), true)['articles'];

$termStorage = \Drupal::entityTypeManager()->getStorage('taxonomy_term');

foreach ($articles as $key => $article) {
  echo "$key...\n";

  $tags = [];
  foreach ($article['tags'] as $tag) {
    if ($tag == 'drafts') continue;

    if ($existingTags = $termStorage->loadByProperties(['vid' => 'tags', 'name' => $tag])) {
      $tags[] = collect($existingTags)->first()->id();
    }
    else {
      $tag = Term::create(['vid' => 'tags', 'name' => $tag]);
      $tag->save();
      $tags[] = $tag->id();
    }
  }

  $path = $article['path'];
  $path = str_replace('articles/', 'blog/', $path);

  $node = Node::create([
    'title' => $article['title'],
    'type' => 'post',
    'created' => $article['created'],
    'changed' => $article['created'],
    'status' => $article['is_draft'] === 'true' ? FALSE : TRUE,
    'uid' => 1,
    'path' => $path,
    'body' => ['value' => $article['body'], 'format' => 'full_html'],
    'field_excerpt' => $article['excerpt'],
    'field_tags' => $tags,
  ]);

  $node->save();
}
