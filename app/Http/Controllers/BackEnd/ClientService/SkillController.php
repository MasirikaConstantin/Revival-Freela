<?php

namespace App\Http\Controllers\BackEnd\ClientService;

use App\Http\Controllers\Controller;
use App\Models\Language;
use App\Models\Skill;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class SkillController extends Controller
{
    public function index(Request $request)
    {
        $language = Language::query()->where('code', '=', $request->language)->firstOrFail();
        $information['language'] = $language;

        $information['skills'] = $language->skill()->orderByDesc('id')->get();

        $information['langs'] = Language::all();

        return view('backend.client-service.skills.index', $information);
    }

    public function store(Request $request)
    {
        $rules = [
            'language_id' => 'required',
            'name' => [
                'required',
                Rule::unique('skills')->where(function ($query) use ($request) {
                    return $query->where('language_id', $request->input('language_id'));
                })
            ],
            'status' => 'required|numeric'
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

        Skill::query()->create($request->except('slug') + [
            'slug' => createSlug($request['name'])
        ]);

        $request->session()->flash('success', 'New skill added successfully!');

        return Response::json(['status' => 'success'], 200);
    }

    public function update(Request $request)
    {
        $skill = Skill::query()->find($request->id);
        $rules = [
            'name' => [
                'required',
                Rule::unique('skills')->where(function ($query) use ($skill) {
                    return $query->where('language_id', $skill->language_id);
                })->ignore($request->id)
            ],
            'status' => 'required|numeric'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return Response::json([
                'errors' => $validator->getMessageBag()
            ], 400);
        }

        $skill->update($request->except('slug') + [
            'slug' => createSlug($request['name'])
        ]);

        $request->session()->flash('success', 'Skill updated successfully!');

        return Response::json(['status' => 'success'], 200);
    }

    public function destroy($id)
    {
        $skill = Skill::query()->find($id);
        $skill->delete();
        return redirect()->back()->with('success', 'Skill deleted successfully!');
    }

    public function bulkDestroy(Request $request)
    {
        $ids = $request->ids;

        foreach ($ids as $id) {
            $skill = Skill::query()->find($id);
            $skill->delete();
        }

        $request->session()->flash('success', 'Skill deleted successfully!');

        return Response::json(['status' => 'success'], 200);
    }
}
