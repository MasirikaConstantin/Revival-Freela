<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\ClientService\Service;
use App\Models\ClientService\ServiceAddon;
use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class AddonController extends Controller
{
    public function index(Request $request, $id)
    {
        $language = Language::query()->where('code', '=', $request->language)->firstOrFail();
        $information['language'] = $language;
        $service = Service::where([['id', $id], ['seller_id', Auth::guard('seller')->user()->id]])->firstOrFail();
        $information['service'] = $service;
        $information['serviceTitle'] = $service->content()->where('language_id', $language->id)->pluck('title')->first();
        $information['addons'] = $service->addon()->where('language_id', $language->id)->orderByDesc('id')->get();
        $information['langs'] = Language::all();
        $information['currencyInfo'] = $this->getCurrencyInfo();
        return view('seller.addon.index', $information);
    }

    public function store(Request $request)
    {
        $rules = [
            'language_id' => 'required',
            'name' => 'required',
            'price' => 'required|numeric'
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

        ServiceAddon::query()->create($request->all());

        $request->session()->flash('success', 'New addon added successfully!');

        return Response::json(['status' => 'success'], 200);
    }

    public function update(Request $request)
    {
        $rules = [
            'name' => 'required',
            'price' => 'required|numeric'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return Response::json([
                'errors' => $validator->getMessageBag()
            ], 400);
        }

        $addon = ServiceAddon::query()->find($request->id);

        $addon->update($request->all());

        $request->session()->flash('success', 'Addon updated successfully!');

        return Response::json(['status' => 'success'], 200);
    }

    public function destroy($id)
    {
        $addon = ServiceAddon::query()->find($id);

        $addon->delete();

        return redirect()->back()->with('success', 'Addon deleted successfully!');
    }

    public function bulkDestroy(Request $request)
    {
        $ids = $request->ids;

        foreach ($ids as $id) {
            $addon = ServiceAddon::query()->find($id);

            $addon->delete();
        }

        $request->session()->flash('success', 'Addons deleted successfully!');

        return response()->json(['status' => 'success'], 200);
    }
}
