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

    // public function get_subordinates($ektp, $limit_date)
    // {
    //     $allSubordinates = [];
    //     $endDate = '99981231';

    //     function fetchSubordinates($ektp, $limit_date, &$allSubordinates)
    //     {
    //         $endDate = '99981231';

    //         $results = DB::select(
    //             "
    //         select  a.*, b.*,c.ektp,c.name,a.pastruct1,f.name as nama_atasan, f.ektp as ektp_atasan
    //         from employeestruct b
    //         left join masterstruct a on a.id=b.struct
    //         left join masteremployee c on c.ektp=b.ektp
    //         left join employeestruct d on a.pastruct1=d.struct
    //         left join masteremployee f on f.ektp=d.ektp
    //         where f.ektp = ? and b.enddate = ? and c.ektp in (select ektp from employeestruct
    //                         where (status like 'JOIN' or status='-' ) and
    //                               ( struct not like '%D100%' and struct not like '%WFC%')
    //                               and startdate < ?
    //                               and ektp not in (select ektp from employeetermination))
    //                               and c.ektp not in ( select ektp from employeepaexception )
    //                               order by a.companycode,a.division,a.department,a.payrollsystem,a.office
    //         ",
    //             [$ektp, $endDate, $limit_date]
    //         );

    //         foreach ($results as $result) {
    //             // Add the current subordinate to the array
    //             $allSubordinates[] = $result;

    //             // Recursively fetch subordinates for this subordinate
    //             fetchSubordinates($result->ektp, $limit_date, $allSubordinates);
    //         }
    //     }

    //     fetchSubordinates($ektp, $limit_date, $allSubordinates);

    //     //Get data from pastruct2
    //     $paStruct2 = DB::select('select c.ektp,c.name,a.*,f.name as nama_atasan, f.ektp as ektp_atasan, b.*
    //         from employeestruct b
    //         left join masterstruct a on a.id=b.struct
    //         left join masteremployee c on c.ektp=b.ektp
    //         left join employeestruct d on a.pastruct2=d.struct
    //         left join masteremployee f on f.ektp=d.ektp
    //         where b.enddate = ? and f.ektp= ? ', [$endDate, $ektp]);



    //     if (!empty($paStruct2)) {
    //         foreach ($paStruct2 as $pa) {
    //             $allSubordinates[] = $pa;
    //         }
    //     }

    //     // dd(json_encode($allSubordinates));

    //     // Sort the array after all subordinates have been collected
    //     usort($allSubordinates, function ($a, $b) {
    //         return strcmp($a->name, $b->name);  // Sort by 'name' or any other field
    //     });


    //     return response()->json(["message" => "sukses", 'data' => $allSubordinates]);
    // }

    // public function get_subordinates($ektp, $limit_date)
    // {
    //     $allSubordinates = [];
    //     $endDate = '99981231';

    //     function fetchSubordinates($ektp, $limit_date, &$allSubordinates)
    //     {
    //         $endDate = '99981231';

    //         // Fetch subordinates via pastruct1
    //         $results = DB::select(
    //             "
    //         select  a.*, b.*,c.ektp,c.name,a.pastruct1,f.name as nama_atasan, f.ektp as ektp_atasan
    //         from employeestruct b
    //         left join masterstruct a on a.id=b.struct
    //         left join masteremployee c on c.ektp=b.ektp
    //         left join employeestruct d on a.pastruct1=d.struct
    //         left join masteremployee f on f.ektp=d.ektp
    //         where f.ektp = ? and b.enddate = ? and c.ektp in (select ektp from employeestruct
    //                         where (status like 'JOIN' or status='-' ) and
    //                               ( struct not like '%D100%' and struct not like '%WFC%')
    //                               and startdate < ?
    //                               and ektp not in (select ektp from employeetermination))
    //                               and c.ektp not in ( select ektp from employeepaexception )
    //                               order by a.companycode,a.division,a.department,a.payrollsystem,a.office
    //         ",
    //             [$ektp, $endDate, $limit_date]
    //         );

    //         foreach ($results as $result) {
    //             // Add the current subordinate to the array
    //             $allSubordinates[] = $result;

    //             // Recursively fetch subordinates for this subordinate
    //             fetchSubordinates($result->ektp, $limit_date, $allSubordinates);
    //         }

    //         // Fetch subordinates via pastruct2
    //         $paStruct2 = DB::select(
    //             '
    //         select c.ektp,c.name,a.*,f.name as nama_atasan, f.ektp as ektp_atasan, b.*
    //         from employeestruct b
    //         left join masterstruct a on a.id=b.struct
    //         left join masteremployee c on c.ektp=b.ektp
    //         left join employeestruct d on a.pastruct2=d.struct
    //         left join masteremployee f on f.ektp=d.ektp
    //         where b.enddate = ? and f.ektp = ?
    //         ',
    //             [$endDate, $ektp]
    //         );

    //         foreach ($paStruct2 as $pa) {
    //             // Add the current subordinate to the array
    //             $allSubordinates[] = $pa;

    //             // Recursively fetch subordinates for this subordinate
    //             fetchSubordinates($pa->ektp, $limit_date, $allSubordinates);
    //         }
    //     }

    //     fetchSubordinates($ektp, $limit_date, $allSubordinates);

    //     // Sort the array after all subordinates have been collected
    //     usort($allSubordinates, function ($a, $b) {
    //         return strcmp($a->name, $b->name);  // Sort by 'name' or any other field
    //     });

    //     return response()->json(["message" => "sukses", 'data' => $allSubordinates]);
    // }

    public function get_subordinates($ektp, $limit_date)
    {
        $endDate = '99981231';

        // --- 1️⃣ Ambil semua data pastruct1 (dengan subquery asli) ---
        $pastruct1Data = DB::select("
            select
                a.*, b.*, c.ektp, c.name,
                a.pastruct1,
                f.name as nama_atasan,
                f.ektp as ektp_atasan
            from employeestruct b
            left join masterstruct a on a.id = b.struct
            left join masteremployee c on c.ektp = b.ektp
            left join employeestruct d on a.pastruct1 = d.struct and b.enddate between d.startdate and d.enddate
            left join masteremployee f on f.ektp = d.ektp
            where b.enddate = ?
            and c.ektp in (
                    select ektp
                    from employeestruct
                    where (status like 'JOIN' or status='-' )
                    and (struct not like '%D100%' and struct not like '%WFC%')
                    and startdate < ?
                    and ektp not in (select ektp from employeetermination)
            )
            and c.ektp not in (select ektp from employeepaexception)
            order by a.companycode, a.division, a.department, a.payrollsystem, a.office
        ", [$endDate, $limit_date]);


        // --- 2️⃣ Ambil semua data pastruct2 (pakai subquery juga) ---
        $pastruct2Data = DB::select("
            select
                c.ektp, c.name, a.*, f.name as nama_atasan, f.ektp as ektp_atasan, b.*
            from employeestruct b
            left join masterstruct a on a.id = b.struct
            left join masteremployee c on c.ektp = b.ektp
            left join employeestruct d on a.pastruct2 = d.struct
            left join masteremployee f on f.ektp = d.ektp
            where b.enddate = ?
            and c.ektp in (
                    select ektp
                    from employeestruct
                    where (status like 'JOIN' or status='-' )
                    and (struct not like '%D100%' and struct not like '%WFC%')
                    and startdate < ?
                    and ektp not in (select ektp from employeetermination)
            )
            and c.ektp not in (select ektp from employeepaexception)
        ", [$endDate, $limit_date]);

        // --- 3️⃣ Buat mapping atasan → bawahan ---
        $mapPastruct1 = [];
        foreach ($pastruct1Data as $row) {
            if (!empty($row->ektp_atasan)) {
                $mapPastruct1[$row->ektp_atasan][] = $row;
            }
        }

        $mapPastruct2 = [];
        foreach ($pastruct2Data as $row) {
            if (!empty($row->ektp_atasan)) {
                $mapPastruct2[$row->ektp_atasan][] = $row;
            }
        }

        // --- 4️⃣ Rekursif di memory ---
        $allSubordinates = [];

        // fungsi rekursif untuk pastruct1
        $collectPastruct1Recursive = function ($ektp, &$mapPastruct1, &$allSubordinates, &$collectPastruct1Recursive) {
            if (isset($mapPastruct1[$ektp])) {
                foreach ($mapPastruct1[$ektp] as $child) {
                    $allSubordinates[] = $child;
                    $collectPastruct1Recursive($child->ektp, $mapPastruct1, $allSubordinates, $collectPastruct1Recursive);
                }
            }
        };

        // fungsi satu level untuk pastruct2
        $collectPastruct2Once = function ($ektp, &$mapPastruct2, &$allSubordinates) {
            if (isset($mapPastruct2[$ektp])) {
                foreach ($mapPastruct2[$ektp] as $child) {
                    $allSubordinates[] = $child;
                }
            }
        };

        $collectPastruct1Recursive($ektp, $mapPastruct1, $allSubordinates, $collectPastruct1Recursive);
        $collectPastruct2Once($ektp, $mapPastruct2, $allSubordinates);

        // --- 5️⃣ Sort hasil akhir ---
        usort($allSubordinates, fn($a, $b) => strcmp($a->name, $b->name));

        return response()->json([
            "message" => "sukses",
            "data" => array_values($allSubordinates),
        ]);
    }




    public function get_nama_atasan($ektp, $limit_date)
    {
        $endDate = '99981231';
        $results = DB::select(
            "
            select  a.*, b.*,c.ektp,c.name, if(a.pastruct2='',a.pastruct1,a.pastruct2) as pastruct1,
            if(a.pastruct2='',f.name,i.name) as nama_atasan, if(a.pastruct2='',f.ektp,i.ektp) as ektp_atasan
            from employeestruct b
            left join masterstruct a on a.id=b.struct
            left join masteremployee c on c.ektp=b.ektp
            left join employeestruct d on a.pastruct1=d.struct
            left join masteremployee f on f.ektp=d.ektp
            left join employeestruct g on a.pastruct2=g.struct
            left join masteremployee i on i.ektp=g.ektp
            where c.ektp = ? and b.enddate = ? and c.ektp in (select ektp from employeestruct
                            where (status like 'JOIN' or status='-' ) and
                                  ( struct not like '%D100%' and struct not like '%WFC%')
                                  and startdate < ?
                                  and ektp not in (select ektp from employeetermination))
                                  and c.ektp not in ( select ektp from employeepaexception )
                                  order by a.companycode,a.division,a.department,a.payrollsystem,a.office",
            [$ektp, $endDate, $limit_date]
        );
        return response()->json(["message" => "sukses", 'data' => $results]);
    }

    public function post_nama_atasan(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'ektpArray' => 'required|array|min:1',
        ]);

        $endDate = '99981231';
        $ektpArray = $request->input('ektpArray');

        $limit_date = $request->input('limit_date');

        $placeholders = implode(',', array_fill(0, count($ektpArray), '?'));

        $sql = "
        SELECT a.*, b.*, c.ektp, c.name,
        IF(a.pastruct2='', a.pastruct1, a.pastruct2) AS pastruct1,
        IF(a.pastruct2='', f.name, i.name) AS nama_atasan,
        IF(a.pastruct2='', f.ektp, i.ektp) AS ektp_atasan
        FROM employeestruct b
        LEFT JOIN masterstruct a ON a.id = b.struct
        LEFT JOIN masteremployee c ON c.ektp = b.ektp
        LEFT JOIN employeestruct d ON a.pastruct1 = d.struct
        LEFT JOIN masteremployee f ON f.ektp = d.ektp
        WHERE f.ektp IN ($placeholders)
        AND b.enddate = ?
        AND c.ektp IN (
            SELECT ektp FROM employeestruct
            WHERE (status LIKE 'JOIN' OR status = '-')
            AND (struct NOT LIKE '%D100%' AND struct NOT LIKE '%WFC%')
            AND startdate < ?
            AND ektp NOT IN (SELECT ektp FROM employeetermination)
        )
        AND c.ektp NOT IN (SELECT ektp FROM employeepaexception)
        ORDER BY a.companycode, a.division, a.department, a.payrollsystem, a.office
        ";

        $results = DB::select($sql, array_merge($ektpArray, [$endDate, $limit_date]));
        return response()->json(["message" => "sukses", 'data' => $results]);
    }


    public function get_all_data_employees($limit_date)
    {
        $endDate = '99981231';

        $results = DB::select(
            "
            select  a.*, b.*,c.ektp, m.code as wingsID ,c.name,a.pastruct1,f.name as nama_atasan, f.ektp as ektp_atasan
            from employeestruct b
            left join masterstruct a on a.id=b.struct
            left join masteremployee c on c.ektp=b.ektp
            left join employeestruct d on a.pastruct1=d.struct
            left join masteremployee f on f.ektp=d.ektp
            left join employeecode m on b.ektp=m.ektp and m.type='WINGSID'
            where b.enddate = ? and c.ektp in (select ektp from employeestruct
                            where (status like 'JOIN' or status='-' ) and
                                  ( struct not like '%D100%' and struct not like '%WFC%')
                                  and startdate < ?
                                  and ektp not in (select ektp from employeetermination))
                                  and c.ektp not in ( select ektp from employeepaexception )
                                  order by a.companycode,a.division,a.department,a.payrollsystem,a.office
            ",
            [$endDate, $limit_date]
        );

        return response()->json(["message" => "sukses", 'data' => $results]);
    }

    public function get_all_company()
    {
        $excludedCompany = [
            '',
            'BMAS',
            'SLL',
            'WFC',
            'PT',
            'AB57',
            'AB60',
            'AB68',
            'AB69',
            'AB71',
            'AB77',
            'AB81',
            'AB84',
            'AB02',
            'ABP',
            'D010',
            'D021',
            'D030',
            'D040',
            'D070',
            'D100',
            'D101',
            'D120',
            'D151',
            'D160',
            'D182',
            'D201',
            'D202',
            'D211',
            'D214',
            'D241',
            'D440',
            'D450',
            'D170',
            'D103',
            'D180'
        ];
        $results = DB::select(
            'select distinct a.payrollsystem
            from masterstruct a
            left join employeestruct b on a.id=b.struct
            left join masteremployee c on c.ektp=b.ektp
            left join employeestruct d on a.pastruct1=d.struct
            left join masteremployee f on f.ektp=d.ektp
            order by a.payrollsystem'
        );

        $filteredResults = array_filter($results, function ($item) use ($excludedCompany) {
            return !in_array($item->payrollsystem, $excludedCompany);
        });

        return response()->json(["message" => "sukses", 'data' => $filteredResults]);
    }

    public function get_departments_byComp($company)
    {
        $results = DB::select(
            'select distinct a.department
            from masterstruct a
            left join employeestruct b on a.id=b.struct
            left join masteremployee c on c.ektp=b.ektp
            left join employeestruct d on a.pastruct1=d.struct
            left join masteremployee f on f.ektp=d.ektp
            where a.payrollsystem = ?
            order by a.department',
            [$company]
        );

        return response()->json(["message" => "sukses", 'data' => $results]);
    }

    public function get_all_pacode()
    {
        $results = DB::select("select distinct a.PACode
            from masterstruct a
            left join employeestruct b on a.id=b.struct
            left join masteremployee c on c.ektp=b.ektp
            left join employeestruct d on a.pastruct1=d.struct
            left join masteremployee f on f.ektp=d.ektp
            where a.PACode <> null OR a.PACode <> ''
            order by a.PACode");
        return response()->json(["message" => "sukses", 'data' => $results]);
    }

    public function get_departments($companyCode)
    {
        $results = DB::select(
            'select distinct a.payrollsystem, a.department
            from masterstruct a
            left join employeestruct b on a.id=b.struct
            left join masteremployee c on c.ektp=b.ektp
            left join employeestruct d on a.pastruct1=d.struct
            left join masteremployee f on f.ektp=d.ektp
            where a.payrollsystem = ?
            order by a.payrollsystem',
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
            where a.payrollsystem = ? and a.department = ?',
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

    public function get_users_specified($company, $department)
    {
        $results = DB::select(
            'select
            distinct f.name AS nama_atasan,
            f.ektp AS ektp_atasan
            from masterstruct a
            left join employeestruct b ON a.id = b.struct
            left join employeestruct d ON a.pastruct1 = d.struct
            left join masteremployee f ON f.ektp = d.ektp
            where f.name is not null and a.department = ? and a.payrollsystem = ?
            order by f.name
            ',
            [$department, $company]
        );

        return response()->json(["message" => "sukses", "data" => $results]);
    }
}
