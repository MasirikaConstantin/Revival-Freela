<?php

namespace App\Http\Controllers\BackEnd\Blog;

use App\Http\Controllers\Controller;
use App\Models\Blog\BlogCategory;
use App\Models\Blog\Post;
use App\Models\Blog\PostInformation;
use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
  public function index(Request $request)
  {
    $language = Language::query()->where('code', '=', $request->language)->firstOrFail();
    $information['language'] = $language;

    $information['categories'] = $language->blogCategory()->orderByDesc('id')->get();

    $information['langs'] = Language::all();

    return view('backend.blog.category.index', $information);
  }

  public function store(Request $request)
  {
    $rules = [
      'language_id' => 'required',
      'name' => [
        'required',
        Rule::unique('blog_categories')->where(function ($query) use ($request) {
          return $query->where('language_id', $request->input('language_id'));
        })
      ],
      'status' => 'required|numeric',
      'serial_number' => 'required|numeric'
    ];

    $message = [
      'language_id.required' => 'The language field is required.'
    ];

    $validator = Validator::make($request->all(), $rules, $message);

    if ($validator->fails()) {
      return Response::json([
        'errors' => $validator->getMessageBag()
      ], 400);
    }

    BlogCategory::query()->create($request->except('slug') + [
      'slug' => createSlug($request['name'])
    ]);

    $request->session()->flash('success', 'New blog category added successfully!');

    return Response::json(['status' => 'success'], 200);
  }

  public function update(Request $request)
  {
    $category = BlogCategory::query()->find($request->id);

    $rules = [
      'name' => [
        'required',
        Rule::unique('blog_categories')->where(function ($query) use ($request) {
          return $query->where('language_id', $request->input('language_id'));
        })->ignore($category->id)
      ],
      'status' => 'required|numeric',
      'serial_number' => 'required|numeric'
    ];

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
      return Response::json([
        'errors' => $validator->getMessageBag()
      ], 400);
    }

    $category->update($request->except('slug') + [
      'slug' => createSlug($request['name'])
    ]);

    $request->session()->flash('success', 'Blog category updated successfully!');

    return Response::json(['status' => 'success'], 200);
  }

  public function destroy($id)
  {
    $this->deleteCategory($id);

    return redirect()->back()->with('success', 'Category deleted successfully!');
  }

  public function bulkDestroy(Request $request)
  {
    $ids = $request->ids;

    foreach ($ids as $id) {
      $this->deleteCategory($id);
    }

    $request->session()->flash('success', 'Categories deleted successfully!');

    return response()->json(['status' => 'success'], 200);
  }

  // category deletion code
  public function deleteCategory($id)
  {
    $category = BlogCategory::query()->find($id);

    // delete all the post-informations of this category
    $postInformations = $category->postInfo()->get();

    if (count($postInformations) > 0) {
      foreach ($postInformations as $postData) {
        $postInformation = $postData;
        $postData->delete();

        // delete the post if, this post does not contain any other post-informations in any other category
        $otherPostInformations = PostInformation::query()->where('blog_category_id', '<>', $category->id)
          ->where('post_id', '=', $postInformation->post_id)
          ->get();

        if (count($otherPostInformations) == 0) {
          $post = Post::query()->find($postInformation->post_id);

          // delete post image
          @unlink(public_path('assets/img/posts/' . $post->image));

          $post->delete();
        }
      }
    }

    $category->delete();
  }
}
