<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Models\Sound;
use App\Models\Tag;

class SoundController extends Controller
{

    public function index(Request $request)
    {
        $search = $request->input('search');
        $query = Sound::query();

        if ($search) {
            $spaceConversion = mb_convert_kana($search, 's');
            $wordArraySearched = preg_split('/[\s,]+/u', $spaceConversion, -1, PREG_SPLIT_NO_EMPTY);

            foreach($wordArraySearched as $word) {
                $query->where(function($q) use ($word) {
                    $q->where('title', 'like', '%' . $word . '%')
                      ->orWhereHas('tags', function($subQuery) use ($word) {
                        $subQuery->where('name', 'like', '%' . $word . '%');
                      });
                });
            }
        }

        $sounds = $query->with('tags', 'user', 'likes')->latest()->get();

        return view('sounds.index', [
            'sounds' => $sounds,
            'search' => $search
        ]);
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
            'user_id' => Auth::id(),
        ]);

        if ($request->tags) {

            $spaceConversion = mb_convert_kana($request->tags, 's');
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

        return redirect()->route('sounds.index')->with('success', '投稿しました！');
    }

    public function toggleLike($id)
    {
        $sound = Sound::findOrFail($id);
        $user = Auth::user();

        if($sound->isLikedBy($user)) {
            $sound->likes()->detach($user->id);
            $liked = false;
        } else {
            $sound->likes()->attach($user->id);
            $liked = true;
        }

        $count = $sound->likes()->count();
        return response()->json([
            'liked' => $liked,
            'count' => $count,
        ]);
        
    }

    public function destroy($id){

        $sound = Sound::findOrFail($id);

        if ($sound->user_id !== Auth::id()) {
            abort(403, '権限がありません');
        }

        try{
            if(Storage::disk('public')->exists($sound->file_path)) {
                Storage::disk('public')->delete($sound->file_path);
            }
        } catch (\Exception $e) {
            
        }

        $sound->delete();
        return redirect()->route('sounds.index')->with('success', '曲を削除しました。');
    }
}
