<?php
namespace App\Http\Controllers\Api;
use App\Exceptions\ErrorMessageException;
use App\Exceptions\MaintenanceException;
use App\Exceptions\NoTokenException;
use App\Extras\StatusCodes;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;

class ApiParentController extends Controller
{
	use AuthorizesRequests, DispatchesJobs, ValidatesRequests;


    // when admin clicks the maintenance mode btn, in this constructor the // maintenance will be replaced by the  maintenanceModeEnabled(), which is the below function
    // and it is responsible for disabling the APIs when its under maintenance
    public function __construct()
    {
       //maintenance
    }


    // ------------------------------------------------------------------------------------------------
    // this function calculates the remaining time until the maintenance is over and disables all APIs
    public function maintenanceModeEnabled(){

		throw new MaintenanceException();
    }


    // --------------------------------------------------------------------------------------------------------------------
    // this function gets the api_token (JWT token) from header which is used to authenticate user
	public static function authorizeToken($cols = [],$canEmpty = false,$justValidated = true) {
        $apiToken = request()->header(H_AUTHORIZATION); // Bearer <Token>

        $parts = explode(' ', $apiToken);
		if (sizeof($parts) == 2) {
			$token = trim($parts[1]);
		} else {
			$token = "";
		}

		if ($token == "") {
			if ($canEmpty) {
				return null;
			} else {
				throw new NoTokenException();
			}
		} else {
            // $justValidated is used to check if the logged in user signup step is finished or not, for most of the methods user signup step must be finished
            $cols2 = $cols;
            if($justValidated){
                $cols2[]= COL_CUSTOMER_SIGNUP_STEP;
            }
			$customer=Customer::validateApiToken($token, $cols2);

			if($customer!==null){
                if($justValidated && $customer[COL_CUSTOMER_SIGNUP_STEP] != ENUM_CUSTOMER_SIGNUP_STEP_FINISHED){
					throw new ErrorMessageException('ثبت نام شما کامل نشده است',StatusCodes::HTTP_UNAUTHORIZED);
                }
                if($cols == [] && $justValidated){
                    return $customer[COL_CUSTOMER_ID];
                }
                return $customer;

			}elseif ($canEmpty) {
				return null;
			} elseif (!$canEmpty) {
				throw new NoTokenException();
			}
		}
	}
}
