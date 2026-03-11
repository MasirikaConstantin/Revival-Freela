<?php

namespace App\Http\Controllers\BackEnd\HomePage;

use App\Http\Controllers\Controller;
use App\Models\HomePage\Section;
use Illuminate\Http\Request;

class SectionController extends Controller
{
  public function index()
  {
    $sectionInfo = Section::query()->first();

    return view('backend.home-page.section-customization', compact('sectionInfo'));
  }

  public function update(Request $request)
  {
    $sectionInfo = Section::query()->first();

    $sectionInfo->update($request->all());

    $request->session()->flash('success', 'Section status updated successfully!');

    return redirect()->back();
  }
}
