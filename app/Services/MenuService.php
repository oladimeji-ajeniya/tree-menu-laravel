<?php

namespace App\Services;

use App\Models\Menu;

class MenuService
{
    public function getAllMenus()
    {
        return Menu::with('children')->get();
    }

    public function getTopLevelMenus()
    {
        return Menu::whereNull('parent_id')->get();
    }

    public function getCollasableMenu()
    {
        $menus = Menu::with('children')->whereNull('parent_id')->get();
        return $menus->map(function ($menu) {
            return $this->formatMenu($menu);
        });
    }

    public function formatMenu($menu)
    {
        return [
            'id' => $menu->id,
            'uuid' => $menu->uuid,
            'label' => $menu->name,
            'slug' => $menu->slug,
            'order' => $menu->order,
            'parent_id' => $menu->parent_id,
            'extra' => $menu->extra ?? true,
            'children' => $menu->children->map(function ($child) {
                return $this->formatMenu($child);
            })->toArray()
        ];
    }

    public function getMenuById($id)
    {
        $menu = Menu::with('children')->findOrFail($id);
        return $this->formatMenu($menu);
    }

    public function createMenu($data)
    {
        $menu = Menu::where('uuid', $data['uuid'])->first();

        if ($menu) {
            $menu->update($data);
            return $menu;
        } else {
            return Menu::create($data);
        }
    }

    public function updateMenu($id, $data)
    {
        $menu = Menu::findOrFail($id);
        $menu->update($data);
        return $menu;
    }

    public function deleteMenu($id)
    {
        $menu = Menu::findOrFail($id);
        $menu->delete();
        return $menu;
    }
}