<?php

namespace App\Http\Controllers\Web;

use App\Traits\Views\Path;
use App\Traits\Views\Variable;
use Core\Base\Services\SingletonAuthPermissions;
use Helper\Attachment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Core\Base\Traits\Model\FilterSort;

class Controller extends \App\Http\Controllers\Controller
{
    use Path, Variable;

    protected $request;
    protected $model;
    protected $queryItem;
    protected $hasDelete;
    protected $media;
    protected $cache;
    protected $cache_time;
    protected $permissions;

    /**
     * the view file namespace
     *
     * @var string
     */
    protected $namespace = 'core#base';

    /**
     * the directory that will contain the views files
     *
     * @var string
     */
    protected $directory;

    /**
     * the full path to the view directory
     *
     * @var string
     */
    protected $path;

    /**
     * init
     *
     * @codeCoverageIgnore
     * @return void
     */
    public function __construct(
        FormRequest $request,
        Model $model,
        $queryItem = [],
        $hasDelete = false,
        $media = false,
        $cache = false,
        $cache_time = 60 * 1,
        $permissions = false,
    ) {
        $this->request = $request;
        $this->model = $model;
        $this->queryItem = $queryItem;
        $this->hasDelete = $hasDelete;
        $this->media = $media;
        $this->cache = $cache;
        $this->cache_time = $cache_time;
        $this->permissions = $permissions;
        $this->setupView();
        view()->share('global', $this->globalVariables());
    }

    // public function can($route)
    // {
    //     $action = 'user.' . $route;
    //     $singleton_obj = SingletonAuthPermissions::getInstance();
    //     if (stripos($singleton_obj->getAllWebPermissions(), $action) !== false) {
    //         if (stripos($singleton_obj->getWebAuthPermissions(), $action) == false) {
    //             return false;
    //         }
    //     }
    //     return true;
    // }

    public function relations(): array
    {
        return [];
    }

    public function lists(): array
    {
        return [];
    }

    public function indexColumns(): array
    {
        return [];
    }

    public function processIndexColumns(): array
    {
        $index_columns = $this->indexColumns() + [
            'created_at' => [
                "grid_view" => true,
                "visible" => true,
                "validation" => "required",
                "type" => "string",
                'input-type' => 'dateTime'

            ],
            'updated_at' => [
                "grid_view" => true,
                "visible" => true,
                "validation" => "required",
                "type" => "string",
                'input-type' => 'dateTime'
            ],
        ];

        foreach ($index_columns as $column => $value) {
            if ($column == 'id') {
                $index_columns[$column]['grid_view']    = false;
                $index_columns[$column]['visible']      = false;
                $index_columns[$column]['input-type']      = 'text';
            } else {
                $index_columns[$column]['grid_view']    = $index_columns[$column]['grid_view'] ?? true;
                $index_columns[$column]['visible']      = false;
                $index_columns[$column]['input-type']      = $index_columns[$column]['input-type'] ??  'text';
            }

            $index_columns[$column]['validation']   = $index_columns[$column]['validation'] ?? 'required';
            $index_columns[$column]['type']         = $index_columns[$column]['type'] ?? 'string';
            $create_fields[$column]['input-type']    = $create_columns[$column]['input-type'] ?? 'text';
        }

        return $index_columns;
    }

    public function createFields(): array
    {
        return [];
    }

    public function processCreateFields(): array
    {
        $create_fields = $this->createFields();

        foreach ($create_fields as $field => $value) {
            $create_fields[$field]['validation']   = $create_fields[$field]['validation'] ?? 'required';
            $create_fields[$field]['type']         = $create_fields[$field]['type'] ?? 'string';
            // $create_fields[$field]['options']      = $create_fields[$field]['options'] ?? [];
            $create_fields[$field]['input-type']    = $create_fields[$field]['input-type'] ?? 'text';
        }

        return $create_fields;
    }

    public function indexActions(): array
    {
        return $this->model->MyColumns();
    }

    public function processIndexActions()
    {
        $index_actions = $this->indexActions();
        $index_actions['detail'] = $index_actions['detail'] ?? true;
        $index_actions['remove'] = $index_actions['remove'] ?? true;
        $index_actions['update'] = $index_actions['update'] ?? true;
        $index_actions['create'] = $index_actions['create'] ?? true;

        return $index_actions;
    }


    // public function filter()
    // {
    //     $filters  = [];
    //     foreach ($this->model->filterColumns() as $key => $value) {
    //         if (is_object($value)) {
    //             $filters[] = $value->getName();
    //         } else {
    //             $filters[] = $value;
    //         }
    //     }
    //     return $filters;
    // }

    public function bearerToken()
    {
        $header = request()->header('Authorization', '');
        if (Str::startsWith($header, 'Bearer ')) {
            return Str::substr($header, 7);
        }
    }


    public function index()
    {
        $record = $this->model;
        if (in_array(FilterSort::class, class_uses_recursive($this->model))) {
            $record = $record->setFilters()->defaultSort('-created_at');
        } else {
            $record = $this->model->where($this->queryItem)->latest();
        }

        if (!empty($this->relations())) {
            $record = $record->with(...$this->relations());
        }

        if ($this->cache) {
            $record = $record->remember($this->cache_time)->cacheTags($this->model->getTableName());
        }

        $permissions = $this->permissions;
        $columns = $this->processIndexColumns();
        $actions = $this->processIndexActions();
        // $filter = $this->filter();
        // $filter = array_intersect_key($columns, array_flip($this->filter()));
        $filter = [];
        $create_route = str_replace('index', 'create', Request::route()->getName());
        $edit_route = str_replace('index', 'edit', Request::route()->getName());
        $destroy_route = str_replace('index', 'destroy', Request::route()->getName());
        $lists = $this->lists();

        $record = $record->paginate($this->request->per_page ?? 10);

        return view($this->path . __FUNCTION__, compact('record', 'permissions', 'columns', 'actions', 'filter', 'create_route', 'edit_route', 'destroy_route', 'lists'));
    }


    public function create()
    {
        $fields = $this->processCreateFields();
        $store_route = str_replace('create', 'store', Request::route()->getName());
        $model = $this->model;
        return view($this->path . __FUNCTION__, compact('model', 'fields', 'store_route'));
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

        if ($this->cache) {
            $this->model->flushCache($this->model->getTableName());
            $this->model->flushCache($this->model->getTableName() . '-list');
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

        $index_route = str_replace('create', 'index', Request::route()->getName());
        return redirect()->route($index_route)->with('success', 'Record added successfully!');
    }


    public function show($uuid)
    {
        return view($this->path . __FUNCTION__);
    }


    public function edit($id)
    {
        $record = $this->model->findOrFail($id);
        $fields = $this->processCreateFields();
        $update_route = str_replace('edit', 'update', Request::route()->getName());
        return view($this->path . __FUNCTION__, compact('record', 'fields', 'update_route'));
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

        // if (request()->has('img')) {
        //     @unlink(storage_path(str_replace('storage/', 'app/public/', $model->img)));
        //     $path = request()->file('img')->store('public');
        //     $model->img = str_replace('public/', 'storage/', $path);
        //     $model->save();
        // }

        if ($this->cache) {
            $this->model->flushCache($this->model->getTableName());
            $this->model->flushCache($this->model->getTableName() . '-list');
        }

        if (!empty($this->relations())) {
            $model = $model->load(...$this->relations());
        }
        $this->model = $model;

        $index_route = str_replace('update', 'index', Request::route()->getName());
        return redirect()->route($index_route)->with('success', 'Record updated successfully!');
    }

    public function destroy($id)
    {
        if ($this->hasDelete) {
            $model = $this->model->findOrFail($id);

            foreach ($this->model->deleteRelations() as $key) {
                if ($model->$key()->count() > 0)
                    return response()->json([
                        'status'  => 0,
                        'message' => __('غير مسموح بالحذف لوجود بيانات مرتبطة'),
                        'id'      => $id
                    ]);
            }

            // if ($model->img) {
            //     @unlink(storage_path(str_replace('storage/', 'app/public/', $model->img)));
            // }

            // if (in_array(AttachmentAttribute::class, class_uses_recursive($this->model))) {
            //     Attachment::deleteAttachment($model, multiple: true);
            // }

            $model->delete();

            if ($this->cache) {
                $this->model->flushCache($this->model->getTableName());
                $this->model->flushCache($this->model->getTableName() . '-list');
            }

            return response()->json([
                'status'  => 1,
                'message' => __('تم الحذف بنجاح'),
                'id'      => $id
            ]);
        } else {
            return response()->json([
                'status'  => 0,
                'message' => __('غير مسموح بالحذف'),
                'id'      => $id
            ]);
        }
    }
}
