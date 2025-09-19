<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\CategoryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class CategoryController extends Controller
{

    public function __construct(CategoryService $categoryService)
    {
        $this->CategoryService = $categoryService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!Gate::allows('Category-list')) {
            return abort(403);
        }
        return view('admin.category.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!Gate::allows('Category-list')) {
            return abort(403);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!Gate::allows('Category-list')) {
            return abort(403);
        }
    }


    public function update(Request $request, $id)
    {
        if (!Gate::allows('Category-list')) {
            return abort(403);
        }
        
        return $Category = $this->CategoryService->update($request, $id);
    }

    public function getdata()
    {
        if (!Gate::allows('Category-list')) {
            return abort(403);
        }
        $category = $this->CategoryService->getdata();
        return $category;
    }

}

