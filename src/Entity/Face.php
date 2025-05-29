<?php

declare(strict_types=1);

namespace UksusoFF\WebtreesModules\Faces\Entity;

class Face
{
    public function __construct(
        public readonly string $pid,
        public readonly array $coords,
    ) {
    }

    public static function fromDb(array $data): self
    {
        return new self(
            pid: $data['pid'],
            coords: $data['coords'],
        );
    }

    public function toApi(): array
    {
        [$x1, $y1, $x2, $y2] = $this->coords;
        return [
            'pid' => $this->pid,
            'area' => [
                'left' => (int)$x1,
                'top' => (int)$y1,
                'width' => (int)($x2 - $x1),
                'height' => (int)($y2 - $y1),
            ]
        ];
    }
}
