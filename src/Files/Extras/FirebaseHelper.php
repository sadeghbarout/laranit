<?php


namespace App\Extras;


use App\Exceptions\ErrorMessageException;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

// composer require google/apiclient --with-all-dependencies
class FirebaseHelper {

//				TODO : this section is specific for each project
    protected $useDispatch = false;
    protected $colToken = COL_USER_FIREBASE_TOKEN;
    protected $userModel = User::class;
    protected $colOs = COL_USER_OS;
    protected $topicNameAll = 'Chat plus';
    protected $topicName = [
        "android" => "android_chatplus_",
    ];

    protected $flavor = ''; // bazaar or play

    protected static $projectName = 'chat-plus-ca374';
    // -----------------------------------------------------------------------------------------------------------------
    protected $sendToTopic = false;
    protected $basedOnOs = false;
    protected $tokens = [
        "android" => [],
        "ios" => [],
        "pwa" => [],
    ];
    protected $userIds = [];
    protected $message, $title, $url, $data;


    /**
     * FirebaseHelper constructor.
     *
     * @param        $message
     * @param        $title
     * @param        $user  token or [tokens] or User model or [user models] or "*"
     * @param string $url
     * @param null   $data
     * @param bool   $basedOnOs
     */
    public function __construct($message, $title, $users, $url="", $data=null, $basedOnOs = true) {
        $this->title = $title;
        $this->message = $message;
        $this->url = $url;
        $this->data = $data;
        $this->basedOnOs = $basedOnOs;

        $this->normalizeUsers($users);
    }

    public function setFlavor($flavor) {
        $this->flavor = $flavor;
        return $this;
    }

    public function saveToDatabase($type=ENUM_NOTIFICATION_TYPE_TEXT) {

        $notifs = array();
        if ($this->data == null) {
            $notifUrl = $this->url;
        } else {
            $notifUrl = $this->url . "?" . http_build_query($this->data);
        }

        if(is_array($this->data))
            $strData=json_encode($this->data);
        else
            $strData=$this->data;


        foreach ($this->userIds AS $userId) {
            $notifs[] = array(
//				TODO : this section is specific for each project

                COL_NOTIFICATION_TYPE=> $type,
                COL_NOTIFICATION_DATA=> $strData,

                // ----------------------------------------- basic
                COL_NOTIFICATION_CUSTOMER_ID => $userId,
                COL_NOTIFICATION_TITLE => $this->title,
                COL_NOTIFICATION_MESSAGE => $this->message,
                COL_NOTIFICATION_URL => $notifUrl,
                COL_NOTIFICATION_DATE=> getServerDateTime(),
            );
        }

        Notification::insert($notifs);

        $this->url=str_replace(APP_LINK_PRE,"",$this->url);

        return $this;
    }


    public function send() {
        $ans='';
        if (sizeof($this->tokens["android"]) > 0 || ($this->sendToTopic && isset($this->topicName['android'])) )
            $ans = $this->sendPushNotificationAndroid();
        if (sizeof($this->tokens["ios"]) > 0 || ($this->sendToTopic && isset($this->topicName['ios'])))
            $ans = $this->sendPushNotificationIos();
        if (sizeof($this->tokens["pwa"]) > 0 ||($this->sendToTopic && isset($this->topicName['pwa'])))
            $ans = $this->sendPushNotificationPWA();

        return $ans;
    }


    private function normalizeUsers($users) {
        if (is_string($users) && $users == "*") {
            $this->sendToTopic = true;
            $this->userIds = [-1];

        } elseif ($users instanceof Collection) {
            $this->separateTokens($users);

        } elseif (is_array($users)) {

            if (sizeof($users) > 0) {
                if (strlen($users[0]) > 10) { // firebase token
                    if ($this->basedOnOs)
                        throw new ErrorMessageException("Users can not be tokens", StatusCodes::HTTP_CONFLICT);

                    $this->tokens["android"] = $users;
                } else { // user ids
                    if ($this->basedOnOs)
                        $usersCollection = $this->userModel::whereIn('id', $users)->get(['id',$this->colToken, $this->colOs]);
                    else
                        $usersCollection = $this->userModel::whereIn('id', $users)->get(['id',$this->colToken]);

                    $this->separateTokens($usersCollection);
                }
            }
        } else {
            if (is_integer($users) || is_string($users))
                $this->normalizeUsers([$users]);
            elseif ($users instanceof $this->userModel)
                $this->normalizeUsers(collect([$users]));
            else
                throw new ErrorMessageException("Incorrect users !", StatusCodes::HTTP_CONFLICT);
        }
    }


    private function separateTokens($usersCollection) {
        $this->userIds = $usersCollection->pluck('id')->all();

        if ($this->basedOnOs) {
            $this->tokens["android"] = $usersCollection->where($this->colOs, 'android')->pluck($this->colToken)->all();
            $this->tokens["ios"] = $usersCollection->where($this->colOs, 'ios')->pluck($this->colToken)->all();
            $this->tokens["pwa"] = $usersCollection->where($this->colOs, 'pwa')->pluck($this->colToken)->all();
        } else {
            $this->tokens["android"] = $usersCollection->pluck($this->colToken)->all();
        }
    }



    //-------------------------------------------------------------------------------------------------

//	private function sendAllPlatforms() {
//
//		$notification = $this->getNotificationAndData();
//
////		$this->getTokens();
//
//
//		$this->data['notification'] = json_encode($notification);
//		$fields['message']['data'] =$this->data;
////		$fields['message']['notification'] =$notification;
//
//		if ($this->sendToTopic) {
//
//			$fields['message']['topic'] =$this->topicNameAll;
//		} else {
//			$fields['message']['token'] =$this->tokens["android"][0];
//		}
//
//		if ($this->useDispatch)
//			sendFirebase::dispatch($fields);
//		else
//			return self::sendFirebase($fields);
//	}

    //-------------------------------------------------------------------------------------------------
    // data example : $data= ["message" => $notification, "moredata" => 'dd']
    private function sendPushNotificationAndroid() {
        $notification = $this->getNotificationAndData();

        $notification['click_action']=$this->url;

        $this->data['notification'] = json_encode($notification);
        $fields['message']['data'] =$this->data;

        if ($this->sendToTopic) {
            $fields['message']['topic'] =$this->topicName["android"] . $this->flavor;


        } else {
            $fields['message']['token'] =$this->tokens["android"][0]; // todo:
        }

        if ($this->useDispatch)
            sendFirebase::dispatch($fields);
        else
            return static::sendFirebase($fields);
    }



    //-------------------------------------------------------------------------------------------------
    // data example : $data= ["message" => $notification, "moredata" => 'dd']
    private function sendPushNotificationIos() {

        $notification = $this->getNotificationAndData();

        $this->data['click_action']=$this->url;

        $fields['message']['data'] =$this->data;
        $fields['message']['notification'] =$notification;

        if ($this->sendToTopic) {
            $fields['message']['topic'] =$this->topicName["ios"] . $this->flavor;

        } else {
            $fields['message']['token'] =$this->tokens["ios"][0]; // todo:
        }

        if ($this->useDispatch)
            sendFirebase::dispatch($fields);
        else
            return self::sendFirebase($fields);
    }

    //-------------------------------------------------------------------------------------------------
    // data example : $data= ["message" => $notification, "moredata" => 'dd']
    private function sendPushNotificationPWA() {

        $notification = $this->getNotificationAndData();

        $this->data['click_action']=$this->url;

        $fields['message']['data'] =$this->data;
        $fields['message']['notification'] =$notification;

        if ($this->sendToTopic) {
            $fields['message']['topic'] =$this->topicName["pwa"] . $this->flavor;

        } else {
            $fields['message']['token'] =$this->tokens["pwa"][0]; // todo:
        }


        if ($this->useDispatch)
            sendFirebase::dispatch($fields);
        else
            return self::sendFirebase($fields);
    }


    private function getNotificationAndData() {
        if (!$this->data) {
            $this->data['a'] = 'a';
        }
        $notification = [
            'title' => $this->title,
            'body' => $this->message,
        ];

        return $notification;
    }


    // -----------------------------------------------------------------------------------------------------------------
    public static function sendFirebase($fields) {

        $headers = [
            'Authorization: Bearer ' . static::getGoogleAccessToken(),
            'Content-Type: application/json'
        ];

        #Send Reponse To FireBase Server
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/v1/projects/'.self::$projectName.'/messages:send');
//		curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        $result = curl_exec($ch);
        curl_close($ch);

        return $result;
    }


    private static function getGoogleAccessToken(){

//		$content=null;
//        $content=Cache::get('firebase-adminsdk-confs');
//        if($content==null){
//            $content=S3Helper::getBucketObjectContent(PATH_SECRETS,'twise-main-project-firebase-adminsdk-92ykg-9546d4e539.json');
//            Cache::put('firebase-adminsdk-confs',$content);
//        }


        $content = file_get_contents(storage_path('google_notification_credential_service.json'));

//		$path=storage_path('app/twise-app-firebase-adminsdk-vnc1s-eba0d0f696.json');
//		$config = file_get_contents($path);
        $config = json_decode($content, true);

        $client = new \Google_Client();
        $client->setAuthConfig($config); // or $path

        $redirect_uri = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
        $client->setRedirectUri($redirect_uri);

        $client->addScope('https://www.googleapis.com/auth/firebase.messaging');
        $client->refreshTokenWithAssertion();
        $token = $client->getAccessToken();
        return $token['access_token'];
    }
}
