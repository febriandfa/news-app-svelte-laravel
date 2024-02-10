<?php

namespace App\Http\Controllers;

use App\Models\Ads;
use App\Models\AdsImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class AdsController extends Controller
{
    public function index()
    {
        $adss = Ads::with('ads_images')->get();

        return Inertia::render('Ads/IndexAds', [
            'adss' => $adss,
        ]);
    }

    public function create()
    {
        return Inertia::render('Ads/AddAds');
    }

    public function store(Request $request)
    {
        try {

            $ads = Ads::create([
                'title' => $request->input('title'),
                'subtitle' => $request->input('subtitle'),
            ]);
    
            $imagesData = [];
    
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $imagePath = $image->storePubliclyAs('ads/images', $image->getClientOriginalName(), 'public');
    
                    $imagesData[] = [
                        'ads_id' => $ads->id,
                        'images' => $imagePath,
                    ];
                }
                AdsImage::insert($imagesData);
            }
    
            return redirect()->route('ads.index');
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }

    public function show($id)
    {
        $ads = Ads::where('id', $id)->with('ads_images')->first();

        return Inertia::render('Ads/ShowAds', [
            'ads' => $ads,
        ]);
    }

    public function destroy($id)
    {
        $ads = Ads::findOrFail($id);

        foreach ($ads->ads_images as $image) {
            Storage::delete('public/ads/images/' . basename($image->images));
        }

        $ads->delete();
    }
}
