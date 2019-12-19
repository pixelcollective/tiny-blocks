<?php

namespace TinyBlocks\Contracts;

/**
 * Asset Interface
 *
 * @package TinyBlocks
 * @subpackage Contracts
 */
interface AssetInterface
{
    public function getName(): string;

    public function setName(string $name): AssetInterface;

    public function getUrl(): string;

    public function setUrl(string $url): AssetInterface;

    public function getDependencies(): array;

    public function setDependencies(array $dependencies): AssetInterface;

    public function getVersion();

    public function setVersion(string $version): AssetInterface;

    public function getManifest();

    public function setManifest(string $manifest): AssetInterface;
}
