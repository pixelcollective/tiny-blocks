<?php
namespace TinyBlocks\Contracts;

use \Illuminate\Support\Collection;
use \Psr\Container\ContainerInterface as Container;

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
