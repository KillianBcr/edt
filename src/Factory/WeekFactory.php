<?php

namespace App\Factory;

use App\Entity\Week;
use Zenstruck\Foundry\ModelFactory;

final class WeekFactory extends ModelFactory
{
    public function __construct()
    {
        parent::__construct();
    }

    protected function getDefaults(): array
    {
        return [
            'numberHours' => self::faker()->numberBetween(1, 40),
            'weekNumber' => self::faker()->numberBetween(1, 52),
        ];
    }

    protected static function getClass(): string
    {
        return Week::class;
    }
}
