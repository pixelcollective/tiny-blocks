<?php

namespace TinyPixel\Blocks\Contracts;

use TinyPixel\Blocks\Contracts\AssetInterface;
use TinyPixel\Blocks\Contracts\ViewInterface;

interface BlockInterface
{
    public function makeAsset(string $type): AssetInterface;

    public function build(): void;

    public function withDomain(string $name): string;

    public function with(array $data): array;

    public function getName(): string;

    public function setName(string $blockname): BlockInterface;

    public function getData();

    public function setData(array $data): BlockInterface;

    public function getView(): ViewInterface;

    public function setView(ViewInterface $view): BlockInterface;

    public function getTemplate();

    public function setTemplate(string $template): BlockInterface;

    public function getClassName();

    public function setClassName(string $template): BlockInterface;
}
