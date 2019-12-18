<?php

namespace TinyBlocks\Contracts;

use \Illuminate\Support\Collection;
use \Psr\Container\ContainerInterface as Container;
use TinyBlocks\Contracts\ViewInterface;

/**
 * Block interface
 *
 * @package TinyBlocks
 * @subpackage Contracts
 */
interface BlockInterface
{
    public function initializeAssetCollections(): void;

    public function setupAssets(): void;

    public function with(array $data): array;

    public function getName(): string;

    public function setName(string $blockname): void;

    public function getData();

    public function setData(array $data): void;

    public function getView();

    public function setView(string $view);

    public function getViewInstance(): ViewInterface;

    public function setViewInstance(ViewInterface $view);

    public function getTemplate();

    public function setTemplate(string $template): BlockInterface;
}
