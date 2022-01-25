<?php


namespace OrSdk\Util;


abstract class HttpErrorCodes extends BasicEnum {
    /**
     * 1xx Informational response
     *
     * An informational response indicates that the request was received and understood.
     * It is issued on a provisional basis while request processing continues.
     * It alerts the client to wait for a final response.
     * The message consists only of the status line and optional header fields, and is terminated by an empty line.
     * As the HTTP/1.0 standard did not define any 1xx status codes, servers must not
     * send a 1xx response to an HTTP/1.0 compliant client except under experimental conditions.
     */
    const CONTINUE_100                              = 100;
    const SWITCHING_PROTOCOLS_101                   = 101;
    const PROCESSING_102                            = 102; // WebDAV; RFC 2518
    const EARLY_HINTS_103                           = 103; //(RFC 8297)
    /**
     * 2xx Success
     *
     * This class of status codes indicates the action requested by the client was received, understood and accepted.
     */
    const OK_200                                    = 200;
    const CREATED_201                               = 201;
    const ACCEPTED_202                              = 202;
    const NON_AUTHORITATIVE_INFORMATION_203         = 203; // since_HTTP/1.1
    const NO_CONTENT_204                            = 204;
    const RESET_CONTENT_205                         = 205;
    const PARTIAL_CONTENT_206                       = 206;
    const MULTI_STATUS_207                          = 207; // WebDAV; RFC 4918
    const ALREADY_REPORTED_208                      = 208; // WebDAV; RFC 5842
    const IM_USED_226                               = 226; // RFC 3229
    /**
     * 3xx Redirection
     *
     * This class of status code indicates the client must take additional action to complete the request.
     * Many of these status codes are used in URL redirection.
     *
     * A user agent may carry out the additional action with no user interaction
     * only if the method used in the second request is GET or HEAD.
     * A user agent may automatically redirect a request.
     * A user agent should detect and intervene to prevent cyclical redirects
     */
    const MULTIPLE_CHOICES_300                      = 300;
    const MOVED_PERMANENTLY_301                     = 301;
    const FOUND_302                                 = 302;
    const SEE_OTHER_303                             = 303; // since_HTTP/1.1
    const NOT_MODIFIED_304                          = 304;
    const USE_PROXY_305                             = 305; // since_HTTP/1.1
    const SWITCH_PROXY_306                          = 306;
    const TEMPORARY_REDIRECT_307                    = 307; // since_HTTP/1.1
    const PERMANENT_REDIRECT_308                    = 308; // approved_as_experimental_RFC
    /**
     * 4xx Client errors
     *
     * This class of status code is intended for situations in which the error seems to have been caused by the client.
     * Except when responding to a HEAD request,
     * the server should include an entity containing an explanation of the error situation,
     * and whether it is a temporary or permanent condition.
     * These status codes are applicable to any request method.
     * User agents should display any included entity to the user.
     */
    const BAD_REQUEST_400                           = 400;
    const UNAUTHORIZED_401                          = 401;
    const PAYMENT_REQUIRED_402                      = 402;
    const FORBIDDEN_403                             = 403;
    const NOT_FOUND_404                             = 404;
    const METHOD_NOT_ALLOWED_405                    = 405;
    const NOT_ACCEPTABLE_406                        = 406;
    const PROXY_AUTHENTICATION_REQUIRED_407         = 407;
    const REQUEST_TIMEOUT_408                       = 408;
    const CONFLICT_409                              = 409;
    const GONE_410                                  = 410;
    const LENGTH_REQUIRED_411                       = 411;
    const PRECONDITION_FAILED_412                   = 412;
    const REQUEST_ENTITY_TOO_LARGE_413              = 413;
    const REQUEST_URI_TOO_LONG_414                  = 414;
    const UNSUPPORTED_MEDIA_TYPE_415                = 415;
    const REQUESTED_RANGE_NOT_SATISFIABLE_416       = 416;
    const EXPECTATION_FAILED_417                    = 417;
    const IM_A_TEAPOT_418                           = 418; // RFC 2324
    const AUTHENTICATION_TIMEOUT_419                = 419; // not_in_RFC 2616
    const ENHANCE_YOUR_CALM_420                     = 420; // Twitter
    const METHOD_FAILURE_420                        = 420; // Spring_Framework
    const UNPROCESSABLE_ENTITY_422                  = 422; // WebDAV; RFC 4918
    const LOCKED_423                                = 423; // WebDAV; RFC 4918
    const FAILED_DEPENDENCY_424                     = 424; // WebDAV; RFC 4918
    const METHOD_FAILURE_424                        = 424; // WebDAV)
    const UNORDERED_COLLECTION_425                  = 425; // Internet_draft
    const UPGRADE_REQUIRED_426                      = 426; // RFC 2817
    const PRECONDITION_REQUIRED_428                 = 428; // RFC 6585
    const TOO_MANY_REQUESTS_429                     = 429; // RFC 6585
    const REQUEST_HEADER_FIELDS_TOO_LARGE_431       = 431; // RFC 6585
    const NO_RESPONSE_444                           = 444; // Nginx
    const RETRY_WITH_449                            = 449; // Microsoft
    const BLOCKED_BY_WINDOWS_PARENTAL_CONTROLS_450  = 450; // Microsoft
    const REDIRECT_451                              = 451; // Microsoft
    const UNAVAILABLE_FOR_LEGAL_REASONS_451         = 451; // Internet_draft
    const REQUEST_HEADER_TOO_LARGE_494              = 494; // Nginx
    const CERT_ERROR_495                            = 495; // Nginx
    const NO_CERT_496                               = 496; // Nginx
    const HTTP_TO_HTTPS_497                         = 497; // Nginx
    const CLIENT_CLOSED_REQUEST_499                 = 499; // Nginx
    /**
     * 5xx Server errors
     *
     * The server failed to fulfill a request.
     * Response status codes beginning with the digit "5" indicate cases in which the server is aware
     * that it has encountered an error or is otherwise incapable of performing the request.
     * Except when responding to a HEAD request,
     * the server should include an entity containing an explanation of the error situation,
     * and indicate whether it is a temporary or permanent condition.
     * Likewise, user agents should display any included entity to the user.
     * These response codes are applicable to any request method.
     */
    const INTERNAL_SERVER_ERROR_500                 = 500;
    const NOT_IMPLEMENTED_501                       = 501;
    const BAD_GATEWAY_502                           = 502;
    const SERVICE_UNAVAILABLE_503                   = 503;
    const GATEWAY_TIMEOUT_504                       = 504;
    const HTTP_VERSION_NOT_SUPPORTED_505            = 505;
    const VARIANT_ALSO_NEGOTIATES_506               = 506; // RFC 2295
    const INSUFFICIENT_STORAGE_507                  = 507; // WebDAV; RFC 4918
    const LOOP_DETECTED_508                         = 508; // WebDAV; RFC 5842
    const BANDWIDTH_LIMIT_EXCEEDED_509              = 509; // Apache_bw/limited_extension
    const NOT_EXTENDED_510                          = 510; // RFC 2774
    const NETWORK_AUTHENTICATION_REQUIRED_511       = 511; // RFC 6585
    const NETWORK_READ_TIMEOUT_ERROR_598            = 598; // Unknown
    const NETWORK_CONNECT_TIMEOUT_ERROR_599         = 599; // Unknown

    /**
     * @param $code
     * @return string
     */
    static public function longDescription($code): string
    {
        switch($code)
        {
            case self::CONTINUE_100:
                return "The server has received the request headers and the client should proceed to send " .
                    "the request body (in the case of a request for which a body needs to be sent; for example, " .
                    "a POST request). Sending a large request body to a server after a request has been rejected " .
                    "for inappropriate headers would be inefficient. To have a server check the request's headers, " .
                    "a client must send Expect: 100-continue as a header in its initial request and receive a 100 " .
                    "Continue status code in response before sending the body. If the client receives an error code " .
                    "such as 403 (Forbidden) or 405 (Method Not Allowed) then it shouldn't send the request's body. " .
                    "The response 417 Expectation Failed indicates that the request should be repeated without the " .
                    "Expect header as it indicates that the server doesn't support expectations (this is the case, " .
                    "for example, of HTTP/1.0 servers).";
            case self::SWITCHING_PROTOCOLS_101:
                return "The requester has asked the server to switch protocols and the server has agreed to do so.";
            case self::PROCESSING_102:
                return "A WebDAV request may contain many sub-requests involving file operations, requiring a long " .
                    "time to complete the request. This code indicates that the server has received and is processing " .
                    "the request, but no response is available yet.[7] This prevents the client from timing out and " .
                    "assuming the request was lost.";
            case self::EARLY_HINTS_103:
                return "Used to return some response headers before final HTTP message.";
            case 1:
                return
                    "1xx Informational response \n" .

                    "An informational response indicates that the request was received and understood." .
                    "It is issued on a provisional basis while request processing continues." .
                    "It alerts the client to wait for a final response." .
                    "The message consists only of the status line and optional header fields, " .
                    "and is terminated by an empty line." .
                    "As the HTTP/1.0 standard did not define any 1xx status codes, servers must not" .
                    "send a 1xx response to an HTTP/1.0 compliant client except under experimental conditions.";

            case self::OK_200:
                return "Standard response for successful HTTP requests. The actual response will depend on the " .
                    "request method used. In a GET request, the response will contain an entity corresponding to " .
                    "the requested resource. In a POST request, the response will contain an entity describing or " .
                    "containing the result of the action.";
            case self::CREATED_201:
                return "The request has been fulfilled, resulting in the creation of a new resource.";
            case self::ACCEPTED_202:
                return "The request has been accepted for processing, but the processing has not been completed. " .
                    "The request might or might not be eventually acted upon, and may be disallowed when processing occurs.";
            case self::NON_AUTHORITATIVE_INFORMATION_203:
                return "Non-Authoritative Information (since HTTP/1.1)";
            case self::NO_CONTENT_204:
                return "The server successfully processed the request and is not returning any content.";
            case self::RESET_CONTENT_205:
                return "The server successfully processed the request, but is not returning any content. " .
                    "Unlike a 204 response, this response requires that the requester reset the document view.";
            case self::PARTIAL_CONTENT_206:
                return "The server is delivering only part of the resource (byte serving) due to a " .
                    "range header sent by the client. The range header is used by HTTP clients to enable " .
                    "resuming of interrupted downloads, or split a download into multiple simultaneous streams.";
            case self::MULTI_STATUS_207:
                return "The message body that follows is by default an XML message and can contain a number of " .
                    "separate response codes, depending on how many sub-requests were made.";
            case self::ALREADY_REPORTED_208:
                return "The members of a DAV binding have already been enumerated in a preceding part of " .
                    "the (multistatus) response, and are not being included again.";
            case self::IM_USED_226:
                return "The server has fulfilled a request for the resource, and the response is a " .
                    "representation of the result of one or more instance-manipulations applied to the current instance.";
            case 2:
                return
                    "2xx Success \n" .

                    "This class of status codes indicates the action requested by the client was received, " .
                    "understood and accepted.";

            case self::MULTIPLE_CHOICES_300:
            case self::MOVED_PERMANENTLY_301:
            case self::FOUND_302:
            case self::SEE_OTHER_303:
            case self::NOT_MODIFIED_304:
            case self::USE_PROXY_305:
            case self::SWITCH_PROXY_306:
            case self::PERMANENT_REDIRECT_308:
            case 3:
                return
                    "3xx Redirection \n" .

                    "This class of status code indicates the client must take additional action " .
                    "to complete the request. Many of these status codes are used in URL redirection.\n" .

                    "A user agent may carry out the additional action with no user interaction " .
                    "only if the method used in the second request is GET or HEAD. " .
                    "A user agent may automatically redirect a request. " .
                    "A user agent should detect and intervene to prevent cyclical redirects.\n" .

                    "Detailed description not yet implemented";

            case self::BAD_REQUEST_400:
            case self::UNAUTHORIZED_401:
            case self::PAYMENT_REQUIRED_402:
            case self::FORBIDDEN_403:
            case self::NOT_FOUND_404:
            case self::METHOD_NOT_ALLOWED_405:
            case self::NOT_ACCEPTABLE_406:
            case self::PROXY_AUTHENTICATION_REQUIRED_407:
            case self::REQUEST_TIMEOUT_408:
            case self::CONFLICT_409:
            case self::GONE_410:
            case self::LENGTH_REQUIRED_411:
            case self::PRECONDITION_FAILED_412:
            case self::REQUEST_ENTITY_TOO_LARGE_413:
            case self::REQUEST_URI_TOO_LONG_414:
            case self::UNSUPPORTED_MEDIA_TYPE_415:
            case self::REQUESTED_RANGE_NOT_SATISFIABLE_416:
            case self::EXPECTATION_FAILED_417:
            case self::IM_A_TEAPOT_418:
            case self::AUTHENTICATION_TIMEOUT_419:
            case self::METHOD_FAILURE_420:
            case self::ENHANCE_YOUR_CALM_420:
            case self::UNPROCESSABLE_ENTITY_422:
            case self::LOCKED_423:
            case self::FAILED_DEPENDENCY_424:
            case self::METHOD_FAILURE_424:
            case self::UNORDERED_COLLECTION_425:
            case self::UPGRADE_REQUIRED_426:
            case self::PRECONDITION_REQUIRED_428:
            case self::TOO_MANY_REQUESTS_429:
            case self::REQUEST_HEADER_FIELDS_TOO_LARGE_431:
            case self::NO_RESPONSE_444:
            case self::RETRY_WITH_449:
            case self::BLOCKED_BY_WINDOWS_PARENTAL_CONTROLS_450:
            case self::REDIRECT_451:
            case self::UNAVAILABLE_FOR_LEGAL_REASONS_451:
            case self::REQUEST_HEADER_TOO_LARGE_494:
            case self::CERT_ERROR_495:
            case self::NO_CERT_496:
            case self::HTTP_TO_HTTPS_497:
            case self::CLIENT_CLOSED_REQUEST_499:
            case 4:
                return
                    "4xx Client errors \n" .

                    "This class of status code is intended for situations in which " .
                    "the error seems to have been caused by the client. Except when responding to a HEAD request, " .
                    "the server should include an entity containing an explanation of the error situation, " .
                    "and whether it is a temporary or permanent condition. " .
                    "These status codes are applicable to any request method. " .
                    "User agents should display any included entity to the user.\n" .

                    "Detailed description not yet implemented";

            case self::INTERNAL_SERVER_ERROR_500:
            case self::NOT_IMPLEMENTED_501:
            case self::BAD_GATEWAY_502:
            case self::SERVICE_UNAVAILABLE_503:
            case self::GATEWAY_TIMEOUT_504:
            case self::HTTP_VERSION_NOT_SUPPORTED_505:
            case self::VARIANT_ALSO_NEGOTIATES_506:
            case self::INSUFFICIENT_STORAGE_507:
            case self::LOOP_DETECTED_508:
            case self::BANDWIDTH_LIMIT_EXCEEDED_509:
            case self::NOT_EXTENDED_510:
            case self::NETWORK_AUTHENTICATION_REQUIRED_511:
            case self::NETWORK_READ_TIMEOUT_ERROR_598:
            case self::NETWORK_CONNECT_TIMEOUT_ERROR_599:
            case 5:
                return
                    "5xx Server errors \n" .

                    "The server failed to fulfill a request. " .
                    "Response status codes beginning with the digit \"5\" indicate cases in which the server is aware" .
                    "that it has encountered an error or is otherwise incapable of performing the request." .
                    "Except when responding to a HEAD request," .
                    "the server should include an entity containing an explanation of the error situation," .
                    "and indicate whether it is a temporary or permanent condition." .
                    "Likewise, user agents should display any included entity to the user." .
                    "These response codes are applicable to any request method. \n" .

                    "Detailed description not yet implemented";
            default:
                return "UNKNOWN HTTP/HTTPS ERROR CODE";
        }
    }
}