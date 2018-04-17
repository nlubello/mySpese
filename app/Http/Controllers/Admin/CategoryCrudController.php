<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;
use Carbon\Carbon;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\CategoryRequest as StoreRequest;
use App\Http\Requests\CategoryRequest as UpdateRequest;

class CategoryCrudController extends CrudController
{
    public function setup()
    {

        /*
        |--------------------------------------------------------------------------
        | BASIC CRUD INFORMATION
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Category');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/category');
        $this->crud->setEntityNameStrings('Categoria', 'Categorie');

        /*
        |--------------------------------------------------------------------------
        | BASIC CRUD INFORMATION
        |--------------------------------------------------------------------------
        */

        //$this->crud->setFromDb();

        // ------ CRUD FIELDS
        // $this->crud->addField($options, 'update/create/both');
        $array_of_arrays = [
          [ // Text
            'name' => 'name',
            'label' => "Nome",
            'type' => 'text',

            // optional
            //'prefix' => '',
            //'suffix' => '',
            //'default'    => 'some value', // default value
            //'hint'       => 'Some hint text', // helpful text, show up after input
            //'attributes' => [
               //'placeholder' => 'Some text when empty',
               //'class' => 'form-control some-class'
             //], // extra HTML attributes and values your input might need
             //'wrapperAttributes' => [
               //'class' => 'form-group col-md-12'
             //], // extra HTML attributes for the field wrapper - mostly for resizing fields
             //'readonly'=>'readonly',
          ],
          [   // Hidden
            'name' => 'user_id',
            'type' => 'hidden',
            'default' => \Auth::user()->id,
          ],
          [
            'label' => "Icona",
            'name' => 'icon',
            'type' => 'icon_picker',
            'iconset' => 'fontawesome' // options: fontawesome, glyphicon, ionicon, weathericon, mapicon, octicon, typicon, elusiveicon, materialdesign
          ],
        ];
        $this->crud->addFields($array_of_arrays, 'update/create/both');
        // $this->crud->removeField('name', 'update/create/both');
        // $this->crud->removeFields($array_of_names, 'update/create/both');

        // ------ CRUD COLUMNS
        // $this->crud->addColumn(); // add a single column, at the end of the stack
        $array_of_arrays = [
          [
             // run a function on the CRUD model and show its return value
             'name' => "icon",
             'label' => "Icona", // Table column heading
             'type' => "model_function",
             'function_name' => 'htmlIcon', // the method in your Model
          ],
          [
             'name' => 'name', // The db column name
             'label' => "Nome categoria", // Table column heading
             // 'prefix' => "Name: ",
             // 'suffix' => "(user)",
             // 'limit' => 120, // character limit; default is 80;
          ],

        ];
        $this->crud->addColumns($array_of_arrays); // add multiple columns, at the end of the stack
        // $this->crud->removeColumn('column_name'); // remove a column from the stack
        // $this->crud->removeColumns(['column_name_1', 'column_name_2']); // remove an array of columns from the stack
        // $this->crud->setColumnDetails('column_name', ['attribute' => 'value']); // adjusts the properties of the passed in column (by name)
        // $this->crud->setColumnsDetails(['column_1', 'column_2'], ['attribute' => 'value']);

        // ------ CRUD BUTTONS
        // possible positions: 'beginning' and 'end'; defaults to 'beginning' for the 'line' stack, 'end' for the others;
        // $this->crud->addButton($stack, $name, $type, $content, $position); // add a button; possible types are: view, model_function
        // $this->crud->addButtonFromModelFunction($stack, $name, $model_function_name, $position); // add a button whose HTML is returned by a method in the CRUD model
        // $this->crud->addButtonFromView($stack, $name, $view, $position); // add a button whose HTML is in a view placed at resources\views\vendor\backpack\crud\buttons
        // $this->crud->removeButton($name);
        // $this->crud->removeButtonFromStack($name, $stack);
        // $this->crud->removeAllButtons();
        // $this->crud->removeAllButtonsFromStack('line');

        // ------ CRUD ACCESS
        // $this->crud->allowAccess(['list', 'create', 'update', 'reorder', 'delete']);
        // $this->crud->denyAccess(['list', 'create', 'update', 'reorder', 'delete']);

        // ------ CRUD REORDER
        // $this->crud->enableReorder('label_name', MAX_TREE_LEVEL);
        // NOTE: you also need to do allow access to the right users: $this->crud->allowAccess('reorder');

        // ------ CRUD DETAILS ROW
        // $this->crud->enableDetailsRow();
        // NOTE: you also need to do allow access to the right users: $this->crud->allowAccess('details_row');
        // NOTE: you also need to do overwrite the showDetailsRow($id) method in your EntityCrudController to show whatever you'd like in the details row OR overwrite the views/backpack/crud/details_row.blade.php

        // ------ REVISIONS
        // You also need to use \Venturecraft\Revisionable\RevisionableTrait;
        // Please check out: https://laravel-backpack.readme.io/docs/crud#revisions
        // $this->crud->allowAccess('revisions');

        // ------ AJAX TABLE VIEW
        // Please note the drawbacks of this though:
        // - 1-n and n-n columns are not searchable
        // - date and datetime columns won't be sortable anymore
        // $this->crud->enableAjaxTable();

        // ------ DATATABLE EXPORT BUTTONS
        // Show export to PDF, CSV, XLS and Print buttons on the table view.
        // Does not work well with AJAX datatables.
        // $this->crud->enableExportButtons();

        // ------ ADVANCED QUERIES
        // $this->crud->addClause('active');
        // $this->crud->addClause('type', 'car');
        // $this->crud->addClause('where', 'name', '==', 'car');
        // $this->crud->addClause('whereName', 'car');
        // $this->crud->addClause('whereHas', 'posts', function($query) {
        //     $query->activePosts();
        // });
        // $this->crud->addClause('withoutGlobalScopes');
        // $this->crud->addClause('withoutGlobalScope', VisibleScope::class);
        // $this->crud->with(); // eager load relationships
        // $this->crud->orderBy();
        // $this->crud->groupBy();
        // $this->crud->limit();
    }

    public function store(StoreRequest $request)
    {
        // your additional operations before save here
        $redirect_location = parent::storeCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }

    public function update(UpdateRequest $request)
    {
        // your additional operations before save here
        $redirect_location = parent::updateCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }

    public function show($id){
      $now = Carbon::now();

      $data = array();

      $data['crud'] = \App\Models\Category::find($id);
      \Debugbar::info($data['crud']);

      $data['statM'] = $data['crud']->montlyStat(12, $now, $id);
      $data['statY'] = $data['crud']->yearlyStat($now, $id);

      return view('showCat', $data);
    }
}
