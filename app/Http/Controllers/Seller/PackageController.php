<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\ClientService\Service;
use App\Models\ClientService\ServicePackage;
use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class PackageController extends Controller
{
    public function index(Request $request, $id)
    {
        $language = Language::query()->where('code', '=', $request->language)->firstOrFail();
        $information['language'] = $language;

        $service = Service::where([['id', $id], ['seller_id', Auth::guard('seller')->user()->id]])->firstOrFail();
        $information['service'] = $service;
        $information['serviceTitle'] = $service->content()->where('language_id', $language->id)->pluck('title')->first();

        $information['packages'] = $service->package()->where('language_id', $language->id)->orderByDesc('id')->get();

        $information['langs'] = Language::all();

        $information['currencyInfo'] = $this->getCurrencyInfo();

        return view('seller.package.index', $information);
    }

    public function store(Request $request)
    {
        $rules = [
            'language_id' => 'required',
            'name' => 'required',
            'current_price' => 'required|numeric',
            'features' => 'required'
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

        ServicePackage::query()->create($request->all());

        $serviceId = $request['service_id'];

        $this->storeLowestPrice($serviceId);

        $request->session()->flash('success', 'New package added successfully!');

        return Response::json(['status' => 'success'], 200);
    }

    public function update(Request $request)
    {
        $rules = [
            'name' => 'required',
            'current_price' => 'required|numeric',
            'features' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return Response::json([
                'errors' => $validator->getMessageBag()
            ], 400);
        }

        $package = ServicePackage::query()->find($request->id);

        $package->update($request->all());

        $serviceId = $package->service_id;

        $this->storeLowestPrice($serviceId);

        $request->session()->flash('success', 'Package updated successfully!');

        return Response::json(['status' => 'success'], 200);
    }

    public function destroy($id)
    {
        $package = ServicePackage::query()->find($id);

        $package->delete();

        $serviceId = $package->service_id;

        $this->storeLowestPrice($serviceId);

        return redirect()->back()->with('success', 'Package deleted successfully!');
    }

    public function bulkDestroy(Request $request)
    {
        $ids = $request->ids;

        foreach ($ids as $id) {
            $package = ServicePackage::query()->find($id);

            $package->delete();

            $serviceId = $package->service_id;
        }

        $this->storeLowestPrice($serviceId);

        $request->session()->flash('success', 'Packages deleted successfully!');

        return response()->json(['status' => 'success'], 200);
    }

    public function storeLowestPrice($serviceId)
    {
        // get minimum price of package
        $minPrice = ServicePackage::query()->where('service_id', '=', $serviceId)->min('current_price');

        // find out the service
        $service = Service::query()->find($serviceId);

        // store minimum price in the service
        $service->update([
            'package_lowest_price' => $minPrice
        ]);

        return;
    }
}
