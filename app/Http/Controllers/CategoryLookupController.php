<?php

namespace App\Http\Controllers;

use App\Models\SettingComponent;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoryLookupController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function eventCategories(Request $request): JsonResponse
    {
        $query = SettingComponent::query()
            ->whereHas('setting', function ($query) {
                $query->where('key', 'event_categories');
            });

        $selected = $request->input('selected');

        if ($selected !== null) {
            $ids = is_array($selected) ? $selected : array_filter(explode(',', (string) $selected));

            return response()->json(
                $query->whereIn('id', $ids)->orderBy('name')->get(['id', 'name'])
            );
        }

        $search = trim((string) $request->input('search'));

        if ($search !== '') {
            $query->where('name', 'like', '%' . $search . '%');
        }

        return response()->json(
            $query->orderBy('name')->limit(20)->get(['id', 'name'])
        );
    }
}
