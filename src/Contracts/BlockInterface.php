<?php
namespace TinyPixel\Modules\Contracts;

interface BlockInterface
{
    public function getBlockName() : string;

    public function setBlockName(string $blockname) : void;
}
