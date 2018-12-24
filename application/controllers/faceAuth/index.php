<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Index extends CI_Controller {
    private $viewdata = array();

     public function __construct()
     {
         parent::__construct();
     }

	/**
	 * メイン処理
	 */
	public function index()
	{
	    //顔検出
	    //$this->viewdata['detect'] = $this->_detect();

	    //パーソングループ取得　⇒　グループが作成できる。グループ作成が完了したらい、一旦コメントアウト
	    //$this->viewdata['personGroup'] = $this->_personGroup();

	    //パーソングループパーソン取得　⇒　personIdができる取得
	    //$this->viewdata['personGroupPerson'] = $this->_personGroupPerson();

	    //パーソングループパーソンに顔画像追加　⇒　persistedFaceIdが取得できる
	    //$this->viewdata['personGroupPersonAdd'] = $this->_personGroupPersonAdd();

	    //パーソングループパーソンをトレーニング　⇒　identifyで使用できるに状態にする
	    //$this->viewdata['personGroupTrain'] = $this->_personGroupTrain();

        //トレーニング結果のステータス取得
        //$this->viewdata['TrainStatus'] = $this->_personGroupTrainStatus();

        //パーソングループパーソンと画像の検証
        $this->viewdata['Identify'] = $this->_Identify();

		$this->load->view('faceAuth/face',$this->viewdata);
	}

	/**
     * 顔検出からのJson形式のデータを検出
     */
	private function _detect()
	{
	    // サブスクリプションキー入力
        $ocpApimSubscriptionKey = '6a6971151de442f2aa082672658c5fb9';

        // APIのURL
        $uriBase = 'https://japaneast.api.cognitive.microsoft.com/face/v1.0';

        //画像URL
        $imageUrl =
            'https://muraseface.blob.core.windows.net/container1/ベッカム2.jpg';

        //
        require_once 'HTTP/Request2.php';

        //method指定
        $request = new Http_Request2($uriBase . '/detect');
        $url = $request->getUrl();

        $headers = array(
            // Request headers
            'Content-Type' => 'application/json',
            'Ocp-Apim-Subscription-Key' => $ocpApimSubscriptionKey
        );
        $request->setHeader($headers);

        $parameters = array(
            // Request parameters
            'returnFaceId' => 'true',
            'returnFaceLandmarks' => 'false',
            'returnFaceAttributes' => 'age,gender,headPose,smile,facialHair,glasses,' .
                'emotion,hair,makeup,occlusion,accessories,blur,exposure,noise');
        $url->setQueryVariables($parameters);

        $request->setMethod(HTTP_Request2::METHOD_POST);

        // Request body parameters
        $body = json_encode(array('url' => $imageUrl));

        // Request body
        $request->setBody($body);

        try
        {
            $response = $request->send();
            return json_encode(json_decode($response->getBody()), JSON_PRETTY_PRINT);
        }
        catch (HttpException $ex)
        {
            return $ex;
        }
	}

	/**
     * パーソングループを作成
     */
     private function _personGroup()
     {

	    // サブスクリプションキー入力
        $ocpApimSubscriptionKey = '6a6971151de442f2aa082672658c5fb9';

        // APIのURL
        $uriBase = 'https://japaneast.api.cognitive.microsoft.com/face/v1.0';

        //
        require_once 'HTTP/Request2.php';

        //パーソングループ名指定
        $personGroupID = 'murasetest';

        //method指定
        $request = new Http_Request2($uriBase . '/persongroups/' . $personGroupID);
        $url = $request->getUrl();

        $headers = array(
            // Request headers
            'Content-Type' => 'application/json',
            'Ocp-Apim-Subscription-Key' => $ocpApimSubscriptionKey
        );
        $request->setHeader($headers);

        $parameters = array(
            // Request parameters
            //'returnFaceId' => 'true',
            //'returnFaceLandmarks' => 'false',
            'returnFaceAttributes' => 'age,gender,headPose,smile,facialHair,glasses,' .
                'emotion,hair,makeup,occlusion,accessories,blur,exposure,noise');

        $url->setQueryVariables($parameters);

        $request->setMethod(HTTP_Request2::METHOD_PUT);

        // Request body parameters
        $personGroupName = 'personGroupName';
        $userData = 'murase is man';
        $body = json_encode(array('name' => $personGroupName, 'userData' => $userData));

        // Request body
        $request->setBody($body);
        try
        {
            $response = $request->send();
            return $response->getBody();
        }
        catch (HttpException $ex)
        {
            return $ex;
        }
     }

	/**
     * パーソングループパーソンを作成
     */
     private function _personGroupPerson()
     {

	    // サブスクリプションキー入力
        $ocpApimSubscriptionKey = '6a6971151de442f2aa082672658c5fb9';

        // APIのURL
        $uriBase = 'https://japaneast.api.cognitive.microsoft.com/face/v1.0';

        //
        require_once 'HTTP/Request2.php';

        //パーソングループ名指定
        $personGroupID = 'murasetest';

        //method指定
        $request = new Http_Request2($uriBase . '/persongroups/' . $personGroupID . '/persons');
        $url = $request->getUrl();

        $headers = array(
            // Request headers
            'Content-Type' => 'application/json',
            'Ocp-Apim-Subscription-Key' => $ocpApimSubscriptionKey
        );
        $request->setHeader($headers);

        $parameters = array(
            // Request parameters
            //'returnFaceId' => 'true',
            //'returnFaceLandmarks' => 'false',
            'returnFaceAttributes' => 'age,gender,headPose,smile,facialHair,glasses,' .
                'emotion,hair,makeup,occlusion,accessories,blur,exposure,noise');

        $url->setQueryVariables($parameters);

        $request->setMethod(HTTP_Request2::METHOD_POST);

        // Request body parameters
        $personName = 'murase taro';
        $userData = 'User-provided data attached to the person.';
        $body = json_encode(array('name' => $personName, 'userData' => $userData));

        // Request body
        $request->setBody($body);
        try
        {
            $response = $request->send();
            return $response->getBody();
        }
        catch (HttpException $ex)
        {
            return $ex;
        }
     }

     /**
        * パーソングループパーソンに顔画像追加
        */
        private function _personGroupPersonAdd()
        {

         // サブスクリプションキー入力
           $ocpApimSubscriptionKey = '6a6971151de442f2aa082672658c5fb9';

           // APIのURL
           $uriBase = 'https://japaneast.api.cognitive.microsoft.com/face/v1.0';

           //
           require_once 'HTTP/Request2.php';

           //パーソングループ名指定
           $personGroupID = 'murasetest';

           //パーソンID取得
           $personId = '476c20cf-79b5-4269-b23e-766623caa8f3';

           //method指定
           $request = new Http_Request2($uriBase . '/persongroups/' . $personGroupID . '/persons/' . $personId . '/persistedFaces');
           $url = $request->getUrl();

           $headers = array(
               // Request headers
               'Content-Type' => 'application/json',
               'Ocp-Apim-Subscription-Key' => $ocpApimSubscriptionKey
           );
           $request->setHeader($headers);

           $parameters = array(
               // Request parameters
               //'returnFaceId' => 'true',
               //'returnFaceLandmarks' => 'false',
               'returnFaceAttributes' => 'age,gender,headPose,smile,facialHair,glasses,' .
                   'emotion,hair,makeup,occlusion,accessories,blur,exposure,noise');

           $url->setQueryVariables($parameters);

           $request->setMethod(HTTP_Request2::METHOD_POST);

           // Request body parameters

           //顔画像指定
           $url = 'https://muraseface.blob.core.windows.net/container1/ベッカム第一号.jpg';

           //ユーザーデータ備考欄
           $userData = 'murase is test';

           //追加する顔のターゲットを指定する。画像に複数の顔が存在する場合、指定しないといけない
           $targetFace = '';

           $body = json_encode(array(
                'url' => $url,
                'userData' => $userData,
                'targetFace' => $targetFace
                ));

           // Request body
           $request->setBody($body);
           try
           {
               $response = $request->send();
               return $response->getBody();
           }
           catch (HttpException $ex)
           {
               return $ex;
           }
        }

     /**
        * パーソングループトレーニング
        */
        private function _personGroupTrain()
        {

         // サブスクリプションキー入力
           $ocpApimSubscriptionKey = '6a6971151de442f2aa082672658c5fb9';

           // APIのURL
           $uriBase = 'https://japaneast.api.cognitive.microsoft.com/face/v1.0';

           //
           require_once 'HTTP/Request2.php';

           //パーソングループID指定
           $personGroupID = 'murasetest';

           //method指定
           $request = new Http_Request2($uriBase . '/persongroups/' . $personGroupID . '/train/');
           $url = $request->getUrl();

           $headers = array(
               // Request headers
               'Content-Type' => 'application/json',
               'Ocp-Apim-Subscription-Key' => $ocpApimSubscriptionKey
           );
           $request->setHeader($headers);

           $parameters = array(
               // Request parameters
               //'returnFaceId' => 'true',
               //'returnFaceLandmarks' => 'false',
               'returnFaceAttributes' => 'age,gender,headPose,smile,facialHair,glasses,' .
                   'emotion,hair,makeup,occlusion,accessories,blur,exposure,noise');

           $url->setQueryVariables($parameters);

           $request->setMethod(HTTP_Request2::METHOD_POST);

           $body = '';

           // Request body
           $request->setBody($body);
           try
           {
               $response = $request->send();
               return $response->getBody();
           }
           catch (HttpException $ex)
           {
               return $ex;
           }
        }

     /**
        * パーソングループトレーニングステーrタス取得
        */
        private function _personGroupTrainStatus()
        {

         // サブスクリプションキー入力
           $ocpApimSubscriptionKey = '6a6971151de442f2aa082672658c5fb9';

           // APIのURL
           $uriBase = 'https://japaneast.api.cognitive.microsoft.com/face/v1.0';

           //
           require_once 'HTTP/Request2.php';

           //パーソングループID指定
           $personGroupID = 'murasetest';

           //method指定
           $request = new Http_Request2($uriBase . '/persongroups/' . $personGroupID . '/training/');
           $url = $request->getUrl();

           $headers = array(
               'Ocp-Apim-Subscription-Key' => $ocpApimSubscriptionKey
           );
           $request->setHeader($headers);

           $parameters = array(
               // Request parameters
               //'returnFaceId' => 'true',
               //'returnFaceLandmarks' => 'false',
               'returnFaceAttributes' => 'age,gender,headPose,smile,facialHair,glasses,' .
                   'emotion,hair,makeup,occlusion,accessories,blur,exposure,noise');

           $url->setQueryVariables($parameters);

           $request->setMethod(HTTP_Request2::METHOD_GET);

           $body = '';

           // Request body
           $request->setBody($body);
           try
           {
               $response = $request->send();
               return $response->getBody();
           }
           catch (HttpException $ex)
           {
               return $ex;
           }
        }

     /**
        * 顔認識
        */
        private function _identify()
        {

        // This sample uses the Apache HTTP client from HTTP Components (http://hc.apache.org/httpcomponents-client-ga/)
        require_once 'HTTP/Request2.php';

        $request = new Http_Request2('https://japaneast.api.cognitive.microsoft.com/face/v1.0/identify');
        $url = $request->getUrl();

        $headers = array(
            // Request headers
            'Content-Type' => 'application/json',
            'Ocp-Apim-Subscription-Key' => '6a6971151de442f2aa082672658c5fb9',
        );

        $request->setHeader($headers);

        $parameters = array(
            // Request parameters
        );

        $url->setQueryVariables($parameters);

        $request->setMethod(HTTP_Request2::METHOD_POST);

          // Request body parameters
          $faceIds = array('f533b135-1200-4f73-84ff-fd1122e0e154');
          $personGroupId = 'murasetest';
          $body = json_encode(array(
                    'personGroupId' => $personGroupId,
                    'faceIds' => $faceIds,
                    'maxNumOfCandidatesReturned' => 1,
                    'confidenceThreshold' =>  0.5
                    ));

          // Request body
          $request->setBody($body);

        try
        {
            $response = $request->send();

            return $response->getBody();
        }
        catch (HttpException $ex)
        {
            return  $ex;
        }
        }
}
