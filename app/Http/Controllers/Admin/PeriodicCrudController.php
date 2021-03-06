<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\PeriodicRequest as StoreRequest;
use App\Http\Requests\PeriodicRequest as UpdateRequest;
use Illuminate\Http\Request;

class PeriodicCrudController extends CrudController
{
  use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
  use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
  use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
  use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
  use \Backpack\CRUD\app\Http\Controllers\Operations\InlineCreateOperation;
  use \Backpack\CRUD\app\Http\Controllers\Operations\FetchOperation;

  public function setup()
    {

        /*
        |--------------------------------------------------------------------------
        | BASIC CRUD INFORMATION
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Periodic');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/periodic');
        $this->crud->setEntityNameStrings('Ricorrenza', 'Ricorrenze');

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
            'label' => "Nome spesa",
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
            //'wrapper' => [
             //'class' => 'form-group col-md-12'
            //], // extra HTML attributes for the field wrapper - mostly for resizing fields
            //'readonly'=>'readonly',
          ],
          [   // Hidden
            'name' => 'user_id',
            'type' => 'hidden',
            'default' => backpack_auth()->user()->id,
          ],
          [
            'name'        => 'type', // the name of the db column
            'label'       => 'Tipo di Movimento', // the input label
            'type'        => 'radio',
            'options'     => [ // the key will be stored in the db, the value will be shown as label;
                                0 => "Spesa",
                                1 => "Entrata"
                            ],
            // optional
            'inline'      => true, // show the radios all on the same line?
          ],
          [
            'name'        => 'period', // the name of the db column
            'label'       => 'Ricorrenza', // the input label
            'type'        => 'radio',
            'options'     => [ // the key will be stored in the db, the value will be shown as label;
                                'm' => "Mensile",
                                'y' => "Annuale",
                                'g' => "Giornaliero",
                                '4m' => "4 mesi",
                            ],
            // optional
            'inline'      => true, // show the radios all on the same line?
          ],
          [   // Date
            'name' => 'starting_at',
            'label' => 'Data di inzio ricorrenza',
            'type' => 'date_picker',
            // optional:
            'date_picker_options' => [
                'todayBtn' => 'linked',
                'format' => 'dd/mm/yyyy',
                'language' => 'it'
            ],
            'allows_null' => false,
          ],
          [   // Date
            'name' => 'ending_at',
            'label' => 'Data di fine ricorrenza',
            'type' => 'date_picker',
            // optional:
            'date_picker_options' => [
                'format' => 'dd/mm/yyyy',
                'language' => 'it'
            ],
            'allows_null' => true,
          ],
          [   // Number
            'name' => 'amount',
            'label' => 'Importo',
            'type' => 'number',
            // optionals
            'attributes' => ["step" => "any"], // allow decimals
            'prefix' => "€",
            // 'suffix' => ".00",
          ],
          [ // relationship
            'label' => "Categorie",
            'type' => 'relationship',
            'name' => 'categories', // the method that defines the relationship in your Model
            //'entity' => 'categories', // the method that defines the relationship in your Model
            //'attribute' => 'name', // foreign key attribute that is shown to user
            //'model' => "App\Models\Category", // foreign key model
            'ajax' => false,
            //'pivot' => true, // on create&update, do you need to add/delete pivot table entries?
            // 'select_all' => true, // show Select All and Clear buttons?
            'inline_create' => [ 'entity' => 'category' ] // you need to specify the entity in singular
          ],
        ];
        $this->crud->addFields($array_of_arrays, 'update/create/both');
        // $this->crud->removeField('name', 'update/create/both');
        // $this->crud->removeFields($array_of_names, 'update/create/both');

        // ------ CRUD COLUMNS
        // $this->crud->addColumn(); // add a single column, at the end of the stack
        $array_of_arrays = [
          [
             'name' => 'name', // The db column name
             'label' => "Nome ricorrenza", // Table column heading
             // 'prefix' => "Name: ",
             // 'suffix' => "(user)",
             // 'limit' => 120, // character limit; default is 80;
          ],
          [
             'name' => 'amount', // The db column name
             'label' => "Importo", // Table column heading
             'type' => 'number',
             'prefix' => "€",
             // 'suffix' => " EUR",
             'decimals' => 2,
          ],
          [
             'name' => "starting_at", // The db column name
             'label' => "Data di inizio", // Table column heading
             'type' => "date"
          ],
          [
             'name' => "ending_at", // The db column name
             'label' => "Data di fine", // Table column heading
             'type' => "date"
          ],
          [ // relationship
            'label' => "Categorie",
            'type' => 'relationship',
            'name' => 'categories', // the method that defines the relationship in your Model
            //'entity' => 'categories', // the method that defines the relationship in your Model
            //'attribute' => 'name', // foreign key attribute that is shown to user
            //'model' => "App\Models\Category", // foreign key model
            'ajax' => false,
            //'pivot' => true, // on create&update, do you need to add/delete pivot table entries?
            // 'select_all' => true, // show Select All and Clear buttons?
            'inline_create' => [ 'entity' => 'category' ] // you need to specify the entity in singular
          ],
          [
             // run a function on the CRUD model and show its return value
             'name' => "type",
             'label' => "Tipo", // Table column heading
             'type' => "model_function",
             'function_name' => 'htmlType', // the method in your Model
             'limit' => 100, // Limit the number of characters shown
          ],
          [
             // run a function on the CRUD model and show its return value
             'name' => "period",
             'label' => "Periodicità", // Table column heading
             'type' => "model_function",
             'function_name' => 'htmlPeriod', // the method in your Model
             'limit' => 100, // Limit the number of characters shown
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

    public function register(Request $request, $id){
        $p = \App\Models\Periodic::findOrFail($id);
        $res = $p->createExpense();

        if($res){
          \Alert::success('Ricorrenza registrata con successo!')->flash();
        } else {
          \Alert::error('Impossibile registrare la ricorrenza')->flash();
        }
        
        return redirect(backpack_url('dashboard'));
    }

    public function fetchCategories()
    {
        return $this->fetch(\App\Models\Category::class);
    }
}
