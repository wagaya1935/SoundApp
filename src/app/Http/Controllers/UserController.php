<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class UserController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'icon' => ['nullable', 'image', 'max:2048'],
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];

        if ($request->hasFile('icon')) {
            if ($user->icon_path && Storage::disk('public')->exists($user->icon_path)) {
                Storage::disk('public')->delete($user->icon_path);
            }

            $path = $request->file('icon')->store('icons', 'public');
            $user->icon_path = $path;
        }

        $user->save();
        return redirect()->route('profile.edit')->with('message', 'プロフィールを更新しました！');
    }

    public function confirmDelete()
    {
        return view('profile.delete');
    }

    public function destroy(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        $user->delete();

        return redirect('/')->with('message', '退会処理が完了しました');
    }
}
