<?php

use App\Recipe;
use App\Ingredient;
use App\RecipeIngredient;
use Illuminate\Database\Seeder;

class AllRecipesDataFromJSONSeeder extends Seeder
{
    const FILENAME = 'database/data/data.json';
    const NEW_LINE = '<br>';
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

            $recipeInstruction = '';
            $stepNumber = 1;
            $id = $recipe->id + 1;
            echo "$id\t$recipe->name" . PHP_EOL;
            foreach ($recipe->recipeInstructions as $recipeInstructionStep) {
                $recipeInstruction .= $stepNumber++ . ". $recipeInstructionStep" . self::NEW_LINE;
            }
            Recipe::create([
                'id' => $id,
                'name' => $recipe->name,
                'picture' => $recipe->image,
                'text' => $recipeInstruction,
            ]);

            foreach ($recipe->recipeIngredient as $recipeIngredient) {
                $ingredient_id = $this->seedUniqIngredientId($recipeIngredient->name);
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
        $index = md5($name);
        if(!isset($this->ingredientsList[$index])) {
            $this->ingredientsList[$index] = $this->ingredient_id++;
            Ingredient::create([
                'id' => $this->ingredientsList[$index],
                'name' => $name,
            ]);
        }
        return $this->ingredientsList[$index];
    }
}