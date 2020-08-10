<?php

/**
 * Created by wilson.sun330@gmail.com
 * Date: 13/05/2015
 * Time: 5:02 PM
 */

namespace Ppsoft\Home\Block\Html;

//extends \Magento\Theme\Block\Html\Topmenu;
use Magento\Framework\Data\Tree\NodeFactory;
use Magento\Framework\Data\TreeFactory;
use Magento\Framework\View\Element\Template;

class Topmenu extends \Magento\Theme\Block\Html\Topmenu {

    public $exist;
    /**
     * @var \Magento\Catalog\Model\CategoryFactory
     */
    protected $categoryFactory;

    public function __construct(Template\Context $context, NodeFactory $nodeFactory, TreeFactory $treeFactory, \Magento\Catalog\Model\CategoryFactory $categoryFactory, array $data = [])
    {
        parent::__construct($context, $nodeFactory, $treeFactory, $data);
        $this->exist = '';
        $this->categoryFactory = $categoryFactory;
    }

    /**
     * Recursively generates top menu html from data that is specified in $menuTree
     *
     * @param \Magento\Framework\Data\Tree\Node $menuTree
     * @param string $childrenWrapClass
     * @param int $limit
     * @param array $colBrakes
     * @return string
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    protected function _getHtml(
        \Magento\Framework\Data\Tree\Node $menuTree,
        $childrenWrapClass,
        $limit,
        array $colBrakes = []
    ) {
        $html = '';

        $children = $menuTree->getChildren();
        $parentLevel = $menuTree->getLevel();
        $childLevel = $parentLevel === null ? 0 : $parentLevel + 1;

        $counter = 1;
        $itemPosition = 1;
        $childrenCount = $children->count();

        $parentPositionClass = $menuTree->getPositionClass();
        $itemPositionClassPrefix = $parentPositionClass ? $parentPositionClass . '-' : 'nav-';

        /** @var \Magento\Framework\Data\Tree\Node $child */
        foreach ($children as $child) {
            if ($childLevel === 0 && $child->getData('is_parent_active') === false) {
                continue;
            }
            $child->setLevel($childLevel);
            $child->setIsFirst($counter == 1);
            $child->setIsLast($counter == $childrenCount);
            $child->setPositionClass($itemPositionClassPrefix . $counter);

            $outermostClassCode = '';
            $outermostClass = $menuTree->getOutermostClass();

            if ($childLevel == 0 && $outermostClass) {
                $outermostClassCode = ' class="' . $outermostClass . '" ';
                $currentClass = $child->getClass();

                if (empty($currentClass)) {
                    $child->setClass($outermostClass);
                } else {
                    $child->setClass($currentClass . ' ' . $outermostClass);
                }
            }

            if (count($colBrakes) && $colBrakes[$counter]['colbrake']) {
                $html .= '</ul></li><li class="column"><ul>';
            }
            $listring = '';
            if ($child->getName()) {
                $listring = str_replace("Ñ", "n", $child->getName());
            }
            $html .= '<li ' . $this->_getRenderedMenuItemAttributes($child) . 'id="li-' . preg_replace('/\s+/', '', strtolower($listring)) . '">';
            if($child->getName() == "Categorías" || $childLevel== 1 ){
                $html .= '<a href="javascript:void(0)" id="' . preg_replace('/\s+/', '', strtolower($child->getName())) . '" ' . $outermostClassCode . '><span>' . $this->escapeHtml(
                        $child->getName()
                    ) . '</span></a>' . $this->_addSubMenu(
                        $child,
                        $childLevel,
                        $childrenWrapClass,
                        $limit
                    ) . '</li>';
                $itemPosition++;
                $counter++;
            }
            else {
                $string = str_replace("Ñ", "n", $child->getName());
                $html .= '<a id="' . preg_replace('/\s+/', '', strtolower($string)) . '" href="' . $child->getUrl() . '" ' . $outermostClassCode . '><span>' . $this->escapeHtml(
                        $child->getName()
                    ) . '</span></a>' . $this->_addSubMenu(
                        $child,
                        $childLevel,
                        $childrenWrapClass,
                        $limit
                    ) . '</li>';
                $itemPosition++;
                $counter++;
            }

        }

        if (count($colBrakes) && $limit) {
            $html = '<li class="column"><ul>' . $html . '</ul></li>';
        }

        return $html;
    }
    protected function getHtmlLevel(
        \Magento\Framework\Data\Tree\Node $menuTree,
        $childrenWrapClass,
        $limit,
        array $colBrakes = []
    ) {
        $html = '';

        $children = $menuTree->getChildren();
        $parentLevel = $menuTree->getLevel();
        $childLevel = $parentLevel === null ? 0 : $parentLevel + 1;

        $counter = 1;
        $itemPosition = 1;
        $childrenCount = $children->count();

        $parentPositionClass = $menuTree->getPositionClass();
        $itemPositionClassPrefix = $parentPositionClass ? $parentPositionClass . '-' : 'nav-';

        /** @var \Magento\Framework\Data\Tree\Node $child */
        foreach ($children as $child) {
            if ($childLevel === 0 && $child->getData('is_parent_active') === false) {
                continue;
            }
            $child->setLevel($childLevel);
            $child->setIsFirst($counter == 1);
            $child->setIsLast($counter == $childrenCount);
            $child->setPositionClass($itemPositionClassPrefix . $counter);

            $outermostClassCode = '';
            $outermostClass = $menuTree->getOutermostClass();

            if ($childLevel == 0 && $outermostClass) {
                $outermostClassCode = ' class="' . $outermostClass . '" ';
                $currentClass = $child->getClass();

                if (empty($currentClass)) {
                    $child->setClass($outermostClass);
                } else {
                    $child->setClass($currentClass . ' ' . $outermostClass);
                }
            }

            if (count($colBrakes) && $colBrakes[$counter]['colbrake']) {
                $html .= '</ul></li><li class="column"><ul>';
            }
            $listring = '';
            if ($child->getName()) {
                $listring = str_replace("Ñ", "n", $child->getName());
            }
            $html .= '<li ' . $this->_getRenderedMenuItemAttributes($child) . 'id="li-' . preg_replace('/\s+/', '', strtolower($listring)) . '">';
            if($child->getName() == "Categorías" || $childLevel== 1 ){
                $html .= '<a href="javascript:void(0)" id="' . preg_replace('/\s+/', '', strtolower($child->getName())) . '" ' . $outermostClassCode . '><span>' . $this->escapeHtml(
                        $child->getName()
                    ) . '</span></a>' . $this->_addSubMenu(
                        $child,
                        $childLevel,
                        $childrenWrapClass,
                        $limit
                    ) . '</li>';
                $itemPosition++;
                $counter++;
            }
            else {
                $string = str_replace("Ñ", "n", $child->getName());
                $html .= '<a id="' . preg_replace('/\s+/', '', strtolower($string)) . '" href="' . $child->getUrl() . '" ' . $outermostClassCode . '><span>' . $this->escapeHtml(
                        $child->getName()
                    ) . '</span></a>' . $this->_addSubMenu(
                        $child,
                        $childLevel,
                        $childrenWrapClass,
                        $limit
                    ) . '</li>';
                $itemPosition++;
                $counter++;
            }

        }

        if (count($colBrakes) && $limit) {
            $html = '<li class="column"><ul>' . $html . '</ul></li>';
        }

        return $html;
    }

    /**
     * Add sub menu HTML code for current menu item
     *
     * @param \Magento\Framework\Data\Tree\Node $child
     * @param string $childLevel
     * @param string $childrenWrapClass
     * @param int $limit
     * @return string HTML code
     */
    protected function _addSubMenu($child, $childLevel, $childrenWrapClass, $limit)
    {
        $html = '';
        if (!$child->hasChildren()) {
            return $html;
        }

        $colStops = [];
        if ($childLevel == 0 && $limit) {
            $colStops = $this->_columnBrake($child->getChildren(), $limit);
        }
        if ($childLevel == 1)
        {
            $html .= '<ul class="level' . $childLevel . ' ' . $childrenWrapClass . ' ' . str_replace("ñ", "n", $child->getName()). '" id="ul-level' . $childLevel . '">';
            $html .= '<div class="cat-container"><span class="cat-name">' . $child->getName() . '</span></div>';
            $html .= $this->_getHtml($child, $childrenWrapClass, $limit, $colStops);
            $html .= '</ul>';
            return $html;
        }
        if ($childLevel == 2)
        {
            $html .= '<ul class="level' . $childLevel . ' ' . $childrenWrapClass  . ' ' . str_replace("Ñ", "N", $child->getName()). '" id="ul-level' . $childLevel . '">';
            $html .= '<div class="cat-container"><span class="cat-name">' . $child->getName() . '</span></div>';
            $html .= $this->_getHtml($child, $childrenWrapClass, $limit, $colStops);
            $html .= '</ul>';
            return $html;
        }
        else
        {
            $html .= '<ul class="level' . $childLevel . ' ' . $childrenWrapClass . '" id="ul-level' . $childLevel . '" >';
            $html .= $this->_getHtml($child, $childrenWrapClass, $limit, $colStops);
            $html .= '</ul>';

            return $html;
        }

    }

}
