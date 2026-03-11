<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\BasicSettings\BasicExtends;
use App\Models\BasicSettings\CookieAlert;
use App\Models\BasicSettings\PageHeading;
use App\Models\BasicSettings\SEO;
use App\Models\Blog\BlogCategory;
use App\Models\Blog\PostInformation;
use App\Models\ClientService\Form;
use App\Models\ClientService\ServiceAddon;
use App\Models\ClientService\ServiceCategory;
use App\Models\ClientService\ServiceContent;
use App\Models\ClientService\ServiceFaq;
use App\Models\ClientService\ServicePackage;
use App\Models\ClientService\ServiceSubcategory;
use App\Models\CustomPage\PageContent;
use App\Models\FAQ;
use App\Models\Footer\FooterContent;
use App\Models\Footer\QuickLink;
use App\Models\HomePage\AboutSection;
use App\Models\HomePage\CtaSectionInfo;
use App\Models\HomePage\Feature;
use App\Models\HomePage\HeroSlider;
use App\Models\HomePage\HeroStatic;
use App\Models\HomePage\SectionTitle;
use App\Models\HomePage\Testimonial;
use App\Models\Language;
use App\Models\MenuBuilder;
use App\Models\Popup;
use App\Models\Skill;
use Illuminate\Support\Facades\DB;

function ensureOne(string $modelClass, int $sourceId, int $targetId, bool $force): int
{
  $source = $modelClass::where('language_id', $sourceId)->first();
  if (empty($source)) {
    return 0;
  }
  $target = $modelClass::where('language_id', $targetId)->first();
  if (!empty($target)) {
    if ($force) {
      $data = $source->toArray();
      unset($data['id'], $data['language_id'], $data['created_at'], $data['updated_at']);
      $target->fill($data);
      $target->save();
      return 1;
    }
    return 0;
  }
  $copy = $source->replicate();
  $copy->language_id = $targetId;
  $copy->save();
  return 1;
}

function ensureManyBy(string $modelClass, int $sourceId, int $targetId, array $keyFields, bool $force, callable $transform = null): array
{
  $created = 0;
  $skipped = 0;
  $updated = 0;

  $items = $modelClass::where('language_id', $sourceId)->get();
  foreach ($items as $item) {
    $query = $modelClass::where('language_id', $targetId);
    foreach ($keyFields as $field) {
      $query->where($field, $item->{$field});
    }
    $target = $query->first();
    if (!empty($target)) {
      if ($force) {
        $data = $item->toArray();
        unset($data['id'], $data['language_id'], $data['created_at'], $data['updated_at']);
        $target->fill($data);
        if ($transform) {
          $transform($target, $item);
        }
        $target->save();
        $updated++;
      } else {
        $skipped++;
      }
      continue;
    }

    $copy = $item->replicate();
    $copy->language_id = $targetId;
    if ($transform) {
      $transform($copy, $item);
    }
    $copy->save();
    $created++;
  }

  return [$created, $updated, $skipped];
}

$sourceCode = $argv[1] ?? null;
$targetCode = $argv[2] ?? 'fr';
$force = in_array('--force', $argv, true);

$source = $sourceCode
  ? Language::where('code', $sourceCode)->first()
  : Language::where('is_default', 1)->first();
$target = Language::where('code', $targetCode)->first();

if (empty($source) || empty($target)) {
  echo "Missing source or target language.\n";
  exit(1);
}

$sourceId = (int) $source->id;
$targetId = (int) $target->id;

DB::beginTransaction();

try {
  $stats = [];

  $stats['page_heading'] = ensureOne(PageHeading::class, $sourceId, $targetId, $force);
  $stats['seo'] = ensureOne(SEO::class, $sourceId, $targetId, $force);
  $stats['cookie_alert'] = ensureOne(CookieAlert::class, $sourceId, $targetId, $force);
  $stats['footer_content'] = ensureOne(FooterContent::class, $sourceId, $targetId, $force);
  $stats['menu_builder'] = ensureOne(MenuBuilder::class, $sourceId, $targetId, $force);
  $stats['section_title'] = ensureOne(SectionTitle::class, $sourceId, $targetId, $force);
  $stats['about_section'] = ensureOne(AboutSection::class, $sourceId, $targetId, $force);
  $stats['hero_static'] = ensureOne(HeroStatic::class, $sourceId, $targetId, $force);
  $stats['basic_extends'] = ensureOne(BasicExtends::class, $sourceId, $targetId, $force);
  $stats['cta_section'] = ensureOne(CtaSectionInfo::class, $sourceId, $targetId, $force);

  $stats['footer_quick_links'] = ensureManyBy(QuickLink::class, $sourceId, $targetId, ['title', 'url'], $force);

  $stats['hero_slider'] = ensureManyBy(HeroSlider::class, $sourceId, $targetId, ['title'], $force);
  $stats['features'] = ensureManyBy(Feature::class, $sourceId, $targetId, ['title'], $force);
  $stats['testimonials'] = ensureManyBy(Testimonial::class, $sourceId, $targetId, ['name'], $force);
  $stats['popups'] = ensureManyBy(Popup::class, $sourceId, $targetId, ['name'], $force);

  $stats['blog_categories'] = ensureManyBy(BlogCategory::class, $sourceId, $targetId, ['slug'], $force);
  $stats['post_information'] = ensureManyBy(PostInformation::class, $sourceId, $targetId, ['post_id'], $force);

  $stats['faqs'] = ensureManyBy(FAQ::class, $sourceId, $targetId, ['question'], $force);

  $stats['skills'] = ensureManyBy(Skill::class, $sourceId, $targetId, ['slug'], $force);

  $stats['service_categories'] = ensureManyBy(ServiceCategory::class, $sourceId, $targetId, ['slug'], $force);
  $stats['service_subcategories'] = ensureManyBy(ServiceSubcategory::class, $sourceId, $targetId, ['slug'], $force);

  $stats['forms'] = ensureManyBy(Form::class, $sourceId, $targetId, ['name', 'seller_id'], $force);

  $categorySlugById = ServiceCategory::where('language_id', $sourceId)->pluck('slug', 'id')->toArray();
  $categoryIdBySlugTarget = ServiceCategory::where('language_id', $targetId)->pluck('id', 'slug')->toArray();

  $subcategorySlugById = ServiceSubcategory::where('language_id', $sourceId)->pluck('slug', 'id')->toArray();
  $subcategoryIdBySlugTarget = ServiceSubcategory::where('language_id', $targetId)->pluck('id', 'slug')->toArray();

  $formIdByNameSellerTarget = Form::where('language_id', $targetId)
    ->get()
    ->mapWithKeys(function ($item) {
      return [$item->name . '::' . $item->seller_id => $item->id];
    })
    ->toArray();

  $formMetaById = Form::where('language_id', $sourceId)->get()->mapWithKeys(function ($item) {
    return [$item->id => [$item->name, $item->seller_id]];
  })->toArray();

  $stats['service_contents'] = ensureManyBy(ServiceContent::class, $sourceId, $targetId, ['service_id'], $force, function ($copy, $sourceItem) use ($categorySlugById, $categoryIdBySlugTarget, $subcategorySlugById, $subcategoryIdBySlugTarget, $formIdByNameSellerTarget, $formMetaById) {
    if (!empty($sourceItem->service_category_id) && isset($categorySlugById[$sourceItem->service_category_id])) {
      $slug = $categorySlugById[$sourceItem->service_category_id];
      if (isset($categoryIdBySlugTarget[$slug])) {
        $copy->service_category_id = $categoryIdBySlugTarget[$slug];
      }
    }
    if (!empty($sourceItem->service_subcategory_id) && isset($subcategorySlugById[$sourceItem->service_subcategory_id])) {
      $slug = $subcategorySlugById[$sourceItem->service_subcategory_id];
      if (isset($subcategoryIdBySlugTarget[$slug])) {
        $copy->service_subcategory_id = $subcategoryIdBySlugTarget[$slug];
      }
    }
    if (!empty($sourceItem->form_id) && isset($formMetaById[$sourceItem->form_id])) {
      $meta = $formMetaById[$sourceItem->form_id];
      $lookup = $meta[0] . '::' . $meta[1];
      if (isset($formIdByNameSellerTarget[$lookup])) {
        $copy->form_id = $formIdByNameSellerTarget[$lookup];
      }
    }
  });

  $stats['service_packages'] = ensureManyBy(ServicePackage::class, $sourceId, $targetId, ['service_id', 'name'], $force);
  $stats['service_addons'] = ensureManyBy(ServiceAddon::class, $sourceId, $targetId, ['service_id', 'name'], $force);
  $stats['service_faqs'] = ensureManyBy(ServiceFaq::class, $sourceId, $targetId, ['service_id', 'question'], $force);

  $stats['page_contents'] = ensureManyBy(PageContent::class, $sourceId, $targetId, ['page_id'], $force);

  DB::commit();

  echo "Duplication completed.\n";
  foreach ($stats as $key => $value) {
    if (is_array($value)) {
      echo $key . ': created=' . $value[0] . ', updated=' . $value[1] . ', skipped=' . $value[2] . "\n";
    } else {
      echo $key . ': created=' . $value . "\n";
    }
  }
} catch (Throwable $e) {
  DB::rollBack();
  echo "Error: " . $e->getMessage() . "\n";
  exit(1);
}
