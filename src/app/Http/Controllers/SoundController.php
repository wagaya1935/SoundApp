<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Sound;

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
            'sound_file' => 'required|file|mimes:mp3,wav,ogg|max:10240', // 10MB以内
        ]);

        $path = $request->file('sound_file')->store('sounds', 'public');

        Sound::create([
            'title' => $request->title,
            'file_path' => $path,
        ]);

        return redirect('/sounds/create')->with('success', '音声の投稿に成功しました！');
    }

    public function destroy($id)
    {
        $sound = Sound::findOrFail($id);

        if(Storage::disk('public')->exists($sound->file_path)) {
            Storage::disk('public')->delete($sound->file_path);
        }

        $sound->delete();
        return redirect('/')->with('success', '曲を削除しました。');
    }
}
