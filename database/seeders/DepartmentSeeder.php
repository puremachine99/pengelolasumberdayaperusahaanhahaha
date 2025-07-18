<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Department;

class DepartmentSeeder extends Seeder
{
    public function run(): void
    {
        $ceo = Department::create(['name' => 'Chief Executive Officer']);
        $coo = Department::create(['name' => 'Chief Operating Officer', 'parent_id' => $ceo->id]);

        // Corporate Secretariat
        $corpSec = Department::create(['name' => 'Corporate Secretariat', 'parent_id' => $coo->id]);
        Department::create(['name' => 'Corporate Legal Unit', 'parent_id' => $corpSec->id]);
        Department::create(['name' => 'Secretariat (Corporate)', 'parent_id' => $corpSec->id]);

        // Directorate of Strategy and Technology
        $dirStrategy = Department::create(['name' => 'Directorate of Strategy and Technology', 'parent_id' => $coo->id]);
        $pmDept = Department::create(['name' => 'Performance Management Department', 'parent_id' => $dirStrategy->id]);
        Department::create(['name' => 'Secretariat (PM)', 'parent_id' => $pmDept->id]);

        $hcDept = Department::create(['name' => 'Human Capital Department', 'parent_id' => $dirStrategy->id]);
        Department::create(['name' => 'Secretariat (HC)', 'parent_id' => $hcDept->id]);

        Department::create(['name' => 'Artificial Intelligence and Technology Department', 'parent_id' => $dirStrategy->id]);

        // Directorate of Finance
        $dirFinance = Department::create(['name' => 'Directorate of Finance', 'parent_id' => $coo->id]);
        $fgDept = Department::create(['name' => 'Finance & General Affair Department', 'parent_id' => $dirFinance->id]);
        Department::create(['name' => 'Disbursement Department', 'parent_id' => $fgDept->id]);
        Department::create(['name' => 'Finance Department', 'parent_id' => $fgDept->id]);

        Department::create(['name' => 'Funding Department', 'parent_id' => $dirFinance->id]);
        Department::create(['name' => 'General Affair Unit', 'parent_id' => $dirFinance->id]);
        Department::create(['name' => 'Secretariat (Finance)', 'parent_id' => $dirFinance->id]);

        // Directorate of Partnership and Product
        $dirPartnership = Department::create(['name' => 'Directorate of Partnership and Product', 'parent_id' => $coo->id]);

        $partnerDept = Department::create(['name' => 'Partnership Department', 'parent_id' => $dirPartnership->id]);
        Department::create(['name' => 'Regional Partnership Department', 'parent_id' => $partnerDept->id]);
        Department::create(['name' => 'Regional Offices Unit', 'parent_id' => $partnerDept->id]);
        Department::create(['name' => 'Digital Marketing Department', 'parent_id' => $partnerDept->id]);
        Department::create(['name' => 'Secretariat (Partnership)', 'parent_id' => $partnerDept->id]);

        $productDept = Department::create(['name' => 'Product Department', 'parent_id' => $dirPartnership->id]);

        $gdd = Department::create(['name' => 'Governance and Development Division', 'parent_id' => $productDept->id]);
        Department::create(['name' => 'Governance and Development Innovation Centers', 'parent_id' => $gdd->id]);
        Department::create(['name' => 'Smart Reform Department', 'parent_id' => $gdd->id]);
        Department::create(['name' => 'SmartCourse Offline Department', 'parent_id' => $gdd->id]);
        Department::create(['name' => 'SmartCourse Online Department', 'parent_id' => $gdd->id]);
        Department::create(['name' => 'Piloting Department', 'parent_id' => $gdd->id]);

        $enf = Department::create(['name' => 'Enfranesia Division', 'parent_id' => $productDept->id]);
        Department::create(['name' => 'Enfranesia Innovation Centers', 'parent_id' => $enf->id]);

        $scn = Department::create(['name' => 'Smart City & Nation Division', 'parent_id' => $productDept->id]);
        Department::create(['name' => 'Smart City & Nation Innovation Centers', 'parent_id' => $scn->id]);
    }
}
