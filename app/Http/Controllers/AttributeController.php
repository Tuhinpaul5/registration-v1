<?php

namespace App\Http\Controllers;

use App\Repositories\AttributeRepository;

class AttributeController extends Controller
{
    protected AttributeRepository $repo;

    public function __construct(AttributeRepository $repo)
    {
        $this->repo = $repo;
    }

    public function show(int $id)
    {
        $attribute = $this->repo->getAttribute($id);

        if (!$attribute) {
            return response_handler(false, 'Attribute not found', 404);
        }

        return response_handler(true, 'Attribute retrieved', 200, $attribute->toArray());
    }
}
