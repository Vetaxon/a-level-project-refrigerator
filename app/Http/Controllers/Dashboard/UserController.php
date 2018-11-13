<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Requests\UserStoreRequest;
use App\Repositories\RecipeRepository;
use App\Repositories\UserRepository;
use App\Services\Contracts\SearchRecipesContract;
use App\Services\UserServices;
use App\User;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    protected $user;

    public function __construct(UserRepository $user)
    {
        $this->user = $user;
    }

    /**Show all users with details
     * @return mixed
     */
    public function index()
    {
        return view('dashboard.users.index')
            ->withUsers($this->user->getAllUsersWithCounts()->paginate(20));
    }

    /**Show all ingredients for user
     * @param User $user
     * @return mixed
     */
    public function showIngredientsByUser(User $user)
    {
        return view('dashboard.users.ingredients')
            ->withIngredients($user->ingredients()->get())
            ->withUser($user);
    }

    /**Show all user's ingredients in refrigerator
     * @param User $user
     * @return mixed
     */
    public function showRefrigeratorsByUser(User $user)
    {
        return view('dashboard.users.refrigerator')
            ->withUser($user)
            ->withRefrigerator($user->refrigeratorIngredients()->get());

    }

    /**Show all user's recipes
     * @param User $user
     * @param RecipeRepository $recipeRepository
     * @return mixed
     */
    public function showRecipesByUser(User $user, RecipeRepository $recipeRepository)
    {
        return view('dashboard.users.recipes')
            ->withUser($user)
            ->withRecipes($recipeRepository->getRecipeWithIngredientsByUser($user->id)->get());
    }

    /**Show form to create a new user
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function storeUserForm()
    {
        return view('dashboard.users.new');
    }

    /**Store a new user
     * @param UserStoreRequest $request
     * @param UserServices $userServices
     * @return mixed
     */
    public function store(UserStoreRequest $request, UserServices $userServices)
    {
        $user = $userServices->createUserFromDashboard($request);

        return back()->withStatus("Created a new user $user->name");
    }

    /**Show form to edit user's name
     * @param User $user
     * @return mixed
     */
    public function editName(User $user)
    {
        return view('dashboard.users.editName')->withUser($user);
    }

    /**Show form to edit
     * @param User $user
     * @return mixed
     */
    public function editEmail(User $user)
    {
        return view('dashboard.users.editEmail')->withUser($user);
    }

    public function update(UserStoreRequest $request, User $user)
    {
        if ($request->has('name')) {
            $user->update(['name' => $request->name]);
            return back()
                ->withStatus("User name has been updated to $user->name")
                ->withUser($user);
        }

        if ($request->has('email')) {
            $user->update(['email' => $request->email]);
            return back()
                ->withStatus("User email has been updated to $user->email")
                ->withUser($user);
        }
    }


    /**Delete the specified user
     * @param User $user
     * @return mixed
     * @throws \Exception
     */
    public function deleteUser(User $user)
    {
        $user->delete();
        return back()->withStatus("User $user->name has been deleted");
    }


    public function recipesForUserByIngredients(User $user, SearchRecipesContract $searchRecipes)
    {
        return view('dashboard.recipes.index')
            ->withRecipes($searchRecipes->searchRecipeForUser($user))
            ->withUser($user)
            ->withPaginate(false);
    }

}
