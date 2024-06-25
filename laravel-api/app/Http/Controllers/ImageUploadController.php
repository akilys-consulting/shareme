<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ImageUploadController extends Controller
{
    public function upload(Request $request)
    {
        // Validation basique pour s'assurer que l'image est présente
        $request->validate([
            'image' => 'required|string',
        ]);

        // Décoder la chaîne base64
        $imageData = $request->input('image');
        $image = str_replace('data:image/png;base64,', '', $imageData);
        $image = str_replace('data:image/jpeg;base64,', '', $image);
        $image = str_replace(' ', '+', $image);
        $imageName = time() . '.png';

        // Sauvegarder l'image sur le serveur
        Storage::disk('public')->put($imageName, base64_decode($image));

        // Retourner une réponse avec le chemin de l'image
        return response()->json(['fichier' => $imageName], 201);
    }
}