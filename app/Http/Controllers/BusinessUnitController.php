<?php

namespace App\Http\Controllers;

use App\Helpers\SlugHelper;
use App\Models\BusinessUnit;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class BusinessUnitController extends Controller
{
    public function index(Request $request): View
    {
        $userId = $request->user()->id;

        return view('business-units.index', [
            'title' => 'Unit Bisnis',
            'pageTitle' => 'Unit Bisnis',
            'businessUnits' => BusinessUnit::query()
                ->where('user_id', $userId)
                ->withCount('transactions')
                ->orderBy('name')
                ->get(),
            'editing' => $request->filled('edit')
                ? BusinessUnit::query()->where('user_id', $userId)->findOrFail($request->integer('edit'))
                : new BusinessUnit(['is_active' => true]),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatedData($request);
        $data['slug'] = SlugHelper::unique($data['name'], BusinessUnit::class);
        $data['is_active'] = $request->boolean('is_active', true);
        $data['user_id'] = $request->user()->id;

        BusinessUnit::query()->create($data);

        return redirect()
            ->route('business-units.index')
            ->with('success', 'Unit bisnis berhasil ditambahkan.');
    }

    public function update(Request $request, BusinessUnit $businessUnit): RedirectResponse
    {
        abort_unless($businessUnit->user_id === $request->user()->id, 403);

        $data = $this->validatedData($request, $businessUnit);
        $data['slug'] = SlugHelper::unique($data['name'], BusinessUnit::class, $businessUnit->id);
        $data['is_active'] = $request->boolean('is_active');

        $businessUnit->update($data);

        return redirect()
            ->route('business-units.index')
            ->with('success', 'Unit bisnis berhasil diperbarui.');
    }

    public function destroy(BusinessUnit $businessUnit): RedirectResponse
    {
        abort_unless($businessUnit->user_id === request()->user()->id, 403);

        if ($businessUnit->transactions()->exists()) {
            return redirect()
                ->route('business-units.index')
                ->with('error', 'Unit bisnis tidak bisa dihapus karena sudah dipakai transaksi.');
        }

        $businessUnit->delete();

        return redirect()
            ->route('business-units.index')
            ->with('success', 'Unit bisnis berhasil dihapus.');
    }

    private function validatedData(Request $request, ?BusinessUnit $businessUnit = null): array
    {
        return $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('business_units', 'name')
                    ->where(fn ($query) => $query->where('user_id', $request->user()->id))
                    ->ignore($businessUnit),
            ],
            'code' => ['nullable', 'string', 'max:20'],
            'description' => ['nullable', 'string'],
        ]);
    }
}
