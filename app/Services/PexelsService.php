<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PexelsService
{
    protected ?string $apiKey;
    protected string $baseUrl;

    public const RATE_LIMIT_CACHE_KEY = 'pexels_ratelimit_stats';

    public function __construct()
    {
        $this->apiKey = config('services.pexels.key');
        $this->baseUrl = rtrim(config('services.pexels.base_url', 'https://api.pexels.com/v1'), '/');
    }

    /**
     * Determine whether the Pexels API key is configured.
     */
    public function isConfigured(): bool
    {
        return !empty($this->apiKey);
    }

    /**
     * Search photos or fetch curated photos if query is empty.
     *
     * @param string|null $query
     * @param int $page
     * @param int $perPage
     * @return array
     */
    public function getPhotos(?string $query = null, int $page = 1, int $perPage = 15): array
    {
        $query = trim($query ?? '');

        if (!$this->isConfigured()) {
            return $this->getMockPhotos($query, $page, $perPage);
        }

        try {
            $verifySsl = config('services.pexels.verify_ssl', app()->isProduction());
            $client = Http::withHeaders([
                'Authorization' => $this->apiKey,
            ])
            ->timeout(10)
            ->withOptions([
                'verify' => $verifySsl,
            ]);

            if ($query !== '') {
                $response = $client->get("{$this->baseUrl}/search", [
                    'query' => $query,
                    'page' => $page,
                    'per_page' => $perPage,
                    'orientation' => 'landscape',
                ]);
            } else {
                $response = $client->get("{$this->baseUrl}/curated", [
                    'page' => $page,
                    'per_page' => $perPage,
                ]);
            }

            $this->storeRateLimitsFromResponse($response->headers());

            if ($response->successful()) {
                $data = $response->json();
                return [
                    'success' => true,
                    'is_curated' => empty($query),
                    'page' => $data['page'] ?? $page,
                    'per_page' => $data['per_page'] ?? $perPage,
                    'total_results' => $data['total_results'] ?? 0,
                    'next_page' => $data['next_page'] ?? null,
                    'prev_page' => $data['prev_page'] ?? null,
                    'photos' => $this->formatPhotos($data['photos'] ?? []),
                    'rate_limits' => $this->getLatestRateLimits(),
                ];
            }

            Log::error('Pexels API error: ' . $response->body());
            return [
                'success' => false,
                'message' => 'Pexels API responded with status ' . $response->status(),
                'photos' => [],
                'rate_limits' => $this->getLatestRateLimits(),
            ];
        } catch (\Throwable $e) {
            Log::error('Pexels request exception: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => $e->getMessage(),
                'photos' => [],
                'rate_limits' => $this->getLatestRateLimits(),
            ];
        }
    }

    /**
     * Download a selected Pexels photo and save a copy locally on disk.
     *
     * @param string $imageUrl
     * @param string|int|null $pexelsId
     * @param string|null $keyword
     * @return string|null The saved local filename
     */
    public function downloadAndSaveLocally(string $imageUrl, string|int|null $pexelsId = null, ?string $keyword = null): ?string
    {
        try {
            $cleanKeyword = $keyword ? Str::slug(Str::limit($keyword, 25, '')) . '_' : 'pexels_';
            $safeId = $pexelsId ? (string)$pexelsId : uniqid();
            $filename = 'pexels_' . $cleanKeyword . $safeId . '_' . time() . '.jpg';
            $destinationDir = public_path('uploads/backgrounds');

            if (!File::isDirectory($destinationDir)) {
                File::makeDirectory($destinationDir, 0755, true, true);
            }

            // Check if file with same pexels ID already exists to save bandwidth
            $existing = glob($destinationDir . '/pexels_*' . $safeId . '*.jpg');
            if (!empty($existing) && file_exists($existing[0])) {
                return basename($existing[0]);
            }

            $verifySsl = config('services.pexels.verify_ssl', app()->isProduction());
            $response = Http::timeout(20)->withOptions(['verify' => $verifySsl])->get($imageUrl);
            if ($response->successful()) {
                File::put($destinationDir . '/' . $filename, $response->body());
                return $filename;
            }

            Log::warning('Failed to download Pexels photo: ' . $imageUrl);
            return null;
        } catch (\Throwable $e) {
            Log::error('Error downloading Pexels image: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Store rate limits received in response headers.
     */
    public function storeRateLimitsFromResponse(array $headers): void
    {
        $limit = $this->getHeaderValue($headers, 'X-Ratelimit-Limit');
        $remaining = $this->getHeaderValue($headers, 'X-Ratelimit-Remaining');
        $reset = $this->getHeaderValue($headers, 'X-Ratelimit-Reset');

        if ($limit !== null || $remaining !== null || $reset !== null) {
            $stats = [
                'limit' => $limit ? (int)$limit : null,
                'remaining' => $remaining ? (int)$remaining : null,
                'reset' => $reset ? (int)$reset : null,
                'reset_human' => $reset ? date('Y-m-d H:i:s', (int)$reset) : null,
                'updated_at' => now()->toDateTimeString(),
            ];

            Cache::forever(self::RATE_LIMIT_CACHE_KEY, $stats);
        }
    }

    /**
     * Retrieve the latest cached rate limits statistics.
     */
    public function getLatestRateLimits(): array
    {
        return Cache::get(self::RATE_LIMIT_CACHE_KEY, [
            'limit' => 20000,
            'remaining' => 20000,
            'reset' => time() + 3600,
            'reset_human' => date('Y-m-d H:i:s', time() + 3600),
            'updated_at' => now()->toDateTimeString(),
            'is_mock' => !$this->isConfigured(),
        ]);
    }

    /**
     * Extract header case-insensitively.
     */
    protected function getHeaderValue(array $headers, string $headerName): ?string
    {
        foreach ($headers as $key => $values) {
            if (strcasecmp($key, $headerName) === 0) {
                return is_array($values) ? ($values[0] ?? null) : $values;
            }
        }
        return null;
    }

    /**
     * Format raw photo items.
     */
    protected function formatPhotos(array $photos): array
    {
        return array_map(function ($photo) {
            return [
                'id' => $photo['id'] ?? null,
                'width' => $photo['width'] ?? null,
                'height' => $photo['height'] ?? null,
                'url' => $photo['url'] ?? 'https://www.pexels.com',
                'photographer' => $photo['photographer'] ?? 'Pexels Photographer',
                'photographer_url' => $photo['photographer_url'] ?? 'https://www.pexels.com',
                'photographer_id' => $photo['photographer_id'] ?? null,
                'avg_color' => $photo['avg_color'] ?? '#1e293b',
                'alt' => $photo['alt'] ?? 'Event Background',
                'src' => [
                    'original' => $photo['src']['original'] ?? '',
                    'large2x' => $photo['src']['large2x'] ?? ($photo['src']['large'] ?? ''),
                    'large' => $photo['src']['large'] ?? '',
                    'medium' => $photo['src']['medium'] ?? '',
                    'small' => $photo['src']['small'] ?? '',
                    'tiny' => $photo['src']['tiny'] ?? '',
                ],
            ];
        }, $photos);
    }

    /**
     * Fallback mock photos when API key is not yet set.
     */
    protected function getMockPhotos(?string $query, int $page, int $perPage): array
    {
        $curatedPresets = [
            [
                'id' => 3184291,
                'photographer' => 'Fauxels',
                'photographer_url' => 'https://www.pexels.com/@fauxels',
                'url' => 'https://www.pexels.com/photo/group-of-people-sitting-indoors-3184291/',
                'alt' => 'Celebration and Team Event',
                'avg_color' => '#3b3131',
                'src' => [
                    'medium' => 'https://images.pexels.com/photos/3184291/pexels-photo-3184291.jpeg?auto=compress&cs=tinysrgb&h=350',
                    'large' => 'https://images.pexels.com/photos/3184291/pexels-photo-3184291.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
                    'original' => 'https://images.pexels.com/photos/3184291/pexels-photo-3184291.jpeg',
                ],
            ],
            [
                'id' => 1190297,
                'photographer' => 'Wendy Wei',
                'photographer_url' => 'https://www.pexels.com/@wendywei',
                'url' => 'https://www.pexels.com/photo/audience-during-a-concert-1190297/',
                'alt' => 'Concert and Night Celebration',
                'avg_color' => '#2b1c2b',
                'src' => [
                    'medium' => 'https://images.pexels.com/photos/1190297/pexels-photo-1190297.jpeg?auto=compress&cs=tinysrgb&h=350',
                    'large' => 'https://images.pexels.com/photos/1190297/pexels-photo-1190297.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
                    'original' => 'https://images.pexels.com/photos/1190297/pexels-photo-1190297.jpeg',
                ],
            ],
            [
                'id' => 1540406,
                'photographer' => 'Daria Shevtsova',
                'photographer_url' => 'https://www.pexels.com/@daria',
                'url' => 'https://www.pexels.com/photo/birthday-cake-with-candles-1540406/',
                'alt' => 'Birthday Cake and Candles',
                'avg_color' => '#5c4333',
                'src' => [
                    'medium' => 'https://images.pexels.com/photos/1540406/pexels-photo-1540406.jpeg?auto=compress&cs=tinysrgb&h=350',
                    'large' => 'https://images.pexels.com/photos/1540406/pexels-photo-1540406.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
                    'original' => 'https://images.pexels.com/photos/1540406/pexels-photo-1540406.jpeg',
                ],
            ],
            [
                'id' => 1024960,
                'photographer' => 'Asad Photo Maldives',
                'photographer_url' => 'https://www.pexels.com/@asadphotography',
                'url' => 'https://www.pexels.com/photo/island-surrounded-by-body-of-water-1024960/',
                'alt' => 'Tropical Island Travel & Vacation',
                'avg_color' => '#14687d',
                'src' => [
                    'medium' => 'https://images.pexels.com/photos/1024960/pexels-photo-1024960.jpeg?auto=compress&cs=tinysrgb&h=350',
                    'large' => 'https://images.pexels.com/photos/1024960/pexels-photo-1024960.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
                    'original' => 'https://images.pexels.com/photos/1024960/pexels-photo-1024960.jpeg',
                ],
            ],
            [
                'id' => 1708912,
                'photographer' => 'SplitShire',
                'photographer_url' => 'https://www.pexels.com/@splitshire',
                'url' => 'https://www.pexels.com/photo/party-decorations-1708912/',
                'alt' => 'Milestone Celebration Confetti',
                'avg_color' => '#3a3a40',
                'src' => [
                    'medium' => 'https://images.pexels.com/photos/1708912/pexels-photo-1708912.jpeg?auto=compress&cs=tinysrgb&h=350',
                    'large' => 'https://images.pexels.com/photos/1708912/pexels-photo-1708912.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
                    'original' => 'https://images.pexels.com/photos/1708912/pexels-photo-1708912.jpeg',
                ],
            ],
            [
                'id' => 1450360,
                'photographer' => 'Evoluer',
                'photographer_url' => 'https://www.pexels.com/@evoluer',
                'url' => 'https://www.pexels.com/photo/skyline-view-during-golden-hour-1450360/',
                'alt' => 'Sunset Cityscape Milestone',
                'avg_color' => '#8e624b',
                'src' => [
                    'medium' => 'https://images.pexels.com/photos/1450360/pexels-photo-1450360.jpeg?auto=compress&cs=tinysrgb&h=350',
                    'large' => 'https://images.pexels.com/photos/1450360/pexels-photo-1450360.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
                    'original' => 'https://images.pexels.com/photos/1450360/pexels-photo-1450360.jpeg',
                ],
            ],
        ];

        return [
            'success' => true,
            'is_curated' => empty($query),
            'page' => $page,
            'per_page' => $perPage,
            'total_results' => count($curatedPresets),
            'next_page' => null,
            'prev_page' => null,
            'photos' => $this->formatPhotos($curatedPresets),
            'rate_limits' => $this->getLatestRateLimits(),
        ];
    }
}
