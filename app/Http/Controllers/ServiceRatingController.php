<?php

namespace App\Http\Controllers;

use App\Models\ServiceRating;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ServiceRatingController extends Controller
{
    private const COOKIE = 'doctoramed_rating_voter';

    public function status(Request $request): JsonResponse
    {
        [$token, $isNew] = $this->voterToken($request);
        $response = response()->json($this->payload(hash('sha256', $token)));

        return $isNew ? $this->attachCookie($response, $token, $request) : $response;
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'score' => ['required', 'integer', 'between:1,5'],
            'locale' => ['nullable', 'in:uz,ru,en'],
        ]);
        [$token, $isNew] = $this->voterToken($request);
        $hash = hash('sha256', $token);

        try {
            ServiceRating::create([
                'score' => $validated['score'],
                'voter_hash' => $hash,
                'locale' => $validated['locale'] ?? 'uz',
            ]);
        } catch (UniqueConstraintViolationException) {
            return response()->json(array_merge($this->payload($hash), [
                'message' => 'Bu brauzer orqali allaqachon ovoz berilgan.',
            ]), 409);
        }

        $response = response()->json(array_merge($this->payload($hash), [
            'message' => 'Bahoyingiz uchun rahmat!',
        ]), 201);

        return $isNew ? $this->attachCookie($response, $token, $request) : $response;
    }

    private function payload(string $hash): array
    {
        $counts = ServiceRating::query()
            ->selectRaw('score, COUNT(*) as total')
            ->groupBy('score')
            ->pluck('total', 'score');
        $total = (int) $counts->sum();
        $weighted = $counts->sum(fn ($count, $score) => $count * $score);

        return [
            'voted' => ServiceRating::where('voter_hash', $hash)->exists(),
            'total' => $total,
            'average' => $total ? round($weighted / $total, 1) : 0,
            'results' => collect(range(5, 1))->map(fn ($score) => [
                'score' => $score,
                'count' => (int) ($counts[$score] ?? 0),
                'percent' => $total ? round(($counts[$score] ?? 0) * 100 / $total) : 0,
            ])->values(),
        ];
    }

    private function voterToken(Request $request): array
    {
        $cookieToken = $request->cookie(self::COOKIE);
        if ($cookieToken) {
            return [$cookieToken, false];
        }

        $browserToken = (string) $request->header('X-Rating-Voter', '');
        if (Str::isUuid($browserToken)) {
            return [$browserToken, true];
        }

        return [(string) Str::uuid(), true];
    }

    private function attachCookie(JsonResponse $response, string $token, Request $request): JsonResponse
    {
        return $response->cookie(self::COOKIE, $token, 60 * 24 * 365 * 5, '/', null, $request->isSecure(), true, false, 'Lax');
    }
}
