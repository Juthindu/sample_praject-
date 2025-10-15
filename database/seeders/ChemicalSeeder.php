<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Modules\Chemical\Entities\Chemical;
use Modules\Chemical\Entities\Stock;

class ChemicalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
      {
        $chemicals = [
            ['code' => 'colour', 'name' => 'Colour', 'unit' => 'Pt/Co unit'],
            ['code' => 'turbidity', 'name' => 'Turbidity', 'unit' => 'NTU'],
            ['code' => 'ph', 'name' => "pH @25°C", 'unit' => ''],
            ['code' => 'electrical', 'name' => 'Electrical Conductivity', 'unit' => 'µS/cm'],
            ['code' => 'chloride', 'name' => 'Chloride (as Cl)', 'unit' => 'mg/L'],
            ['code' => 'alkalinity', 'name' => 'Total Alkalinity (as CaCO3)', 'unit' => 'mg/L'],
            ['code' => 'nitrate', 'name' => 'Nitrate (as NO3-)', 'unit' => 'mg/L'],
            ['code' => 'nitrite', 'name' => 'Nitrite (as NO2-)', 'unit' => 'mg/L'],
            ['code' => 'fluoride', 'name' => 'Fluoride (as F-)', 'unit' => 'mg/L'],
            ['code' => 'phosphate', 'name' => 'Total Phosphate (as PO43-)', 'unit' => 'mg/L'],
            ['code' => 'dissolvedSolid', 'name' => 'Total Dissolved Solid', 'unit' => 'mg/L'],
            ['code' => 'hardness', 'name' => 'Total Hardness (as CaCO3)', 'unit' => 'mg/L'],
            ['code' => 'iron', 'name' => 'Total Iron', 'unit' => 'mg/L'],
            ['code' => 'sulphate', 'name' => 'Sulphate (as SO42-)', 'unit' => 'mg/L'],
            ['code' => 'calcium', 'name' => 'Calcium', 'unit' => 'mg/L'],
            ['code' => 'manganese', 'name' => 'Manganese', 'unit' => 'mg/L'],
            ['code' => 'coliform', 'name' => 'Total Coliform', 'unit' => 'Nos/100ml'],
            ['code' => 'coli', 'name' => 'E.Coli', 'unit' => 'Nos/100ml'],
        ];

        foreach ($chemicals as $c) {
            $chemical = Chemical::firstOrCreate(
                ['chemical_code' => $c['code']],
                [
                    'chemical_name'   => $c['name'],
                    'quantity'        => 1000,
                    'scal_metionment' => $c['unit'],
                ]
            );

            Stock::firstOrCreate(
                ['chemical_code' => $c['code']],
                [
                    'chemical_name'   => $c['name'],
                    'quantity'        => 1000,
                    'scal_metionment' => $c['unit'],
                ]
            );
        }
      }
    }
}
