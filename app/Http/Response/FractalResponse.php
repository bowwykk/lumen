<?php
namespace App\Http\Response;

use League\Fractal\Manager;
use League\Fractal\Resource\Item;
use League\Fractal\Resource\Collection;
use League\Fractal\TransformerAbstract;
use League\Fractal\Resource\ResourceInterface;
use League\Fractal\Serializer\SerializerAbstract;
use Illuminate\Http\Request;

class FractalResponse
{
    private $manager;
    /**
     * @var SerializerAbstract
     */
    private $serializer;

    private $request;

    public function __construct(Manager $manager, SerializerAbstract $serializer, Request $request)
    {
        $this->manager = $manager;
        $this->serializer = $serializer;
        $this->manager->setSerializer($serializer);
        $this->request = $request;
    }

    public function item($data, TransformerAbstract $transformer, $resourceKey = null)
    {
        return $this->createDataArray(
            new Item($data, $transformer, $resourceKey)
        );
    }

    public function collection($data, TransformerAbstract $transformer, $resourceKey = null)
    {
        return $this->createDataArray(
            new Collection($data, $transformer, $resourceKey)
        );
    }

    private function createDataArray(ResourceInterface $resource)
    {
        return $this->manager->createData($resource)->toArray();
    }

    public function parseIncludes($includes = null)
    {
        if (empty($includes)) {
            $includes = $this->request->query('include', '');
        }
        
        $this->manager->parseIncludes($includes);
    }
}
