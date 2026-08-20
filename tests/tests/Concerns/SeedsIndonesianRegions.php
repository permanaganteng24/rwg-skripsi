<?php

namespace Tests\Concerns;

use Laravolt\Indonesia\Models\City;
use Laravolt\Indonesia\Models\District;
use Laravolt\Indonesia\Models\Province;

/**
 * Seeds a minimal set of Indonesian region data (province/city/district)
 * so CheckoutPage's location dropdowns and Lombok-detection logic
 * can be exercised in tests without requiring the full `indonesia:seed`
 * dataset to be imported.
 */
trait SeedsIndonesianRegions
{
    /**
     * Seed a Lombok (Mataram) location and return its codes.
     *
     * @return array{province: string, city: string, district: string}
     */
    protected function seedLombokRegion(): array
    {
        Province::firstOrCreate(
            ['code' => '52'],
            ['name' => 'Nusa Tenggara Barat']
        );

        City::firstOrCreate(
            ['code' => '5271'],
            ['province_code' => '52', 'name' => 'Kota Mataram']
        );

        District::firstOrCreate(
            ['code' => '5271010'],
            ['city_code' => '5271', 'name' => 'Cakranegara']
        );

        return ['province' => '52', 'city' => '5271', 'district' => '5271010'];
    }

    /**
     * Seed a non-Lombok Indonesian location (Jakarta) and return its codes.
     *
     * @return array{province: string, city: string, district: string}
     */
    protected function seedNonLombokRegion(): array
    {
        Province::firstOrCreate(
            ['code' => '31'],
            ['name' => 'DKI Jakarta']
        );

        City::firstOrCreate(
            ['code' => '3171'],
            ['province_code' => '31', 'name' => 'Jakarta Pusat']
        );

        District::firstOrCreate(
            ['code' => '3171010'],
            ['city_code' => '3171', 'name' => 'Gambir']
        );

        return ['province' => '31', 'city' => '3171', 'district' => '3171010'];
    }
}
