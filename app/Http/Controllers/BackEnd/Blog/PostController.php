<?php

namespace App\Http\Controllers\BackEnd\Blog;

use App\Http\Controllers\Controller;
use App\Http\Helpers\UploadFile;
use App\Http\Requests\Post\StoreRequest;
use App\Http\Requests\Post\UpdateRequest;
use App\Models\Blog\Post;
use App\Models\Blog\PostInformation;
use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Mews\Purifier\Facades\Purifier;

class PostController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index(Request $request)
  {
    $language = Language::query()->where('code', '=', $request->language)->firstOrFail();

    $information['posts'] = Post::query()->join('post_informations', 'posts.id', '=', 'post_informations.post_id')
      ->join('blog_categories', 'blog_categories.id', '=', 'post_informations.blog_category_id')
      ->where('post_informations.language_id', '=', $language->id)
      ->select('posts.id', 'posts.serial_number', 'posts.created_at', 'post_informations.title', 'post_informations.slug', 'blog_categories.name AS categoryName')
      ->orderByDesc('posts.id')
      ->get();

    $information['langs'] = Language::all();

    return view('backend.blog.post.index', $information);
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    // get all the languages from db
    $languages = Language::all();

    // get all the categories of each language from db
    $languages->map(function ($language) {
      $language['categories'] = $language->blogCategory()->where('status', 1)->orderByDesc('id')->get();
    });

    $information['languages'] = $languages;

    return view('backend.blog.post.create', $information);
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(StoreRequest $request)
  {
    // store image in storage
    $imgName = UploadFile::store('./assets/img/posts/', $request->file('image'));

    // store data in db
    $post = Post::query()->create($request->except('image') + [
      'image' => $imgName
    ]);

    $languages = Language::all();

    foreach ($languages as $language) {
      $postInformation = new PostInformation();
      $postInformation->language_id = $language->id;
      $postInformation->blog_category_id = $request[$language->code . '_category_id'];
      $postInformation->post_id = $post->id;
      $postInformation->title = $request[$language->code . '_title'];
      $postInformation->slug = createSlug($request[$language->code . '_title']);
      $postInformation->author = $request[$language->code . '_author'];
      $postInformation->content = Purifier::clean($request[$language->code . '_content'], 'youtube');
      $postInformation->meta_keywords = $request[$language->code . '_meta_keywords'];
      $postInformation->meta_description = $request[$language->code . '_meta_description'];
      $postInformation->save();
    }

    $request->session()->flash('success', 'New post added successfully!');

    return Response::json(['status' => 'success'], 200);
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function edit($id)
  {
    $post = Post::query()->findOrFail($id);
    $information['post'] = $post;
    // get all the languages from db
    $languages = Language::all();

    $languages->map(function ($language) use ($post) {
      // get post information of each language from db
      $language['postData'] = $language->postInformation()->where('post_id', $post->id)->first();

      // get all the categories of each language from db
      $language['categories'] = $language->blogCategory()->where('status', 1)->orderByDesc('id')->get();
    });

    $information['languages'] = $languages;

    return view('backend.blog.post.edit', $information);
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function update(UpdateRequest $request, $id)
  {
    $post = Post::query()->find($id);

    // store new image in storage
    if ($request->hasFile('image')) {
      $newImg = $request->file('image');
      $oldImg = $post->image;
      $imgName = UploadFile::update('./assets/img/posts/', $newImg, $oldImg);
    }

    // update data in db
    $post->update($request->except('image') + [
      'image' => $request->hasFile('image') ? $imgName : $post->image
    ]);

    $languages = Language::all();

    foreach ($languages as $language) {
      $postInformation = PostInformation::query()->where('post_id', '=', $id)
        ->where('language_id', '=', $language->id)
        ->first();
      if (empty($postInformation)) {
        $postInformation = new PostInformation();
        $postInformation->language_id = $language->id;
        $postInformation->post_id = $id;
      }

      $postInformation->blog_category_id = $request[$language->code . '_category_id'];
      $postInformation->title = $request[$language->code . '_title'];
      $postInformation->slug = createSlug($request[$language->code . '_title']);
      $postInformation->author = $request[$language->code . '_author'];
      $postInformation->content = Purifier::clean($request[$language->code . '_content'], 'youtube');
      $postInformation->meta_keywords = $request[$language->code . '_meta_keywords'];
      $postInformation->meta_description = $request[$language->code . '_meta_description'];
      $postInformation->save();
    }

    $request->session()->flash('success', 'Post updated successfully!');

    return Response::json(['status' => 'success'], 200);
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy($id)
  {
    $this->deletePost($id);

    return redirect()->back()->with('success', 'Post deleted successfully!');
  }

  /**
   * Remove the selected or all resources from storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function bulkDestroy(Request $request)
  {
    $ids = $request->ids;

    foreach ($ids as $id) {
      $this->deletePost($id);
    }

    $request->session()->flash('success', 'Posts deleted successfully!');

    return Response::json(['status' => 'success'], 200);
  }

  /**
   * Post deletion code.
   *
   * @param  int  $id
   */
  public function deletePost($id)
  {
    $post = Post::query()->find($id);

    // delete the image
    @unlink(public_path('assets/img/posts/' . $post->image));

    $postInformations = $post->information()->get();

    foreach ($postInformations as $postInformation) {
      $postInformation->delete();
    }

    $post->delete();
  }
}
