<?php

namespace App\Models;

use App\Controller\Login;

use CodeIgniter\Model;

class MyModel extends Model
{   
    protected $table = 'tbl_example'; // Default table
    protected $primaryKey = 'id';
    protected $allowedFields = ['id', 'columnName', 'isDeleted', 'status', 'updatedby', 'masterId', 'possibleGMPPoint', 'possibleOSCPoint', 'yearExperience', 'speciality', 'type', 'memberName', 'baselineGroup', 'facilityTypeId', 'physicalAddress', 'category']; // Add other fields as necessary

    //########################## AJAX GET QUERIES ##########################

    //######  These are all AJAX functions used to retrieve data from the database. ###### //

    public function example1AjaxGet($data, $compare)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('tbl_example as BaseTbl');
        $builder->select('BaseTbl.*')
                ->where('BaseTbl.isDeleted', 0)
                ->where('BaseTbl.status', 'Active')
                ->where('BaseTbl.id', $data);

        $query = $builder->get();

        $output = '';
        foreach ($query->getResult() as $record) {
            $output .= '<option value="' . $record->id . '"';
            if ($compare == $record->id) {
                $output .= ' selected';
            }
            $output .= '>' . $record->columnName . '</option>';
        }
        return $output;
    }

    public function myAjaxGet($table, $data, $compare, $columnName)
    {
        $db = \Config\Database::connect();
        $builder = $db->table($table . ' as BaseTbl');
        $builder->select('BaseTbl.*')
                ->where('BaseTbl.isDeleted', 0)
                ->where('BaseTbl.status', 'Active');

        $query = $builder->get();

        $output = '<option value="">Select an option</option>';
        foreach ($query->getResult() as $record) {
            $output .= '<option value="' . $record->id . '"';
            if ($compare == $record->id) {
                $output .= ' selected';
            }
            $output .= '>' . $record->$columnName . '</option>';
        }
        return $output;
    }

    public function myAjaxGet1($table, $data, $compare, $columnName)
    {
        $db = \Config\Database::connect();
        $builder = $db->table($table . ' as BaseTbl');
        $builder->select('BaseTbl.*, 
            (CASE WHEN (BaseTbl.masterId = 7) OR (BaseTbl.masterId = 5) OR (BaseTbl.masterId = 4) 
            THEN ((SELECT COUNT(*) FROM tbl_systemelement WHERE tbl_systemelement.masterId = BaseTbl.masterId) * 3) 
            ELSE BaseTbl.possibleGMPPoint END) as possibleGMPPoint, 
            (CASE WHEN (BaseTbl.masterId = 7) 
            THEN ((SELECT COUNT(*) FROM tbl_systemelement WHERE tbl_systemelement.masterId = BaseTbl.masterId) * 9) 
            ELSE BaseTbl.possibleOSCPoint END) as possibleOSCPoint')
                ->join('tbl_system as System1', 'System1.id = BaseTbl.masterId', 'left')
                ->where('BaseTbl.isDeleted', 0)
                ->where('BaseTbl.status', 'Active')
                ->where('System1.category', 'GMP')
                ->where('System1.name', trim($data));

        $query = $builder->get();

        $output = '';
        foreach ($query->getResult() as $record) {
            $output = $record->$columnName;
        }
        return $output;
    }

    public function myAjaxGet2($table, $data, $compare, $columnName)
    {
        $db = \Config\Database::connect();
        $builder = $db->table($table . ' as BaseTbl');
        $builder->select('BaseTbl.*, 
            ((SELECT COUNT(*) FROM tbl_systemelement WHERE tbl_systemelement.masterId = BaseTbl.masterId) * 3) as possibleGMPPoint, 
            ((SELECT COUNT(*) FROM tbl_systemelement WHERE tbl_systemelement.masterId = BaseTbl.masterId) * 9) as possibleOSCPoint')
                ->join('tbl_system as System1', 'System1.id = BaseTbl.masterId', 'left')
                ->where('BaseTbl.isDeleted', 0)
                ->where('BaseTbl.status', 'Active')
                ->where('System1.category', 'GMP')
                ->where('System1.name', trim($data));

        $query = $builder->get();

        $output = '';
        foreach ($query->getResult() as $record) {
            $output = $record->$columnName;
        }
        return $output;
    }

    public function myAjaxGet3($table, $data, $compare, $columnName)
    {
        $db = \Config\Database::connect();
        $builder = $db->table($table . ' as BaseTbl');
        $builder->select('BaseTbl.*')
                ->where('BaseTbl.isDeleted', 0)
                ->where('BaseTbl.status', 'Active');

        $query = $builder->get();

        $output = '<option value="">Select an option</option>';
        foreach ($query->getResult() as $record) {
            // Check user role and skip if conditions are met
            if (($roleId == 706 || $roleId == 705) && $this->userId != $record->updatedby) {
                continue;
            }
            $output .= '<option value="' . $record->id . '"';
            if ($compare == $record->id) {
                $output .= ' selected';
            }
            $output .= '>' . $record->memberName . ' (' . $record->type . ' , ' . $record->yearExperience . ' , ' . $record->speciality . ')</option>';
        }
        return $output;
    }

    public function myAjaxGet4($table, $data, $compare, $columnName)
    {
        // Load the database connection
        $db = \Config\Database::connect();
        $builder = $db->table($table);

        // Build the query
        $builder->select('*');
        $builder->where('isDeleted', 0);
        $builder->where('status', 'Active');
        $builder->where('baselineGroup', $data);

        // Execute the query
        $query = $builder->get();

        // Generate the HTML output
        $output = '<option value="">Select an option</option>';
        foreach ($query->getResult() as $record) {
            $output .= '<option value="' . $record->id . '"';
            if ($compare == $record->id) {
                $output .= ' selected';
            }
            $output .= '>' . $record->$columnName . '</option>';
        }

        return $output;
    }

    public function myAjaxGet5($table, $data, $compare, $columnName)
    {
        $db = \Config\Database::connect();
        $builder = $db->table($table . ' as BaseTbl');
        $builder->select('BaseTbl.*')
                ->where('BaseTbl.isDeleted', 0)
                ->where('BaseTbl.status', 'Active')
                ->where('BaseTbl.facilityTypeId', $data);

        $query = $builder->get();
        $output = '<option value="">Select an option</option>';

        foreach ($query->getResult() as $record) {
            if (($roleId == 2 || $roleId == 706) && $this->userId != $record->updatedby) {
                continue;
            }
            $output .= '<option value="' . $record->id . '"';
            if ($compare == $record->id) {
                $output .= ' selected';
            }
            $output .= '>' . $record->$columnName . ' (' . $record->physicalAddress . ')</option>';
        }
        return $output;
    }

    public function myAjaxGet6($table, $data, $compare, $columnName)
    {
        $db = \Config\Database::connect();
        $builder = $db->table($table . ' as BaseTbl');
        $builder->select('BaseTbl.*')
                ->join('tbl_system as System1', 'System1.id = BaseTbl.masterId', 'left')
                ->where('BaseTbl.isDeleted', 0)
                ->where('BaseTbl.status', 'Active')
                ->where('System1.category', 'GSDP');

        $query = $builder->get();
        $output = '<option value="">Select an option</option>';

        foreach ($query->getResult() as $record) {
            $output .= '<option value="' . $record->id . '"';
            if ($compare == $record->id) {
                $output .= ' selected';
            }
            $output .= '>' . $record->$columnName . '</option>';
        }
        return $output;
    }

    public function myAjaxGet7($table, $data, $compare, $columnName)
    {
        $db = \Config\Database::connect();
        $builder = $db->table($table . ' as BaseTbl');
        $builder->select('BaseTbl.*')
                ->where('BaseTbl.isDeleted', 0)
                ->where('BaseTbl.status', 'Active')
                ->where('BaseTbl.masterId', $data);

        $query = $builder->get();
        $output = '<option value="">Select an option</option>';

        foreach ($query->getResult() as $record) {
            $output .= '<option value="' . $record->id . '"';
            if ($compare == $record->id) {
                $output .= ' selected';
            }
            $output .= '>' . $record->$columnName . '</option>';
        }
        return $output;
    }

    public function myAjaxGet8($table, $data, $compare, $columnName)
    {
        $db = \Config\Database::connect();
        $builder = $db->table($table . ' as BaseTbl');
        $builder->select('BaseTbl.*')
                ->where('BaseTbl.isDeleted', 0)
                ->where('BaseTbl.status', 'Active')
                ->where('BaseTbl.category', 'GSDP')
                ->where('BaseTbl.masterId', $data);

        $query = $builder->get();
        $output = '<option value="">Select an option</option>';

        foreach ($query->getResult() as $record) {
            $output .= '<option value="' . $record->id . '"';
            if ($compare == $record->id) {
                $output .= ' selected';
            }
            $output .= '>' . $record->$columnName . '</option>';
        }
        return $output;
    }

    //########################## ..The End.. AJAX GET QUERIES ##########################


    //This function is used to retrieve data from any table in the database.
    //Explanation of the parameters in this function:
    //$table: This parameter is used to specify the table name.
    //$selectQuery: This parameter is used to specify the select query.
    //$orderByColumn: This parameter is used to specify the column order name.
    //$order: This parameter is used to specify the order value (asc or desc)."
    public function myGet($table, $selectQuery, $whereClause, $orderByColumn, $order)
    {
        // Get the database connection
        $db = \Config\Database::connect();
        
        // Build the query
        $builder = $db->table($table . ' as BaseTbl');
        $builder->select($selectQuery, false) // Use false to prevent escaping
                ->where('BaseTbl.isDeleted', 0);

        if ($whereClause) {
            $builder->where($whereClause);
        }

        $builder->orderBy($orderByColumn, $order);
        $query = $builder->get();

        return $query->getResult();
    }
    // The End myGet function //
    
    // This function used to count the Scheduled Data
    // This function related for NMRA Admin
    public function countScheduled($roleId, $countryId)
    {   
        $db = \Config\Database::connect();

        // Define tables based on roleId
        if ($roleId == 705 || $roleId == 707 || $roleId == 709) {
            $table = 'tbl_gmpinspection';
        } elseif ($roleId == 706 || $roleId == 708) {
            $table = 'tbl_gdpinspection';
        } else {
            return []; // Handle undefined roleId gracefully
        }

        // Build query
        $builder = $db->table($table . ' as BaseTbl');
        $builder->select('COUNT(*) as resultCount')
                ->join('tbls_user as User', 'User.id = BaseTbl.updatedby', 'left') // Fixed space issue
                ->where('BaseTbl.isDeleted', 0)
                ->where('BaseTbl.status', 'Scheduled')
                ->where('User.countryId', $countryId)
                ->orderBy('User.id', 'asc')
                ->groupBy('User.id'); // Group by the non-aggregated field;

        // Execute and return result
        $query = $builder->get();
        return $query->getResult();
    }
    
    // This function used to count the Scheduled Data
    // This function related for Inspector
    public function countScheduled1($roleId, $userId)
    {
        // Define tables based on roleId
        if ($roleId == 705 || $roleId == 707 || $roleId == 709) {
            $table = 'tbl_gmpinspection';
            $table1 = 'tbl_gmpinspectionmemberdetail';
        } elseif ($roleId == 706 || $roleId == 708) {
            $table = 'tbl_gdpinspection';
            $table1 = 'tbl_gdpinspectionmemberdetail';
        } else {
            return []; // Handle undefined roleId gracefully
        }

        $db = \Config\Database::connect();
        $builder = $db->table($table . ' as BaseTbl');
        $builder->select('COUNT(*) as resultCount')
                ->join($table1 . ' as InspectionMember', 'InspectionMember.masterId = BaseTbl.id', 'left')
                ->join('tbl_panelpool as PanelPool', 'PanelPool.id = InspectionMember.memberId', 'left')
                ->join('tbls_user as User', 'User.id = PanelPool.userId', 'left') // Fixed space issue
                ->where('BaseTbl.isDeleted', 0)
                ->where('BaseTbl.status', 'Scheduled');

        // Additional condition for specific roleId
        if ($roleId == 707 || $roleId == 708) {
            $builder->where('User.id', $userId);
        }

        $builder->orderBy('User.id', 'asc');
        $builder->groupBy('User.id');

        $query = $builder->get();
        return $query->getResult();
    }
    

    // This function used to count the In Process Data
    // This function related for NMRA Admin
    public function countInProcess($roleId, $countryId)
    {
        // Define tables based on roleId
        if ($roleId == 705 || $roleId == 707 || $roleId == 709) {
            $table = 'tbl_gmpinspection';
        } elseif ($roleId == 706 || $roleId == 708) {
            $table = 'tbl_gdpinspection';
        } else {
            return []; // Return an empty array if roleId is not valid
        }

        // Initialize database connection
        $db = \Config\Database::connect();

        // Build the query
        $builder = $db->table($table . ' as BaseTbl');
        $builder->select('COUNT(*) as resultCount')
                ->join('tbls_user as User', 'User.id = BaseTbl.updatedby', 'left') // Fixed space issue
                ->where('BaseTbl.isDeleted', 0)
                ->where('BaseTbl.status', 'In Process')
                ->where('User.countryId', $countryId)
                ->orderBy('User.id', 'asc')
                 ->groupBy('User.id'); // Group by roleId

        // Execute query and return results
        $query = $builder->get();
        return $query->getResult();
    }
    
    // This function used to count the In Process Data
    // This function related for Inspector
    public function countInProcess1($roleId, $userId)
    {
        // Define tables based on roleId
        if ($roleId == 705 || $roleId == 707 || $roleId == 709) {
            $table = 'tbl_gmpinspection';
            $table1 = 'tbl_gmpinspectionmemberdetail';
        } elseif ($roleId == 706 || $roleId == 708) {
            $table = 'tbl_gdpinspection';
            $table1 = 'tbl_gdpinspectionmemberdetail';
        } else {
            return []; // Return an empty array if roleId is not valid
        }

        // Initialize database connection
        $db = \Config\Database::connect();

        // Build the query
        $builder = $db->table($table . ' as BaseTbl');
        $builder->select('COUNT(*) as resultCount')
                ->join($table1 . ' as InspectionMember', 'InspectionMember.masterId = BaseTbl.id', 'left')
                ->join('tbl_panelpool as PanelPool', 'PanelPool.id = InspectionMember.memberId', 'left')
                ->join('tbls_user as User', 'User.id = PanelPool.userId', 'left') // Fixed space issue
                ->where('BaseTbl.isDeleted', 0)
                ->where('BaseTbl.status', 'In Process');

        // Apply additional condition for specific roles
        if ($roleId == 707 || $roleId == 708 || $roleId == 709) {
            $builder->where('User.id', $userId);
        }

        $builder->orderBy('User.id', 'asc');
        $builder->groupBy('User.id');

        // Execute query and return results
        $query = $builder->get();
        return $query->getResult();
    }
    

    // This function used to count the Inspection Completed Data
    // This function related for NMRA Admin
    public function countInspectionCompleted($roleId, $countryId)
    {
        // Define tables based on roleId
        if ($roleId == 705 || $roleId == 707 || $roleId == 709) {
            $table = 'tbl_gmpinspection';
        } elseif ($roleId == 706 || $roleId == 708) {
            $table = 'tbl_gdpinspection';
        } else {
            return []; // Return an empty array if roleId is invalid
        }

        // Initialize database connection
        $db = \Config\Database::connect();

        // Build the query
        $builder = $db->table($table . ' as BaseTbl');
        $builder->select('COUNT(*) as resultCount')
                ->join('tbls_user as User', 'User.id = BaseTbl.updatedby', 'left') // Fixed space issue
                ->where('BaseTbl.isDeleted', 0)
                ->where('BaseTbl.status', 'Inspection Completed')
                ->where('User.countryId', $countryId)
                ->orderBy('User.id', 'asc')
                ->groupBy('User.id');

        // Execute query and return results
        $query = $builder->get();
        return $query->getResult();
    }
    

    // This function used to count the Inspection Completed Data
    // This function related for Inspector
    public function countInspectionCompleted1($roleId, $userId)
    {
        // Define tables based on roleId
        if ($roleId == 705 || $roleId == 707 || $roleId == 709) {
            $table = 'tbl_gmpinspection';
            $table1 = 'tbl_gmpinspectionmemberdetail';
        } elseif ($roleId == 706 || $roleId == 708) {
            $table = 'tbl_gdpinspection';
            $table1 = 'tbl_gdpinspectionmemberdetail';
        } else {
            return []; // Return an empty array if roleId is invalid
        }

        // Initialize database connection
        $db = \Config\Database::connect();

        // Build the query
        $builder = $db->table($table . ' as BaseTbl');
        $builder->select('COUNT(*) as resultCount')
                ->join($table1 . ' as InspectionMember', 'InspectionMember.masterId = BaseTbl.id', 'left')
                ->join('tbl_panelpool as PanelPool', 'PanelPool.id = InspectionMember.memberId', 'left')
                ->join('tbls_user as User', 'User.id = PanelPool.userId', 'left') // Fixed space issue
                ->where('BaseTbl.isDeleted', 0)
                ->where('BaseTbl.status', 'Inspection Completed');

        // Apply condition for specific roles
        if ($roleId == 707 || $roleId == 708 || $roleId == 709) {
            $builder->where('User.id', $userId);
        }

        $builder->orderBy('User.id', 'asc');
        $builder->groupBy('User.id');

        // Execute query and return results
        $query = $builder->get();
        return $query->getResult();
    }
    

    // This function used to count the Under CAPA Data
    // This function related for NMRA Admin
    public function countUnderCapa($roleId, $countryId)
    {
        // Determine the correct tables based on roleId
        if ($roleId == 705 || $roleId == 707 || $roleId == 709) {
            $table = 'tbl_gmpinspection';
        } elseif ($roleId == 706 || $roleId == 708) {
            $table = 'tbl_gdpinspection';
        } else {
            return []; // Return an empty array if roleId is invalid
        }

        // Initialize database connection
        $db = \Config\Database::connect();

        // Build the query
        $builder = $db->table($table . ' as BaseTbl');
        $builder->select('COUNT(*) as resultCount')
                ->join('tbls_user as User', 'User.id = BaseTbl.updatedby', 'left') // Fixed space issue
                ->where('BaseTbl.isDeleted', 0)
                ->where('BaseTbl.status', 'Under CAPA')
                ->where('User.countryId', $countryId)
                ->orderBy('User.id', 'asc')
                ->groupBy('User.id');

        // Execute query and return results
        $query = $builder->get();
        return $query->getResult();
    }
    
    // This function used to count the Under CAPA Data
    // This function related for Inspector
    public function countUnderCapa1($roleId, $userId)
    {
        // Determine the correct tables based on roleId
        if ($roleId == 705 || $roleId == 707 || $roleId == 709) {
            $table = 'tbl_gmpinspection';
            $table1 = 'tbl_gmpinspectionmemberdetail';
        } elseif ($roleId == 706 || $roleId == 708) {
            $table = 'tbl_gdpinspection';
            $table1 = 'tbl_gdpinspectionmemberdetail';
        } else {
            return []; // Return an empty array if roleId is invalid
        }

        // Initialize database connection
        $db = \Config\Database::connect();

        // Build the query
        $builder = $db->table($table . ' as BaseTbl');
        $builder->select('COUNT(*) as resultCount')
                ->join($table1 . ' as InspectionMember', 'InspectionMember.masterId = BaseTbl.id', 'left')
                ->join('tbl_panelpool as PanelPool', 'PanelPool.id = InspectionMember.memberId', 'left')
                ->join('tbls_user as User', 'User.id = PanelPool.userId', 'left') // Fixed space issue
                ->where('BaseTbl.isDeleted', 0)
                ->where('BaseTbl.status', 'Under CAPA');

        // Add user-specific condition for roles 707 and 708
        if ($roleId == 707 || $roleId == 708 || $roleId == 709) {
            $builder->where('User.id', $userId);
        }

        $builder->orderBy('User.id', 'asc');
        $builder->groupBy('User.id');

        // Execute query and return results
        $query = $builder->get();
        return $query->getResult();
    }
    
    // This function used to count the Inspection Approved Data
    // This function related for NMRA Admin
    public function countInspectionApproved($roleId, $countryId)
    {
        if ($roleId == 705 || $roleId == 707 || $roleId == 709) {
            $table = 'tbl_gmpinspection';
            $table1 = 'tbl_gmpinspectionmemberdetail';
        } elseif ($roleId == 706 || $roleId == 708) {
            $table = 'tbl_gdpinspection';
            $table1 = 'tbl_gdpinspectionmemberdetail';
        } else {
            return []; // Return empty array if roleId is invalid
        }

        $db = \Config\Database::connect();
        $builder = $db->table($table . ' as BaseTbl');

        // Add the select statement with corrected alias and subquery
        $builder->select('COUNT(*) as resultCount, 
            (SELECT tbl_panelpool.userId 
             FROM ' . $table1 . ' as DetailTbl
             LEFT JOIN tbl_panelpool ON tbl_panelpool.id = DetailTbl.memberId 
             WHERE DetailTbl.masterId = BaseTbl.id 
             AND DetailTbl.role = "Lead" 
             ORDER BY DetailTbl.id DESC 
             LIMIT 1) as leadUserId')
            ->join('tbls_user as User', 'User.id = BaseTbl.updatedby', 'left')
            ->where('BaseTbl.isDeleted', 0);

        // Add status condition based on roleId
        if ($roleId == 705 || $roleId == 707 || $roleId == 709) {
            $builder->where('BaseTbl.status', 'Site Accepted');
        } elseif ($roleId == 706 || $roleId == 708) {
            $builder->where('BaseTbl.status', 'Inspection Approved');
        }

        $builder->where('User.countryId', $countryId)
                ->orderBy('leadUserId', 'asc')
                ->groupBy('leadUserId');

        $query = $builder->get();
        return $query->getResult();
    }
    
    // This function used to count the Inspection Approved Data
    // This function related for Inspector
    public function countInspectionApproved1($roleId, $userId)
    {
        if ($roleId == 705 || $roleId == 707 || $roleId == 709) {
            $table = 'tbl_gmpinspection';
            $table1 = 'tbl_gmpinspectionmemberdetail';
        } elseif ($roleId == 706 || $roleId == 708) {
            $table = 'tbl_gdpinspection';
            $table1 = 'tbl_gdpinspectionmemberdetail';
        } else {
            return []; // Return empty array if roleId is invalid
        }

        $db = \Config\Database::connect();
        $builder = $db->table($table . ' as BaseTbl');

        // Add the select statement
        $builder->select('COUNT(*) as resultCount')
            ->join($table1 . ' as InspectionMember', 'InspectionMember.masterId = BaseTbl.id', 'left')
            ->join('tbl_panelpool as PanelPool', 'PanelPool.id = InspectionMember.memberId', 'left')
            ->join('tbls_user as User', 'User.id = PanelPool.userId', 'left')
            ->where('BaseTbl.isDeleted', 0);

        // Add status condition based on roleId
        if ($roleId == 705 || $roleId == 707 || $roleId == 709) {
            $builder->where('BaseTbl.status', 'Site Accepted');
        } elseif ($roleId == 706 || $roleId == 708) {
            $builder->where('BaseTbl.status', 'Inspection Approved');
        }

        // Add user-specific condition for roles 707 and 708
        if ($roleId == 707 || $roleId == 708 || $roleId == 709) {
            $builder->where('User.id', $userId);
        }

        $builder->orderBy('User.id', 'asc');
        $builder->groupBy('User.id');

        $query = $builder->get();
        return $query->getResult();
    }
    
    // This function used to retrieve the users data
    public function userGet()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('tbls_user as BaseTbl');
        $builder->select('BaseTbl.*, Role.department, Role.designation')
                ->join('tbls_role as Role', 'Role.id = BaseTbl.roleId', 'left')
                ->where('BaseTbl.isDeleted', 0)
                ->where('BaseTbl.status', 'Active')
                ->where('BaseTbl.roleId !=', 1);

        $query = $builder->get();
        return $query->getResult();
    }
    
    // This function used to retrieve the country data
    public function countryGet()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('tbls_country as BaseTbl');
        $builder->select('BaseTbl.*')
                ->where('BaseTbl.isDeleted', 0)
                ->where('BaseTbl.status', 'Active');

        $query = $builder->get();
        return $query->getResult();
    }
    
    // This function used to retrieve the city data
    public function cityGet()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('tbls_city as BaseTbl');
        $builder->select('BaseTbl.*')
                ->where('BaseTbl.isDeleted', 0)
                ->where('BaseTbl.status', 'Active');

        $query = $builder->get();
        return $query->getResult();
    }
    
    // This function used to retrieve the manufacturers data
    public function manufacturerGet()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('tbl_company as BaseTbl');
        $builder->select('BaseTbl.*')
                ->where('BaseTbl.isDeleted', 0)
                ->where('BaseTbl.status', 'Active');

        $query = $builder->get();
        return $query->getResult();
    }

    //########################## MAIN GET QUERIES ##########################
    
    // This function is used to load the modules queries
    public function viewLoader($table, $searchText = null)
    {
        $db = \Config\Database::connect();
        $builder = $db->table($table . ' as BaseTbl');
        $builder->select('BaseTbl.*');

        if (!empty($searchText)) {
            $builder->groupStart()
                    ->like('BaseTbl.dosageFormName', $searchText)
                    ->orLike('BaseTbl.status', $searchText)
                    ->groupEnd();
        }

        $builder->where('BaseTbl.isDeleted', 0)
                ->orderBy('BaseTbl.id', 'desc');

        $query = $builder->get();
        return $query->getResult();
    }
    
    // This function is used to update the all modules queries
    public function viewLoaderEdit($id, $table)
    {
        $db = \Config\Database::connect();
        $builder = $db->table($table . ' as BaseTbl');
        $builder->select('BaseTbl.*')
                ->where('BaseTbl.isDeleted', 0)
                ->where('BaseTbl.id', $id);

        $query = $builder->get();
        return $query->getResult();
    }
    
    // This function is used to retrieve document data
    public function document($table, $searchText = null)
    {
        $db = \Config\Database::connect();
        $builder = $db->table($table . ' as BaseTbl');
        $builder->select('BaseTbl.*, Country.countryName')
                ->join('tbls_country as Country', 'Country.id = BaseTbl.countryId', 'left');

        if (!empty($searchText)) {
            $builder->groupStart()
                    ->like('BaseTbl.dosageFormName', $searchText)
                    ->orLike('BaseTbl.status', $searchText)
                    ->groupEnd();
        }

        $builder->where('BaseTbl.isDeleted', 0)
                ->orderBy('BaseTbl.id', 'desc');

        $query = $builder->get();
        return $query->getResult();
    }
    
    // This function is use to update the documentation data.
    public function documentEdit($id, $table)
    {
        $db = \Config\Database::connect();
        $builder = $db->table($table . ' as BaseTbl');
        $builder->select('BaseTbl.*')
                ->where('BaseTbl.isDeleted', 0)
                ->where('BaseTbl.id', $id);

        $query = $builder->get();
        return $query->getResult();
    }
    
    // This function is used to retrieve query data
    public function query($table, $searchText = null)
    {
        $db = \Config\Database::connect();
        $builder = $db->table($table . ' as BaseTbl');
        $builder->select('BaseTbl.*');

        if (!empty($searchText)) {
            $builder->groupStart()
                    ->like('BaseTbl.dosageFormName', $searchText)
                    ->orLike('BaseTbl.status', $searchText)
                    ->groupEnd();
        }

        $builder->where('BaseTbl.isDeleted', 0)
                ->orderBy('BaseTbl.id', 'desc');

        $query = $builder->get();
        return $query->getResult();
    }
    
    // This function is use to update the query data.
    public function queryEdit($id, $table)
    {
        $db = \Config\Database::connect();
        $builder = $db->table($table . ' as BaseTbl');
        $builder->select('BaseTbl.*')
                ->where('BaseTbl.isDeleted', 0)
                ->where('BaseTbl.id', $id);

        $query = $builder->get();
        return $query->getResult();
    }

}    
