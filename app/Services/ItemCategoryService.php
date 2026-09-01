<?php

namespace App\Services;


use Exception;
use Illuminate\Support\Str;
use App\Models\ItemCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\PaginateRequest;
use App\Libraries\QueryExceptionLibrary;
use App\Http\Requests\ItemCategoryRequest;
use App\Http\Requests\TranslationRequest;

class ItemCategoryService
{
    protected $itemCateFilter = [
        'name',
        'slug',
        'description',
        'status'
    ];

    protected $exceptFilter = [
        'excepts'
    ];

    /**
     * @throws Exception
     */
    public function list(PaginateRequest $request)
    {
        try {
            $requests    = $request->all();
            $method      = $request->get('paginate', 0) == 1 ? 'paginate' : 'get';
            $methodValue = $request->get('paginate', 0) == 1 ? $request->get('per_page', 10) : '*';
            $orderColumn = $request->get('order_column') ?? 'id';
            $orderType   = $request->get('order_type') ?? 'desc';

            return ItemCategory::with('media', 'translations')->where(function ($query) use ($requests) {
                foreach ($requests as $key => $request) {
                    if (in_array($key, $this->itemCateFilter)) {
                        if ($key == "status") {
                            $query->where($key, $request);
                        } else {
                            $query->where($key, 'like', '%' . $request . '%');
                        }
                    }

                    if (in_array($key, $this->exceptFilter)) {
                        $explodes = explode('|', $request);
                        if (is_array($explodes)) {
                            foreach ($explodes as $explode) {
                                $query->where('id', '!=', $explode);
                            }
                        }
                    }
                }
            })->orderBy($orderColumn, $orderType)->$method(
                $methodValue
            );
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception(QueryExceptionLibrary::message($exception), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function store(ItemCategoryRequest $request)
    {
        try {
            $itemCategory = ItemCategory::create($request->validated() + ['slug' => Str::slug($request->name)]);
            if ($request->image) {
                $itemCategory->addMediaFromRequest('image')->toMediaCollection('item-category');
            }
            return $itemCategory;
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception(QueryExceptionLibrary::message($exception), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function update(ItemCategoryRequest $request, ItemCategory $itemCategory): ItemCategory
    {
        try {
            $itemCategory->update($request->validated() + ['slug' => Str::slug($request->name)]);
            if ($request->image) {
                $itemCategory->clearMediaCollection('item-category');
                $itemCategory->addMediaFromRequest('image')->toMediaCollection('item-category');
            }
            return $itemCategory;
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception(QueryExceptionLibrary::message($exception), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function destroy(ItemCategory $itemCategory)
    {
        try {
            $hasActiveItems = \App\Models\Item::where('item_category_id', $itemCategory->id)->whereNull('deleted_at')->exists();
            if ($hasActiveItems) {
                throw new Exception("Cannot delete category because active items are assigned to it.", 422);
            }
            $items = \App\Models\Item::where('item_category_id', $itemCategory->id)->withTrashed()->get();
            foreach ($items as $item) {
                $item->variations()->forceDelete();
                $item->extras()->forceDelete();
                $item->addons()->forceDelete();
                $item->forceDelete();
            }
            $itemCategory->delete();
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception(QueryExceptionLibrary::message($exception), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function show(ItemCategory $itemCategory)
    {
        try {
            return $itemCategory->load('items', 'translations');
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception(QueryExceptionLibrary::message($exception), 422);
        }
    }

    public function saveTranslations(TranslationRequest $request, ItemCategory $itemCategory): \Illuminate\Http\Response|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            foreach ($request->input('translations', []) as $locale => $fields) {
                foreach ($fields as $key => $value) {
                    $itemCategory->translations()->updateOrCreate(
                        ['locale' => $locale, 'key' => $key],
                        ['value' => $value]
                    );
                }
            }
            return response(['message' => trans('all.message.translations_saved_successfully')], 200);
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception(QueryExceptionLibrary::message($exception), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function sortCategory(Request $request)
    {
        try {
            DB::transaction(function () use ($request) {
                foreach ($request->category_id as $index => $id) {
                    ItemCategory::where('id', $id)->update(['sort' => $index + 1]);
                }
            });
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            DB::rollBack();
            throw new Exception(QueryExceptionLibrary::message($exception), 422);
        }
    }
}
