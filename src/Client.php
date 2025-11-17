<?php
/**
 * Copyright (c) 2021. Fakturaservice A/S - All Rights Reserved 
 * Unauthorized copying of this file, via any medium is strictly prohibited.
 * Proprietary and confidential
 * Written by Torben Wrang Laursen <twl@fakturaservice.dk>, February 2021
 */

/**
 * Created by PhpStorm.
 * User: twl2 test
 * Date: 10-09-2020
 * Time: 13:26
 */

namespace OrSdk;

use OrSdk\Models\BaseModels;
use OrSdk\Models\Com\Documents\DocumentType;


/**
 * Class OrApiClient
 * @package Models
 */
class Client
{
    const TOKEN_PATTERN = "/^\d{1,6}_\d{1,6};[BERT][AG](ST|AC|AP|SU|DE)[a-zA-Z0-9]+;[a-zA-Z0-9.-]+\.(onlineregnskab\.dk|onlineregnskab\.test)$/";
    private string $_ORApiHost;
    private ?string $_ORApiToken;
    private bool $_ORApiTokenRenewed;

    /**
     * Client constructor.
     * @param string $host
     * @param string $userName
     * @param string $password
     * @param int $ledgersId
     * @param string|null $token
     */
    public function __construct(string $host, string $userName, string $password, int $ledgersId, string $token = null)
    {
        $this->_ORApiHost           = $host;
        $this->_ORApiTokenRenewed   = false;
        if(isset($token))
        {
            $this->_ORApiToken          = $token;
            if(!$this->challengeToken())
                $this->_ORApiTokenRenewed   = $this->renewToken($userName, $password, $ledgersId);
        }
        else
            $this->_ORApiTokenRenewed   = $this->renewToken($userName, $password, $ledgersId);
    }

    /**
     * @return bool
     */
    private function challengeToken(): bool
    {
        $res = $this->get("com/settings", ["id" => 1]);
        return (bool)$res["result"]??false;
    }

    /**
     * @param string $userName
     * @param string $password
     * @param int $ledgersId
     * @return bool
     */
    protected function renewToken(string $userName, string $password, int $ledgersId): bool
    {
        $this->_ORApiToken = $this->login($userName, $password, $ledgersId);
        if(preg_match(self::TOKEN_PATTERN, $this->_ORApiToken))
        {
            return true;
        }
        return false;
    }

    /**
     * @return bool
     */
    protected function isTokenRenewed(): bool
    {
        return $this->_ORApiTokenRenewed;
    }

    /**
     * @return string
     */
    protected function getRenewedToken(): string
    {
        return $this->_ORApiToken;
    }


    /**
     * @param BaseModels $mod
     * @param bool $debug
     * @return mixed
     */
    protected function modelPost(BaseModels &$mod, bool $debug=false)
    {
        $args       = array_filter($mod->getValues(true, 0, null, ["id"]));
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
        if(isset($response["id"]))
            $mod->setValue("id", $response["id"]);
        return $response;
    }
    /**
     * @param BaseModels $mod
     * @param bool $debug
     * @return mixed
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

        if(isset($info["content_type"]) && (str_replace(' ', '', $info["content_type"]) != "application/json;charset=utf-8"))
        {
            echo "HELLO WORLD!";
            header("Content-Type: {$info["content_type"]}");
            return $response;
        }

        $response = json_decode($response, true);
        if(isset($response[$mod->getModelName(true)][0]))
            $mod->setValues($response[$mod->getModelName(true)][0]);
        return $response;
    }
    /**
     * @param BaseModels $mod
     * @param bool $debug
     * @return mixed
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

        return json_decode($response, true);

    }
    /**
     * @param BaseModels $mod
     * @param bool $debug
     * @return mixed
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

        return json_decode($response, true);
    }
    /**
     * @param $documentId
     * @param bool $dryRun
     * @param bool $debug
     * @return mixed
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

        return json_decode($response, true);
    }

    /**
     * @param string $api
     * @param array $arg
     * @param bool $debug
     * @return mixed
     */
    protected function get(string $api, array $arg, bool $debug=false)
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

        if(isset($info["content_type"]) && (str_replace(' ', '', $info["content_type"]) != "application/json;charset=utf-8"))
        {
            header("Content-Type: {$info["content_type"]}");
            return $response;
        }
        return json_decode($response, true);
    }

    /**
     * @param string $api
     * @param array $arg
     * @param bool $debug
     * @return mixed
     */
    protected function put(string $api, array $arg, bool $debug=false)
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
            CURLOPT_CUSTOMREQUEST => "PUT",
            CURLINFO_HEADER_OUT => true
        ]);

        $response   = curl_exec($curl);
        $err        = curl_error($curl);
        $info       = curl_getinfo($curl);
        curl_close($curl);

        if($debug)
            $this->debug("$api", $argStr, "PUT", $response, $err, $info);

        return json_decode($response, true);
    }

    /**
     * @param $api
     * @param array $arg
     * @param bool $debug
     * @return mixed
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

        return json_decode($response, true);
    }

    /**
     * @param string $api
     * @param array $arg
     * @param bool $debug
     * @return mixed
     */
    protected function post(string $api, array $arg, bool $debug=false)
    {
        $curl       = curl_init();
        $argStr     = http_build_query((array("token" => $this->_ORApiToken) + $arg));

        curl_setopt_array($curl, [
            CURLOPT_URL => $this->_ORApiHost . "$api/",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => false,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $argStr,
            CURLINFO_HEADER_OUT => true
        ]);

        $response   = curl_exec($curl);
        $err        = curl_error($curl);
        $info       = curl_getinfo($curl);
        curl_close($curl);

        if($debug)
            $this->debug("$api", $argStr, "POST", $response, $err, $info);

        return json_decode($response, true);
    }

    /**
     * @param string $api
     * @param array $arg
     * @param null $filePath
     * @param bool $debug
     * @return false|int
     */
    protected function downloadFile(string $api, array $arg, $filePath=null, bool $debug=false)
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

        return json_decode($response, true);
    }
    /**
     * @param string $userName
     * @param string $password
     * @param int $ledgersId
     * @param bool $debug
     * @return mixed
     */
    private function login(string $userName, string $password, int $ledgersId, bool $debug=false)
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
        return $response["token"]??null;
    }


    /**
     * @param string $api
     * @param $argStr
     * @param string $restCmd
     * @param $response
     * @param $err
     * @param null $info
     */
    private function debug(string $api, $argStr, string $restCmd, $response, $err, $info=null)
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
