<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use Illuminate\Http\Request;

class categoriesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        return view('categories.index', [
            'categories'  => Categories::all(),
        ]);
    }

    public function get_all()
    {
        return response()->json(Categories::all());
    }

    public function all(Request $request)
    {
        return datatables()::of(Categories::latest())
            ->addColumn('action', function ($row) {
                $actionBtn = '<a onclick="editCat(' . $row['id'] . ')" class="btn btn-success btn-sm text-white"><i class="fa fa-edit"></i></a> <a onclick="deleteCat(' . $row['id'] . ')" class="btn btn-danger btn-sm text-white"><i class="fa fa-trash"></i></a>';
                return $actionBtn;
            })
            ->filter(function ($query) use ($request) {
                if (!empty($request->filter_active)) {
                    $query->where('active', $request->filter_active);
                }
                if (!empty($request->filter_search)) {
                    $query->where('name', 'LIKE', '%' . $request->filter_search . '%');
                }
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function save(Request $request)
    {
        $data = [
            'user_id'   => auth()->user()->id,
            'name'      => $request->categories,
        ];

        if (!empty($request->id)) {
            Categories::find($request->id)->update($data);
            return response()->json('update');
        } else {
            Categories::insert($data);
            return response()->json('save');
        }
    }

    public function edit($id)
    {
        return response()->json(Categories::find($id));
    }

    public function delete($id)
    {
        Categories::find($id)->delete();
        return response()->json('success');
    }
}
