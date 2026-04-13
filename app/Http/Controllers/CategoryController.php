<?php

namespace App\Http\Controllers;

use App\Helpers\MenuHelper;
use App\Helpers\SlugHelper;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function index(Request $request): View
    {
        $userId = $request->user()->id;

        return view('categories.index', [
            'title' => 'Kategori',
            'pageTitle' => 'Kategori',
            'categories' => Category::query()
                ->where('user_id', $userId)
                ->withCount('transactions')
                ->orderBy('type')
                ->orderBy('name')
                ->get(),
            'editing' => $request->filled('edit')
                ? Category::query()->where('user_id', $userId)->findOrFail($request->integer('edit'))
                : new Category(['is_active' => true, 'type' => 'income', 'color' => '#12B76A', 'icon' => 'wallet']),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatedData($request);
        $data['slug'] = SlugHelper::unique($data['name'], Category::class);
        $data['is_active'] = $request->boolean('is_active', true);
        $data['user_id'] = $request->user()->id;

        Category::query()->create($data);

        return redirect()
            ->route('categories.index')
            ->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function update(Request $request, Category $category): RedirectResponse
    {
        abort_unless($category->user_id === $request->user()->id, 403);

        $data = $this->validatedData($request, $category);
        $data['slug'] = SlugHelper::unique($data['name'], Category::class, $category->id);
        $data['is_active'] = $request->boolean('is_active');

        $category->update($data);

        return redirect()
            ->route('categories.index')
            ->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(Category $category): RedirectResponse
    {
        abort_unless($category->user_id === request()->user()->id, 403);

        if ($category->transactions()->exists()) {
            return redirect()
                ->route('categories.index')
                ->with('error', 'Kategori tidak bisa dihapus karena sudah dipakai transaksi.');
        }

        $category->delete();

        return redirect()
            ->route('categories.index')
            ->with('success', 'Kategori berhasil dihapus.');
    }

    private function validatedData(Request $request, ?Category $category = null): array
    {
        $allowedIcons = array_keys(MenuHelper::getCategoryIconOptions());

        if ($category?->icon && !in_array($category->icon, $allowedIcons, true)) {
            $allowedIcons[] = $category->icon;
        }

        return $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('categories', 'name')
                    ->where(fn ($query) => $query->where('user_id', $request->user()->id))
                    ->ignore($category),
            ],
            'type' => ['required', Rule::in(array_keys(config('finance.transaction_types')))],
            'color' => ['nullable', 'regex:/^#([A-Fa-f0-9]{6})$/'],
            'icon' => ['nullable', Rule::in($allowedIcons)],
            'description' => ['nullable', 'string'],
        ]);
    }
}
