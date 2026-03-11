<?php

namespace App\Http\Controllers\BackEnd\ClientService;

use App\Http\Controllers\Controller;
use App\Models\ClientService\Service;
use App\Models\ClientService\ServiceAddon;
use App\Models\ClientService\ServiceContent;
use App\Models\ClientService\ServiceFaq;
use App\Models\ClientService\ServicePackage;
use App\Models\ClientService\ServiceSubcategory;
use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class SubcategoryController extends Controller
{
  public function index(Request $request)
  {
    $language = Language::query()->where('code', '=', $request->language)->firstOrFail();
    $information['language'] = $language;

    $subcategories = $language->serviceSubcategory()->where('status', 1)->orderByDesc('id')->get();

    $subcategories->map(function ($subcategory) use ($language) {
      $category = $subcategory->category()->where('language_id', '=', $language->id)->first();
      $subcategory['categoryName'] = $category->name;
    });

    $information['subcategories'] = $subcategories;

    $information['langs'] = Language::all();

    $information['categories'] = $language->serviceCategory()->where('status', 1)->orderBy('serial_number', 'asc')->get();

    return view('backend.client-service.subcategory.index', $information);
  }

  public function getCategories($id)
  {
    $language = Language::query()->find($id);

    $categories = $language->serviceCategory()->where('status', 1)->orderBy('serial_number', 'asc')->get();

    return response()->json(['serviceCategories' => $categories], 200);
  }

  public function store(Request $request)
  {
    $rules = [
      'language_id' => 'required',
      'service_category_id' => 'required',
      'name' => [
        'required',
        Rule::unique('service_subcategories')->where(function ($query) use ($request) {
          return $query->where('language_id', $request->input('language_id'));
        })
      ],
      'status' => 'required|numeric',
      'serial_number' => 'required|numeric'
    ];

    $messages = [
      'language_id.required' => 'The language field is required.',
      'service_category_id.required' => 'The category field is required.'
    ];

    $validator = Validator::make($request->all(), $rules, $messages);

    if ($validator->fails()) {
      return Response::json([
        'errors' => $validator->getMessageBag()
      ], 400);
    }

    ServiceSubcategory::query()->create($request->except('slug') + [
      'slug' => createSlug($request['name'])
    ]);

    $request->session()->flash('success', 'New subcategory added successfully!');

    return Response::json(['status' => 'success'], 200);
  }

  public function update(Request $request)
  {
    $subcategory = ServiceSubcategory::query()->find($request->id);
    $rules = [
      'service_category_id' => 'required',
      'name' => [
        'required',
        Rule::unique('service_subcategories')->where(function ($query) use ($subcategory) {
          return $query->where('language_id', $subcategory->language_id);
        })->ignore($request->id)
      ],
      'status' => 'required|numeric',
      'serial_number' => 'required|numeric'
    ];

    $message = [
      'service_category_id.required' => 'The category field is required.'
    ];

    $validator = Validator::make($request->all(), $rules, $message);

    if ($validator->fails()) {
      return Response::json([
        'errors' => $validator->getMessageBag()
      ], 400);
    }

    $subcategory->update($request->except('slug') + [
      'slug' => createSlug($request['name'])
    ]);

    $request->session()->flash('success', 'Subcategory updated successfully!');

    return Response::json(['status' => 'success'], 200);
  }

  public function destroy($id)
  {
    $this->deleteSubcategory($id);

    return redirect()->back()->with('success', 'Subcategory deleted successfully!');
  }

  public function bulkDestroy(Request $request)
  {
    $ids = $request->ids;

    foreach ($ids as $id) {
      $this->deleteSubcategory($id);
    }

    $request->session()->flash('success', 'Subcategories deleted successfully!');

    return response()->json(['status' => 'success'], 200);
  }

  // subcategory deletion code
  public function deleteSubcategory($id)
  {
    $subcategory = ServiceSubcategory::query()->find($id);

    // delete all the service-contents of this subcategory
    $serviceContents = $subcategory->serviceContent()->get();

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

        // delete the service if, this service does not contain any other service-contents in any other subcategory
        $otherServiceContents = ServiceContent::query()->where('service_subcategory_id', '<>', $subcategory->id)
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

    $subcategory->delete();
  }
}
