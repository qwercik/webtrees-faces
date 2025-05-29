<?php

declare(strict_types=1);

namespace UksusoFF\WebtreesModules\Faces\Helpers;

use Fisharebest\Webtrees\MediaFile;
use Fisharebest\Webtrees\Registry;
use Fisharebest\Webtrees\Tree;
use UksusoFF\WebtreesModules\Faces\Entity\Face;
use UksusoFF\WebtreesModules\Faces\Entity\MediaFileData;
use UksusoFF\WebtreesModules\Faces\Exceptions\SznupaNotConfiguredException;
use UksusoFF\WebtreesModules\Faces\SznupaApi;

class SznupaHelper
{
    public static function isConfigured(): bool
    {
        try {
            SznupaApi::create();
            return true;
        } catch (SznupaNotConfiguredException) {
            return false;
        }
    }

    public static function createImage(Tree $tree, MediaFile $mediaFile, MediaFileData $data): string
    {
        $api = SznupaApi::create();
        $response = $api->createImage(
            static::getPath($tree, $mediaFile),
            static::createPayload($data)
        );
        return $response['id'];
    }

    public static function updateImage(string $sznupaId, Tree $tree, MediaFile $mediaFile, MediaFileData $data): void
    {
        $api = SznupaApi::create();
        $api->updateImage(
            $sznupaId,
            static::getPath($tree, $mediaFile),
            static::createPayload($data)
        );
    }

    public static function deleteFace(MediaFileData $data, ?string $pid, ?string $sznupaFaceId): void
    {
        $api = SznupaApi::create();
        if ($sznupaFaceId !== null) {
            $api->deleteFace($sznupaFaceId);
        } elseif ($pid !== null) {
            $api->deleteImageFaceByPid($data->sznupaId, $pid);
        }
    }

    public static function createPayload(MediaFileData $data): array
    {
        $faces = array_map(
            fn($face) => Face::fromDb($face)->toApi(),
            $data->coordinates
        );
        return ['faces' => $faces];
    }

    public static function getPath(Tree $tree, MediaFile $mediaFile): string
    {
        $basePath = Registry::filesystem()->dataName();
        $treeFolder = $tree->getPreference('MEDIA_DIRECTORY');
        return $basePath . $treeFolder . $mediaFile->filename();
    }

    public static function isEnabled(): bool
    {
        return $_SESSION['sznupa_enabled'] ?? false;       
    }

    public static function setEnabled(bool $enabled): void
    {
        $_SESSION['sznupa_enabled'] = $enabled;
    }

    public static function setWarning(string $message): void
    {
        $_SESSION['sznupa_warning'] = $message;
    }

    public static function popWarning(): ?string
    {
        $message = $_SESSION['sznupa_warning'] ?? null;
        unset($_SESSION['sznupa_warning']);
        return $message;
    }
}
