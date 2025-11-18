<?php
/**
 * Copyright (c) 2021. Fakturaservice A/S - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited.
 * Proprietary and confidential
 * Written by Torben Wrang Laursen <twl@fakturaservice.dk>, February 2021
 */

namespace OrSdk;

use OrSdk\Models\BaseModels;
use OrSdk\Models\Com\Documents\DocumentType;

class Client
{
    public const TOKEN_PATTERN =
        "/^\d{1,6}_\d{1,6};[BERT][AG](ST|AC|AP|SU|DE)[a-zA-Z0-9]+;[a-zA-Z0-9.-]+\.(onlineregnskab\.dk|onlineregnskab\.test)$/";

    private string  $_ORApiHost;
    private ?string $_ORApiToken;
    private bool    $_ORApiTokenRenewed;

    /**
     * Client constructor.
     */
    public function __construct(string $host, string $userName, string $password, int $ledgersId, ?string $token = null)
    {
        $this->_ORApiHost         = $host;
        $this->_ORApiTokenRenewed = false;

        if ($token !== null) {
            $this->_ORApiToken = $token;
            if (!$this->challengeToken()) {
                $this->_ORApiTokenRenewed = $this->renewToken($userName, $password, $ledgersId);
            }
        } else {
            $this->_ORApiTokenRenewed = $this->renewToken($userName, $password, $ledgersId);
        }
    }

    /**
     * @return bool
     */
    private function challengeToken(): bool
    {
        $res = $this->get("com/settings", ["id" => 1]);
        // Expecting array with ['result' => bool|int] – be defensive.
        if (is_array($res) && array_key_exists('result', $res)) {
            return (bool) $res['result'];
        }
        return false;
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

        if ($this->_ORApiToken !== null && preg_match(self::TOKEN_PATTERN, $this->_ORApiToken) === 1) {
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
     * May be null if token could not be renewed.
     */
    protected function getRenewedToken(): ?string
    {
        return $this->_ORApiToken;
    }

    /**
     * @param BaseModels $mod
     * @param bool $debug
     * @return array|null
     */
    protected function modelPost(BaseModels &$mod, bool $debug = false): ?array
    {
        $args   = array_filter($mod->getValues(true, 0, null, ["id"]));
        $curl   = curl_init();
        $argStr = $this->_ORApiToken !== null
            ? (["token" => $this->_ORApiToken] + $args)
            : $args;

        curl_setopt_array($curl, [
            CURLOPT_URL            => $this->_ORApiHost . "{$mod->getApiName(true)}/",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING       => "",
            CURLOPT_MAXREDIRS      => 10,
            CURLOPT_TIMEOUT        => 0,
            CURLOPT_FOLLOWLOCATION => false,
            CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST  => "POST",
            CURLOPT_POSTFIELDS     => $argStr,
            CURLINFO_HEADER_OUT    => true,
        ]);

        $response = curl_exec($curl);
        $err      = curl_error($curl);
        $info     = curl_getinfo($curl);
        curl_close($curl);

        if ($debug) {
            $this->debug($mod->getApiName(true), $argStr, "POST", $response, $err, $info);
        }

        $decoded = is_string($response) ? json_decode($response, true) : null;

        if (is_array($decoded) && isset($decoded["id"])) {
            $mod->setValue("id", $decoded["id"]);
        }

        return $decoded;
    }

    /**
     * Returns array (JSON), string (non-JSON content) or null.
     *
     * @param BaseModels $mod
     * @param bool $debug
     * @return array|string|null
     */
    protected function modelGet(BaseModels &$mod, bool $debug = false): array|string|null
    {
        $curl   = curl_init();
        $argStr = http_build_query(
            (["token" => $this->_ORApiToken] + $mod->getValues(true))
        );

        curl_setopt_array($curl, [
            CURLOPT_URL            => $this->_ORApiHost . "{$mod->getApiName(true)}/?$argStr",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING       => "",
            CURLOPT_MAXREDIRS      => 10,
            CURLOPT_TIMEOUT        => 0,
            CURLOPT_FOLLOWLOCATION => false,
            CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST  => "GET",
            CURLINFO_HEADER_OUT    => true,
        ]);

        $response = curl_exec($curl);
        $err      = curl_error($curl);
        $info     = curl_getinfo($curl);
        curl_close($curl);

        if ($debug) {
            $this->debug($mod->getApiName(true), $argStr, "GET", $response, $err, $info);
        }

        if (isset($info["content_type"])
            && str_replace(' ', '', (string) $info["content_type"]) !== "application/json;charset=utf-8"
        ) {
            if (!headers_sent()) {
                header("Content-Type: {$info["content_type"]}");
            }
            return $response === false ? null : $response;
        }

        $decoded = is_string($response) ? json_decode($response, true) : null;

        if (is_array($decoded)
            && isset($decoded[$mod->getModelName(true)][0])
            && is_array($decoded[$mod->getModelName(true)][0])
        ) {
            $mod->setValues($decoded[$mod->getModelName(true)][0]);
        }

        return $decoded;
    }

    /**
     * @param BaseModels $mod
     * @param bool $debug
     * @return array|null
     */
    protected function modelPut(BaseModels $mod, bool $debug = false): ?array
    {
        $curl   = curl_init();
        $argStr = http_build_query((["token" => $this->_ORApiToken] + $mod->getValues(true)));

        curl_setopt_array($curl, [
            CURLOPT_URL            => $this->_ORApiHost . "{$mod->getApiName(true)}/?$argStr",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING       => "",
            CURLOPT_MAXREDIRS      => 10,
            CURLOPT_TIMEOUT        => 0,
            CURLOPT_FOLLOWLOCATION => false,
            CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST  => "PUT",
        ]);

        $response = curl_exec($curl);
        $err      = curl_error($curl);
        $info     = curl_getinfo($curl);
        curl_close($curl);

        if ($debug) {
            $this->debug($mod->getApiName(true), $argStr, "PUT", $response, $err, $info);
        }

        return is_string($response) ? json_decode($response, true) : null;
    }

    /**
     * @param BaseModels $mod
     * @param bool $debug
     * @return array|null
     */
    protected function modelDelete(BaseModels $mod, bool $debug = false): ?array
    {
        $curl   = curl_init();
        $argStr = http_build_query((["token" => $this->_ORApiToken] + $mod->getValues(true)));

        curl_setopt_array($curl, [
            CURLOPT_URL            => $this->_ORApiHost . "{$mod->getApiName(true)}/?$argStr",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING       => "",
            CURLOPT_MAXREDIRS      => 10,
            CURLOPT_TIMEOUT        => 0,
            CURLOPT_FOLLOWLOCATION => false,
            CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST  => "DELETE",
        ]);

        $response = curl_exec($curl);
        $err      = curl_error($curl);
        $info     = curl_getinfo($curl);
        curl_close($curl);

        if ($debug) {
            $this->debug($mod->getApiName(true), $argStr, "DELETE", $response, $err, $info);
        }

        return is_string($response) ? json_decode($response, true) : null;
    }

    /**
     * @param int $documentId
     * @param bool $dryRun
     * @param bool $debug
     * @return array|null
     */
    protected function book(int $documentId, bool $dryRun = true, bool $debug = false): ?array
    {
        $curl   = curl_init();
        $argStr = http_build_query(
            (["token" => $this->_ORApiToken] + ["id" => $documentId])
        );

        curl_setopt_array($curl, [
            CURLOPT_URL            => $this->_ORApiHost . "ext/book/?$argStr",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING       => "",
            CURLOPT_MAXREDIRS      => 10,
            CURLOPT_TIMEOUT        => 0,
            CURLOPT_FOLLOWLOCATION => false,
            CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST  => ($dryRun ? "GET" : "PUT"),
            CURLINFO_HEADER_OUT    => $dryRun,
        ]);

        $response = curl_exec($curl);
        $err      = curl_error($curl);
        $info     = curl_getinfo($curl);
        curl_close($curl);

        if ($debug) {
            $this->debug("ext/book", $argStr, ($dryRun ? "GET" : "PUT"), $response, $err, $info);
        }

        return is_string($response) ? json_decode($response, true) : null;
    }

    /**
     * @param string $api
     * @param array $arg
     * @param bool $debug
     * @return array|string|null
     */
    protected function get(string $api, array $arg, bool $debug = false): array|string|null
    {
        $curl   = curl_init();
        $argStr = http_build_query((["token" => $this->_ORApiToken] + $arg));

        curl_setopt_array($curl, [
            CURLOPT_URL            => $this->_ORApiHost . "$api/?$argStr",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING       => "",
            CURLOPT_MAXREDIRS      => 10,
            CURLOPT_TIMEOUT        => 0,
            CURLOPT_FOLLOWLOCATION => false,
            CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST  => "GET",
            CURLINFO_HEADER_OUT    => true,
        ]);

        $response = curl_exec($curl);
        $err      = curl_error($curl);
        $info     = curl_getinfo($curl);
        curl_close($curl);

        if ($debug) {
            $this->debug($api, $argStr, "GET", $response, $err, $info);
        }

        if (isset($info["content_type"])
            && str_replace(' ', '', (string) $info["content_type"]) !== "application/json;charset=utf-8"
        ) {
            if (!headers_sent()) {
                header("Content-Type: {$info["content_type"]}");
            }
            return $response === false ? null : $response;
        }

        return is_string($response) ? json_decode($response, true) : null;
    }

    /**
     * @param string $api
     * @param array $arg
     * @param bool $debug
     * @return array|null
     */
    protected function put(string $api, array $arg, bool $debug = false): ?array
    {
        $curl   = curl_init();
        $argStr = http_build_query((["token" => $this->_ORApiToken] + $arg));

        curl_setopt_array($curl, [
            CURLOPT_URL            => $this->_ORApiHost . "$api/?$argStr",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING       => "",
            CURLOPT_MAXREDIRS      => 10,
            CURLOPT_TIMEOUT        => 0,
            CURLOPT_FOLLOWLOCATION => false,
            CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST  => "PUT",
            CURLINFO_HEADER_OUT    => true,
        ]);

        $response = curl_exec($curl);
        $err      = curl_error($curl);
        $info     = curl_getinfo($curl);
        curl_close($curl);

        if ($debug) {
            $this->debug($api, $argStr, "PUT", $response, $err, $info);
        }

        return is_string($response) ? json_decode($response, true) : null;
    }

    /**
     * @param string $api
     * @param array $arg
     * @param bool $debug
     * @return array|null
     */
    protected function delete(string $api, array $arg, bool $debug = false): ?array
    {
        $curl   = curl_init();
        $argStr = http_build_query((["token" => $this->_ORApiToken] + $arg));

        curl_setopt_array($curl, [
            CURLOPT_URL            => $this->_ORApiHost . "$api/?$argStr",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING       => "",
            CURLOPT_MAXREDIRS      => 10,
            CURLOPT_TIMEOUT        => 0,
            CURLOPT_FOLLOWLOCATION => false,
            CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST  => "DELETE",
            CURLINFO_HEADER_OUT    => true,
        ]);

        $response = curl_exec($curl);
        $err      = curl_error($curl);
        $info     = curl_getinfo($curl);
        curl_close($curl);

        if ($debug) {
            $this->debug($api, $argStr, "DELETE", $response, $err, $info);
        }

        return is_string($response) ? json_decode($response, true) : null;
    }

    /**
     * @param string $api
     * @param array $arg
     * @param bool $debug
     * @return array|null
     */
    protected function post(string $api, array $arg, bool $debug = false): ?array
    {
        $curl   = curl_init();
        $argStr = http_build_query((["token" => $this->_ORApiToken] + $arg));

        curl_setopt_array($curl, [
            CURLOPT_URL            => $this->_ORApiHost . "$api/",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING       => "",
            CURLOPT_MAXREDIRS      => 10,
            CURLOPT_TIMEOUT        => 0,
            CURLOPT_FOLLOWLOCATION => false,
            CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST  => "POST",
            CURLOPT_POSTFIELDS     => $argStr,
            CURLINFO_HEADER_OUT    => true,
        ]);

        $response = curl_exec($curl);
        $err      = curl_error($curl);
        $info     = curl_getinfo($curl);
        curl_close($curl);

        if ($debug) {
            $this->debug($api, $argStr, "POST", $response, $err, $info);
        }

        return is_string($response) ? json_decode($response, true) : null;
    }

    /**
     * @return int|string|false  int if written to file, string/false from file_get_contents()
     */
    protected function downloadFile(string $api, array $arg, ?string $filePath = null, bool $debug = false): int|string|false
    {
        $argStr = http_build_query((["token" => $this->_ORApiToken] + $arg));
        $url    = $this->_ORApiHost . "$api/?$argStr";

        // $debug not used previously – kept in signature for BC

        if ($filePath !== null) {
            return file_put_contents($filePath, file_get_contents($url));
        }

        return file_get_contents($url);
    }

    /**
     * @param int $customerId
     * @param bool $debug
     * @return array|null
     */
    protected function creatDraftInvoice(int $customerId, bool $debug = false): ?array
    {
        $curl   = curl_init();
        $argStr = http_build_query(
            (["token" => $this->_ORApiToken]
                + ["contactsId" => $customerId, "documentType" => DocumentType::income])
        );

        curl_setopt_array($curl, [
            CURLOPT_URL            => $this->_ORApiHost . "ext/documents/",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING       => "",
            CURLOPT_MAXREDIRS      => 10,
            CURLOPT_TIMEOUT        => 0,
            CURLOPT_FOLLOWLOCATION => false,
            CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST  => "POST",
            CURLOPT_POSTFIELDS     => $argStr,
            CURLINFO_HEADER_OUT    => true,
        ]);

        $response = curl_exec($curl);
        $err      = curl_error($curl);
        $info     = curl_getinfo($curl);
        curl_close($curl);

        if ($debug) {
            $this->debug("ext/documents/", $argStr, "POST", $response, $err, $info);
        }

        return is_string($response) ? json_decode($response, true) : null;
    }

    /**
     * @param string $userName
     * @param string $password
     * @param int $ledgersId
     * @param bool $debug
     * @return string|null
     */
    private function login(string $userName, string $password, int $ledgersId, bool $debug = false): ?string
    {
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL            => $this->_ORApiHost . "acc/token/",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING       => "",
            CURLOPT_MAXREDIRS      => 10,
            CURLOPT_TIMEOUT        => 0,
            CURLOPT_FOLLOWLOCATION => false,
            CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST  => "POST",
            CURLOPT_POSTFIELDS     => [
                "userName"  => $userName,
                "password"  => $password,
                "ledgersId" => $ledgersId,
            ],
            CURLINFO_HEADER_OUT    => true,
        ]);

        $response = curl_exec($curl);
        $err      = curl_error($curl);
        $info     = curl_getinfo($curl);
        curl_close($curl);

        if ($debug) {
            $this->debug("ext/login", "", "POST", $response, $err, $info);
        }

        $decoded = is_string($response) ? json_decode($response, true) : null;

        return is_array($decoded) && isset($decoded["token"])
            ? (string) $decoded["token"]
            : null;
    }

    /**
     * Debug output helper.
     * Only outputs to STDOUT in CLI; in web context uses error_log to avoid breaking headers.
     *
     * @param string            $api
     * @param string|array      $argStr
     * @param string            $restCmd
     * @param string|false|null $response
     * @param string            $err
     * @param array|null        $info
     */
    private function debug(string $api, string|array $argStr, string $restCmd, string|false|null $response, string $err, ?array $info = null): void
    {
        $output = '';

        if ($err) {
            $output .= "Error $api:\n";
            $output .= $err . "\n";
        } else {
            $output .= "\n\n\n~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~\n\n\n###################################\n";
            $output .= strtoupper("Debug $api $restCmd: \n");
            $output .= "###################################\n";
            $output .= "\nRequest:\n";
            $output .= "--------\n";

            if ($restCmd === "POST") {
                $output .= "URL: " . $this->_ORApiHost . "$api/\n";
                $output .= (is_array($argStr) ? var_export($argStr, true) : $argStr) . "\n";
            } else {
                $output .= $this->_ORApiHost . "$api/?" . (is_array($argStr) ? http_build_query($argStr) : $argStr) . "\n";
            }

            $output .= "\n\n*****************\n";
            $output .= "\nResponse:\n";
            $output .= "--------\n";

            $responseArr = null;
            if (is_string($response)) {
                $responseArr = json_decode($response, true);
            }

            if (is_array($responseArr)) {
                if (isset($responseArr["result"])) {
                    $responseArr["result"] = $responseArr["result"] ? "true" : "false";
                }
                $output .= var_export($responseArr, true) . "\n";
            } else {
                $output .= $response . "\n";
            }

            if ($info !== null) {
                $output .= "\n*****************\n";
                $output .= "\nInfo:\n";
                $output .= "-----\n";
                $output .= var_export($info, true) . "\n";
            }

            $output .= "\n#################################\n";
        }

        if (PHP_SAPI === 'cli') {
            fwrite(STDOUT, $output);
        } else {
            // Do not echo in web context – avoids "headers already sent"
            error_log($output);
        }
    }
}
