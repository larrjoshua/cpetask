<?php    

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include the MysqliDb class
require_once('MysqliDb.php');

/*
 *	Tells the browser to allow code from any origin to access
 */
header("Access-Control-Allow-Origin: *");

/*
 *	Tells browsers whether to expose the response to the frontend JavaScript code.
 *	when the request's credentials mode (Request.credentials) is include
 */
header("Access-Control-Allow-Credentials: true");

/*
 *	Specifies one or more methods allowed when accessing a resource in response to a preflight request.
 */
header("Access-Control-Allow-Methods: POST, GET, PUT, DELETE");

/*
 *	Used in response to a preflight request which includes the Access-Control-Request-Headers to 
 *	indicate which HTTP headers can be used during the actual request.
 * */
header("Access-Control-Allow-Headers: Content-Type");

header('Content-Type: application/json');

class API {
    public $db;

    public function __construct(MysqliDb $db) {
		//$this->db = $db;
		$this->db = new MysqliDb('localhost', 'root', '', 'employee');
	}

    // HTTP GET Request
    /*
    *@param array $payload
    *@return array
    * */
    public function httpGet($payload) {
        // Ensure $payload is an array
        if (!is_array($payload)) {
            return $this->failedResponse('GET', 'failed', 'Invalid payload. It must be an array.');
        }

        // Check if 'id' or 'task_title' is provided in the payload
        if (isset($payload['id'])) {
            $data_id_filter = $this->db->where('id', $payload['id'])->getOne('tbl_to_do_list');
            if ($data_id_filter) {
                return $this->successResponse('GET', 'success', $data_id_filter);
            } else {
                return $this->failedResponse('GET', 'failed', 'Unexpected result for data_name_filter');
            }
        }elseif (isset($payload['task_title'])) {
            // Use the 'equal' condition to search for the specified task_title
            $search_name = $payload['task_title'];
            $this->db->where('task_title', $search_name);
            
            // Execute the query and get the result
            $data_name_filter = $this->db->get('tbl_to_do_list');
            
            // Check if data_name_filter is false
            if ($data_name_filter === false) {
                return $this->failedResponse('GET', 'failed', 'Failed to Retrieve Data');
            }
            
            // Check if data_name_filter is an array
            if (is_array($data_name_filter)) {
                return $this->successResponse('GET', 'success', $data_name_filter);
            } else {
                return $this->failedResponse('GET', 'failed', 'Failed to Retrieve Data');
            }
        }
        elseif (empty($payload)) {
            // handles the query if the payload is empty
            $data_no_filter = $this->db->get('tbl_to_do_list');
            // check result
            if ($data_no_filter) {
                return $this->successResponse('GET', 'success', $data_no_filter);
            } else {
                return $this->failedResponse('GET', 'failed', 'Failed to Retrieve Data');
            }
        }
    }

    // HTTP POST Request
    /*
    *@param array $payload
    *@return bool
    * */
    public function httpPost($payload): string {
        // Decode payload if it's a string (assuming $payload is JSON string)
        if (is_string($payload)) {
            $payload = json_decode($payload, true);
        }
    
        // Required parameters based on API documentation
        $requiredKeys = ['task_title', 'task_name'];
    
        // Check if all required keys are present in the payload
        $missingKeys = array_diff($requiredKeys, array_keys($payload));
        if (!empty($missingKeys)) {
            // If any required fields are missing, return a failed response
            return json_encode(['status' => 'failed', 'method' => 'POST']);
        }
    
        // Execute a query to insert data into the database
        $query = $this->db->insert('tbl_to_do_list', $payload);
    
        if ($query) {
            // If data is successfully inserted, return success response with inserted ID
            $insertedId = $this->db->getInsertId();
            return json_encode(['status' => 'success', 'method' => 'POST', 'message' => $insertedId]);
        } else {
            // If insertion fails, return failure response
            return json_encode(['status' => 'failed', 'method' => 'POST', 'message' => 'Failed to Insert Data']);
        }
    }
    
    /*
    * Update a task in the database based on its ID
    *
    * @param int $id The ID of the task to update
    * @param array $payload The data to update the task with
    * @return string JSON-encoded response indicating success or failure
    */
    public function httpPut($payload): string {
        // Extract ID from payload and validate it
        if (isset($payload['id']) && is_numeric($payload['id'])) {
            $id = $payload['id'];
            unset($payload['id']); // Remove the ID from the payload to avoid attempting to update it in the database

            // Specify the task to update using its ID
            $this->db->where('id', $id);

            // Execute the update operation with the provided payload
            if ($this->db->update('tbl_to_do_list', $payload)) {
                // Check if any rows were actually updated
                if ($this->db->count > 0) {
                    // Fetch the updated data from the database to return it
                    $updatedData = $this->db->where('id', $id)->getOne('tbl_to_do_list');
                    return $this->successResponse('PUT', 'success', $updatedData);
                } else {
                    // No rows updated, possibly because the data was the same
                    return $this->failedResponse('PUT', 'failed', 'No changes made or task not found');
                }
            } else {
                // If there's an error during the update operation, return a failed response
                return $this->failedResponse('PUT', 'failed', $this->db->getLastError());
            }
        } else {
            // If ID is invalid or missing
            return $this->failedResponse('PUT', 'failed', 'Invalid or missing ID in payload');
        }
    }
    

    // HTTP DELETE Request
        /*
    *@param $id
    *@param $payload
    *@return bool
    * */
    // Updated httpDelete function
    public function httpDelete($payload): string {
    if (empty($payload)) {
        return json_encode([
            'method' => 'DELETE',
            'status' => 'failed',
            'message' => 'Empty payload. Please provide criteria for deletion.'
        ]);
    }

    $deleteConditionsSet = false;

    // Loop through the payload and apply each as a condition for deletion.
    foreach ($payload as $key => $value) {
        $this->db->where($key, $value);
        $deleteConditionsSet = true;
    }

    if (!$deleteConditionsSet) {
        return json_encode([
            'method' => 'DELETE',
            'status' => 'failed',
            'message' => 'No valid criteria found in payload for deletion.'
        ]);
    }

    $result = $this->db->delete('tbl_to_do_list');
    $deletedCount = $this->db->count; // Assuming 'count' reflects the number of affected rows

    // Change here: Always return 'success', even if $deletedCount is 0
    return json_encode([
        'method' => 'DELETE',
        'status' => 'success',
        'message' => "Successfully deleted $deletedCount records."
    ]);
}

    /*
    *
    *@param string $method 
    *@param sting $status
    *@param string $message
    *@return array
    * */
        public function failedResponse($method, $status, $message): string {
            return json_encode([
                'method' => $method,
                'status' => $status,
                'message' => $message,
            ]);
        }
    /*
    *@param mixed $data
    *@param string $method
    *@param string $status
    *@return array
    * */
        public function successResponse($method, $status, $data): string {
            return json_encode([
                'method' => $method,
                'status' => $status,
                'message'=> $data,
            ]);
            // $response = [
            // 	'method' => $method,
            // 	'status' => $status,
            // 	'message' => $data,
            // 	];
        
            // 	echo json_encode($response);
            // 	exit;
        }
}

//Identifier if what type of request
$request_method = isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD']: null;

// Combine data from GET and POST
$received_data = $_REQUEST;

if ($request_method === 'PUT' || $request_method === 'DELETE') {
	$request_uri = $_SERVER['REQUEST_URI'];
	$exploded_request_uri = array_values(explode("/", $request_uri));

		// Use the last part of the URL as the ID
		$last_index = count($exploded_request_uri) - 1;
		$ids = $exploded_request_uri[$last_index];
		$received_data['id'] = $ids;
	}

    if ($request_method === 'POST' || $request_method === 'PUT' || $request_method === 'DELETE') {
        $received_data = json_decode(file_get_contents('php://input'), true);
        if (!is_array($received_data)) {
            echo json_encode(['error' => 'Invalid JSON']);
            exit;
        }
    }
    
$db = new MysqliDb('localhost', 'root', '', 'employee');
$api = new API($db);

// Checking if what type of request and designating to specific functions

switch ($request_method) {
    case 'GET':
        echo $api->httpGet($received_data); 
        break;
    case 'POST':
        echo $api->httpPost($received_data); 
        break;
    case 'PUT':
        echo $api->httpPut($received_data); 
        break;
    case 'DELETE':
        // This is where you place the provided block for DELETE request handling
        $received_data = json_decode(file_get_contents('php://input'), true);
        if (!is_array($received_data)) {
            echo json_encode(['error' => 'Invalid JSON']);
            exit;
        }
        echo $api->httpDelete($received_data);
        break;
    default:
        // Optionally handle other request types or invalid requests
        echo json_encode(['message' => 'Request method not supported']);
        break;
}