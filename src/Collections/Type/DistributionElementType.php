<?php declare(strict_types=1);

namespace GraphQL\Collections\Type;

use Exception;

use GraphQL\Collections\Data\Collection;
use GraphQL\Collections\Types;
use GraphQL\Type\Definition\NonNull;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\ResolveInfo;
use function method_exists;
use function ucfirst;

class DistributionElementType extends ObjectType
{
    public function __construct()
    {
        parent::__construct([
            'name' => 'Distribution Element',
            'description' => 'Distribution element',
            'fields' => static fn (): array => [
                'id' => [
                    'type' => Types::int(),
                ],
                'name' => [
                    'type' => Types::string(),
                ],
                'value' => [
                    'type' => Types::float(),
                ],
                'format' => [
                    'type' => Types::string(),
                ],
                'value_percent' => [
                    'type' => Types::float(),
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
    }
}