<?php
/**
 * Copyright (c) 2021. Fakturaservice A/S - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited.
 * Proprietary and confidential
 * Written by Torben Wrang Laursen <twl@fakturaservice.dk>, February 2021
 *
 *
 */

/**
 * Created by PhpStorm.
 * User: twl2
 * Date: 10-09-2020
 * Time: 13:26
 */

namespace OrSdk;

use CURLFile;
use OrSdk\Models\BaseModels;
use OrSdk\Models\Com\Documents\DocumentType;
use OrSdk\Util\ApiResponseCodes;
use OrSdk\Util\ORException;


/**
 * Class OrApiClient
 * @package Models
 */
class Client
{
    private $_ORApiHost;
    private $_ORApiToken;

    /**
     * Client constructor.
     * @param $host
     * @param $userName
     * @param $password
     * @param $ledgersId
     * @throws ORException
     */
    public function __construct($host, $userName, $password, $ledgersId)
    {
        $this->_ORApiHost   = $host;
        $this->_ORApiToken  = $this->login($userName, $password, $ledgersId);
        if(!$this->_ORApiToken)
            throw new ORException("Access denied", ORException::CH_PERMISSION);
    }

    /**
     * @param BaseModels $mod
     * @param bool $debug
     * @return mixed
     * @throws ORException
     */
    protected function modelPost(BaseModels &$mod, bool $debug=false)
    {
        $args       = array_filter($mod->getValues(true, 0, null, ["id"]), "strlen");
        $curl       = curl_init();
        $argStr     = (isset($this->_ORApiToken))?(array("token" => $this->_ORApiToken) + $args):$args;
        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->_ORApiHost . "{$mod->getApiName(true)}/",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => false,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $argStr,
            CURLINFO_HEADER_OUT => true
        ));

        $response   = curl_exec($curl);
        $err        = curl_error($curl);
        $info       = curl_getinfo($curl);
        curl_close($curl);

        if($debug)
            $this->debug($mod->getApiName(true), $argStr, "POST", $response, $err, $info);

        $response = json_decode($response, true);
        if(
            ($response["error_code"] > ApiResponseCodes::OK) &&
            ($response["error_code"] < ApiResponseCodes::SYS_WARNING)
        )
            throw new ORException($response["message"]);
        if(isset($response["id"]))
            $mod->setValue("id", $response["id"]);
        return $response;
    }

    /**
     * @param BaseModels $mod
     * @param bool $debug
     * @return mixed
     * @throws ORException
     */
    protected function modelGet(BaseModels &$mod, bool $debug=false)
    {
        $curl       = curl_init();
        $argStr     = http_build_query((array("token" => $this->_ORApiToken) + $mod->getValues(true)));

        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->_ORApiHost . "{$mod->getApiName(true)}/?$argStr",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => false,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLINFO_HEADER_OUT => true
        ));

        $response   = curl_exec($curl);
        $err        = curl_error($curl);
        $info       = curl_getinfo($curl);
        curl_close($curl);

        if($debug)
            $this->debug($mod->getApiName(true), $argStr, "GET", $response, $err, $info);

        $response = json_decode($response, true);
        if(
            ($response["error_code"] > ApiResponseCodes::OK) &&
            ($response["error_code"] < ApiResponseCodes::SYS_WARNING)
        )
            throw new ORException($response["message"]);
        if(isset($response[$mod->getModelName(true)][0]))
            $mod->setValues($response[$mod->getModelName(true)][0]);
        return $response;
    }

    /**
     * @param BaseModels $mod
     * @param bool $debug
     * @return mixed
     * @throws ORException
     */
    protected function modelPut(BaseModels $mod, bool $debug=false)
    {
        $curl       = curl_init();
        $argStr     = http_build_query(array("token" => $this->_ORApiToken) + $mod->getValues(true));

        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->_ORApiHost . "{$mod->getApiName(true)}/?$argStr",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => false,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "PUT"
        ));

        $response   = curl_exec($curl);
        $err        = curl_error($curl);
        $info       = curl_getinfo($curl);
        curl_close($curl);

        if($debug)
            $this->debug($mod->getApiName(true), $argStr, "PUT", $response, $err, $info);

        $response = json_decode($response, true);
        if(
            ($response["error_code"] > ApiResponseCodes::OK) &&
            ($response["error_code"] < ApiResponseCodes::SYS_WARNING)
        )
            throw new ORException($response["message"]);

        return $response;
    }

    /**
     * @param BaseModels $mod
     * @param bool $debug
     * @return mixed
     * @throws ORException
     */
    protected function modelDelete(BaseModels $mod, bool $debug=false)
    {
        $curl       = curl_init();
        $argStr     = http_build_query(array("token" => $this->_ORApiToken) + $mod->getValues(true));

        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->_ORApiHost . "{$mod->getApiName(true)}/?$argStr",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => false,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "DELETE"
        ));

        $response   = curl_exec($curl);
        $err        = curl_error($curl);
        $info       = curl_getinfo($curl);
        curl_close($curl);

        if($debug)
            $this->debug($mod->getApiName(true), $argStr, "DELETE", $response, $err, $info);

        $response = json_decode($response, true);
        if(
            ($response["error_code"] > ApiResponseCodes::OK) &&
            ($response["error_code"] < ApiResponseCodes::SYS_WARNING)
        )
            throw new ORException($response["message"]);

        return $response;
    }

    /**
     * @param $documentId
     * @param bool $dryRun
     * @param bool $debug
     * @return mixed
     * @throws ORException
     */
    protected function book($documentId, bool $dryRun=true, bool $debug=false)
    {
        $curl       = curl_init();
        $argStr     = http_build_query((array("token" => $this->_ORApiToken) + ["id" => $documentId]));

        curl_setopt_array($curl, [
            CURLOPT_URL => $this->_ORApiHost . "ext/book/?$argStr",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => false,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => ($dryRun?"GET":"PUT"),
            CURLINFO_HEADER_OUT => $dryRun
        ]);

        $response   = curl_exec($curl);
        $err        = curl_error($curl);
        $info       = curl_getinfo($curl);
        curl_close($curl);

        if($debug)
            $this->debug("ext/book", $argStr, ($dryRun?"GET":"PUT"), $response, $err, $info);

        $response = json_decode($response, true);
        if(
            ($response["error_code"] > ApiResponseCodes::OK) &&
            ($response["error_code"] < ApiResponseCodes::SYS_WARNING)
        )
            throw new ORException($response["message"]);
        return $response;
    }

    /**
     * @param $api
     * @param array $arg
     * @param bool $debug
     * @return mixed
     * @throws ORException
     */
    protected function get($api, array $arg, bool $debug=false)
    {
        $curl       = curl_init();
        $argStr     = http_build_query((array("token" => $this->_ORApiToken) + $arg));

        curl_setopt_array($curl, [
            CURLOPT_URL => $this->_ORApiHost . "$api/?$argStr",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => false,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLINFO_HEADER_OUT => true
        ]);

        $response   = curl_exec($curl);
        $err        = curl_error($curl);
        $info       = curl_getinfo($curl);
        curl_close($curl);

        if($debug)
            $this->debug("$api", $argStr, "GET", $response, $err, $info);

        $response = json_decode($response, true);
        if(
            ($response["error_code"] > ApiResponseCodes::OK) &&
            ($response["error_code"] < ApiResponseCodes::SYS_WARNING)
        )
            throw new ORException($response["message"]);
        return $response;
    }

    /**
     * @param $api
     * @param array $arg
     * @param bool $debug
     * @return mixed
     * @throws ORException
     */
    protected function put($api, array $arg, bool $debug=false)
    {
        $curl       = curl_init();
        $arg        = array_filter($arg, "strlen");
        $argStr     = http_build_query((array("token" => $this->_ORApiToken) + $arg));

        curl_setopt_array($curl, [
            CURLOPT_URL => $this->_ORApiHost . "$api/?$argStr",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => false,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "PUT",
            CURLINFO_HEADER_OUT => true
        ]);

        $response   = curl_exec($curl);
        $err        = curl_error($curl);
        $info       = curl_getinfo($curl);
        curl_close($curl);

        if($debug)
            $this->debug("$api", $argStr, "PUT", $response, $err, $info);

        $response = json_decode($response, true);
        if(
            ($response["error_code"] > ApiResponseCodes::OK) &&
            ($response["error_code"] < ApiResponseCodes::SYS_WARNING)
        )
            throw new ORException($response["message"]);
        return $response;
    }

    /**
     * @param $api
     * @param array $arg
     * @param bool $debug
     * @return mixed
     * @throws ORException
     */
    protected function delete($api, array $arg, bool $debug=false)
    {
        $curl       = curl_init();
        $argStr     = http_build_query((array("token" => $this->_ORApiToken) + $arg));

        curl_setopt_array($curl, [
            CURLOPT_URL => $this->_ORApiHost . "$api/?$argStr",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => false,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "DELETE",
            CURLINFO_HEADER_OUT => true
        ]);

        $response   = curl_exec($curl);
        $err        = curl_error($curl);
        $info       = curl_getinfo($curl);
        curl_close($curl);

        if($debug)
            $this->debug("$api", $argStr, "DELETE", $response, $err, $info);

        $response = json_decode($response, true);
        if(
            ($response["error_code"] > ApiResponseCodes::OK) &&
            ($response["error_code"] < ApiResponseCodes::SYS_WARNING)
        )
            throw new ORException($response["message"]);
        return $response;
    }

    /**
     * @param $api
     * @param array $arg
     * @param bool $debug
     * @return mixed
     * @throws ORException
     */
    protected function post($api, array $arg, bool $debug=false)
    {
        $curl           = curl_init();
        $arg["token"]   = $this->_ORApiToken;
        $arg            = array_filter($arg, "strlen");

        if(in_array("file", array_keys($arg)))
        {
            $dataSize       = strlen(file_get_contents($arg["file"]));
            $arg["file"]    = $this->getCurlFile($arg["file"]);

            curl_setopt_array($curl, [
                CURLOPT_URL => $this->_ORApiHost . "$api/",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => false,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => $arg,
                CURLOPT_HTTPHEADER => [
                    "Content-Type" => "multipart/form-data",
                    "Content-Length" =>  $dataSize
                ]
            ]);
        }
        else
        {
            curl_setopt_array($curl, [
                CURLOPT_URL => $this->_ORApiHost . "$api/",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => false,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => $arg,
                CURLINFO_HEADER_OUT => true
            ]);
        }

        $response   = curl_exec($curl);
        $err        = curl_error($curl);
        $info       = curl_getinfo($curl);
        curl_close($curl);

        if($debug)
            $this->debug("$api", $arg, "POST", $response, $err, $info);

        $response = json_decode($response, true);
        if(
            ($response["error_code"] > ApiResponseCodes::OK) &&
            ($response["error_code"] < ApiResponseCodes::SYS_WARNING)
        )
            throw new ORException($response["message"]);
        return $response;
    }

    /**
     * @param $api
     * @param array $arg
     * @param $filePath
     * @return false|int
     */
    protected function downloadFile($api, array $arg, $filePath=null)
    {
        $argStr     = http_build_query((array("token" => $this->_ORApiToken) + $arg));
        $url        = $this->_ORApiHost . "$api/?$argStr";
        if(isset($filePath))
            return file_put_contents($filePath, file_get_contents($url));
        else
            return file_get_contents($url);
    }

    /**
     * @param $customerId
     * @param bool $debug
     * @return mixed
     * @throws ORException
     */
    protected function creatDraftInvoice($customerId, bool $debug=false)
    {
        $curl       = curl_init();
        $argStr     = http_build_query((array("token" => $this->_ORApiToken) + ["contactsId" => $customerId, "documentType" => DocumentType::income]));

        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->_ORApiHost . "ext/documents/",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => false,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $argStr,
            CURLINFO_HEADER_OUT => true
        ));

        $response   = curl_exec($curl);
        $err        = curl_error($curl);
        $info       = curl_getinfo($curl);
        curl_close($curl);

        if($debug)
        {
            $this->debug("ext/documents/", $argStr, "POST", $response, $err, $info);
        }

        $response = json_decode($response, true);
        if(
            ($response["error_code"] > ApiResponseCodes::OK) &&
            ($response["error_code"] < ApiResponseCodes::SYS_WARNING)
        )
            throw new ORException($response["message"]);
        return $response;
    }

    private function getCurlFile($path): CURLFile
    {
        $mime   = mime_content_type($path);
        $info   = pathinfo($path);
        $name   = $info['basename'];
        return curl_file_create($path, $mime, $name);
    }

    /**
     * @throws ORException
     */
    private function login($userName, $password, $ledgersId, $debug=false)
    {
        $curl       = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->_ORApiHost . "acc/token/",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => false,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => ["userName" => $userName,
                                   "password" => $password,
                                   "ledgersId" => $ledgersId],
            CURLINFO_HEADER_OUT => true
        ));

        $response   = curl_exec($curl);
        $err        = curl_error($curl);
        $info       = curl_getinfo($curl);
        curl_close($curl);

        if($debug)
        {
            $this->debug("ext/login", "", "POST", $response, $err, $info);
        }

        $response = json_decode($response, true);
        if(
            ($response["error_code"] > ApiResponseCodes::OK) &&
            ($response["error_code"] < ApiResponseCodes::SYS_WARNING)
        )
            throw new ORException($response["message"]);
        return $response["token"];
    }

    /**
     * @param $api
     * @param $argStr
     * @param $restCmd
     * @param $response
     * @param $err
     * @param $info
     */
    private function debug($api, $argStr, $restCmd, $response, $err, $info=null)
    {
        if($err)
        {
            echo "Error $api: \n";
            print_r($err);
            echo "\n";
        }
        else
        {
            echo "\n\n\n~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~\n\n\n###################################\n";
            echo strtoupper("Debug $api $restCmd: \n");
            echo "###################################\n";
            echo "\nRequest:\n";
            echo "--------\n";
            if($restCmd == "POST")
            {
                echo "URL: " . $this->_ORApiHost . "$api/\n";
                print_r($argStr);
            }
            else
                print_r($this->_ORApiHost . "$api/?$argStr");
            if(($restCmd == "PUT") && (isset($response["updated"])))
            {
                $response["updated"] = ($response["updated"])?"true":"false";
            }
            echo "\n\n*****************\n";
            echo "\nResponse:\n";
            echo "--------\n";
            $responseArr            = json_decode($response, true);
            $responseArr["result"]  = ($responseArr["result"])?"true":"false";
            print_r($responseArr);
            if(isset($info))
            {
                echo "\n*****************\n";
                echo "\nInfo:\n";
                echo "-----\n";
                print_r($info);
            }
            echo "\n#################################\n";

        }
    }
}