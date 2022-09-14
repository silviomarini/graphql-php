<?php declare(strict_types=1);

namespace GraphQL\Collections\Type;

use Exception;

use GraphQL\Collections\Data\Collection;
use GraphQL\Collections\Types;
use GraphQL\Type\Definition\NonNull;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\ListOfType;
use function method_exists;
use function ucfirst;

class DistributionType
{

    public function __construct()
    {
        $queryType = new ObjectType([
            'name' => 'Distribution',
            'description' => 'Distribution over the top X elements of a specific asset',
            'fields' => static fn (): array => [
                'name' => [
                    'type' => Types::string(),
                ],
                'volume' => [
                    'type' => Types::float(),
                ],
                'elements' => [
                    'type' => new ListOfType(Types::distributionElement()),
                ]
            ],
            'interfaces' => [Types::node()],
            'resolveField' => function ($collection, $args, $context, ResolveInfo $info) {
                $fieldName = $info->fieldName;

                $method = 'resolve' . ucfirst($fieldName);
                if (method_exists($this, $method)) {
                    return $this->{$method}($collection, $args, $context, $info);
                }

                return $collection->{$fieldName};
            },
        ]);
            
//        parent::__construct([
//            'name' => 'Distribution',
//            'description' => 'Distribution over the top X elements of a specific asset',
//            'fields' => static fn (): array => [
//                'name' => [
//                    'type' => Types::string(),
//                ],
//                'volume' => [
//                    'type' => Types::float(),
//                ],
//                'elements' => [
//                    'type' => new ListOfType(Types::distributionElement()),
//                ]
//            ],
//            'interfaces' => [Types::node()],
//            'resolveField' => function ($collection, $args, $context, ResolveInfo $info) {
//                $fieldName = $info->fieldName;
//
//                $method = 'resolve' . ucfirst($fieldName);
//                if (method_exists($this, $method)) {
//                    return $this->{$method}($collection, $args, $context, $info);
//                }
//
//                return $collection->{$fieldName};
//            },
//        ]);

        
    }
}