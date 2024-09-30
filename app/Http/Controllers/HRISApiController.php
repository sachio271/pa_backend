<?php

namespace App\Http\Controllers;

use App\Models\MasterStruct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HRISApiController extends Controller
{
    public function get_subordinates_one_level($ektp)
    {
        $results = DB::select(
            'select  a.*, b.*,c.ektp,c.name,a.pastruct1,f.name as nama_atasan, f.ektp as ektp_atasan
            from masterstruct a
            left join employeestruct b on a.id=b.struct
            left join masteremployee c on c.ektp=b.ektp
            left join employeestruct d on a.parentstruct=d.struct
            left join masteremployee f on f.ektp=d.ektp
            where f.ektp = ?',
            [$ektp]
        );
        return response()->json(["message" => "sukses", 'data' => $results]);
    }

    public function get_subordinates($ektp)
    {

        function fetchSubordinates($ektp, &$allSubordinates)
        {

            $results = DB::select(
                'select  a.*, b.*,c.ektp,c.name,a.pastruct1,f.name as nama_atasan, f.ektp as ektp_atasan
            from masterstruct a
            left join employeestruct b on a.id=b.struct
            left join masteremployee c on c.ektp=b.ektp
            left join employeestruct d on a.parentstruct=d.struct
            left join masteremployee f on f.ektp=d.ektp
            where f.ektp = ?',
                [$ektp]
            );

            foreach ($results as $result) {
                // Add the current subordinate to the array
                $allSubordinates[] = $result;

                // Recursively fetch subordinates for this subordinate
                fetchSubordinates($result->ektp, $allSubordinates);
            }
        }

        fetchSubordinates($ektp, $allSubordinates);


        return response()->json(["message" => "sukses", 'data' => $allSubordinates]);
    }

    public function get_all_company()
    {
        $excludedCompany = ['BMAS', 'SLL', 'WFC', 'PT'];
        $results = DB::select(
            'select distinct a.companycode
            from masterstruct a
            left join employeestruct b on a.id=b.struct
            left join masteremployee c on c.ektp=b.ektp
            left join employeestruct d on a.parentstruct=d.struct
            left join masteremployee f on f.ektp=d.ektp
            where a.companycode not in (?, ?, ?, ?)
            order by a.companycode',
            $excludedCompany
        );

        return response()->json(["message" => "sukses", 'data' => $results]);
    }

    public function get_departments($companyCode)
    {
        $results = DB::select(
            'select distinct a.companycode, a.department
            from masterstruct a
            left join employeestruct b on a.id=b.struct
            left join masteremployee c on c.ektp=b.ektp
            left join employeestruct d on a.parentstruct=d.struct
            left join masteremployee f on f.ektp=d.ektp
            where a.companycode = ?
            order by a.companycode',
            [$companyCode]
        );

        return response()->json(["message" => "sukses", 'data' => $results]);
    }

    public function get_employee_department($companycode, $department)
    {
        $results = DB::select(
            'select  a.*, b.*,c.ektp,c.name,a.parentstruct,f.name as nama_atasan, f.ektp as ektp_atasan
            from masterstruct a
            left join employeestruct b on a.id=b.struct
            left join masteremployee c on c.ektp=b.ektp
            left join employeestruct d on a.parentstruct=d.struct
            left join masteremployee f on f.ektp=d.ektp
            where a.companycode = ? and a.department = ?',
            [$companycode, $department]
        );
        return response()->json(["message" => "sukses", 'data' => $results]);
    }
}
