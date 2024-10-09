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
            left join employeestruct d on a.pastruct1=d.struct
            left join masteremployee f on f.ektp=d.ektp
            where f.ektp = ?',
            [$ektp]
        );
        return response()->json(["message" => "sukses", 'data' => $results]);
    }

    public function get_subordinates($ektp, $limit_date)
    {
        $allSubordinates = [];
        $endDate = '99981231';
        function fetchSubordinates($ektp, $limit_date, &$allSubordinates)
        {
            $endDate = '99981231';

            $results = DB::select(
            "
            select  a.*, b.*,c.ektp,c.name,a.pastruct1,f.name as nama_atasan, f.ektp as ektp_atasan
            from employeestruct b
            left join masterstruct a on a.id=b.struct
            left join masteremployee c on c.ektp=b.ektp
            left join employeestruct d on a.pastruct1=d.struct
            left join masteremployee f on f.ektp=d.ektp
            where f.ektp = ? and b.enddate = ? and c.ektp in (select ektp from employeestruct
                            where (status like 'JOIN' or status='-' ) and
                                  ( struct not like '%D100%' and struct not like '%WFC%')
                                  and startdate < ?
                                  and ektp not in (select ektp from employeetermination))
                                  and c.ektp not in ( select ektp from employeepaexception )
                                  order by a.companycode,a.division,a.department,a.payrollsystem,a.office
            ", [$ektp, $endDate, $limit_date]
            );

            foreach ($results as $result) {
                // Add the current subordinate to the array
                $allSubordinates[] = $result;

                // Recursively fetch subordinates for this subordinate
                fetchSubordinates($result->ektp, $limit_date, $allSubordinates);
            }
        }

        fetchSubordinates($ektp, $limit_date, $allSubordinates);

        //Get data from pastruct2
        $paStruct2 = DB::select('select c.ektp,c.name,a.*,f.name as nama_atasan, f.ektp as ektp_atasan, b.*
            from employeestruct b
            left join masterstruct a on a.id=b.struct
            left join masteremployee c on c.ektp=b.ektp
            left join employeestruct d on a.pastruct2=d.struct
            left join masteremployee f on f.ektp=d.ektp
            where b.enddate = ? and f.ektp= ? ', [$endDate, $ektp]);



        if(!empty($paStruct2)) {
            foreach($paStruct2 as $pa) {
                $allSubordinates[] = $pa;
            }
        }

        // dd(json_encode($allSubordinates));

        // Sort the array after all subordinates have been collected
        usort($allSubordinates, function ($a, $b) {
            return strcmp($a->name, $b->name);  // Sort by 'name' or any other field
        });


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
            left join employeestruct d on a.pastruct1=d.struct
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
            left join employeestruct d on a.pastruct1=d.struct
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
            'select  a.*, b.*,c.ektp,c.name,a.pastruct1,f.name as nama_atasan, f.ektp as ektp_atasan
            from masterstruct a
            left join employeestruct b on a.id=b.struct
            left join masteremployee c on c.ektp=b.ektp
            left join employeestruct d on a.pastruct1=d.struct
            left join masteremployee f on f.ektp=d.ektp
            where a.companycode = ? and a.department = ?',
            [$companycode, $department]
        );
        return response()->json(["message" => "sukses", 'data' => $results]);
    }

    public function get_users()
    {
        $results = DB::select(
            'select
            distinct f.name AS nama_atasan,
            f.ektp AS ektp_atasan
            from masterstruct a
            left join employeestruct b ON a.id = b.struct
            left join employeestruct d ON a.pastruct1 = d.struct
            left join masteremployee f ON f.ektp = d.ektp
            where f.name is not null
            order by f.name
            '
        );

        return response()->json(["message" => "sukses", "data" => $results]);
    }
}
