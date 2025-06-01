<?php

declare(strict_types=1);

namespace UksusoFF\WebtreesModules\Faces\Entity;

final class MediaFileData
{
    public function __construct(
        public int $id,
        public string $mediaId,
        public string $filename,
        public int $order,
        public int $treeId,
        public $coordinates,
        public ?string $sznupaId,
    ) {}
}