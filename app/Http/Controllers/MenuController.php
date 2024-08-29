<?php

namespace App\Http\Controllers;

use App\Services\MenuService;
use App\Http\Requests\StoreMenuRequest;
use App\Http\Requests\UpdateMenuRequest;
use Illuminate\Http\JsonResponse;

class MenuController extends Controller
{
    protected $menuService;

    /**
     * MenuController constructor.
     *
     * @param MenuService $menuService
     */
    public function __construct(MenuService $menuService)
    {
        $this->menuService = $menuService;
    }

    /**
     * Fetch all menus with their children.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
            $menus = $this->menuService->getAllMenus();
            return response()->json($menus);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch menus'], 500);
        }
    }

    /**
     * Get all top-level menus (where parent_id is null).
     *
     * @return JsonResponse
     */
    public function getTopLevelMenus(): JsonResponse
    {
        try {
            $menus = $this->menuService->getTopLevelMenus();
            return response()->json($menus);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch top-level menus'], 500);
        }
    }

    /**
     * Fetch top-level menus and format them for collapsible display.
     *
     * @return JsonResponse
     */
    public function getCollasableMenu(): JsonResponse
    {
        try {
            $treeData = $this->menuService->getCollasableMenu();
            return response()->json($treeData);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch collapsible menus'], 500);
        }
    }

    /**
     * Get a specific menu by ID, including its children.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show($id): JsonResponse
    {
        try {
            $menu = $this->menuService->getMenuById($id);
            return response()->json([$menu]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch menu'], 500);
        }
    }


    /**
     * Store a new menu or update an existing one.
     *
     * @param StoreMenuRequest $request
     * @return JsonResponse
     */
    public function store(StoreMenuRequest $request): JsonResponse
    {
        try {
            $validatedData = $request->validated();
            $menu = $this->menuService->createMenu($validatedData);
            return response()->json($menu, 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to process request'], 500);
        }
    }

    /**
     * Update an existing menu.
     *
     * @param UpdateMenuRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(UpdateMenuRequest $request, $id): JsonResponse
    {
        try {
            $validatedData = $request->validated();
            $menu = $this->menuService->updateMenu($id, $validatedData);
            return response()->json($menu);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to update menu'], 500);
        }
    }

    /**
     * Delete a menu by ID.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy($id): JsonResponse
    {
        try {
            $this->menuService->deleteMenu($id);
            return response()->json(['message' => 'Menu deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to delete menu'], 500);
        }
    }
}