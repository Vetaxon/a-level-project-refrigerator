<?php

namespace App\Http\Controllers\API;

use App\Events\ClientEvent;
use App\Events\Messages\EventMessages;
use App\Http\Requests\StoreRefrigeratorRequest;
use App\Http\Requests\UpdateRefrigeratorRequest;
use App\Ingredient;
use App\Http\Controllers\Controller;
use App\Services\Contracts\MessageLogEvent;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;


class RefrigeratorController extends Controller
{
    /**
     * Display a listing of ingredients in user refrigerator.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(['ingredients' => auth()->user()->refrigeratorIngredients()->get()]);
    }

    /**
     * Store ingredient in user's refrigerator.
     *
     * @param StoreRefrigeratorRequest $request
     * @param MessageLogEvent $event
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRefrigeratorRequest $request, MessageLogEvent $event)
    {
        $request->merge(['user_id' => Auth::user()->id]);

        $user = Auth::user();

        $ingredient = Ingredient::find($request->ingredient_id);

        if ($user->owns($ingredient) || $ingredient->user_id === null) {

            $event->send(EventMessages::userAddIngredInRefrig($ingredient));

            $user->refrigeratorIngredients()->attach($ingredient, ['amount' => $request->amount]);

            return $this->show($ingredient);
        }
        return response()->json(['error' => 'The ingredient is not available for current user!'], 403);
    }

    /**
     * Display the specified ingredient of user's refrigerator.
     *
     * @param Ingredient $ingredient
     * @return \Illuminate\Http\Response
     */
    public function show(Ingredient $ingredient)
    {
        if ($ingredient = Auth::user()->refrigeratorIngredients()->find($ingredient->id)) {
            return response()->json($ingredient);
        }
        return response()->json(['error' => 'There is no such ingredient in refrigerator!'], 404);
    }


    /**
     * Update amount of the specified ingredient in user's refrigerator.
     *
     * @param UpdateRefrigeratorRequest $request
     * @param Ingredient $ingredient
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRefrigeratorRequest $request, Ingredient $ingredient)
    {
        if ((!$ingredient = Auth::user()->refrigeratorIngredients->find($ingredient))) {
            return response()->json(['error' => 'There is no such ingredient in refrigerator!'], 403);
        }

        $ingredient->pivot->amount = $request->amount;

        if ($ingredient->pivot->save()) {
            return $this->show($ingredient);
        }
        return response()->json(['error' => 'Update error!'], 500);
    }

    /**
     * Remove the specified ingredient from refrigerator.
     *
     * @param Ingredient $ingredient
     * @return \Illuminate\Http\Response
     */
    public function destroy(Ingredient $ingredient)
    {
        if ($ingredient = Auth::user()->refrigeratorIngredients->find($ingredient)
            && Auth::user()->refrigeratorIngredients()->detach($ingredient)) {
            return response()->json(['message' => 'Successfully deleted']);
        }
        return response()->json(['error' => 'There is no such ingredient in refrigerator!'], 404);
    }

}