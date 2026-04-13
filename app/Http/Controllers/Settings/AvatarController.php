<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use Cloudinary\Cloudinary;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AvatarController extends Controller
{
    private Cloudinary $cloudinary;

    public function __construct()
    {
        $this->cloudinary = new Cloudinary([
            'cloud' => [
                'cloud_name' => config('services.cloudinary.cloud_name'),
                'api_key'    => config('services.cloudinary.api_key'),
                'api_secret' => config('services.cloudinary.api_secret'),
            ],
            'url' => [
                'secure' => true,
            ],
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'avatar' => [
                'required',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],
        ]);

        $user = $request->user();

        // Eliminar avatar anterior
        if ($user->avatar_public_id) {
            $this->cloudinary->adminApi()->deleteAssets([$user->avatar_public_id]);
        }

        // Subir nuevo avatar
        $result = $this->cloudinary->uploadApi()->upload(
            $request->file('avatar')->getRealPath(),
            [
                'folder'         => 'starkit/avatars',
                'transformation' => [
                    'width'        => 200,
                    'height'       => 200,
                    'crop'         => 'fill',
                    'gravity'      => 'face',
                    'quality'      => 'auto',
                    'fetch_format' => 'auto',
                ],
            ]
        );

        $user->update([
            'avatar_url'       => $result['secure_url'],
            'avatar_public_id' => $result['public_id'],
        ]);

        return back();
    }

    public function destroy(Request $request): RedirectResponse
    {
        $user = $request->user();

        if ($user->avatar_public_id) {
            $this->cloudinary->adminApi()->deleteAssets([$user->avatar_public_id]);

            $user->update([
                'avatar_url'       => null,
                'avatar_public_id' => null,
            ]);
        }

        return back();
    }
}
