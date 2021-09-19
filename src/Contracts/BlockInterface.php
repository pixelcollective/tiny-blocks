<?php

namespace TinyPixel\Blocks\Contracts;

use TinyPixel\Blocks\Contracts\AssetInterface;
use TinyPixel\Blocks\Contracts\ViewInterface;

interface BlockInterface
{
    public function initializeAssetCollections(): void;

    public function setupAssets(): void;

    public function makeAsset(): AssetInterface;

    public function with(array $data): array;

    public function getName(): string;

    public function setName(string $blockname): void;

    public function getData();

    public function setData(array $data): void;

    public function getView(): ViewInterface;

    public function setView(ViewInterface $view);

    public function getTemplate();

    public function setTemplate(string $template): BlockInterface;
}
