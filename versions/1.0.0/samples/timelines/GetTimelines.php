<?php
namespace timelines;

use com\zoho\api\authenticator\OAuthBuilder;
use com\zoho\crm\api\dc\USDataCenter;
use com\zoho\crm\api\InitializeBuilder;
use com\zoho\crm\api\timelines\APIException;
use com\zoho\crm\api\timelines\ResponseWrapper;
use com\zoho\crm\api\timelines\TimelinesOperations;
use com\zoho\crm\api\util\Choice;
use com\zoho\crm\api\ParameterMap;

require_once "vendor/autoload.php";

class GetTimelines 
{
	public static function initialize()
    {
        $environment = USDataCenter::PRODUCTION();
        $token = (new OAuthBuilder())
            ->clientId("client_id")
            ->clientSecret("client_secret")
            ->refreshToken("refresh_token")
            ->build();
        (new InitializeBuilder())
            ->environment($environment)
            ->token($token)
            ->initialize();
    }
	public static function getTimelines(string $module, string $recordId)
	{
		$timelinesoperations = new TimelinesOperations();
		$paramInstance = new ParameterMap();
		$response = $timelinesoperations->getTimelines($module, $recordId, $paramInstance);
		if ($response != null) {
            echo ("Status code " . $response->getStatusCode() . "\n");
            if (in_array($response->getStatusCode(), array(204, 304))) {
                echo ($response->getStatusCode() == 204 ? "No Content\n" : "Not Modified\n");
                return;
            }
            $responseHandler = $response->getObject();
			if ($responseHandler instanceof ResponseWrapper)
			{
				$responseWrapper = $responseHandler;
				$timelines = $responseWrapper->getTimeline();
				if ($timelines != null)
				{
					foreach ($timelines as $timeline)
					{
						$doneBy = $timeline->getDoneBy();
						if ($doneBy != null)
						{
							echo("DoneBy Name: " . $doneBy->getName() . "\n");
							echo("DoneBy Id: " . $doneBy->getId() . "\n");
						}
						$relatedRecord = $timeline->getRelatedRecord();
						if ($relatedRecord != null)
						{
							echo("RelatedRecord Id: " . $relatedRecord->getId() . "\n");
							echo("RelatedRecord Name: " . $relatedRecord->getName() . "\n");
							$module1 = $relatedRecord->getModule();
							echo("Module : ");
							if ($module1 != null)
							{
								echo("RelatedRecord Module Name: " . $module1->getName() . "\n");
								echo("RelatedRecord Module Id: " . $module1->getId() . "\n");
							}
						}
						$automationDetails = $timeline->getAutomationDetails();
						if ($automationDetails != null)
						{
							echo("automationdetails type: " . $automationDetails->getType() . "\n");
							$rule = $automationDetails->getRule();
							if ($rule != null)
							{
								echo("automationDetails Rule Name :" . $rule->getName() . "\n");
								echo("automationDetails Rule Id :" . $rule->getId() . "\n");
							}
							$pathfinder = $automationDetails->getPathfinder();
							if ($pathfinder != null)
							{
								echo("automationDetails Pathfinder ProcessEntry :" . $pathfinder->getProcessEntry() . "\n");
								echo("automationDetails Pathfinder ProcessExit :" . $pathfinder->getProcessExit() . "\n");
								$state = $pathfinder->getState();
								if($state != null)
								{
									echo("automationDetails Pathfinder State TriggerType:" . $state->getTriggerType() . "\n");
									echo("automationDetails Pathfinder State Name:" . $state->getName() . "\n");
									echo("automationDetails Pathfinder State IsLastState:" . $state->getIsLastState() . "\n");
									echo("automationDetails Pathfinder State Id:" . $state->getId() . "\n");
								}
							}
						}
						$record1 = $timeline->getRecord();
						if ($record1 != null)
						{
							echo("Record Id: ". $record1->getId() . "\n");
							echo("Record Name: ". $record1->getName() . "\n");
							$module2 = $record1->getModule();
							echo("Module : ");
							if ($module2 != null)
							{
								echo("Record Module Name: " . $module2->getAPIName() . "\n");
								echo("Record Module Id: " . $module2->getId() . "\n");
							}
						}
						echo("auditedTime : "); print_r($timeline->getAuditedTime()); echo("\n");
						echo("action : " . $timeline->getAction() . "\n");
						echo("Timeline Id: " . $timeline->getId() . "\n");
						echo("source: " . $timeline->getSource() . "\n");
						echo("extension: " . $timeline->getExtension() . "\n");
						echo("Type:: " . $timeline->getType() . "\n");
						$fieldHistory = $timeline->getFieldHistory();
						if ($fieldHistory != null)
						{
							foreach ($fieldHistory as $history)
							{
								echo("FieldHistory dataType: " . $history->getDataType() . "\n");
								echo("FieldHistory enableColourCode: " . $history->getEnableColourCode() . "\n");
								echo("FieldHistory fieldLabel: " . $history->getFieldLabel() . "\n");
								echo("FieldHistory apiName: " . $history->getAPIName() . "\n");
								echo("FieldHistory id: " . $history->getId() . "\n");
								$value = $history->getValue();
								if ($value != null)
								{
									echo("new :" . $value->getNew() . "\n");
									echo("old :" . $value->getOld() . "\n");
								}
								$pickListValues = $history->getPickListValues();
								if ($pickListValues != null)
								{
									foreach ($pickListValues as $pickListValue)
									{
										echo("picklistvalue DisplayValue : " . $pickListValue->getDisplayValue() . "\n");
										echo("picklistvalue sequenceNumber : " . $pickListValue->getSequenceNumber() . "\n");
										echo("picklistvalue colourCode : " . $pickListValue->getColourCode() . "\n");
										echo("picklistvalue actualValue : " . $pickListValue->getActualValue() . "\n");
										echo("picklistvalue id : " . $pickListValue->getId() . "\n");
										echo("picklistvalue type : " . $pickListValue->getType() . "\n");
									}
								}
							}
						}
					}
				}
			}
            else if ($responseHandler instanceof APIException) {
                $exception = $responseHandler;
                echo ("Status: " . $exception->getStatus()->getValue() . "\n");
                echo ("Code: " . $exception->getCode()->getValue() . "\n");
                echo ("Details: ");
                foreach ($exception->getDetails() as $key => $value) {
                    echo ($key . " : " . $value . "\n");
                }
                echo ("Message : " . ($exception->getMessage() instanceof Choice ? $exception->getMessage()->getValue() : $exception->getMessage()));
            }
		}
	}
}

$module = "leads";
$recordId = "347706118883002";
GetTimelines::initialize();
GetTimelines::getTimelines($module, $recordId);