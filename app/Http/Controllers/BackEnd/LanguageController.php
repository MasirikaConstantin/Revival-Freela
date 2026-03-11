<?php

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\Controller;
use App\Http\Requests\Language\StoreRequest;
use App\Http\Requests\Language\UpdateRequest;
use App\Models\BasicSettings\Basic;
use App\Models\BasicSettings\BasicExtends;
use App\Models\Blog\Post;
use App\Models\Blog\PostInformation;
use App\Models\ClientService\Service;
use App\Models\ClientService\ServiceAddon;
use App\Models\ClientService\ServiceContent;
use App\Models\ClientService\ServiceFaq;
use App\Models\ClientService\ServicePackage;
use App\Models\CustomPage\Page;
use App\Models\CustomPage\PageContent;
use App\Models\Language;
use App\Models\MenuBuilder;
use App\Models\Shop\Product;
use App\Models\Shop\ProductContent;
use App\Models\Shop\ProductOrder;
use App\Models\Shop\ProductPurchaseItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class LanguageController extends Controller
{
  public function settings(Request $request)
  {
    $language = Language::query()->where('code', '=', $request->language)->firstOrFail();
    $language_settings = Basic::select('is_language')->first();
    return view('backend.language.settings', compact('language', 'language_settings'));
  }

  public function settingsUpdate(Request $request)
  {
    $language_settings = Basic::first();
    $language_settings->update([
      'is_language' => $request->is_language,
    ]);
    Session::flash('success', 'Language settings update successfully!');

    return Response::json(['status' => 'success'], 200);
  }

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    $languages = Language::all();

    return view('backend.language.index', compact('languages'));
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(StoreRequest $request)
  {
    // get all the keywords from the default file of language
    $data = file_get_contents(resource_path('lang/') . 'default.json');

    // make a new json file for the new language
    $fileName = strtolower($request->code) . '.json';

    // create the path where the new language json file will be stored
    $fileLocated = resource_path('lang/') . $fileName;

    // finally, put the keywords in the new json file and store the file in lang folder
    file_put_contents($fileLocated, $data);

    // then, store data in db
    $language = Language::query()->create($request->all());
    MenuBuilder::create([
      'language_id' => $language->id,
      'menus' => '[{"text":"Home","href":"","icon":"empty","target":"_self","title":"","type":"home"},{"text":"Services","href":"","icon":"empty","target":"_self","title":"","type":"services"},{"text":"Sellers","href":"","icon":"empty","target":"_self","title":"","type":"sellers"},{"text":"Blog","href":"","icon":"empty","target":"_self","title":"","type":"blog"},{"text":"FAQ","href":"","icon":"empty","target":"_self","title":"","type":"faq"},{"text":"Contact","href":"","icon":"empty","target":"_self","title":"","type":"contact"}]'
    ]);
    BasicExtends::create([
      'language_id' => $language->id,
    ]);

    $request->session()->flash('success', 'Language added successfully!');

    return response()->json(['status' => 'success'], 200);
  }

  /**
   * Make a default language for this system.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function makeDefault($id)
  {
    // first, make other languages to non-default language
    $prevDefLang = Language::query()->where('is_default', '=', 1);

    $prevDefLang->update(['is_default' => 0]);

    // second, make the selected language to default language
    $language = Language::query()->find($id);

    $language->update(['is_default' => 1]);

    return back()->with('success', $language->name . ' ' . 'is set as default language.');
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function update(UpdateRequest $request)
  {
    $language = Language::query()->find($request->id);

    if ($language->code !== $request->code) {
      /**
       * get all the keywords from the previous file,
       * which was named using previous language code
       */
      $data = file_get_contents(resource_path('lang/') . $language->code . '.json');

      // make a new json file for the new language (code)
      $fileName = strtolower($language->code) . '.json';

      // now, delete the previous language code file
      @unlink(resource_path('lang/') . $language->code . '.json');

      // create the path where the new language (code) json file will be stored
      $fileLocated = resource_path('lang/') . $fileName;

      // then, put the keywords in the new json file and store the file in lang folder
      file_put_contents($fileLocated, $data);
      // finally, update the info in db
      $language->update($request->except('code'));
    } else {
      $language->update($request->except('code'));
    }

    $request->session()->flash('success', 'Language updated successfully!');

    return response()->json(['status' => 'success'], 200);
  }

  /**
   * Display all the keywords of specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function editKeyword($id)
  {
    $language = Language::query()->findOrFail($id);

    // get all the keywords of the selected language
    $jsonData = file_get_contents(resource_path('lang/') . $language->code . '.json');

    // convert json encoded string into a php associative array
    $keywords = json_decode($jsonData);

    return view('backend.language.edit-keyword', compact('language', 'keywords'));
  }

  /**
   * Update the keywords of specified resource in respective json file.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function updateKeyword(Request $request, $id)
  {
    $arrData = $request['keyValues'];

    // first, check each key has value or not
    foreach ($arrData as $key => $value) {
      if ($value == null) {
        $request->session()->flash('warning', 'Value is required for "' . $key . '" key.');

        return redirect()->back();
      }
    }

    $jsonData = json_encode($arrData);

    $language = Language::query()->find($id);

    $fileLocated = resource_path('lang/') . $language->code . '.json';

    // put all the keywords in the selected language file
    file_put_contents($fileLocated, $jsonData);

    $request->session()->flash('success', $language->name . ' language\'s keywords updated successfully!');

    return redirect()->back();
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy($id)
  {
    $language = Language::query()->find($id);

    if ($language->is_default == 1) {
      return redirect()->back()->with('error', 'Default language cannot be delete.');
    } else {
      // delete basic extends info
      BasicExtends::where('language_id', $language->id)->delete();
      /**
       * delete about-section info
       */
      $aboutSecInfo = $language->aboutSection()->first();

      if (!empty($aboutSecInfo)) {
        $aboutSecInfo->delete();
      }

      /**
       * delete blog category infos
       */
      $blogCategoryInfos = $language->blogCategory()->get();

      if (count($blogCategoryInfos) > 0) {
        foreach ($blogCategoryInfos as $blogCategory) {
          // delete all the post-informations of this category
          $postInformations = $blogCategory->postInfo()->get();

          if (count($postInformations) > 0) {
            foreach ($postInformations as $postData) {
              $postInformation = $postData;
              $postData->delete();

              // delete the post if, this post does not contain any other post-informations in any other category
              $otherPostInformations = PostInformation::query()->where('blog_category_id', '<>', $blogCategory->id)
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

          $blogCategory->delete();
        }
      }

      /**
       * delete cookie-alert info
       */
      $cookieAlertInfo = $language->cookieAlertInfo()->first();

      if (!empty($cookieAlertInfo)) {
        $cookieAlertInfo->delete();
      }

      /**
       * delete faq infos
       */
      $faqs = $language->faq()->get();

      if (count($faqs) > 0) {
        foreach ($faqs as $faq) {
          $faq->delete();
        }
      }

      /**
       * delete feature infos
       */
      $features = $language->feature()->get();

      if (count($features) > 0) {
        foreach ($features as $feature) {
          $feature->delete();
        }
      }

      /**
       * delete footer-content info
       */
      $footerContentInfo = $language->footerContent()->first();

      if (!empty($footerContentInfo)) {
        $footerContentInfo->delete();
      }

      /**
       * delete form infos
       */
      $forms = $language->form()->get();

      if (count($forms) > 0) {
        foreach ($forms as $form) {
          // delete all input fields of each form
          $inputFields = $form->input()->get();

          if (count($inputFields) > 0) {
            foreach ($inputFields as $inputField) {
              $inputField->delete();
            }
          }

          $form->delete();
        }
      }

      /**
       * delete hero-slider infos
       */
      $sliders = $language->heroSlider()->get();

      if (count($sliders) > 0) {
        foreach ($sliders as $slider) {
          @unlink(public_path('assets/img/hero-sliders/' . $slider->image));

          $slider->delete();
        }
      }

      /**
       * delete hero-static info
       */
      $heroInfo = $language->heroStatic()->first();

      if (!empty($heroInfo)) {
        $heroInfo->delete();
      }

      /**
       * delete website-menu info
       */
      $websiteMenuInfo = $language->menuInfo()->first();

      if (!empty($websiteMenuInfo)) {
        $websiteMenuInfo->delete();
      }

      /**
       * delete custom-page infos
       */
      $customPageInfos = $language->customPageInfo()->get();

      if (count($customPageInfos) > 0) {
        foreach ($customPageInfos as $customPageData) {
          $customPageInfo = $customPageData;
          $customPageData->delete();

          // delete the custom-page if, this page does not contain any other page-content in any other language
          $otherPageContents = PageContent::query()->where('language_id', '<>', $language->id)
            ->where('page_id', '=', $customPageInfo->page_id)
            ->get();

          if (count($otherPageContents) == 0) {
            $page = Page::query()->find($customPageInfo->page_id);
            $page->delete();
          }
        }
      }

      /**
       * delete page-heading info
       */
      $pageHeadingInfo = $language->pageName()->first();

      if (!empty($pageHeadingInfo)) {
        $pageHeadingInfo->delete();
      }

      /**
       * delete popup infos
       */
      $popups = $language->announcementPopup()->get();

      if (count($popups) > 0) {
        foreach ($popups as $popup) {
          @unlink(public_path('assets/img/popups/' . $popup->image));
          $popup->delete();
        }
      }

      /**
       * delete footer-quick-links
       */
      $quickLinks = $language->footerQuickLink()->get();

      if (count($quickLinks) > 0) {
        foreach ($quickLinks as $quickLink) {
          $quickLink->delete();
        }
      }

      /**
       * delete section-title info
       */
      $sectionTitle = $language->sectionTitle()->first();

      if (!empty($sectionTitle)) {
        $sectionTitle->delete();
      }

      /**
       * delete seo info
       */
      $seoInfo = $language->seoInfo()->first();

      if (!empty($seoInfo)) {
        $seoInfo->delete();
      }

      /**
       * delete service category infos
       */
      $serviceCategoryInfos = $language->serviceCategory()->get();

      if (count($serviceCategoryInfos) > 0) {
        foreach ($serviceCategoryInfos as $serviceCategory) {
          // delete all the subcategories of this category
          $subcategories = $serviceCategory->subcategory()->get();

          if (count($subcategories) > 0) {
            foreach ($subcategories as $subcategory) {
              $subcategory->delete();
            }
          }

          // delete all the service-contents of this category
          $serviceContents = $serviceCategory->serviceContent()->get();

          if (count($serviceContents) > 0) {
            foreach ($serviceContents as $serviceData) {
              $serviceContent = $serviceData;
              $serviceData->delete();

              // delete all the packages of deleted service-content, because of language dependency
              $packages = ServicePackage::query()->where('language_id', '=', $serviceContent->language_id)
                ->where('service_id', '=', $serviceContent->service_id)
                ->get();

              if (count($packages) > 0) {
                foreach ($packages as $package) {
                  $package->delete();
                }
              }

              // delete all the addons of deleted service-content, because of language dependency
              $addons = ServiceAddon::query()->where('language_id', '=', $serviceContent->language_id)
                ->where('service_id', '=', $serviceContent->service_id)
                ->get();

              if (count($addons) > 0) {
                foreach ($addons as $addon) {
                  $addon->delete();
                }
              }

              // delete all the faqs of deleted service-content, because of language dependency
              $faqs = ServiceFaq::query()->where('language_id', '=', $serviceContent->language_id)
                ->where('service_id', '=', $serviceContent->service_id)
                ->get();

              if (count($faqs) > 0) {
                foreach ($faqs as $faq) {
                  $faq->delete();
                }
              }

              // delete the service if, this service does not contain any other service-contents in any other category
              $otherServiceContents = ServiceContent::query()->where('service_category_id', '<>', $serviceCategory->id)
                ->where('service_id', '=', $serviceContent->service_id)
                ->get();

              if (count($otherServiceContents) == 0) {
                $service = Service::query()->find($serviceContent->service_id);

                // delete all the orders of this service
                $orders = $service->order()->get();

                if (count($orders) > 0) {
                  foreach ($orders as $order) {
                    // delete zip file which has uploaded by the user
                    $informations = json_decode($order->informations);

                    if (!is_null($informations)) {
                      foreach ($informations as $key => $information) {
                        if ($information->type == 8) {
                          @unlink(public_path('assets/file/zip-files/' . $information->value));
                        }
                      }
                    }

                    // delete order receipt
                    @unlink(public_path('assets/img/attachments/service/' . $order->receipt));

                    // delete order invoice
                    @unlink(public_path('assets/file/invoices/service/' . $order->invoice));

                    // delete messages of this service-order
                    $messages = $order->message()->get();

                    if (count($messages) > 0) {
                      foreach ($messages as $msgInfo) {
                        @unlink(public_path('assets/file/message-files/' . $msgInfo->file_name));
                        $msgInfo->delete();
                      }
                    }

                    $order->delete();
                  }
                }

                // delete all the reviews of this service
                $reviews = $service->review()->get();

                if (count($reviews) > 0) {
                  foreach ($reviews as $review) {
                    $review->delete();
                  }
                }

                // delete wishlist records of this service
                $records = $service->wishlist()->get();

                if (count($records) > 0) {
                  foreach ($records as $record) {
                    $record->delete();
                  }
                }

                // delete the thumbnail image
                @unlink(public_path('assets/img/services/thumbnail-images/' . $service->thumbnail_image));

                // delete the slider images
                $sliderImages = json_decode($service->slider_images);

                foreach ($sliderImages as $sliderImage) {
                  @unlink(public_path('assets/img/services/slider-images/' . $sliderImage));
                }

                $service->delete();
              }
            }
          }

          // delete category image
          @unlink(public_path('assets/img/service-categories/' . $serviceCategory->image));

          $serviceCategory->delete();
        }
      }

      /**
       * delete testimonial infos
       */
      $testimonials = $language->testimonial()->get();

      if (count($testimonials) > 0) {
        foreach ($testimonials as $testimonial) {
          $clientImg = $testimonial->image;

          @unlink(public_path('assets/img/clients/' . $clientImg));
          $testimonial->delete();
        }
      }

      /**
       * delete the language json file
       */
      @unlink(resource_path('lang/') . $language->code . '.json');

      /**
       * finally, delete the language info from db
       */
      $language->delete();

      return redirect()->back()->with('success', 'Language deleted successfully!');
    }
  }

  /**
   * Check the specified language is RTL or not.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function checkRTL($id)
  {
    if (!is_null($id)) {
      $direction = Language::query()->where('id', '=', $id)
        ->pluck('direction')
        ->first();

      return response()->json(['successData' => $direction], 200);
    } else {
      return response()->json(['errorData' => 'Sorry, an error has occured!'], 400);
    }
  }
  public function addKeyword(Request $request)
  {
    $rules = [
      'keyword' => 'required'
    ];

    $validator = Validator::make($request->all(), $rules);
    if ($validator->fails()) {
      return Response::json([
        'errors' => $validator->getMessageBag()->toArray()
      ], 400);
    }
    $languages = Language::get();

    foreach ($languages as $language) {
      if (file_exists(resource_path('lang/') . $language->code . '.json')) {
        // get all the keywords of the selected language
        $jsonData = file_get_contents(resource_path('lang/') . $language->code . '.json');

        // convert json encoded string into a php associative array
        $keywords = json_decode($jsonData, true);
        $datas = [];
        $datas[$request->keyword] = $request->keyword;

        foreach ($keywords as $key => $keyword) {
          $datas[$key] = $keyword;
        }
        //put data
        $jsonData = json_encode($datas);

        $fileLocated = resource_path('lang/') . $language->code . '.json';

        // put all the keywords in the selected language file
        file_put_contents($fileLocated, $jsonData);
      }
    }

    //for default json
    // get all the keywords of the selected language
    $jsonData = file_get_contents(resource_path('lang/') . 'default.json');

    // convert json encoded string into a php associative array
    $keywords = json_decode($jsonData, true);
    $datas = [];
    $datas[$request->keyword] = $request->keyword;

    foreach ($keywords as $key => $keyword) {
      $datas[$key] = $keyword;
    }
    //put data
    $jsonData = json_encode($datas);

    $fileLocated = resource_path('lang/') . 'default.json';

    // put all the keywords in the selected language file
    file_put_contents($fileLocated, $jsonData);

    Session::flash('success', 'A new keyword add successfully');

    return response()->json(['status' => 'success'], 200);
  }
}
