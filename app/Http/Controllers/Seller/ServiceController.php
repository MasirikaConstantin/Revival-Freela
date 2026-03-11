<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Http\Helpers\UploadFile;
use App\Http\Requests\ClientService\ServiceStoreRequest;
use App\Http\Requests\ClientService\ServiceUpdateRequest;
use App\Models\ClientService\Service;
use App\Models\ClientService\ServiceCategory;
use App\Models\ClientService\ServiceContent;
use App\Models\Language;
use App\Rules\ImageMimeTypeRule;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Mews\Purifier\Facades\Purifier;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $language = Language::query()->where('code', '=', $request->language)->firstOrFail();

        $information['services'] = Service::query()->join('service_contents', 'services.id', '=', 'service_contents.service_id')
            ->join('service_categories', 'service_categories.id', '=', 'service_contents.service_category_id')
            ->where([['service_contents.language_id', '=', $language->id], ['seller_id', Auth::guard('seller')->user()->id]])
            ->select('services.id', 'service_contents.title', 'service_contents.slug', 'service_categories.name as categoryName', 'services.is_featured', 'services.quote_btn_status')
            ->orderByDesc('services.id')
            ->get();
        $information['langs'] = Language::all();

        return view('seller.service.index', $information);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $languages = Language::all();

        $languages->map(function ($language) {
            $language['forms'] = $language->form()->where('seller_id', Auth::guard('seller')->user()->id)->orderByDesc('id')->get();

            $language['categories'] = $language->serviceCategory()->where('status', 1)->orderByDesc('id')->get();
        });

        $information['languages'] = $languages;

        $information['currencyInfo'] = $this->getCurrencyInfo();

        return view('seller.service.create', $information);
    }

    /**
     * Store a new slider image in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function uploadImage(Request $request)
    {
        $rule = [
            'slider_image' => new ImageMimeTypeRule()
        ];

        $validator = Validator::make($request->all(), $rule);

        if ($validator->fails()) {
            return Response::json([
                'error' => $validator->getMessageBag()
            ], 400);
        }

        $imageName = UploadFile::store('./assets/img/services/slider-images/', $request->file('slider_image'));

        return Response::json(['uniqueName' => $imageName], 200);
    }

    /**
     * Remove a slider image from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function removeImage(Request $request)
    {
        $img = $request['imageName'];

        try {
            @unlink(public_path('assets/img/services/slider-images/') . $img);

            return Response::json(['success' => 'The image has been deleted.'], 200);
        } catch (Exception $e) {
            return Response::json(['error' => 'Something went wrong!'], 400);
        }
    }

    /**
     * Get subcategory of selected category.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getSubcategory($id)
    {
        try {
            $category = ServiceCategory::query()->findOrFail($id);

            $subcategories = $category->subcategory()->where('status', 1)->orderByDesc('id')->get();

            return response()->json(['successData' => $subcategories], 200);
        } catch (Exception $e) {
            return response()->json(['errorData' => 'Something went wrong!'], 400);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ServiceStoreRequest $request)
    {

        // store thumbnail image in storage
        $thumbnailImage = UploadFile::store('./assets/img/services/thumbnail-images/', $request->file('thumbnail_image'));

        //Video Link format 
        $link = NULL;
        if ($request->filled('video_preview_link')) {
            $link = $request->video_preview_link;
            if (strpos($link, '&') != 0) {
                $link = substr($link, 0, strpos($link, '&'));
            }
        }

        $sliderArr = [];
        foreach ($request['slider_images'] as $image) {
            if (file_exists(public_path('assets/img/services/slider-images/' . $image))) {
                array_push($sliderArr, $image);
            }
        }

        //End video Link store
        $service = Service::query()->create($request->except('thumbnail_image', 'slider_images', 'video_preview_link') + [
            'seller_id' => Auth::guard('seller')->user()->id,
            'thumbnail_image' => $thumbnailImage,
            'slider_images' => json_encode($sliderArr),
            'video_preview_link' => $link
        ]);

        $languages = Language::all();

        foreach ($languages as $language) {
            $serviceContent = new ServiceContent();
            $serviceContent->language_id = $language->id;
            $serviceContent->service_category_id = $request[$language->code . '_category_id'];
            $serviceContent->service_subcategory_id = !empty($request[$language->code . '_subcategory_id']) ? $request[$language->code . '_subcategory_id'] : NULL;
            $serviceContent->service_id = $service->id;
            $serviceContent->form_id = $request[$language->code . '_form_id'];
            $serviceContent->title = $request[$language->code . '_title'];
            $serviceContent->slug = createSlug($request[$language->code . '_title']);
            $serviceContent->description = Purifier::clean($request[$language->code . '_description'], 'youtube');
            $serviceContent->tags = $request[$language->code . '_tags'];
            $serviceContent->skills = json_encode($request[$language->code . '_skills']);
            $serviceContent->meta_keywords = $request[$language->code . '_meta_keywords'];
            $serviceContent->meta_description = $request[$language->code . '_meta_description'];
            $serviceContent->save();
        }

        $request->session()->flash('success', 'New service added successfully!');

        return Response::json(['status' => 'success'], 200);
    }

    /**
     * Update the 'featured' status of a specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateFeaturedStatus(Request $request, $id)
    {
        $service = Service::query()->find($id);

        if ($request['is_featured'] == 'yes') {
            $service->update([
                'is_featured' => 'yes'
            ]);

            $request->session()->flash('success', 'Service featured successfully!');
        } else {
            $service->update([
                'is_featured' => 'no'
            ]);

            $request->session()->flash('success', 'Service unfeatured successfully!');
        }

        return redirect()->back();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $service = Service::where([['id', $id], ['seller_id', Auth::guard('seller')->user()->id]])->firstOrFail();
        $information['service'] = $service;

        // get all the languages from db
        $languages = Language::all();

        $languages->map(function ($language) use ($service) {
            // get service content information of each language from db
            $language['serviceData'] = $language->serviceContent()->where('service_id', $service->id)->first();

            // get all the forms of each language from db
            $language['forms'] = $language->form()->where('seller_id', Auth::guard('seller')->user()->id)->orderByDesc('id')->get();

            // get all the categories of each language from db
            $language['categories'] = $language->serviceCategory()->where('status', 1)->orderByDesc('id')->get();
        });

        $information['languages'] = $languages;

        return view('seller.service.edit', $information);
    }

    /**
     * Remove 'stored' slider image form storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function detachImage(Request $request)
    {
        $id = $request['id'];
        $key = $request['key'];

        $service = Service::query()->find($id);

        $sliderImages = json_decode($service->slider_images);

        if (count($sliderImages) == 1) {
            return Response::json(['message' => 'Sorry, the last image cannot be delete.'], 400);
        } else {
            $image = $sliderImages[$key];

            @unlink(public_path('assets/img/services/slider-images/' . $image));

            array_splice($sliderImages, $key, 1);

            $service->update([
                'slider_images' => json_encode($sliderImages)
            ]);

            return Response::json(['message' => 'Slider image removed successfully!'], 200);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ServiceUpdateRequest $request, $id)
    {
        $service = Service::query()->find($id);

        // merge slider images with existing images if request has new slider image
        if ($request->filled('slider_images')) {
            $prevImages = json_decode($service->slider_images);
            $newImages = $request['slider_images'];
            $imgArr = array_merge($prevImages, $newImages);
        }

        // store thumbnail image in storage
        if ($request->hasFile('thumbnail_image')) {
            $newImage = $request->file('thumbnail_image');
            $oldImage = $service->thumbnail_image;
            $thumbnailImage = UploadFile::update('./assets/img/services/thumbnail-images/', $newImage, $oldImage);
        }
        //Video Link format 
        $link = NULL;
        if ($request->filled('video_preview_link')) {
            $link = $request->video_preview_link;
            if (strpos($link, '&') != 0) {
                $link = substr($link, 0, strpos($link, '&'));
            }
        }
        //End video Link store


        // update data in db
        $service->update($request->except('thumbnail_image', 'slider_images', 'video_preview_link') + [
            'thumbnail_image' => $request->hasFile('thumbnail_image') ? $thumbnailImage : $service->thumbnail_image,
            'slider_images' => isset($imgArr) ? json_encode($imgArr) : $service->slider_images,
            'video_preview_link' => $link
        ]);

        $languages = Language::all();

        foreach ($languages as $language) {
            $serviceContent = ServiceContent::query()->where('service_id', '=', $id)
                ->where('language_id', '=', $language->id)
                ->first();
            if (empty($serviceContent)) {
                $serviceContent = new ServiceContent();
            }
            $serviceContent->service_category_id = $request[$language->code . '_category_id'];
            $serviceContent->service_subcategory_id = !empty($request[$language->code . '_subcategory_id']) ? $request[$language->code . '_subcategory_id'] : NULL;
            $serviceContent->form_id = $request[$language->code . '_form_id'];
            $serviceContent->title = $request[$language->code . '_title'];
            $serviceContent->slug = createSlug($request[$language->code . '_title']);
            $serviceContent->description = Purifier::clean($request[$language->code . '_description'], 'youtube');
            $serviceContent->tags = $request[$language->code . '_tags'];
            $serviceContent->skills = json_encode($request[$language->code . '_skills']);
            $serviceContent->meta_keywords = $request[$language->code . '_meta_keywords'];
            $serviceContent->meta_description = $request[$language->code . '_meta_description'];
            $serviceContent->save();
        }

        $request->session()->flash('success', 'Service updated successfully!');

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
        $this->deleteService($id);

        return redirect()->back()->with('success', 'Service deleted successfully!');
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
            $this->deleteService($id);
        }

        $request->session()->flash('success', 'Services deleted successfully!');

        return Response::json(['status' => 'success'], 200);
    }

    // service deletion code
    public function deleteService($id)
    {
        $service = Service::query()->find($id);

        // delete the thumbnail image
        @unlink(public_path('assets/img/services/thumbnail-images/' . $service->thumbnail_image));

        // delete the slider images
        $sliderImages = json_decode($service->slider_images);

        foreach ($sliderImages as $sliderImage) {
            @unlink(public_path('assets/img/services/slider-images/' . $sliderImage));
        }

        // delete all the service-contents
        $serviceContents = $service->content()->get();

        foreach ($serviceContents as $serviceContent) {
            $serviceContent->delete();
        }

        // delete all the packages of this service
        $packages = $service->package()->get();

        if (count($packages) > 0) {
            foreach ($packages as $package) {
                $package->delete();
            }
        }

        // delete all the addons of this service
        $addons = $service->addon()->get();

        if (count($addons) > 0) {
            foreach ($addons as $addon) {
                $addon->delete();
            }
        }

        // delete all the faqs of this service
        $faqs = $service->faq()->get();

        if (count($faqs) > 0) {
            foreach ($faqs as $faq) {
                $faq->delete();
            }
        }

        // delete all the reviews of this service
        $reviews = $service->review()->get();

        if (count($reviews) > 0) {
            foreach ($reviews as $review) {
                $review->delete();
            }
        }

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

        // delete wishlist records of this service
        $records = $service->wishlist()->get();

        if (count($records) > 0) {
            foreach ($records as $record) {
                $record->delete();
            }
        }

        $service->delete();
    }
}
