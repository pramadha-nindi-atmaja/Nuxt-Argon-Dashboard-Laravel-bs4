<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use LaravelJsonApi\Core\Document\Error;
use LaravelJsonApi\Core\Responses\ErrorResponse;

class ProfileImageController extends Controller
{
    /**
     * Upload a profile image for the authenticated user
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function upload(Request $request)
    {
        try {
            $request->validate([
                'attachment' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            $user = auth()->user();
            
            // Delete old profile image if exists
            if ($user->profile_image) {
                Storage::disk('public')->delete($user->profile_image);
            }

            // Store the new profile image
            $path = $request->file('attachment')->store('profile_images', 'public');
            
            // Update user profile image path
            $user->profile_image = $path;
            $user->save();

            return response()->json([
                'message' => 'Profile image uploaded successfully',
                'url' => Storage::url($path)
            ], 200);
        } catch (\Exception $e) {
            return ErrorResponse::make([
                Error::fromArray([
                    'title' => 'Upload Failed',
                    'detail' => 'Failed to upload profile image: ' . $e->getMessage()
                ])
            ]);
        }
    }
}