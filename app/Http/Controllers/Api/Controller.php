<?php

namespace App\Http\Controllers\Api\User;

use Helper\Attachment;
use Illuminate\Support\Arr;
use App\Http\Controllers\Controller as LaravelController;
use App\Traits\SendResponse;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Http\FormRequest;

class Controller extends LaravelController
{
    use SendResponse;

    protected $request;
    protected $model;
    protected $resource;
    protected $media;
    protected $hasDelete;

    public function __construct(
        FormRequest $request,
        Model $model,
        $resource,
        $media = false,
        $hasDelete = false,
    ) {
        $this->request = $request;
        $this->model = $model;
        $this->resource = $resource;
        $this->media = $media;
        $this->hasDelete = $hasDelete;
    }


    public function relations(): array
    {
        return [];
    }

    public function index()
    {
        $record = $this->model->latest();

        if (!empty($this->relations())) {
            $record = $record->with(...$this->relations());
        }

        $record = $record->paginate($this->request->per_page ?? 10);
        return $this->sendResponse(
            $this->resource::collection($record),
            withmeta: true,
        );
    }

    public function store()
    {
        if ($this->media) {
            $validator = Validator::make(request()->all(), [
                'media' => 'required|array',
                'media.*' => 'mimes:jpg,png,jpeg,gif,svg,pdf|max:4000',
            ]);

            if ($validator->fails()) {
                return $this->ErrorValidate(
                    $validator->errors()->toArray(),
                );
            }
        }

        $record = $this->model->create($this->request->validated());

        // if (!empty(request()->media)) {
        //     $options = [
        //         "usage" => ((new \ReflectionClass($this->model))->getShortName()),
        //     ];

        //     foreach (request()->media as $image) {
        //         if ($image) {
        //             Attachment::addAttachment($image, $record, 'upload/' . ((new \ReflectionClass($this->model))->getShortName()), $options);
        //         }
        //     }
        // }

        // if (request()->has('img')) {
        //     $path = request()->file('img')->store('public');
        //     $record->img = str_replace('public/', 'storage/', $path);
        //     $record->save();
        // }

        $record->fresh();

        if (!empty($this->relations())) {
            $record = $record->load(...$this->relations());
        }


        $this->model = $record;
        return $this->sendResponse(
            new $this->resource($record),
            'تم الاضافة بنجاح',
            true,
            201
        );
    }

    public function show($id)
    {
        if (!empty($this->relations())) {
            $record = $this->model->with(...$this->relations())->findOrFail($id);
        } else {
            $record = $this->model->findOrFail($id);
        }

        return $this->sendResponse(new $this->resource($record));
    }

    public function update($id)
    {
        $model = $this->model->findOrFail($id);
        $model->update(Arr::except($this->request->validated(), 'img'));


        // if (!empty(request()->media)) {
        //     $options = [
        //         "usage" => ((new \ReflectionClass($model))->getShortName()),
        //     ];

        //     foreach (request()->media as $image) {
        //         if ($image) {
        //             Attachment::addAttachment($image, $model, 'upload/' . ((new \ReflectionClass($model))->getShortName()), $options);
        //         }
        //     }
        // }

        if (request()->has('img')) {
            @unlink(storage_path(str_replace('storage/', 'app/public/', $model->img)));
            $path = request()->file('img')->store('public');
            $model->img = str_replace('public/', 'storage/', $path);
            $model->save();
        }

        if (!empty($this->relations())) {
            $model = $model->load(...$this->relations());
        }
        $this->model = $model;

        return $this->sendResponse(new $this->resource($model), 'تم التعديل بنجاح');
    }

    public function destroy($id)
    {
        if ($this->hasDelete) {
            $model = $this->model->findOrFail($id);

            $model->delete();

            return $this->SuccessMessage('تم الحذف بنجاح');
        } else {
            return $this->ErrorMessage('غير مسموح بالحذف');
        }
    }

    // public function excelExport()
    // {
    //     $record = $this->model;
    //     if (in_array(FilterSort::class, class_uses_recursive($this->model))) {
    //         $record = $record->setFilters()->defaultSort('-created_at');
    //     } else {
    //         $record = $this->model->where($this->queryItem)->latest();
    //     }
    //     if (!empty($this->relations())) {
    //         $record = $record->with(...$this->relations());
    //     }
    //     $record = $record->take(3000)->remember($this->cache_time)->cacheTags($this->model->getTableName() . '-export')->get();
    //     $collection =  $this->resource::collection($record);
    //     return (new PublicExport($collection))->download($this->model->getTableName() . '.xlsx');
    // }
}
