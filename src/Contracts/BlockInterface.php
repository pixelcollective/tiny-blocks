<?php
namespace TinyBlocks\Contracts;

use \Illuminate\Support\Collection;
use TinyBlocks\Contracts\ViewInterface;

/**
 * Block interface
 *
 * @package TinyBlocks
 * @subpackage Contracts
 */
interface BlockInterface
{
    public function getName() : string;

    public function setName(string $blockname) : void;

    public function getData() : Collection;

    public function setData(Collection $data) : void;
}
