<?php

use ship\interoperability\IShippable;
use ship\interoperability\ShipperRegister;
use webship\containers\StandardHtmlView;
use webship\inputs\EditableHtmlView;

/**
 * Class FolderHtmlView.
 *
 * @package webship
 * @subpackage php/model/containers
 *
 * @author Dr. Dipl.-Inf. Thomas M. Prinz
 * @version 1.1.0
 */
class FolderHtmlView extends StandardHtmlView {

    /**
     * Constructor.
     * @param string[]|string $classes Some classes.
     */
    private function __construct(array|string $classes = []) {
        if (!is_array($classes)) $classes = [ $classes ];
        $classes[] = 'folders';
        parent::__construct($classes);

        $this->style(Tools::css('.folders', [' ', '.f-group'], 'padding: 0 5px', 'position: relative',
            'width: 100%', 'border: 1px solid var(--border-2)', 'margin-bottom: 20px', 'min-height: 30px',
            'margin-top: 10px', 'border-radius: 5px', '-webkit-box-shadow: var(--shadow-flow-1)',
            'box-shadow: var(--shadow-flow-1)'));
        $this->style(Tools::css('.folders', [' ', '.f-group', ' > ', '.folder-title'], 'position: absolute',
            'top: 5px', 'left: 10px', 'width: 90%',
            'padding-right: 10px', 'display: flex', 'flex-direction: row', 'flex-wrap: nowrap', 'height: 20px',
            'align-items: center', 'cursor: pointer'));
        $this->style(Tools::css('.folders', [' ', '.f-group', ' > ', '.folder-title', ' ',
            'span[data-field]:first-child'], 'font-weight: bold', 'display: flex', 'flex-direction: row'));
        $this->style(Tools::css('.folders', [' ', '.f-group', ' > ', '.folder-title', ' ',
            'span[data-field]:first-child:after'], 'content: \':\'', 'display: block', 'margin-right: 5px'));
        $this->style(Tools::css('.folders', [' ', '.f-group', ' > ', '.folder-title', ' > textarea'],
            'width: 100%'));
        $this->style(Tools::css('.folders', [' ', '.f-group', ' > ', '.folder-title', ' > .pencil'],
            'display: none', 'margin-left: 10px'));
        $this->style(Tools::css('.folders', [' ', '.f-group', ' > ', '.folder-title:hover', ' > .pencil'],
            'display: inherit'));
        $this->style(Tools::css('.folders', [' ', '.f-group', ' > ', '.box'], 'left: 0', 'top: 0',
            'position: absolute', 'width: 100%', 'height: 30px', 'background: var(--system-background)',
            'border-radius: 5px 5px 0 0'));
        $this->style(Tools::css('.folders', [' ', '.f-group', ' > ', '.content'],
            'margin: 40px 0 10px 0'));

        /*$this->style(Tools::css('.surveys', [' ', '.survey-group', ' > ', '.menu'],
            'height: 300px', 'overflow-y: auto'));
        $this->style(Tools::css('.surveys', [' ', '.survey-group', ' > ', '.menu', ' ', 'li.menu-entry'],
            'min-height: 15px', 'font-size: 12px'));
        $this->style(Tools::css('.surveys', [' ', '.survey-group', ' > ', '.menu', ' ', 'li.menu-entry', ' > ',
            'span:not(:empty):first-child:after'], 'content: \':\'', 'margin-right: 5px', 'font-weight: bold'));*/

        $this->style(Tools::css('.folders', [' ', '.f-group.folded', ' > ', '.content'], 'display: none'));
        $this->style(Tools::css('.folders', [' ', '.f-group:not(.folded)', ' ', '#toggle-group-min'],
            'display: inherit'));
        $this->style(Tools::css('.folders', [' ', '.f-group.folded', ' ', '#toggle-group-min'],
            'display: none'));
        $this->style(Tools::css('.folders', [' ', '.f-group.folded', ' ', '#toggle-group-max'],
            'display: inherit'));
        $this->style(Tools::css('.folders', [' ', '.f-group:not(.folded)', ' ', '#toggle-group-max'],
            'display: none'));
    }

    /**
     * Creates a folder view.
     * @param IFolderGroup[] $folders A list of folders of type IFolderGroup.
     * @param CliffSerializable[]|null $allItems An optional list of all items/objects in all groups.
     * @param Closure|null $refreshFunction A function (FolderHtmlView) : void to refresh the view.
     * @param Closure|null $addFolderFunction A function (FolderHtmlView, StandardButtonHtmlView) : void to add functionality to an add-folder-button.
     * @param Closure|null $unlistedFunction A function (FolderHtmlView, CliffSerializable[], IFolderGroup[]) : HtmlView to create a view of the unlisted items.
     * @param Closure|null $addFunction A function (CliffSerializable, IFolderGroup, HtmlView, HtmlView, string) : void to add adding-functionality.
     * @param Closure|null $deleteFunction A function (StandardButtonHtmlView, HtmlView) : void to add delete-functionality.
     * @param Closure|null $toggleFunction A function (IFolderGroup, StandardButtonHtmlView, HtmlView) : void to add toggle-functionality.
     * @param Closure|null $downFunction A function (IFolderGroup, StandardButtonHtmlView, string) : void to add down-functionality.
     * @param Closure|null $upFunction A function (IFolderGroup, StandardButtonHtmlView, string) : void to add up-functionality.
     * @param string[]|string $classes Some classes.
     * @return FolderHtmlView
     */
    public static function create(array $folders, array $allItems = null, ?Closure $refreshFunction = null,
                                  ?Closure $addFolderFunction = null, ?Closure $unlistedFunction = null,
                                  ?Closure $addFunction = null, ?Closure $deleteFunction = null,
                                  ?Closure $toggleFunction = null, ?Closure $downFunction = null,
                                  ?Closure $upFunction = null, array|string $classes = []) : FolderHtmlView {
        $folder = new FolderHtmlView($classes);
        if ($refreshFunction) {
            $folder->ref('main-folder-list');
            $refreshFunction($folder);
        }

        if (!Tools::isEmpty($folders) && $folders[array_key_first($folders)] instanceof CliffSerializable &&
            $addFolderFunction) {
            $control = ControlPanelHtmlView::create(1, 1, false);
            $control->fix();
            $folder->add($control);

            $groupAdder = $control->button('add-folder', '/sunstone/pic/add-folder.svg');
            $groupAdder->notice(Language::ENGLISH, 'Create new folder');
            $groupAdder->notice(Language::GERMAN,  'Neuen Ordner erstellen');
            $addFolderFunction($folder, $groupAdder);
        }

        // Determine the priority order
        usort($folders, function(IFolderGroup $a, IFolderGroup $b) {
            $v1 = $a->getPriority();
            $v2 = $b->getPriority();
            if ($v1 == $v2) {
                return 0;
            }
            return ($v1 > $v2) ? -1 : 1;
        });

        // Print the folder groups
        $list = $folder->add(new HtmlView());
        $list->each($folders, function(HtmlView $v, IFolderGroup $folderGroup) use ($folders, $allItems, $addFunction,
            $deleteFunction, $toggleFunction, $downFunction, $upFunction) {
            $v->add(self::createFolder($folderGroup, $folders, $allItems, $addFunction, $deleteFunction,
                $toggleFunction, $downFunction, $upFunction));
        });


        // Print the items that are non-referred.
        if ($allItems && $unlistedFunction) {
            // Determine ungrouped items
            $nonReferred = array_filter($allItems, function (CliffSerializable $item) use ($folders) {
                return Tools::isEmpty(array_filter($folders, function (IFolderGroup $group) use ($item) {
                    return Tools::inArray($group->getItems(), $item);
                }));
            });
            $unlistedFunction($folder, $nonReferred, $folders);
        }

        return $folder;
    }

    /**
     * Creates a single folder.
     * @param IFolderGroup $folderGroup The folder.
     * @param IFolderGroup[] $folderGroups All folder groups.
     * @param CliffSerializable[]|null $allItems All items.
     * @param Closure|null $addFunction A function (CliffSerializable, IFolderGroup, HtmlView, HtmlView, string) : void to add adding-functionality.
     * @param Closure|null $deleteFunction A function (StandardButtonHtmlView, HtmlView) : void to add delete-functionality.
     * @param Closure|null $toggleFunction A function (IFolderGroup, StandardButtonHtmlView, HtmlView) : void to add toggle-functionality.
     * @param Closure|null $downFunction A function (IFolderGroup, StandardButtonHtmlView, string) : void to add down-functionality.
     * @param Closure|null $upFunction A function (IFolderGroup, StandardButtonHtmlView, string) : void to add up-functionality.
     * @return HtmlView
     */
    public static function createFolder(IFolderGroup $folderGroup, array $folderGroups, array $allItems = null,
                                        ?Closure $addFunction = null,
                                        ?Closure $deleteFunction = null,
                                        ?Closure $toggleFunction = null,
                                        ?Closure $downFunction = null,
                                        ?Closure $upFunction = null) : HtmlView {
        $view = new HtmlView('f-group');
        if ($folderGroup instanceof IShippable) {
            $view->ref('folderGroup-' . ShipperRegister::getKey($folderGroup));
        }
        if ($folderGroup->isFolded()) $view->addClass('folded');

        $view->add(new HtmlView('box'));

        $title = $view->add(new HtmlView('folder-title'));
        if ($folderGroup instanceof ITextBoxUser) {
            $titleView = $title->add(EditableHtmlView::create());
            $titleView->ref('text', 'value');
            $titleView->texts($folderGroup->getTitle(self::getLanguage()));
            $titleView->protocol(Event::ONFOCUSOUT)->
                doIt('ChangeTextField', [
                    'part' => $folderGroup,
                    'language' => self::getLanguage(),
                    'fieldName' => 'header'
                ], 'texts');
            $title->add(PencilHtmlView::_new());
        } else {
            $title->texts($folderGroup->getTitle(self::getLanguage()));
        }

        $adder = null;
        if ($addFunction || $deleteFunction || $toggleFunction || $downFunction || $upFunction) {
            $control = ControlPanelHtmlView::createUnbounded();
            $control->fix();
            $view->add($control);

            if ($addFunction) $adder = $control->button('add-to-folder', '/sunstone/pic/add.svg');

            if (Tools::isEmpty($folderGroup->getItems()) && $deleteFunction) {
                $deleteButton = $control->delete('delete-group');
                $deleteFunction($deleteButton, $view);
            }
            if ($toggleFunction) {
                $toggleButtonMin = $control->button('toggle-group-min', '/sunstone/pic/minimize.svg');
                $toggleButtonMax = $control->button('toggle-group-max', '/sunstone/pic/maximize.svg');
                $toggleFunction($folderGroup, $toggleButtonMin, $view);
                $toggleFunction($folderGroup, $toggleButtonMax, $view);
            }
            if ($upFunction) {
                $upButton = $control->button('up-group', '/sunstone/pic/up.svg');
                $upFunction($folderGroup, $upButton, 'main-folder-list');
            }
            if ($downFunction) {
                $downButton = $control->button('down-group', '/sunstone/pic/down.svg');
                $downFunction($folderGroup, $downButton, 'main-folder-list');
            }
        }

        $content = $view->add(new HtmlView('content'));
        if (!$folderGroup->isFolded()) {
            $content->add($folderGroup->createView($folderGroups));
        }

        // Items to add
        if ($allItems && $addFunction) {
            $posItems = array_filter($allItems, function (CliffSerializable $item) use ($folderGroup) {
                return !Tools::inArray($folderGroup->getItems(), $item);
            });

            if (!$folderGroup->isFolded() && !Tools::isEmpty($posItems)) {
                $menu = new ContextMenuHtmlView();
                $menu->ref('add-to-folder-group-menu');
                $menu->each($posItems, function (ContextMenuHtmlView $menu, CliffSerializable $item) use ($folderGroup, $addFunction, $view) {
                    $name = $folderGroup->getItemTitle($item, self::getLanguage());
                    $entry = $menu->entry()->texts()->text(self::getLanguage(), $name)->parent();
                    $addFunction($item, $folderGroup, $entry, $view, 'main-survey-list');
                });
                $view->add($menu);
                $view->addClass('has-context-menu');
                $adder->protocol(Event::CLICK)->
                    mousePosition($menu)->
                    addClass($menu, 'active');
                $view->protocol(Event::CLICK_OUTSIDE)->
                    removeClass($menu, 'active');
            }
        }
        return $view;
    }

}
?>