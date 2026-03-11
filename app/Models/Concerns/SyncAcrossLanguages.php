<?php

namespace App\Models\Concerns;

use App\Models\Language;

trait SyncAcrossLanguages
{
  protected static bool $syncingTranslations = false;

  public static function bootSyncAcrossLanguages()
  {
    static::saved(function ($model) {
      if (self::$syncingTranslations) {
        return;
      }

      if (empty($model->language_id)) {
        return;
      }

      $languages = Language::where('id', '!=', $model->language_id)->get();
      if ($languages->isEmpty()) {
        return;
      }

      $keyFields = method_exists($model, 'translationKeyFields') ? $model->translationKeyFields() : [];
      $copyFields = method_exists($model, 'translationCopyFields') ? $model->translationCopyFields() : $model->getFillable();

      if (empty($copyFields)) {
        $copyFields = array_keys($model->getAttributes());
        $copyFields = array_diff($copyFields, ['id', 'language_id', 'created_at', 'updated_at']);
      }

      $data = [];
      foreach ($copyFields as $field) {
        if ($field === 'language_id') {
          continue;
        }
        if (array_key_exists($field, $model->getAttributes())) {
          $data[$field] = $model->{$field};
        }
      }

      self::$syncingTranslations = true;

      foreach ($languages as $language) {
        $query = $model->newQuery()->where('language_id', $language->id);

        if (!empty($keyFields)) {
          foreach ($keyFields as $field) {
            if (array_key_exists($field, $model->getAttributes())) {
              $query->where($field, $model->{$field});
            }
          }
        }

        $target = $query->first();

        if ($target) {
          $target->fill($data);
          $target->save();
        } else {
          $createData = array_merge($data, ['language_id' => $language->id]);
          $model->newQuery()->create($createData);
        }
      }

      self::$syncingTranslations = false;
    });
  }
}
