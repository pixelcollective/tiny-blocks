<?php

namespace TinyPixel\Blocks\Contracts;

use Illuminate\Support\Collection;

interface AssetsInterface
{
    public function enqueueEditorAssets(Collection $blocks): AssetsInterface;
    public function enqueuePublicAssets(Collection $blocks): AssetsInterface;
}
