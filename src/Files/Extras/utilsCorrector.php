<?php

define("U_MILADI_TO_HEJRI", "1");
define("U_HEJRI_TO_MILADI", "2");
define("U_DATE", "3");
define("U_TIME_DIFF", "4");
define("U_SHIFT_SECOND", "5");
define("U_EXTENTION", "6");
define("U_REMOVE_LAST_CHAR", "7");
define("U_MULTILINE_TEXT", "8");
define("U_SUB_TEXT", "9");
define("U_PUBLISH_DATE", "10");
define("U_NUMBER", "13");
define("U_FILE_NAME", "14");
define("U_MILADI_TO_HEJRI_TEXT", "15");
define("U_TWO_DIGIT_DATE", "16");
define("U_YES_NO", "yesNo");

define("U_USER_STATUSES", "userStatuses");
define("U_USER_STATUS_COLOR", "userStatusColor");

function UC($input1, $typeName, $input2 = 0) {

	switch ($typeName) {

        // converting miladi date to hejri
		case U_MILADI_TO_HEJRI:  //-------------------------------------------------------------------------------

			try {
				$inputDate = $input1;
				$endOfDay = $input2;
				$inputDate = preg_replace('!\s+!', ' ', $inputDate);

				if ($inputDate != "0000-00-00 00:00:00" && $inputDate != "0000-00-00" && $inputDate != "" && $inputDate != null && $inputDate > 0) {
					$dateTime = explode(" ", $inputDate); // seprating date and time
					$date = $dateTime[0];
					$time = $endOfDay == 0 ? "" : "23:59:59";
					if (count($dateTime) > 1) { // check $inputDate contains time or not
						$time = $dateTime[1];
					}

					$dateParts=explode('-',$date);
					$date = gregorian_to_jalali($dateParts[0], $dateParts[1], $dateParts[2], '/');
                    $date = UC($date, U_TWO_DIGIT_DATE);

					return trim($date . " " . $time);
				} else {
					return "0000-00-00 00:00:00";
				}
			} catch (\Throwable $e) {
				throw new \App\Exceptions\ErrorMessageException("فرمت تاریخ صحیح نمی باشد.", \App\Extras\StatusCodes::HTTP_NOT_ACCEPTABLE);
			}
            break;

        // converting hejri date to miladi
		case U_HEJRI_TO_MILADI: //-------------------------------------------------------------------------------
			try {
				$inputDate = $input1;
				$endOfDay = $input2;
				$inputDate = preg_replace('!\s+!', ' ', $inputDate);

				if ($inputDate != "0000/00/00 00:00:00" && $inputDate != "0000/00/00" && $inputDate != "" && $inputDate != null) {
					$dateTime = explode(" ", $inputDate); // seprating date and time
					$date = $dateTime[0];
					$time = $endOfDay == 0 ? "" : "23:59:59";
					if (count($dateTime) > 1) { // check $inputDate contains time or not
						$time = $dateTime[1];
					}
					$dateParts=explode('/',$date);
					$date = jalali_to_gregorian($dateParts[0], $dateParts[1], $dateParts[2], '-');
					$date = DateTime::createFromFormat("Y-m-d", $date) -> format('Y-m-d');

					return trim($date . " " . $time);
				} else {
					return "0000-00-00 00:00:00";
				}
			} catch (\Throwable $e) {
				throw new \App\Exceptions\ErrorMessageException("فرمت تاریخ صحیح نمی باشد.", \App\Extras\StatusCodes::HTTP_NOT_ACCEPTABLE);
			}
			break;

        // extracting just the date (Y m d) from a datetime (Y m d H:i:s)
		case U_DATE: //-------------------------------------------------------------------------------

			if (substr(date("Y", strtotime($input1)), 0, 2) < 16) { // hejri
				return date("Y/m/d", strtotime($input1));
			} else { // miladi
				return date("Y-m-d", strtotime($input1));
			}

			break;

        // difference between two dates in seconds
		case U_TIME_DIFF: //-------------------------------------------------------------------------------

			if ($input2 == null) {
				return time() - strtotime($input1);
			} else {
				return strtotime($input2) - strtotime($input1);
			}

			break;

        // shifting datetime either formward (+) or backward (-) in seconds
        // i.e : 24*60*60 means 1 day forward  and  -24*60*60 means 1 day backward
		case U_SHIFT_SECOND: //-------------------------------------------------------------------------------

			$time = strtotime("$input2 second", strtotime($input1));
			return date("Y-m-d H:i:s", $time);

            break;

        // get the extension of a file
		case U_EXTENTION: //-------------------------------------------------------------------------------

			$ext = pathinfo($input1, PATHINFO_EXTENSION);
			return $ext;

			break;

        //
		case U_REMOVE_LAST_CHAR: //-------------------------------------------------------------------------------

			return substr($input1, 0, strlen($input1) - 1);
			break;


		case U_MULTILINE_TEXT: //-------------------------------------------------------------------------------

			$input1 = str_replace('\r\n', '<br>', $input1);
			$input1 = str_replace('\n', '<br>', $input1);
			return $input1;
			break;


		case U_SUB_TEXT: //-------------------------------------------------------------------------------
			$numOfLetters = $input2;
			if (strlen($input1) > $numOfLetters) {
				$stringCut = substr($input1, 0, $numOfLetters);
				$final1 = substr($stringCut, 0, strrpos($stringCut, ' ')) . ' ... ';
				$final2 = substr($stringCut, 0, strrpos($stringCut, '-')) . ' ... ';

				if (strlen($final1) > strlen($final2)) {
					$input1 = $final1;
				} else {
					$input1 = $final2;
				}
			}
			return $input1;


        // returns a date like : 2days ago, 5 years ago ,....
		case U_PUBLISH_DATE: //-------------------------------------------------------------------------------

			if ($input1 == "0000-00-00 00:00:00") {
				return "";
			}


			$diffSec = time() - strtotime($input1);

			if ($diffSec < 60) {
				return "few moments ago";
			} else if ($diffSec < 60 * 60) {
				return (int)($diffSec / 60) . " minutes ago";
			} else if ($diffSec < 60 * 60 * 24) {
				return (int)($diffSec / (60 * 60)) . " hours ago";

			} else if ($diffSec < 60 * 60 * 24 * 7) {
				return (int)($diffSec / (60 * 60 * 24)) . "days ago";

			} else if ($diffSec < 60 * 60 * 24 * 7 * 4) {
				return (int)($diffSec / (60 * 60 * 24 * 7)) . " weeks ago";

			} else if ($diffSec < 60 * 60 * 24 * 7 * 4 * 12) {
				return (int)($diffSec / (60 * 60 * 24 * 7 * 4)) . " months ago";

			} else {
				return (int)($diffSec / (60 * 60 * 24 * 7 * 4 * 12)) . " years ago";

			}

			break;


		case U_NUMBER:
			return number_format($input1);
			break;


        // returns a file name
		case U_FILE_NAME:
			$parts = explode('/', $input1);
			return $parts[sizeof($parts) - 1];
			break;



        // returns a date like : شنبه 29 آذر 1399
        case U_MILADI_TO_HEJRI_TEXT:
            $endOfDay = $input2;
            $dateTime = explode(" ", $input1); // seprating date and time
            $date = $dateTime[0];
            $time = $endOfDay == 0 ? "" : "23:59";
            if (count($dateTime) > 1) { // check $inputDate contains time or not
                $timeParts = explode(":",$dateTime[1]);
                $time = $timeParts[0].":".$timeParts[1];
            }
            $dateText =  jdate('j F Y',strtotime($input1));
            return trim($dateText . " " . $time);
            return $dateText;
			break;


        case U_TWO_DIGIT_DATE:
            $dateParts = explode("/", $input1);
            $dateParts[1] = strlen($dateParts[1]) == 1 ? '0'.$dateParts[1] : $dateParts[1];
            $dateParts[2] = strlen($dateParts[2]) == 1 ? '0'.$dateParts[2] : $dateParts[2];

            return $dateParts[0].'/'.$dateParts[1].'/'.$dateParts[2];
			break;


		default:
            // returns the pre defined constant in resources/files/utlis.json according to the input
			$file = file_get_contents(resource_path('files/utils.json'));
			$datas = json_decode($file, true);
			if (isset($datas[$typeName])) {
				if (isset($datas[$typeName][$input1])) {
					return $datas[$typeName][$input1];
				} else {
					return '';

				}
			} else {
				return '';

			}

			break;
	}
}
