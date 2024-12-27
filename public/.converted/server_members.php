<?php
/**
 * Created by JetBrains PhpStorm.
 * User: wwatters
 * Date: 7/5/16
 * Time: 4:16 PM
 * To change this template use File | Settings | File Templates.
 */
//require_once __DIR__ . '/bootstrap.php';

/* Database connection start */
$servername = "52.40.143.45";
$servername = "127.0.0.1";
$username = "ares_user";
$password = "axanar2016";
$dbname = "dbDigital";
$dbport = 3307;
$conn = mysqli_connect($servername, $username, $password, $dbname, $dbport) or
die("Connection failed: " . mysqli_connect_error());
$conn->set_charset("utf8");
/* Database connection end */
// storing  request (ie, get/post) global array to a variable
$requestData = $_REQUEST;
$columns = [
    // datatable column index  => database column name
    0 => 'user_id',
    1 => 'full_name',
    2 => 'email'
];

// getting total number records without any search
$sql = "SELECT * FROM Users WHERE deleted=0";
$query = mysqli_query($conn, $sql) or die("server_members.php: get employees");
$totalData = mysqli_num_rows($query);
$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.

function utf8_encode_deep(&$input)
{
    if (is_string($input)) {
        $input = utf8_encode($input);
    } elseif (is_array($input)) {
        foreach ($input as &$value) {
            utf8_encode_deep($value);
        }

        unset($value);
    } elseif (is_object($input)) {
        $vars = array_keys(get_object_vars($input));

        foreach ($vars as $var) {
            utf8_encode_deep($input->$var);
        }
    }
}

if (!empty($requestData['platform']) || !empty($requestData['campaign'])) {
    if (!empty($requestData['search']['value'])) {
        // if there is a search parameter
        $sql =
            "SELECT DISTINCT u.* FROM Users u JOIN UserCampaignPackages p ON u.user_id = p.user_id JOIN Campaigns c ON p.campaign_id = c.campaign_id WHERE deleted=0 ";
        if (!empty($requestData['platform'])) {
            $sql .= " and c.provider = " . $requestData['platform'] . " ";
        }

        if (!empty($requestData['campaign'])) {
            $sql .= " and ";
            $sql .= " c.campaign_id = " . $requestData['campaign'] . " ";
        }
        $sql .= " and ( u.user_id LIKE '%" .
                $requestData['search']['value'] .
                "%' ";    // $requestData['search']['value'] contains search parameter
        $sql .= " OR email LIKE '%" . $requestData['search']['value'] . "%' ";
        $sql .= " OR full_name LIKE '%" . $requestData['search']['value'] . "%') ";
        $query = mysqli_query($conn, $sql) or die("server_members.php: get employees");
        $totalFiltered =
            mysqli_num_rows($query); // when there is a search parameter then we have to modify total number filtered rows as per search result without limit in the query
        $sql .= " ORDER BY " .
                $columns[$requestData['order'][0]['column']] .
                "   " .
                $requestData['order'][0]['dir'] .
                "   LIMIT " .
                $requestData['start'] .
                " ," .
                $requestData['length'] .
                "   "; // $requestData['order'][0]['column'] contains colmun index, $requestData['order'][0]['dir'] contains order such as asc/desc , $requestData['start'] contains start row number ,$requestData['length'] contains limit length.
        $query = mysqli_query($conn, $sql) or die("server_members.php: get employees"); // again run query with limit

    } else {
        $sql =
            "SELECT DISTINCT u.* FROM Users u JOIN UserCampaignPackages p ON u.user_id = p.user_id JOIN Campaigns c ON p.campaign_id = c.campaign_id WHERE deleted = 0 ";
        if (!empty($requestData['platform'])) {
            $sql .= " and c.provider = " . $requestData['platform'] . " ";
        }

        if (!empty($requestData['campaign'])) {
            $sql .= " and ";
            $sql .= " c.campaign_id = " . $requestData['campaign'] . " ";
        }
        $sql .= " ORDER BY " .
                $columns[$requestData['order'][0]['column']] .
                "   " .
                $requestData['order'][0]['dir'] .
                "   LIMIT " .
                $requestData['start'] .
                " ," .
                $requestData['length'] .
                "   ";
        //echo $sql;
        $query = mysqli_query($conn, $sql) or die("server_members.php: get employees");
    }
} else {
    if (!empty($requestData['search']['value'])) {
        // if there is a search parameter
        $sql =
            "SELECT u.*, a.firstName, a.lastName, a.altName FROM Users u LEFT JOIN Addresses a ON u.user_id = a.user_id ";
        $sql .= " WHERE u.deleted=0 and (u.user_id LIKE '%" .
                $requestData['search']['value'] .
                "%' ";    // $requestData['search']['value'] contains search parameter
        $sql .= " OR u.email LIKE '%" . $requestData['search']['value'] . "%' ";
        $sql .= " OR u.full_name LIKE '%" . $requestData['search']['value'] . "%' ";
        $sql .= " OR u.first_name LIKE '%" . $requestData['search']['value'] . "%' ";
        $sql .= " OR u.last_name LIKE '%" . $requestData['search']['value'] . "%' ";
        $sql .= " OR a.firstName LIKE '%" . $requestData['search']['value'] . "%' ";
        $sql .= " OR a.lastName LIKE '%" . $requestData['search']['value'] . "%' ";
        $sql .= " OR a.altName LIKE '%" . $requestData['search']['value'] . "%' ";
        $sql .= " OR concat(a.firstName, ' ', a.lastName) LIKE '%" . $requestData['search']['value'] . "%' ";
        $sql .= " OR concat(u.first_name, ' ', u.last_name) LIKE '%" . $requestData['search']['value'] . "%' )";

        error_log("bw: $sql ");
        $query = mysqli_query($conn, $sql) or die("server_members.php: get employees - 1");
        $totalFiltered =
            mysqli_num_rows($query); // when there is a search parameter then we have to modify total number filtered rows as per search result without limit in the query
        $sql .= " ORDER BY u." .
                $columns[$requestData['order'][0]['column']] .
                "   " .
                $requestData['order'][0]['dir'] .
                "   LIMIT " .
                $requestData['start'] .
                " ," .
                $requestData['length'] .
                "   "; // $requestData['order'][0]['column'] contains colmun index, $requestData['order'][0]['dir'] contains order such as asc/desc , $requestData['start'] contains start row number ,$requestData['length'] contains limit length.
        $query = mysqli_query($conn, $sql) or
        die("server_members.php: get employees - 2"); // again run query with limit

    } else {
        $sql =
            "SELECT u.*, a.firstName, a.lastName, a.altName FROM Users u LEFT JOIN Addresses a ON u.user_id = a.user_id WHERE deleted=0 ";
        $sql .= " ORDER BY u." .
                $columns[$requestData['order'][0]['column']] .
                "   " .
                $requestData['order'][0]['dir'] .
                "   LIMIT " .
                $requestData['start'] .
                " ," .
                $requestData['length'] .
                "   ";
        //        echo $sql;
        $query = mysqli_query($conn, $sql) or die("server_members.php: get employees - 3");
    }
}

$data = [];
while ($row = mysqli_fetch_array($query)) {  // preparing an array
    $nestedData = [];
    $nestedData[] = $row["user_id"];

    $name = $row['full_name'];

    if (!empty($row['firstName'])) {
        $name = $row['firstName'];
        if (!empty($row['lastName'])) {
            $name .= ' ' . $row['lastName'];
        }
    }

    if (!empty($row['altName'])) {
        $name .= " (listed as " . $row['altName'] . ')';
    }

    $nestedData[] = $name;
    $nestedData[] = $row["email"];

    $data[] = $nestedData;
}
error_log("bw:" . $sql);

$json_data = [
    "draw" => intval($requestData['draw']),
    // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw.
    "recordsTotal" => intval($totalData),
    // total number of records
    "recordsFiltered" => intval($totalFiltered),
    // total number of records after searching, if there is no searching then totalFiltered = totalData
    "data" => $data
    // total data array
];

//utf8_encode_deep($json_data);
$x = json_encode($json_data, JSON_UNESCAPED_UNICODE);
return $json_data;  // send data as json format
