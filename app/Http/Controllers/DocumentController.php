<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\Document\StoreRequest;
use App\Http\Requests\Document\UpdateRequest;
use App\Services\DocumentService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;


class DocumentController extends Controller implements HasMiddleware
{
    public function __construct(private readonly DocumentService $service)
    {

    }

    public function index()
    {
        return ResponseHelper::response($this->service->findAll());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {

        return ResponseHelper::response($this->service->save($request->all() + ['user_id' => auth()->id()]));

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return ResponseHelper::response($this->service->findById($id));

    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, string $id)
    {
        return ResponseHelper::response($this->service->update([
            ...$request->all(),
            'items' => $request->input('items'),
            'user_id' => auth()->id()
        ], $id));

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id)
    {
        return ResponseHelper::response($this->service->delete($id));
    }


    public static function middleware(): array
    {
        return [
            new Middleware(['auth:sanctum', 'verified']),
        ];
    }
}
