<?php

namespace App\Http\Controllers;

use App\Models\Articles;
use App\Models\Categories;
use Illuminate\Http\Request;

class articlesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('articles.index', [
            'categories'  => Categories::all(),
        ]);
    }

    public function all(Request $request)
    {
        return datatables()::of(Articles::latest())
            ->addColumn('category', function ($row) {
                $category = Categories::find($row->category_id);
                return $category->name;
            })
            ->addColumn('action', function ($row) {
                $actionBtn = '<a onclick="editArt(' . $row['id'] . ')" class="btn btn-success btn-sm text-white"><i class="fa fa-edit"></i></a> <a onclick="deleteArt(' . $row['id'] . ')" class="btn btn-danger btn-sm text-white"><i class="fa fa-trash"></i></a>';
                return $actionBtn;
            })
            ->filter(function ($query) use ($request) {
                if (!empty($request->filter_active)) {
                    $query->where('active', $request->filter_active);
                }
                if (!empty($request->filter_search)) {
                    $query->where('title', 'LIKE', '%' . $request->filter_search . '%');
                    $query->orWhere('content', 'LIKE', '%' . $request->filter_search . '%');
                }
            })
            ->rawColumns(['category', 'action'])
            ->make(true);
    }

    public function save(Request $request)
    {
        $data = [
            'user_id'       => auth()->user()->id,
            'category_id'   => $request->category,
            'title'         => $request->title,
            'content'       => $request->content,
        ];

        if (!empty($request->id)) {
            Articles::find($request->id)->update($data);
            return response()->json('update');
        } else {
            Articles::insert($data);
            return response()->json('save');
        }
    }

    public function edit($id)
    {
        return response()->json(Articles::find($id));
    }

    public function delete($id)
    {
        Articles::find($id)->delete();
        return response()->json('success');
    }
}
