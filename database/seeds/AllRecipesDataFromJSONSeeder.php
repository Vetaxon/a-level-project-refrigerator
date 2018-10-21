<?php

use App\Recipe;
use App\Ingredient;
use App\RecipeIngredient;
use Illuminate\Database\Seeder;

class AllRecipesDataFromJSONSeeder extends Seeder
{
    const FILENAME = 'database/data/data.json';
    private $ingredient_id = 1;
    private $ingredientsList = [];

    /**
     * Run the database seeds from JSON file.
     *
     * @return void
     */
    public function run()
    {
        $jsonRaw = File::get(self::FILENAME);
        $itemListElement = json_decode($jsonRaw);
        $data = $itemListElement->itemListElement[0];

        foreach ($data as $recipe) {

            echo ($recipe->id) . "\t";
            echo ($recipe->name) . PHP_EOL;

            $recipeInstruction = '';
            $stepNumber = 1;
            $id = $recipe->id + 1;
            foreach ($recipe->recipeInstructions as $recipeInstructionStep) {
                $recipeInstruction .= $stepNumber++ . ". $recipeInstructionStep<br>";
            }
            Recipe::create([
                'id' => $id,
                'name' => $recipe->name,
                'picture' => $recipe->image,
                'text' => $recipeInstruction,
            ]);

            foreach ($recipe->recipeIngredient as $recipeIngredient) {
                $ingredient_id = $this->seedUniqIngredientId($recipeIngredient->name);
                echo "$id $ingredient_id $recipeIngredient->name \n";
                RecipeIngredient::create([
                    'recipe_id' => $id,
                    'ingredient_id' => $ingredient_id,
                    'amount' => isset($recipeIngredient->count) &&
                    isset($recipeIngredient->measure) ?
                        "$recipeIngredient->count $recipeIngredient->measure" :
                        ""
                ]);
            }
        }
    }

    private function seedUniqIngredientId($name){
        if(!isset($this->ingredientsList[md5($name)])) {
            $this->ingredientsList[md5($name)] = $this->ingredient_id++;
            Ingredient::create([
                'id' => $this->ingredientsList[md5($name)],
                'name' => $name,
            ]);
        }
        return $this->ingredientsList[md5($name)];
    }
}