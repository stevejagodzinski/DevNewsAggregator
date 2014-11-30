<?php

class RemoteHTMLContentRequester {

    private $urlArray;
    private $curlHandles = array();
    private $curlMultiHandle;
    private $results = array();

    function __construct($urlArray) {
        $this->urlArray = $urlArray;
    }

    public function fetchAsynchronously() {
        $this->curlMultiHandle = curl_multi_init();

        $this->initCurlHandles();
        $this->initCurlMultiHandles();
        $this->validateCurlHandles();
        $this->executeRequests();
        $this->buildResults();
        $this->closeCurlHandles();
        $this->validateResults();
        return $this->results;
    }

    private function initCurlHandles() {
        foreach($this->urlArray as $i => $url) {
            $this->curlHandles[$i] = curl_init($url);
            curl_setopt($this->curlHandles[$i], CURLOPT_RETURNTRANSFER, 1);
        }
    }

    private function initCurlMultiHandles() {
        foreach($this->urlArray as $i => $url) {
            curl_multi_add_handle($this->curlMultiHandle, $this->curlHandles[$i]);
        }
    }

    private function executeRequests() {
        $isStillRunning = null;

        do {
            $resultCode = curl_multi_exec($this->curlMultiHandle, $isStillRunning);
        } while ($resultCode == CURLM_CALL_MULTI_PERFORM);

        while ($isStillRunning && $resultCode == CURLM_OK) {
            // Block until response or sleep for 500ms if error detecting response signal
            if (curl_multi_select($this->curlMultiHandle) == -1) {
                usleep(100000);
            }
            do {
                $resultCode = curl_multi_exec($this->curlMultiHandle, $isStillRunning);
            } while ($resultCode == CURLM_CALL_MULTI_PERFORM);
        }
    }

    private function closeCurlHandles() {
        foreach($this->curlHandles as $curlHandle) {
            curl_multi_remove_handle($this->curlMultiHandle,$curlHandle);
        }
        curl_multi_close($this->curlMultiHandle);
    }

    private function buildResults() {
        foreach($this->curlHandles as $curlHandle) {
            curl_multi_select($this->curlMultiHandle);
            $this->results[] = curl_multi_getcontent($curlHandle);
        }
    }

    private function validateCurlHandles() {
        $requestCount = count($this->urlArray);
        $curlHandleCount = count($this->curlHandles);
        assert( $curlHandleCount == $requestCount,
            "Must have the same number of curl handles as URLs requested. Requested: $requestCount. Curl Handles: $curlHandleCount.");
    }

    private function validateResults() {
        $requestCount = count($this->urlArray);
        $resultCount = count($this->results);
        assert( $resultCount == $requestCount,
            "Must have the same number of results as URLs requested. Requested: $requestCount. Received: $resultCount.");
    }

    public function fetchSynchronously() {
        $this->results = array();
        foreach($this->urlArray as $url) {
            $this->results[] = self::fetchSynchronouslyForURL($url);
        }

        $this->validateResults();
        return $this->results;
    }

    private static function fetchSynchronouslyForURL($url) {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }
} 