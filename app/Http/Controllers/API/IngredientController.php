<?php

namespace App\Http\Controllers\API;

use App\Events\ClientEvent;
use App\Events\Messages\EventMessages;
use App\Ingredient;
use App\Http\Controllers\Controller;
use App\Http\Requests\IngredientRequest;

class IngredientController extends Controller
{

    protected $getParameters = ['id', 'name', 'user_id'];


    /**
     * Display all ingredients that belongs to user with default(null) ingredients.
     *
     * @return \Illuminate\Http\JsonResponse
     *
     *
     */
    public function index()
    {
        return response()->json(['ingredients' => Ingredient::getAllUsersIngredient(auth()->id())->get()]);
    }


    /**
     * Store a newly created ingredient by auth user.
     *
     * @param IngredientRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(IngredientRequest $request)
    {
        $newIngredient['name'] = mb_convert_case($request->name, MB_CASE_TITLE, "UTF-8");
        $newIngredient ['user_id'] = auth()->user()->id;

        $ingredient = Ingredient::create($newIngredient);

        $message = EventMessages::userAddIngredient($ingredient);
        activity()->withProperties($message)->log('messages');
        ClientEvent::dispatch($message);

        return response()->json([
            'message' => 'Ingredient ' . $ingredient->name . ' has been stored',
            'ingredient' => $ingredient->only($this->getParameters)
        ]);
    }


    /**
     * Display the specified ingredient that can belong to user or default user(null).
     *
     * @param  int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $ingredient = Ingredient::getUsersIngredientById($id, auth()->id());

        if ($ingredient) {
            return response()->json(['ingredient' => $ingredient->only($this->getParameters)]);
        }

        return response()->json(['error' => 'Ingredient was not found for current user'], 422);
    }


    /**
     * Update the specified ingredient that belongs to user in storage.
     *
     * @param IngredientRequest $request
     * @param  int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(IngredientRequest $request, $id)
    {
        if (!$ingredient = auth()->user()->ingredients()->find($id)) {
            return response()->json(['error' => 'Ingredient was not found for current user'], 422);
        }

        $updatedIngredient['name'] = mb_convert_case($request->name, MB_CASE_TITLE, "UTF-8");
        $ingredient->fill($updatedIngredient)->save();

        if ($updatedIngredient) {
            return $this->show($id);
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        if (!$ingredient = auth()->user()->ingredients()->find($id)) {
            return response()->json(['error' => 'Ingredient was not found for current user'], 422);
        }
        $ingredient->delete();

        return response()->json(['message' => 'Ingredient ' . $ingredient->name . ' has been deleted']);
    }

}
