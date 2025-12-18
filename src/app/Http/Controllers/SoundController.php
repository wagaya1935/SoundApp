<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Sound;
use App\Models\Tag;

class SoundController extends Controller
{

    public function index()
    {
        $sounds = Sound::latest()->get();

        return view('sounds.index', ['sounds' => $sounds]);
    }

    public function create()
    {
        return view('sounds.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:100',
            'sound_file' => 'required|file|mimes:mp3,wav,ogg|max:20480',
            'tags' => 'nullable|string', 
        ]);

        $path = $request->file('sound_file')->store('sounds', 'public');

        $sound = Sound::create([
            'title' => $request->title,
            'file_path' => $path,
        ]);

        if ($request->tags) {
            $tagNameArray = preg_split('/[\s ]+/u', $request->tags, -1, PREG_SPLIT_NO_EMPTY);

            $tagNameArray = array_unique($tagNameArray);

            foreach ($tagNameArray as $tagName) {
                //　#が付いていなければ付ける
                if (!str_starts_with($tagName, '#')) {
                    $tagName = '#' . $tagName;
                }

                $tag = Tag::firstOrCreate(['name' => $tagName]);

                $sound->tags()->attach($tag->id);
            }
        }

        return redirect('/sounds/create')->with('success', '投稿しました！');
    }

    public function destroy($id){

        $sound = Sound::findOrFail($id);

        if(Storage::disk('public')->exists($sound->file_path)) {
            Storage::disk('public')->delete($sound->file_path);
        }

        $sound->delete();
        return redirect('/')->with('success', '曲を削除しました。');
    }
}
