<?php

namespace App\Transformer;

use League\Fractal\TransformerAbstract;

use App\Bundle;

class BundleTransformer extends TransformerAbstract
{
    protected $defaultIncludes = ['books'];

    public function transform(Bundle $bundle)
    {
        return [
            'id' => $bundle->id,
            'title' => $bundle->title,
            'description' => $bundle->description,
            'created' => $bundle->created_at->toIso8601String(),
            'updated' => $bundle->updated_at->toIso8601String(),
        ];
    }

    public function includeBooks(Bundle $bundle)
    {
        return $this->collection($bundle->books, new BookTransformer());
    }
}
