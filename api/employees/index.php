<?php

include_once "../helper.php";

function endpoint(string $match, string $method, $callback) {
    #run the callback if the request matches the method and regex
    if($_SERVER['REQUEST_METHOD'] !== $method) return;
    $uri = $_SERVER['REQUEST_URI'];
    //remove parts of the URI that do not need to match
    $uri = explode("?", $uri)[0];
    $uri = trim($uri, '/');
    
    if(!preg_match($match, $uri, $params)) return;

    $body = file_get_contents('php://input');
    if($body){
        $body = json_decode($body, true);
    } else {
        $body = [];
    }

    $body["url_params"] = $params;

    print json_encode($callback($body));

    exit();
}

function get_employee($employee_id){
    if(!file_exists("../../data/$employee_id")){
        error(404, "Employee not found");
    }
    $employee = file_get_contents("../../data/$employee_id");
    $employee = explode("\n", $employee);
    if(count($employee) < 3){
        error(500, "Employee data is corrupted (employee ID: $employee_id)");
    }
    return [
        "id" => $employee_id,
        "name" => $employee[0],
        "email" => $employee[1],
        "position" => $employee[2]
    ];
}

#create a new employee
#takes params name, email, and position

endpoint("/^employees$/", "POST", function($params){
    #get params and remove newlines (to prevent fle corruption since the format uses new lines for data)
    if(empty($params) || empty(@$params["name"])){
        error(400, "Name is required");
    }
    $name = str_replace("\n", " ", $params["name"]);

    if(empty(@$params["email"])){
        error(400, "Email is required");
    }
    if(!filter_var(@$params["email"], FILTER_VALIDATE_EMAIL)){
        error(400, "Email is invalid");
    }
    $email = str_replace("\n", " ", $params["email"]);

    if(empty(@$params["position"])){
        error(400, "Position is required");
    }
    $position = str_replace("\n", " ", $params["position"]);

    #save the employee to a file
    $employee_id = false;
    while(!$employee_id){
        $employee_id = mt_rand(100000, 999999);
        if(file_exists("../../data/$employee_id")){
            $employee_id = false;
        }
    }

    file_put_contents("../../data/$employee_id", "$name\n$email\n$position");

    return get_employee($employee_id);
});

#get all employees
endpoint("/^employees$/", "GET", function($_){
    $employees = [];
    #get all files in the data directory
    $files = glob("../../data/*");
    if($files === false){
        error(500, "Failed to read employee data");
    }
    if(empty($files)){
        error(404, "No employees found");
    }
    foreach($files as $file){
        $employee_id = basename($file);
        $employees[] = get_employee($employee_id);
    }
    return $employees;
});

#get one employee
endpoint("/^employees\/(\d{6})$/", "GET", function($params){
    $employee_id = $params["url_params"][1];
    if(empty($employee_id)){
        error(400, "Employee ID is required");
    }
    return get_employee($employee_id);
});

#update an employee
endpoint("/^employees\/(\d{6})$/", "PUT", function($params){
    $employee_id = $params["url_params"][1];
    if(empty($employee_id)){
        error(400, "Employee ID is required");
    }
    $existing_data = get_employee($employee_id);
    if(@$params["name"]){
        $existing_data["name"] = str_replace("\n", " ", $params["name"]);
    }
    if(@$params["email"]){
        if(!filter_var($params["email"], FILTER_VALIDATE_EMAIL)){
            error(400, "Email is invalid");
        }
        $existing_data["email"] = str_replace("\n", " ", $params["email"]);
    }
    if(@$params["position"]){
        $existing_data["position"] = str_replace("\n", " ", $params["position"]);
    }
    if(!is_writable("../../data/$employee_id")){
        error(500, "Failed to update employee data");
    }
    file_put_contents("../../data/$employee_id", $existing_data["name"] . "\n" . $existing_data["email"] . "\n" . $existing_data["position"]);
    return get_employee($employee_id);
});

#delete an employee
endpoint("/^employees\/(\d{6})$/", "DELETE", function($params){
    $employee_id = $params["url_params"][1];
    if(empty($employee_id)){
        error(400, "Employee ID is required");
    }
    if(!file_exists("../../data/$employee_id")){
        error(404, "Employee not found");
    }
    unlink("../../data/$employee_id");
    header("HTTP/1.1 204 No Content");
    exit();
});

error(404, "Endpoint not found");