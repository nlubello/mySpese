<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
      App\Models\Category::create(['name' => 'Casa', 'icon' => 'fa-home', 'type' => 0]);
      App\Models\Category::create(['name' => 'Auto', 'icon' => 'fa-car', 'type' => 0]);
      App\Models\Category::create(['name' => 'Ristoranti', 'icon' => 'fa-cutlery', 'type' => 0]);
      App\Models\Category::create(['name' => 'Bar', 'icon' => 'fa-glass', 'type' => 0]);
      App\Models\Category::create(['name' => 'Utenze', 'icon' => 'fa-plug', 'type' => 0]);
      App\Models\Category::create(['name' => 'Spesa', 'icon' => 'fa-shopping-cart', 'type' => 0]);
      App\Models\Category::create(['name' => 'Salute', 'icon' => 'fa-medkit', 'type' => 0]);
      App\Models\Category::create(['name' => 'Regali', 'icon' => 'fa-gift', 'type' => 0]);
      App\Models\Category::create(['name' => 'Relazioni', 'icon' => 'fa-heart-o', 'type' => 0]);
      App\Models\Category::create(['name' => 'Animali', 'icon' => 'fa-heart-o', 'type' => 0]);
      App\Models\Category::create(['name' => 'Altro', 'icon' => 'fa-star-o', 'type' => 0]);
      App\Models\Category::create(['name' => 'Stipendio', 'icon' => 'fa-money', 'type' => 1]);
      App\Models\Category::create(['name' => 'Resi', 'icon' => 'fa-building-o', 'type' => 1]);
      App\Models\Category::create(['name' => 'Fatture', 'icon' => 'fa-paper-plane', 'type' => 1]);

      \Backpack\Base\app\Models\BackpackUser::create(['name' => 'Admin', 'email'=> 'admin@admin.com', 'password' => bcrypt('123456')]);
    }
}
