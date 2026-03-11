<?php

namespace App\Http\Controllers\BackEnd\ClientService;

use App\Http\Controllers\Controller;
use App\Http\Helpers\UploadFile;
use App\Models\ClientService\Service;
use App\Models\ClientService\ServiceAddon;
use App\Models\ClientService\ServiceCategory;
use App\Models\ClientService\ServiceContent;
use App\Models\ClientService\ServiceFaq;
use App\Models\ClientService\ServicePackage;
use App\Models\Language;
use App\Rules\ImageMimeTypeRule;
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

    $information['categories'] = $language->serviceCategory()->orderByDesc('id')->get();

    $information['langs'] = Language::all();

    return view('backend.client-service.category.index', $information);
  }

  public function store(Request $request)
  {
    $rules = [
      'language_id' => 'required',
      'image' => [
        'required',
        new ImageMimeTypeRule()
      ],
      'name' => [
        'required',
        Rule::unique('service_categories')->where(function ($query) use ($request) {
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

    // store image in storage
    $imgName = UploadFile::store('./assets/img/service-categories/', $request->file('image'));

    ServiceCategory::query()->create($request->except('image', 'slug') + [
      'image' => $imgName,
      'slug' => createSlug($request['name'])
    ]);

    $request->session()->flash('success', 'New category added successfully!');

    return Response::json(['status' => 'success'], 200);
  }

  public function updateFeaturedStatus(Request $request, $id)
  {
    $category = ServiceCategory::query()->find($id);
    if ($request['is_featured'] == 'yes') {
      $category->update([
        'is_featured' => 'yes'
      ]);

      $request->session()->flash('success', 'Category featured successfully!');
    } else {
      $category->update([
        'is_featured' => 'no'
      ]);

      $request->session()->flash('success', 'Category unfeatured successfully!');
    }

    return redirect()->back();
  }

  public function updateAddToMenuStatus(Request $request, $id)
  {
    $category = ServiceCategory::query()->find($id);
    if ($request['is_menu'] == 1) {
      $category->update([
        'add_to_menu' => 1
      ]);

      $request->session()->flash('success', 'Category has been added to menu successfully!');
    } else {
      $category->update([
        'add_to_menu' => 0
      ]);

      $request->session()->flash('warning', 'Category has been removed from menu successfully!');
    }

    return redirect()->back();
  }

  public function update(Request $request)
  {
    $category = ServiceCategory::query()->find($request->id);

    $rules = [
      'image' => $request->hasFile('image') ? new ImageMimeTypeRule() : '',
      'name' => [
        'required',
        Rule::unique('service_categories')->where(function ($query) use ($category) {
          return $query->where('language_id', $category->language_id);
        })->ignore($request->id)
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

    if ($request->hasFile('image')) {
      $newImage = $request->file('image');
      $oldImage = $category->image;
      $imgName = UploadFile::update('./assets/img/service-categories/', $newImage, $oldImage);
    }

    $category->update($request->except('image', 'slug') + [
      'image' => isset($imgName) ? $imgName : $category->image,
      'slug' => createSlug($request['name'])
    ]);

    $request->session()->flash('success', 'Category updated successfully!');

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

    return Response::json(['status' => 'success'], 200);
  }

  // category deletion code
  public function deleteCategory($id)
  {
    $category = ServiceCategory::query()->find($id);

    // delete all the subcategories of this category
    $subcategories = $category->subcategory()->get();

    if (count($subcategories) > 0) {
      foreach ($subcategories as $subcategory) {
        $subcategory->delete();
      }
    }

    // delete all the service-contents of this category
    $serviceContents = $category->serviceContent()->get();

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
        $otherServiceContents = ServiceContent::query()->where('service_category_id', '<>', $category->id)
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

              foreach ($messages as $msgInfo) {
                if (!empty($msgInfo->file_name)) {
                  @unlink(public_path('assets/file/message-files/' . $msgInfo->file_name));
                }

                $msgInfo->delete();
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
    @unlink(public_path('assets/img/service-categories/' . $category->image));

    $category->delete();
  }
}
