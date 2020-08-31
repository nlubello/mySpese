<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;
use Carbon\Carbon;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\ExpenseRequest as StoreRequest;
use App\Http\Requests\ExpenseRequest as UpdateRequest;

class ExpenseCrudController extends CrudController
{
  use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
  use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
  use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
  use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
  use \Backpack\CRUD\app\Http\Controllers\Operations\FetchOperation;

  public function setup()
    {

        /*
        |--------------------------------------------------------------------------
        | BASIC CRUD INFORMATION
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\Expense');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/expense');
        $this->crud->setEntityNameStrings('Movimento', 'Movimentazioni');

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
          [   // DateTime
            'name' => 'expensed_at',
            'label' => 'Data del movimento',
            'type' => 'datetime_picker',
            // optional:
            'datetime_picker_options' => [
                'format' => 'DD/MM/YYYY HH:mm',
                'language' => 'it'
            ],
            'allows_null' => false,
            'default' => Carbon::now()->toDateTimeString(),
          ],
          [   // Textarea
            'name' => 'description',
            'label' => 'Descrizione estesa',
            'type' => 'textarea'
          ],
          [  // relationship
            'label' => "Ricorrenza di riferimento",
            'type' => 'relationship',
            'ajax' => false,
            'name' => 'periodic', // the method on your model that defines the relationship
            //'entity' => 'periodic', // the method that defines the relationship in your Model
            //'attribute' => 'name', // foreign key attribute that is shown to user
            //'model' => "App\Models\Periodic", // foreign key model
            'inline_create' => true,
          ],
          [
            'label' => "Prova d'acquisto",
            'name' => "image",
            'type' => 'image',
            'crop' => true, // set to true to allow cropping, false to disable
            //'aspect_ratio' => 1, // ommit or set to 0 to allow any aspect ratio
            // 'disk'      => 's3_bucket', // in case you need to show images from a different disk
            //'prefix'    => 'uploads/expenses/' // in case your db value is only the file name (no path), you can use this to prepend your path to the image src (in HTML), before it's shown to the user;
        ]
        ];
        $this->crud->addFields($array_of_arrays, 'update/create/both');
        // $this->crud->removeField('name', 'update/create/both');
        // $this->crud->removeFields($array_of_names, 'update/create/both');

        
        
    }

    protected function setupListOperation()
    {
      // ------ CRUD COLUMNS
      // $this->crud->addColumn(); // add a single column, at the end of the stack
      $array_of_arrays = [
        [
            'name' => 'name', // The db column name
            'label' => "Nome spesa", // Table column heading
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
            'name' => "expensed_at", // The db column name
            'label' => "Data", // Table column heading
            'type' => "datetime"
        ],
        [
            // n-n relationship (with pivot table)
            'label' => "Categorie", // Table column heading
            'type' => "select_multiple",
            'name' => 'categories', // the method that defines the relationship in your Model
            'entity' => 'categories', // the method that defines the relationship in your Model
            'attribute' => "name", // foreign key attribute that is shown to user
            'model' => "App\Models\Category", // foreign key model
        ],
        [
            // run a function on the CRUD model and show its return value
            'name' => "type",
            'label' => "Tipo", // Table column heading
            'type' => "model_function",
            'function_name' => 'htmlType', // the method in your Model
        ],
      ];
      $this->crud->addColumns($array_of_arrays); // add multiple columns, at the end of the stack
      
      $this->crud->addFilter([ // daterange filter
        'type' => 'date_range',
        'name' => 'from_to',
        'label'=> 'Intervallo di date'
      ],
      false,
      function($value) { // if the filter is active, apply these constraints
        $dates = json_decode($value);
        $this->crud->addClause('where', 'expensed_at', '>=', $dates->from);
        $this->crud->addClause('where', 'expensed_at', '<=', $dates->to);
      });
      $this->crud->addFilter([ // select2_ajax filter
          'name' => 'category_id',
          'type' => 'select2_ajax',
          'label'=> 'Categoria',
          'placeholder' => 'Seleziona una categoria'
        ],
        url('admin/category/ajax-category-options'), // the ajax route
        function($value) { // if the filter is active
          $this->crud->query = $this->crud->query->whereHas('categories', function ($query) use ($value) {
            $query->where('category_id', $value);
          });
        });
    }

    public function fetchCategories()
    {
        return $this->fetch(\App\Models\Category::class);
    }

    public function fetchPeriodic()
    {
        return $this->fetch(\App\Models\Periodic::class);
    }

}
