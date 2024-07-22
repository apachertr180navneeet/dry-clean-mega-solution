<?php

namespace App\Http\Controllers\backends;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
// use DataTables;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;


class RoleController extends Controller
{


    // ==========================for role code start===================
    public function roleList(Request $request)
    {

        if ($request->ajax()) {
            $data = Role::select('*');
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $edit = route('role.edit', ['id' => base64_encode($row->id)]);
                    $delete = route('role.destroy', ['id' => $row->id]);
                    $btn = '<a href="' . $edit . '" class="edit btn btn-outline-success btn-sm"  title="Edit"><i class="fa fa-edit"></i></a>';
                    $deletebtn =  "<a href='" . $delete . "' id='remove-page'  class='btn btn-outline-danger btn-sm remove-page-$row->id'  title='Delete' onClick='removePage($row->id)'><i class='fa fa-trash'></i></a>";
                    return $btn . '&nbsp;' . $deletebtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('backend.roles.list');
    }

    public function roleAdd()
    {
        $arrData = array();
        $arrData['blogcategorydata'] = Permission::where('type_id', 1)->get();
        $arrData['blogdata'] = Permission::where('type_id', 2)->get();
        $arrData['curruntopeningdata'] = Permission::where('type_id', 3)->get();
        $arrData['userdata'] = Permission::where('type_id', 4)->get();
        $arrData['roledata'] = Permission::where('type_id', 5)->get();
        return view('backend.roles.add', $arrData);
    }

    public function roleStore(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles,name',
            'permissions' => 'required',
        ]);
        $role = Role::create(['name' => $request->name]);
        $role->syncPermissions($request->permissions);
        return redirect()->route('role.list')->with('success', 'Role Added Successfully');
    }

    // public function roleStore(Request $request)
    // {
    //     $request->validate([
    //         'name' => 'required|unique:roles,name',
    //         'permission' => 'required',
    //     ]);
    //     $role = DB::table('roles')->insertGetId(['name' => $request->name, 'guard_name' => 'web']);
    //     $this->permissionSync($role, $request->permission);
    //     return redirect()->route('role.list')->with('success', 'Role Added Successfully');
    // }

    // public function permissionSync($role_id, $permission)
    // {
    //     if (!empty($permission)) {
    //         foreach ($permission as $res) {
    //             $data = DB::table('role_has_permissions')->where('permission_id', $res)->where('role_id', $role_id)->first();
    //             if (empty($data)) {
    //                 DB::table('role_has_permissions')->insert(['permission_id' => $res, 'role_id' => $role_id]);
    //             }
    //         }
    //     }
    // }



    public function roleEdit(Request $request, $id)
    {
        $id = base64_decode($id);
        $arrData = array();
        $role = Role::find($id);
        $arrData['role'] = $role;
        $arrData['blogcategorydata'] = Permission::where('type_id', 1)->get();
        $arrData['blogdata'] = Permission::where('type_id', 2)->get();
        $arrData['curruntopeningdata'] = Permission::where('type_id', 3)->get();
        $arrData['userdata'] = Permission::where('type_id', 4)->get();
        $arrData['roledata'] = Permission::where('type_id', 5)->get();
        $arrData['allpermissions'] = DB::table('role_has_permissions')->where('role_has_permissions.role_id', $id)
            ->pluck('role_has_permissions.permission_id')->all();
        // dd($arrData);
        return view('backend.roles.add', $arrData);
    }

    public function roleupdate(Request $request, $id)
    {
        $role = Role::find($id);
        $role->name = $request->name;
        $role->save();
        $role->syncPermissions($request->permissions);
        return redirect()->route('role.list')->with('success', 'Role Updated Successfully');
    }


    public function roledelete($id)
    {
        $role = Role::where('id', $id)->first();
        if ($role != null) {
            $role->delete();
            return redirect()->route('role.list')->with(['success' => 'Role Delete Successfully !!']);
        }
        return redirect()->route('role.list')->with('error', 'Something went wrongs');
    }

    // ==========================for role code ens===================


    // ==========================for permission code start===================

    public function permissionlist()
    {
        $arrData['list'] = Permission::get();
        return view('backend.permission.list', $arrData);
    }

    public function permission()
    {
        return view('backend.permission.add');
    }

    public function permissionPost(Request $request)
    {
        $permissionname = str_replace(' ', '-', strtolower($request->permission));
        $getpermission = Permission::where('name', $permissionname)->first();
        if (empty($getpermission)) {
            $permission = new Permission;
            $permission->name = $permissionname;
            $permission->type_id  = $request->type_id;
            $permission->save();
            return redirect()->route('permission.add')->with('success', 'Permission Added Successfully');
        }
        return redirect()->route('permission.add')->with('error', 'Permission Added Unsuccessfully  Or Already Added');
    }


    // ==========================for permission code end===================
}
