<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class ExternalEduController extends Controller
{
    public function openLibrarySearch(Request $request)
    {
        $validated = $request->validate([
            'q' => 'required|string|max:120',
            'limit' => 'sometimes|integer|min:1|max:25',
            'ttl' => 'sometimes|integer|min:1|max:120',
        ]);

        $q  = trim($validated['q']);
        $limit  = (int)($validated['limit'] ?? 10);
        $ttlMin = (int)($validated['ttl'] ?? 60);
        $ttlSec = $ttlMin * 60;
        $cacheKey = "ext:ol:search:q=" . md5($q) . ":limit={$limit}";

        $payload = Cache::remember($cacheKey, $ttlSec, function () use ($q, $limit) {
            $res = Http::timeout(10)
                ->acceptJson()
                ->get('https://openlibrary.org/search.json', [
                    'q' => $q,
                    'limit' => $limit,
                ]);

            if (!$res->ok()) {
                abort(502, 'Open Library unavailable');
            }

            $json = $res->json();
            $docs = $json['docs'] ?? [];

            $items = [];
            foreach ($docs as $d) {
                $items[] = [
                    'title' => $d['title'] ?? null,
                    'authors' => $d['author_name'] ?? [],
                    'first_year' => $d['first_publish_year'] ?? null,
                    'edition_cnt' => $d['edition_count'] ?? null,
                    'ol_key' => $d['key'] ?? null,
                    'work_url' => isset($d['key']) ? 'https://openlibrary.org' . $d['key'] : null,
                    'cover_i' => $d['cover_i'] ?? null,
                ];
            }

            return [
                'query' => $q,
                'count' => count($items),
                'items' => $items,
                'source' => 'openlibrary',
            ];
        });

        return response()->json($payload + ['cached' => true]);
    }
}
