<?php declare(strict_types=1);

namespace GraphQL\Collections\Type;

use Exception;
use GraphQL\Collections\AppContext;
use GraphQL\Collections\Data\Collection;
use GraphQL\Collections\Data\Event;
use GraphQL\Collections\Data\Format;
use GraphQL\Collections\Data\Distribution;
use GraphQL\Collections\Data\DistributionElement;
use GraphQL\Collections\Data\Sale;
use GraphQL\Collections\Data\DataSource;
use GraphQL\Collections\Types;
use GraphQL\Type\Definition\ListOfType;
use GraphQL\Type\Definition\NonNull;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;

class QueryType extends ObjectType
{
    public function __construct()
    {
        parent::__construct([
            'name' => 'Query',
            'description' => 'Standard operation for Rarespot GraphQL API',
            'fields' => [
                'collection' => [
                    'type' => Types::collection(),
                    'description' => 'Returns collection by internal RS id ',
                    'args' => [
                        'id' => new NonNull(Types::id()),
                    ],
                ],
                'collections' => [
                    'type' => new ListOfType(Types::collection()),
                    'description' => 'Returns list of collections',
                    'args' => [
                        'limit' => [
                            'type' => Types::int(),
                            'description' => 'Number of collections to be returned',
                            'defaultValue' => 100,
                        ],
                        'period' => [
                            'type' => Types::string(),
                            'description' => 'Unit of time to be analyzed, example 7d for 7 days',
                            'defaultValue' => '7d',
                        ],
                    ],
                ],
                'event' => [
                    'type' => Types::event(),
                    'description' => 'Returns event by id',
                    'args' => [
                        'id' => new NonNull(Types::id()),
                    ],
                ],
                'events' => [
                    'type' => new ListOfType(Types::event()),
                    'description' => 'Returns list of events',
                    'args' => [
                        'limit' => [
                            'type' => Types::int(),
                            'description' => 'Number of events to be returned',
                            'defaultValue' => 10,
                        ],
                    ],
                ],
                'format' => [
                    'type' => Types::format(),
                    'description' => 'Returns formatting rules',
                    'args' => [
                        'lang' => new NonNull(Types::string()),
                    ],
                ],
                'distribution' => [
                    'type' => Types::distribution(),
                    'description' => 'Returns distribution of elements',
                    'args' => [
                        'id' => Types::string(),
                    ],
                ],
                'distributionElement' => [
                    'type' => Types::distributionElement(),
                    'description' => 'distribution elements',
                    'args' => [],
                ],       
             ],
            'resolveField' => fn ($rootValue, array $args, $context, ResolveInfo $info) => $this->{$info->fieldName}($rootValue, $args, $context, $info),
        ]);
    }
    
    
    /**
     * @param null $rootValue
     * @param array{id: string} $args
     */
    public function collection($rootValue, array $args): ?Collection
    {
        return DataSource::findCollection((int) $args['id']);
    }

    /**
     * @param null                              $rootValue
     * @param array{limit: int} $args
     *
     * @return array<int, Collection>
     */
    public function collections($rootValue, array $args): array
    {
        return DataSource::findCollections(
            $args['limit']
        );
    }

     /**
     * @param null $rootValue
     * @param array{id: string} $args
     */
    public function event($rootValue, array $args): ?Event
    {
        return DataSource::findEvent((int) $args['id']);
    }

    /**
     * @param null                              $rootValue
     * @param array{limit: int} $args
     *
     * @return array<int, Collection>
     */
    public function events($rootValue, array $args): array
    {
        return DataSource::findEvents(
            $args['limit']
        );
    }

    /**
     * @param null $rootValue
     * @param array{lang: string} $args
     */
    public function format($rootValue, array $args): ?Format
    {
        return DataSource::findFormat( $args['lang']);
    }

    /**
     * @param null $rootValue
     * @param array{id: string} $args
     */
    public function distribution($rootValue, array $args): ?Distribution
    {
        return DataSource::findDistribution( $args['id']);
    }


}