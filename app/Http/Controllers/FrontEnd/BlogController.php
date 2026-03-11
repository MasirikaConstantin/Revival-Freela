<?php

namespace App\Http\Controllers\FrontEnd;

use App\Http\Controllers\Controller;
use App\Http\Controllers\FrontEnd\MiscellaneousController;
use App\Models\BasicSettings\Basic;
use App\Models\Blog\BlogCategory;
use App\Models\Blog\Post;
use App\Models\Language;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class BlogController extends Controller
{
  public function index(Request $request)
  {
    $misc = new MiscellaneousController();
    $language = $misc->getLanguage();
    $defaultLanguage = Language::query()->where('is_default', '=', 1)->first();

    $seoInfo = $language->seoInfo()->select('meta_keyword_blog', 'meta_description_blog')->first();
    if (empty($seoInfo) && !empty($defaultLanguage)) {
      $seoInfo = $defaultLanguage->seoInfo()->select('meta_keyword_blog', 'meta_description_blog')->first();
    }
    $queryResult['seoInfo'] = $seoInfo;

    $queryResult['pageHeading'] = $misc->getPageHeading($language);

    $queryResult['breadcrumb'] = $misc->getBreadcrumb();

    $postTitle = $blogCategorySlug = null;

    if ($request->filled('title')) {
      $postTitle = $request['title'];
    }
    if ($request->filled('category')) {
      $blogCategorySlug = $request['category'];
    }

    $posts = Post::join('post_informations', 'posts.id', '=', 'post_informations.post_id')
      ->join('blog_categories', 'blog_categories.id', '=', 'post_informations.blog_category_id')
      ->where('post_informations.language_id', '=', $language->id)
      ->when($postTitle, function (Builder $query, $postTitle) {
        return $query->where('post_informations.title', 'like', '%' . $postTitle . '%');
      })
      ->when($blogCategorySlug, function (Builder $query, $blogCategorySlug) {
        $blogCategory = BlogCategory::query()->where('slug', '=', $blogCategorySlug)->first();

        return $query->where('post_informations.blog_category_id', '=', $blogCategory->id);
      })
      ->select('posts.id', 'posts.image', 'blog_categories.name as categoryName', 'blog_categories.slug as categorySlug', 'post_informations.title', 'post_informations.slug', 'post_informations.author', 'posts.created_at')
      ->orderBy('posts.serial_number', 'asc')
      ->paginate(6);

    if ($posts->isEmpty() && !empty($defaultLanguage)) {
      $posts = Post::join('post_informations', 'posts.id', '=', 'post_informations.post_id')
        ->join('blog_categories', 'blog_categories.id', '=', 'post_informations.blog_category_id')
        ->where('post_informations.language_id', '=', $defaultLanguage->id)
        ->when($postTitle, function (Builder $query, $postTitle) {
          return $query->where('post_informations.title', 'like', '%' . $postTitle . '%');
        })
        ->when($blogCategorySlug, function (Builder $query, $blogCategorySlug) {
          $blogCategory = BlogCategory::query()->where('slug', '=', $blogCategorySlug)->first();

          return $query->where('post_informations.blog_category_id', '=', $blogCategory->id);
        })
        ->select('posts.id', 'posts.image', 'blog_categories.name as categoryName', 'blog_categories.slug as categorySlug', 'post_informations.title', 'post_informations.slug', 'post_informations.author', 'posts.created_at')
        ->orderBy('posts.serial_number', 'asc')
        ->paginate(6);
    }

    $queryResult['posts'] = $posts;
    $queryResult['categories'] = $this->getCategories($language, $defaultLanguage);

    $totalPost = $language->postInformation()->count();
    if ($totalPost === 0 && !empty($defaultLanguage)) {
      $totalPost = $defaultLanguage->postInformation()->count();
    }
    $queryResult['totalPost'] = $totalPost;

    return view('frontend.blog.posts', $queryResult);
  }

  public function show(Request $request, $slug, $postId)
  {
    if (!$postId) {
      abort(404);
    }
    $postId = $request->id;
    $misc = new MiscellaneousController();
    $language = $misc->getLanguage();
    $defaultLanguage = Language::query()->where('is_default', '=', 1)->first();
    $queryResult['pageHeading'] = $misc->getPageHeading($language);
    $queryResult['breadcrumb'] = $misc->getBreadcrumb();
    $details = Post::join('post_informations', 'posts.id', '=', 'post_informations.post_id')
      ->join('blog_categories', 'blog_categories.id', '=', 'post_informations.blog_category_id')
      ->where('post_informations.language_id', '=', $language->id)
      ->where('posts.id', '=', $postId)
      ->select('posts.image', 'posts.created_at', 'post_informations.title', 'post_informations.content', 'post_informations.meta_keywords', 'post_informations.meta_description', 'blog_categories.name as categoryName')
      ->first();

    if (empty($details) && !empty($defaultLanguage)) {
      $details = Post::join('post_informations', 'posts.id', '=', 'post_informations.post_id')
        ->join('blog_categories', 'blog_categories.id', '=', 'post_informations.blog_category_id')
        ->where('post_informations.language_id', '=', $defaultLanguage->id)
        ->where('posts.id', '=', $postId)
        ->select('posts.image', 'posts.created_at', 'post_informations.title', 'post_informations.content', 'post_informations.meta_keywords', 'post_informations.meta_description', 'blog_categories.name as categoryName')
        ->first();
    }

    if (empty($details)) {
      abort(404);
    }

    $queryResult['details'] = $details;

    $queryResult['disqusInfo'] = Basic::query()->select('disqus_status', 'disqus_short_name')->firstOrFail();
    $queryResult['categories'] = $this->getCategories($language, $defaultLanguage);
    $totalPost = $language->postInformation()->count();
    if ($totalPost === 0 && !empty($defaultLanguage)) {
      $totalPost = $defaultLanguage->postInformation()->count();
    }
    $queryResult['totalPost'] = $totalPost;

    return view('frontend.blog.post-details', $queryResult);
  }

  public function getCategories($language, $defaultLanguage = null)
  {
    $categories = $language->blogCategory()->where('status', 1)->orderBy('serial_number', 'asc')->get();
    if ($categories->isEmpty() && !empty($defaultLanguage)) {
      $categories = $defaultLanguage->blogCategory()->where('status', 1)->orderBy('serial_number', 'asc')->get();
    }

    $categories->map(function ($category) {
      $category['postCount'] = $category->postInfo()->count();
    });

    return $categories;
  }
}
