<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

class EditorUploadController extends Controller
{
    /**
     * Upload one or multiple images for Rich Text Editor (narrative articles/projects).
     * Accessible by authenticated Admin or Author.
     */
    public function upload(Request $request)
    {
        // Require either Admin or Author guard
        if (!auth('admin')->check() && !auth('author')->check()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Please login to upload content images.'
            ], 401);
        }

        // Gather all uploaded files from various possible input names
        $files = [];
        if ($request->hasFile('images')) {
            $files = is_array($request->file('images')) ? $request->file('images') : [$request->file('images')];
        } elseif ($request->hasFile('image')) {
            $files = [$request->file('image')];
        } elseif ($request->hasFile('upload')) {
            $files = [$request->file('upload')];
        } elseif ($request->hasFile('file')) {
            $files = [$request->file('file')];
        }

        if (empty($files)) {
            return response()->json([
                'success' => false,
                'message' => 'No image file was received.'
            ], 422);
        }

        $uploadedUrls = [];
        $uploadedFilenames = [];
        $allowedExts = ['jpg', 'jpeg', 'png', 'webp', 'gif', 'svg'];

        foreach ($files as $file) {
            if ($file && $file->isValid()) {
                $ext = strtolower($file->getClientOriginalExtension());
                if (!in_array($ext, $allowedExts)) {
                    continue;
                }

                $filename = time() . '_' . Str::random(10) . '.' . $ext;
                $file->move(public_path('uploads'), $filename);

                $url = asset('uploads/' . $filename);
                $uploadedUrls[] = $url;
                $uploadedFilenames[] = $filename;
            }
        }

        if (empty($uploadedUrls)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid image format. Allowed: JPG, PNG, WEBP, GIF, SVG.'
            ], 422);
        }

        return response()->json([
            'success' => true,
            'url' => $uploadedUrls[0],
            'urls' => $uploadedUrls,
            'filename' => $uploadedFilenames[0],
            'filenames' => $uploadedFilenames,
            'count' => count($uploadedUrls)
        ]);
    }
}
