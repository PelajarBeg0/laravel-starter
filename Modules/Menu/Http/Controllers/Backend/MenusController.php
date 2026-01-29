<?php

namespace Modules\Menu\Http\Controllers\Backend;

use App\Authorizable;
use App\Http\Controllers\Backend\BackendBaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MenusController extends BackendBaseController
{
    use Authorizable;

    public function __construct()
    {
        // Page Title
        $this->module_title = 'Menus';

        // module name
        $this->module_name = 'menus';

        // directory path of the module
        $this->module_path = 'menu::backend';

        // module icon
        $this->module_icon = 'fa-solid fa-list';

        // module model name, path
        $this->module_model = "Modules\Menu\Models\Menu";
    }

    /**
     * Store a new resource in the database.
     *
     * @param  Request  $request  The request object containing the data to be stored.
     * @return \Illuminate\Http\RedirectResponse The response object that redirects to the index page of the module.
     *
     * @throws \Exception If there is an error during the creation of the resource.
     */
    public function store(Request $request)
    {
        $module_title = $this->module_title;
        $module_name = $this->module_name;
        $module_model = $this->module_model;
        $module_name_singular = Str::singular($module_name);

        $module_action = 'Store';

        $$module_name_singular = $module_model::create($request->all());

        // Clear menu cache when a new menu is created
        $module_model::clearMenuCache($$module_name_singular->location);

        flash("New '".Str::singular($module_title)."' Added")->success()->important();

        logUserAccess($module_title.' '.$module_action.' | Id: '.$$module_name_singular->id);

        return redirect("admin/{$module_name}");
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request  The request object.
     * @param  int  $id  The ID of the resource to update.
     * @return \Illuminate\Http\RedirectResponse The redirect response.
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException If the resource is not found.
     */
    public function update(Request $request, $id)
    {
        $module_title = $this->module_title;
        $module_name = $this->module_name;
        $module_model = $this->module_model;
        $module_name_singular = Str::singular($module_name);

        $module_action = 'Update';

        $$module_name_singular = $module_model::findOrFail($id);

        $$module_name_singular->update($request->all());

        // Clear menu cache when a menu is updated
        $module_model::clearMenuCache($$module_name_singular->location);

        flash(Str::singular($module_title)."' Updated Successfully")->success()->important();

        logUserAccess($module_title.' '.$module_action.' | Id: '.$$module_name_singular->id);

        return redirect()->route("backend.{$module_name}.show", $$module_name_singular->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\View\View
     */
    public function show($id)
    {
        $module_title = $this->module_title;
        $module_name = $this->module_name;
        $module_path = $this->module_path;
        $module_icon = $this->module_icon;
        $module_model = $this->module_model;
        $module_name_singular = Str::singular($module_name);

        $module_action = 'Show';

        // Eager load items and their children recursively to prevent lazy loading violations
        // We load up to 5 levels deep which should be sufficient for most menus
        $$module_name_singular = $module_model::with([
            'items.children.children.children.children',
        ])->findOrFail($id);

        logUserAccess($module_title.' '.$module_action.' | Id: '.$$module_name_singular->id);

        return view(
            "{$module_path}.{$module_name}.show",
            compact('module_title', 'module_name', 'module_path', 'module_icon', 'module_name_singular', 'module_action', "{$module_name_singular}")
        );
    }

    /**
     * Remove the specified resource from storage.
     * Prevents deletion if the menu has menu items.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $module_title = $this->module_title;
        $module_name = $this->module_name;
        $module_name_singular = Str::singular($module_name);
        $module_model = $this->module_model;

        $module_action = 'Destroy';

        $$module_name_singular = $module_model::findOrFail($id);

        // Check if menu has any menu items
        if ($$module_name_singular->allItems()->count() > 0) {
            $itemCount = $$module_name_singular->allItems()->count();

            flash("Cannot delete menu '".$$module_name_singular->name."'! This menu has {$itemCount} menu item(s). Please delete all menu items first.", 'warning');

            logUserAccess($module_title.' '.$module_action.' Failed | Id: '.$$module_name_singular->id.' | Reason: Has menu items');

            return redirect()->route("backend.{$module_name}.index");
        }

        // Store location before deletion for cache clearing
        $location = $$module_name_singular->location;

        // Proceed with deletion if no menu items exist
        $$module_name_singular->delete();

        // Clear menu cache for this location
        $module_model::clearMenuCache($location);

        flash(Str::singular($module_title).' Deleted Successfully!')->success()->important();

        logUserAccess($module_title.' '.$module_action.' | Id: '.$$module_name_singular->id);

        return redirect()->route("backend.{$module_name}.index");
    }
}
