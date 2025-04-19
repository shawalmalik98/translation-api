namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Translation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class TranslationController extends Controller
{
    public function index(Request $request)
    {
        $query = Translation::query();

        if ($request->filled('key')) {
            $query->where('key', 'like', "%{$request->key}%");
        }

        if ($request->filled('value')) {
            $query->where('value', 'like', "%{$request->value}%");
        }

        if ($request->filled('tag')) {
            $query->whereJsonContains('tags', $request->tag);
        }

        return response()->json($query->paginate(20));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'key' => 'required|string',
            'locale' => 'required|string',
            'value' => 'required|string',
            'tags' => 'nullable|array',
        ]);

        $translation = Translation::create($data);

        return response()->json($translation, 201);
    }

    public function show(Translation $translation)
    {
        return response()->json($translation);
    }

    public function update(Request $request, Translation $translation)
    {
        $data = $request->validate([
            'key' => 'sometimes|string',
            'locale' => 'sometimes|string',
            'value' => 'sometimes|string',
            'tags' => 'nullable|array',
        ]);

        $translation->update($data);

        return response()->json($translation);
    }

    public function destroy(Translation $translation)
    {
        $translation->delete();
        return response()->json(['message' => 'Deleted']);
    }

    public function export(Request $request)
    {
        $locale = $request->get('locale', 'en');
        $cacheKey = \"export_{$locale}\";

        $data = Cache::remember($cacheKey, 60, function () use ($locale) {
            return Translation::where('locale', $locale)->pluck('value', 'key');
        });

        return response()->json($data);
    }
}
