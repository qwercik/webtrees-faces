<?php

declare(strict_types=1);

namespace UksusoFF\WebtreesModules\Faces\Repository;

use Fisharebest\Webtrees\DB;
use Fisharebest\Webtrees\Media;
use UksusoFF\WebtreesModules\Faces\Entity\MediaFileData;

class MediaFileRepository
{
    public function getData(Media $media, int $order): ?MediaFileData
    {
        return $this->getMediaFileData(
            $media->tree()->id(),
            $media->xref(),
            $order
        );
    }

    public function getDataById(int $id): ?MediaFileData
    {
        $data = DB::table('media_faces')
            ->where('f_id', '=', $id)
            ->select()
            ->first();

        if ($data === null) {
            return null;
        }

        return new MediaFileData(
            id: (int)$data->f_id,
            mediaId: $data->f_m_id,
            filename: $data->f_m_filename,
            order: (int)$data->f_m_order,
            treeId: (int)$data->f_m_tree,
            coordinates: json_decode($data->f_coordinates, true),
            sznupaId: $data->f_sznupa_id
        );
    }

    public function getMediaFileData(int $tree, string $media, int $order): ?MediaFileData {
        $row = DB::table('media_faces')
            ->where('f_m_id', '=', $media)
            ->where('f_m_order', '=', $order)
            ->where('f_m_tree', '=', $tree)
            ->select()
            ->first();

        if ($row === null) {
            return null;
        }

        return new MediaFileData(
            id: (int)$row->f_id,
            mediaId: $media,
            filename: $row->f_m_filename,
            order: $order,
            treeId: $tree,
            coordinates: json_decode($row->f_coordinates, true),
            sznupaId: $row->f_sznupa_id
        );
    }

    public function setSznupaId(int $id, string $sznupaId): void
    {
        DB::table('media_faces')
            ->where('f_id', $id)
            ->update(['f_sznupa_id' => $sznupaId]);
    }

    public function findUnindexedMediaFiles(int $limit): array
    {
        return DB::table('media_faces')
            ->limit($limit)
            ->whereNull('f_sznupa_id')
            ->pluck('f_id')
            ->toArray();
    }

    public function insertFacesData(
        int $tree,
        string $media,
        int $order,
        string $filename
    ): int
    {
        return DB::table('media_faces')->insertGetId([
            'f_m_id' => $media,
            'f_m_order' => $order,
            'f_m_tree' => $tree,
            'f_coordinates' => json_encode([]),
            'f_m_filename' => $filename,
        ]);
    }
}
