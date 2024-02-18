<?php
    
namespace Tests\Api;

use Tests\Support\ApiTester;

class FailTestTodoListCest
{
    public function _before(ApiTester $I)
    {
        // This method is executed before each test method
    }
    
    // Test to ensure that the API returns a failure response when inserting data with missing fields
    public function iShouldFailToInsertDataDueToMissingFields(ApiTester $I)
    {
        $I->haveHttpHeader('Content-Type', 'application/json');
        $payload = ['task_title' => 'Incomplete Task'];
        $I->sendPost('/website/FirstAPI.php', json_encode($payload));
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['status' => 'failed', 'method' => 'POST']);
    }

    // Test to ensure that the API returns a failure response when trying to retrieve a task with an invalid ID
    public function iShouldFailToRetrieveSpecificTaskDueToInvalidId(ApiTester $I)
    {
        $I->sendGet('/website/FirstAPI.php?id=nonexistent');
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['status' => 'failed']);
    }

    // Test to ensure that the API returns a failure response when trying to update a nonexistent task to "Done"
    public function iShouldFailToUpdateTaskToDoneForNonexistentTask(ApiTester $I)
    {
        $I->haveHttpHeader('Content-Type', 'application/json');
        $requestData = [
            'id' => 9999, // Nonexistent Task ID
            'status' => 'Done', // Attempt to update status to Done
        ];
        $I->sendPut('/website/FirstAPI.php', json_encode($requestData));
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['status' => 'failed', 'method' => 'PUT']);
    }

    // Test to ensure that the API returns a failure response when trying to move a nonexistent task back to "In Progress"
    public function iShouldFailToMoveTaskBackToInProgressForNonexistentTask(ApiTester $I)
    {
        $I->haveHttpHeader('Content-Type', 'application/json');
        $requestData = [
            'id' => 9999, // Nonexistent Task ID
            'status' => 'Inprogress', // Attempt to move status back to In Progress
        ];
        $I->sendPut('/website/FirstAPI.php', json_encode($requestData));
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['status' => 'failed', 'method' => 'PUT']);
    }
    
    // Test to ensure that the API returns a failure response when trying to update a nonexistent task
    public function iShouldFailToUpdateNonexistentTask(ApiTester $I)
    {
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPut('/website/FirstAPI.php?id=999', ['task_title' => 'Nonexistent Task Title', 'task_name' => 'Trying to update a task that does not exist']);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['status' => 'failed']);
    }

    // Test to ensure that the API returns a success response with no records deleted when trying to delete a nonexistent task
    public function iShouldFailToDeleteNonexistentTask(ApiTester $I)
    {
        $I->haveHttpHeader('Content-Type', 'application/json');
        $payload = ['id' => 999];
        $I->sendDelete('/website/FirstAPI.php', $payload);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['status' => 'success', 'message' => 'Successfully deleted 0 records.']);
    }
}
