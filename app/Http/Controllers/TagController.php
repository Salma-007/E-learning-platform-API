<?php

namespace App\Http\Controllers;

use App\Services\TagService;
use Illuminate\Http\Request;

class TagController extends Controller
{
    protected $tagService;

    public function __construct(TagService $tagService)
    {
        $this->tagService = $tagService;
    }

    public function index()
    {
        return response()->json($this->tagService->listTags());
    }

    public function show($id)
    {
        return response()->json($this->tagService->getTag($id));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        return response()->json($this->tagService->createTag($validated), 201);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
        ]);

        return response()->json($this->tagService->updateTag($id, $validated));
    }

    public function destroy($id)
    {
        return response()->json($this->tagService->deleteTag($id));
    }

}
