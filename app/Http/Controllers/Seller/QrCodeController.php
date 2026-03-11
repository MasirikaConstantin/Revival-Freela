<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Http\Helpers\UploadFile;
use App\Models\BasicSettings\Basic;
use App\Models\QRCode as QRCodeModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QrCodeController extends Controller
{
    public function generate()
    {
        $bs = Basic::query()->first();

        if (is_null($bs->qr_image) || !file_exists(public_path('./assets/img/qr-codes/' . $bs->qr_image))) {
            $fileName = uniqid() . '.png';
            $directory = public_path('./assets/img/qr-codes/');
            @mkdir($directory, 0775, true);

            $qrLocation = $directory . $fileName;

            // generate a default qr-code
            QrCode::size(250)->errorCorrection('H')
                ->color(0, 0, 0)
                ->format('png')
                ->style('square')
                ->eye('square')
                ->generate(url('/'), $qrLocation);

            $bs->updateOrCreate(
                ['uniqid' => '12345'],
                [
                    'qr_url' => url('/'),
                    'qr_image' => $fileName
                ]
            );
        }
        $bs = Basic::query()->first();

        return view('seller.qr-code.generate', compact('bs'));
    }

    public function regenerate(Request $request)
    {
        if (!$request->filled('url')) {
            return Response::json([
                'message' => 'URL field cannot be empty.'
            ], 400);
        }

        $bs = Basic::query()->first();

        $type = $request->type;
        $directory = './assets/img/qr-codes/';

        // code for inserted image in qr-code
        if ($type == 'image' && $request->hasFile('image')) {
            $newInsertedImg = $request->file('image');
            $oldInsertedImg = $bs->qr_inserted_image;

            $imageName = UploadFile::update($directory, $newInsertedImg, $oldInsertedImg);
        }

        // get rgb color from hex code
        $color = $this->hex2rgb($request->color);
        $directory = public_path($directory);
        // delete previous qr code
        @unlink($directory . $bs->qr_image);

        @mkdir($directory, 0775, true);
        $qrCodeName = uniqid() . '.png';
        $qrLocation = $directory . $qrCodeName;

        // generate a new qr-code
        QrCode::size($request->size)
            ->errorCorrection('H')
            ->margin($request->margin)
            ->color($color['red'], $color['green'], $color['blue'])
            ->format('png')
            ->style($request->style)
            ->eye($request->eye_style)
            ->generate($request->url, $qrLocation);

        $qrcodeSize = $request->size;

        if ($type == 'image') {
            // calculate the size of inserted image
            $insertedImageSize = $request->image_size;
            $calculatedSize = ($qrcodeSize * $insertedImageSize) / 100;

            // inserting image using 'image intervention' & saving the qr-code in folder
            if ($request->hasFile('image')) {
                $qrCode = Image::make($directory . $qrCodeName);
                $logo = Image::make($directory . $imageName);

                $logo->resize(null, $calculatedSize, function ($constraint) {
                    $constraint->aspectRatio();
                });

                $logoWidth = $logo->width();
                $logoHeight = $logo->height();

                $xPos = (($qrcodeSize - $logoWidth) * $request->img_x_pos) / 100;
                $yPos = (($qrcodeSize - $logoHeight) * $request->img_y_pos) / 100;

                $qrCode->insert($logo, 'top-left', (int)$xPos, (int)$yPos);

                $qrCode->save($directory . $qrCodeName);
            } else {
                if (!is_null($bs->qr_inserted_image) && file_exists($directory . $bs->qr_inserted_image)) {
                    $qrCode = Image::make($directory . $qrCodeName);
                    $logo = Image::make($directory . $bs->qr_inserted_image);

                    $logo->resize(null, $calculatedSize, function ($constraint) {
                        $constraint->aspectRatio();
                    });

                    $logoWidth = $logo->width();
                    $logoHeight = $logo->height();

                    $xPos = (($qrcodeSize - $logoWidth) * $request->img_x_pos) / 100;
                    $yPos = (($qrcodeSize - $logoHeight) * $request->img_y_pos) / 100;

                    $qrCode->insert($logo, 'top-left', (int)$xPos, (int)$yPos);

                    $qrCode->save($directory . $qrCodeName);
                }
            }
        } else if ($type == 'text') {
            // calculate the size of input text
            $textSize = $request->text_size;
            $calculatedSize = ($qrcodeSize * $textSize) / 100;

            $qrCode = Image::make($directory . $qrCodeName);

            $logo = Image::canvas($request->text_width, $calculatedSize, '#ffffff')
                ->text($request->text, 0, 0, function ($font) use ($request, $calculatedSize) {
                    $font->file(public_path('./assets/fonts/Lato-Regular.ttf'));
                    $font->size($calculatedSize);
                    $font->color('#' . $request->text_color);
                    $font->align('left');
                    $font->valign('top');
                });

            $logoWidth = $logo->width();
            $logoHeight = $logo->height();

            $xPos = (($qrcodeSize - $logoWidth) * $request->txt_x_pos) / 100;
            $yPos = (($qrcodeSize - $logoHeight) * $request->txt_y_pos) / 100;

            // inserting text using 'image intervention' & saving the qr-code in folder
            $qrCode->insert($logo, 'top-left', (int)$xPos, (int)$yPos);

            $qrCode->save($directory . $qrCodeName);
        }

        // save data into db
        $bs->qr_url = $request->url;
        $bs->qr_image = $qrCodeName;
        $bs->qr_color = $request->color;
        $bs->qr_size = $request->size;
        $bs->qr_style = $request->style;
        $bs->qr_eye_style = $request->eye_style;
        $bs->qr_margin = $request->margin;
        $bs->qr_type = $request->type;

        if ($type == 'image') {
            $bs->qr_inserted_image = $request->hasFile('image') ? $imageName : $bs->qr_inserted_image;
            $bs->qr_inserted_image_size = $request->image_size;
            $bs->qr_inserted_image_x = $request->img_x_pos;
            $bs->qr_inserted_image_y = $request->img_y_pos;
        } else if ($type == 'text') {
            $bs->qr_text = $request->text;
            $bs->qr_text_color = $request->text_color;
            $bs->qr_text_size = $request->text_size;
            $bs->qr_text_x = $request->txt_x_pos;
            $bs->qr_text_y = $request->txt_y_pos;
        }

        $bs->save();

        return Response::json([
            'qrcode' => asset('assets/img/qr-codes/' . $bs->qr_image)
        ], 200);
    }

    public function hex2rgb($hexValue)
    {
        $arr = str_split($hexValue);

        if (sizeof($arr) == 6) {
            list($r, $g, $b) = array($arr[0] . $arr[1], $arr[2] . $arr[3], $arr[4] . $arr[5]);
        } else if (sizeof($arr) == 3) {
            list($r, $g, $b) = array($arr[0] . $arr[0], $arr[1] . $arr[1], $arr[2] . $arr[2]);
        } else {
            return false;
        }

        $red = hexdec($r);
        $green = hexdec($g);
        $blue = hexdec($b);

        return array('red' => $red, 'green' => $green, 'blue' => $blue);
    }

    public function saveQrCode(Request $request)
    {
        $rule = [
            'name' => 'required'
        ];

        $validator = Validator::make($request->only('name'), $rule);

        if ($validator->fails()) {
            $request->session()->flash('warning', 'Please enter the name of this qr code.');
            return redirect()->back();
        }

        $bs = Basic::query()->first();
        QRCodeModel::query()->create($request->except('image', 'url') + [
            'image' => $bs->qr_image,
            'url' => $bs->qr_url,
            'seller_id' => Auth::guard('seller')->user()->id
        ]);
        $this->resetFilters($bs);

        $request->session()->flash('success', 'QR code saved successfully.');
        return redirect()->back();
    }

    public function clearFilters()
    {
        $bs = Basic::query()->first();

        $this->resetFilters($bs, 'full clear');

        Session::flash('success', 'Everything has cleared.');

        return redirect()->back();
    }

    public function resetFilters($data, $type = NULL)
    {
        if ($type == 'full clear') {

            @unlink(public_path('./assets/img/qr-codes/' . $data->qr_image));
        }

        @unlink(public_path('./assets/img/qr-codes/' . $data->qr_inserted_image));

        // set default information for qr-code in db
        $data->update([
            'qr_url' => NULL,
            'qr_image' => NULL,
            'qr_color' => '000000',
            'qr_size' => 250,
            'qr_style' => 'square',
            'qr_eye_style' => 'square',
            'qr_margin' => 0,
            'qr_type' => 'default',
            'qr_inserted_image' => NULL,
            'qr_inserted_image_size' => 20,
            'qr_inserted_image_x' => 50,
            'qr_inserted_image_y' => 50,
            'qr_text' => NULL,
            'qr_text_color' => '000000',
            'qr_text_size' => 15,
            'qr_text_x' => 50,
            'qr_text_y' => 50
        ]);

        return;
    }

    public function savedCodes()
    {
        $qrcodes = QRCodeModel::query()->where('seller_id', Auth::guard('seller')->user()->id)->orderByDesc('id')->get();

        return view('seller.qr-code.saved-codes', compact('qrcodes'));
    }

    public function deleteQrCode($id)
    {
        $qrcode = QRCodeModel::query()->where([['seller_id', Auth::guard('seller')->user()->id], ['id', $id]])->firstOrFail();

        @unlink(public_path('./assets/img/qr-codes/' . $qrcode->image));

        $qrcode->delete();

        Session::flash('success', 'QR code deleted successfully.');

        return redirect()->back();
    }

    public function bulkDeleteQrCode(Request $request)
    {
        $ids = $request->ids;

        foreach ($ids as $id) {
            $qrcode = QRCodeModel::where([['seller_id', Auth::guard('seller')->user()->id], ['id', $id]])->firstOrFail();

            @unlink(public_path('./assets/img/qr-codes/' . $qrcode->image));

            $qrcode->delete();
        }

        $request->session()->flash('success', 'QR codes deleted successfully.');

        return Response::json(['status' => 'success'], 200);
    }
}
