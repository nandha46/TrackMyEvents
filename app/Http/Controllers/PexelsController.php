<?php

namespace App\Http\Controllers;

use App\Services\PexelsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PexelsController extends Controller
{
    protected PexelsService $pexelsService;

    public function __construct(PexelsService $pexelsService)
    {
        $this->pexelsService = $pexelsService;
    }

    /**
     * Search Pexels photos or fetch curated photos with pagination.
     */
    public function search(Request $request): JsonResponse
    {
        $query = $request->input('query');
        $page = max(1, (int) $request->input('page', 1));
        $perPage = min(30, max(1, (int) $request->input('per_page', 12)));

        $results = $this->pexelsService->getPhotos($query, $page, $perPage);

        return response()->json($results);
    }

    /**
     * Download and cache selected Pexels photo to local disk.
     */
    public function download(Request $request): JsonResponse
    {
        $request->validate([
            'image_url' => ['required', 'url'],
            'pexels_id' => ['nullable'],
            'keyword' => ['nullable', 'string'],
        ]);

        $imageUrl = $request->input('image_url');
        $pexelsId = $request->input('pexels_id');
        $keyword = $request->input('keyword');

        $savedFilename = $this->pexelsService->downloadAndSaveLocally($imageUrl, $pexelsId, $keyword);

        if (!$savedFilename) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to download and store image locally.',
            ], 500);
        }

        return response()->json([
            'success' => true,
            'filename' => $savedFilename,
            'asset_url' => asset('uploads/backgrounds/' . $savedFilename),
        ]);
    }

    /**
     * Show Pexels API Rate Limit statistics page under Profile.
     */
    public function rateLimits(Request $request): View
    {
        $rateLimits = $this->pexelsService->getLatestRateLimits();
        $isConfigured = $this->pexelsService->isConfigured();

        return view('profile.pexels-stats', [
            'rateLimits' => $rateLimits,
            'isConfigured' => $isConfigured,
        ]);
    }
}
