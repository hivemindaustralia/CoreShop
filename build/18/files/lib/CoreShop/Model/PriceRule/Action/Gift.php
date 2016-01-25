<?php
/**
 * CoreShop
 *
 * LICENSE
 *
 * This source file is subject to the GNU General Public License version 3 (GPLv3)
 * For the full copyright and license information, please view the LICENSE.md and gpl-3.0.txt
 * files that are distributed with this source code.
 *
 * @copyright  Copyright (c) 2015 Dominik Pfaffenbauer (http://dominik.pfaffenbauer.at)
 * @license    http://www.coreshop.org/license     GNU General Public License version 3 (GPLv3)
 */

namespace CoreShop\Model\PriceRule\Action;

use CoreShop\Model\PriceRule;
use CoreShop\Tool;

use Pimcore\Model\Object\CoreShopProduct;
use Pimcore\Model\Object\CoreShopCart;

class Gift extends AbstractAction {

    /**
     * @var int
     */
    public $gift;

    /**
     * @var string
     */
    public $type = "gift";

    /**
     * @return \Pimcore\Model\Object\CoreShopProduct
     */
    public function getGift()
    {
        if(!$this->gift instanceof CoreShopProduct)
            $this->gift = CoreShopProduct::getByPath($this->gift);

        return $this->gift;
    }

    /**
     * @param int $gift
     */
    public function setGift($gift)
    {
        $this->gift = $gift;
    }

    /**
     * Calculate discount
     *
     * @param CoreShopCart $cart
     * @return int
     */
    public function getDiscount(CoreShopCart $cart)
    {
        $discount = Tool::convertToCurrency($this->getGift()->getPrice(), Tool::getCurrency());

        return $discount;
    }

    /**
     * Apply Rule to Cart
     *
     * @param CoreShopCart $cart
     * @return bool
     */
    public function applyRule(CoreShopCart $cart)
    {
        if($this->getGift() instanceof CoreShopProduct) {
            $item = $cart->updateQuantity($this->getGift(), 1, false, false);
            $item->setIsGiftItem(true);
            $item->save();
        }

        return true;
    }

    /**
     * Remove Rule from Cart
     *
     * @param CoreShopCart $cart
     * @return bool
     */
    public function unApplyRule(CoreShopCart $cart)
    {
        if($this->getGift() instanceof CoreShopProduct) {
            $cart->updateQuantity($this->getGift(), 0, false, false);
        }

        return true;
    }
}
