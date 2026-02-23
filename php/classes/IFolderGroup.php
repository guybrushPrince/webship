<?php

namespace webship;

use ship\interoperability\IShippable;
use webship\core\HtmlView;

/**
 * Interface IFolderGroup.
 *
 * @package webship
 * @subpackage php/classes
 *
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 * @version 1.0.0
 */
interface IFolderGroup {

    /**
     * Whether the group is folded, or not.
     * @return bool
     */
    public function isFolded() : bool;

    /**
     * Get the items (objects) referred to this group.
     * @return IShippable[]
     */
    public function getItems() : array;

    /**
     * Get the priority of the group.
     * @return int
     */
    public function getPriority() : int;

    /**
     * Get the title of the group.
     * @param int $language The language.
     * @return string
     */
    public function getTitle(int $language) : string;

    /**
     * Get the title of a specific item.
     * @param IShippable $item The item.
     * @param int $language The language.
     * @return string
     */
    public function getItemTitle(IShippable $item, int $language) : string;

    /**
     * Creates a view of the items.
     * @param IFolderGroup[] $allGroups All folder groups.
     * @return HtmlView
     */
    public function createView(array $allGroups) : HtmlView;
}
?>