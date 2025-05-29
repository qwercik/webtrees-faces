<?php

namespace UksusoFF\WebtreesModules\Faces;

use CURLFile;
use CurlHandle;
use Fisharebest\Webtrees\I18N;
use Fisharebest\Webtrees\Site;
use UksusoFF\WebtreesModules\Faces\Exceptions\SznupaException;
use UksusoFF\WebtreesModules\Faces\Exceptions\SznupaInvalidCredentialsException;
use UksusoFF\WebtreesModules\Faces\Exceptions\SznupaNotConfiguredException;
use UksusoFF\WebtreesModules\Faces\Exceptions\SznupaUnavailableException;

class SznupaApi
{
    public function __construct(
        protected string $apiUrl,
        protected string $apiKey,
    ) {}

    public static function create(): self
    {
        $api_url = Site::getPreference('sznupa_api_url');
        $api_key = Site::getPreference('sznupa_api_key');
        if (empty($api_url) || empty($api_key)) {
            throw new SznupaNotConfiguredException();
        }

        return new self($api_url, $api_key);
    }

    public function fetchFaces(string $imageId): array
    {
        return $this->get("/images/{$imageId}")['faces'];
    }

    public function createImage(string $filePath, array $payload): array
    {
        return $this->imageRequest('POST', '/images', $filePath, $payload);
    }

    public function updateImage(string $imageId, string $filePath, array $payload): array
    {
        return $this->imageRequest('PUT', "/images/{$imageId}", $filePath, $payload);
    }

    public function deleteImage(string $imageId): array
    {
        $ch = $this->makeCurl("/images/{$imageId}", 'application/json');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
        return $this->getJsonResponse($ch, [200]);
    }

    public function deleteFace(string $faceId): array
    {
        $ch = $this->makeCurl("/images/faces/{$faceId}", 'application/json');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
        return $this->getJsonResponse($ch, [200]);
    }

    public function deleteImageFaceByPid(string $imageId, string $pid): array
    {
        $pid = urlencode($pid);
        $ch = $this->makeCurl("/images/{$imageId}/faces?pid={$pid}", 'application/json');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
        return $this->getJsonResponse($ch, [200]);
    }

    protected function get(string $url): array
    {
        $ch = $this->makeCurl($url, 'application/json');
        return $this->getJsonResponse($ch, [200]);
    }

    protected function imageRequest(string $method, string $url, string $filePath, array $payload): array
    {
        $ch = $this->makeCurl($url, 'multipart/form-data');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, [
            'file' => new CURLFile($filePath),
            'payload' => json_encode($payload)
        ]);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        return $this->getJsonResponse($ch, [200]);
    }

    private function makeCurl(string $url, string $contentType): CurlHandle
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "{$this->apiUrl}{$url}");
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Accept: application/json',
            'Content-Type: ' . $contentType,
            'X-API-KEY: ' . $this->apiKey
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        return $ch;
    }

    private function getJsonResponse(CurlHandle $ch, array $expectedStatusCodes): array
    {
        $response = curl_exec($ch);
        $errno = curl_errno($ch);
        if ($errno === 7) {
            throw new SznupaUnavailableException();
        } elseif ($errno > 0) {
            throw new SznupaException('');
        }
        $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($statusCode === 401) {
            throw new SznupaInvalidCredentialsException();
        }

        if (!in_array($statusCode, $expectedStatusCodes, true)) {
            throw new SznupaException(I18N::translate('LBL_SZNUPA_ERROR'));
        }

        $json = json_decode($response, true);
        if ($json === null) {
            throw new SznupaException(I18N::translate('LBL_SZNUPA_ERROR'));
        }

        return $json;
    }
}