<?php

namespace App\Factory;

use App\Entity\Assignment;
use App\Repository\AssignmentRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<Assignment>
 *
 * @method        Assignment|Proxy                     create(array|callable $attributes = [])
 * @method static Assignment|Proxy                     createOne(array $attributes = [])
 * @method static Assignment|Proxy                     find(object|array|mixed $criteria)
 * @method static Assignment|Proxy                     findOrCreate(array $attributes)
 * @method static Assignment|Proxy                     first(string $sortedField = 'id')
 * @method static Assignment|Proxy                     last(string $sortedField = 'id')
 * @method static Assignment|Proxy                     random(array $attributes = [])
 * @method static Assignment|Proxy                     randomOrCreate(array $attributes = [])
 * @method static AssignmentRepository|RepositoryProxy repository()
 * @method static Assignment[]|Proxy[]                 all()
 * @method static Assignment[]|Proxy[]                 createMany(int $number, array|callable $attributes = [])
 * @method static Assignment[]|Proxy[]                 createSequence(iterable|callable $sequence)
 * @method static Assignment[]|Proxy[]                 findBy(array $attributes)
 * @method static Assignment[]|Proxy[]                 randomRange(int $min, int $max, array $attributes = [])
 * @method static Assignment[]|Proxy[]                 randomSet(int $number, array $attributes = [])
 */
final class AssignmentFactory extends ModelFactory
{
    public function __construct()
    {
        parent::__construct();
    }

    protected function getDefaults(): array
    {
        return [
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(Assignment $assignment): void {})
        ;
    }

    protected static function getClass(): string
    {
        return Assignment::class;
    }
}
