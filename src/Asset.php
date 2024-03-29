<?php

namespace TinyPixel\Blocks;

use TinyPixel\Blocks\Contracts\AssetInterface;

class Asset implements AssetInterface
{
    /**
     * Name.
     *
     * @var string
     */
    public $name;

    /**
     * Url.
     *
     * @var string
     */
    public $url;

    /**
     * Dependencies.
     *
     * @var array
     */
    public $dependencies;

    /**
     * Version
     *
     * @var string
     */
    public $version;

    /**
     * Manifest
     *
     * @var object
     */
    public $manifest;

    /**
     * Class constructor
     */
    public function __construct($container)
    {
        $this->container = $container;
    }

    /**
     * Get asset name
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Set asset name
     *
     * @param  string
     * @return AssetInterface
     */
    public function setName(string $name): AssetInterface
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get asset url
     *
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * Set asset url
     *
     * @param  string
     * @return AssetInterface
     */
    public function setUrl(string $url): AssetInterface
    {
        $this->url = $this->container->get('project')['base_url'] . $this->container->get('project')['dist'] . '/' . $url;

        return $this;
    }

    /**
     * Get dependencies
     *
     * @return array
     */
    public function getDependencies(): array
    {
        return $this->dependencies ?: [];
    }

    /**
     * Set dependencies
     *
     * @param  array
     * @return AssetInterface
     */
    public function setDependencies(array $dependencies): AssetInterface
    {
        $this->dependencies = $dependencies;

        return $this;
    }

    /**
     * Get version
     *
     * @return array
     */
    public function getVersion()
    {
        return $this->version ?: null;
    }

    /**
     * Set version
     *
     * @param  string
     * @return AssetInterface
     */
    public function setVersion(string $version): AssetInterface
    {
        $this->version = $version;

        return $this;
    }

    /**
     * Get manifest.
     *
     * @return object; null if none
     */
    public function getManifest()
    {
        return $this->manifest;
    }

    /**
     * Set manifest.
     *
     * @param  string
     * @return AssetInterface
     */
    public function setManifest(string $manifest): AssetInterface
    {
        $path = $this->container->get('project')['base_path'] . '/dist/' . $manifest;

        if (file_exists($path)) {
            $this->manifest = (object) require $path;
        }

        return $this;
    }
}
