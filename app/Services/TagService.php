<?php

namespace App\Services;

use App\Interfaces\TagInterface;

class TagService
{
    protected $tagRepository;

    public function __construct(TagInterface $tagRepository)
    {
        $this->tagRepository = $tagRepository;
    }

    public function listTags()
    {
        return $this->tagRepository->getAll();
    }

    public function getTag(int $id){
        return $this->tagRepository->findById($id);
    }

    public function createTag(array $data)
    {
        return $this->tagRepository->create($data);
    }

    public function updateTag(int $id, array $data)
    {
        return $this->tagRepository->update($id, $data);
    }

    public function deleteTag(int $id)
    {
        return $this->tagRepository->delete($id);
    }
}