<?php
/**
 * Integrates Magento with the custom price calculator based on user input on product view page.
 *
 * @author Bhavin Shah
 * @copyright Copyright (c) 2021 Bhavin (https://bhavin-shah.com)
 * @license https://opensource.org/licenses/OSL-3.0.php Open Software License 3.0
 */

declare(strict_types=1);

namespace Bhavin\Calculator\Observer;

use Magento\Framework\App\RequestInterface;
use Magento\Framework\Event\Observer as EventObserver;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Serialize\Serializer\Json;

/**
 * Observer for checkout_cart_product_add_after event.
 *
 * @see \Magento\Checkout\Model\Cart::addProduct($productInfo, $requestInfo = null)
 */
class CheckoutCartProductAddAfterObserver implements ObserverInterface
{
    /**
     * @var \Magento\Framework\App\RequestInterface
     */
    protected $_request;

    /**
     * @var \Magento\Framework\Serialize\Serializer\Json
     */
    public $serialize;

    public function __construct(RequestInterface $request, Json $serialize)
    {
        $this->_request = $request;
        $this->serialize = $serialize;
    }

    /**
     * {@inheritdoc}
     */
    public function execute(EventObserver $observer)
    {
        /* @var \Magento\Quote\Model\Quote\Item $item */
        $item = $observer->getQuoteItem();

        $additionalOptions = [];

        if ($additionalOption = $item->getOptionByCode('additional_options')) {
            $additionalOptions = $this->serialize->unserialize($additionalOption->getValue());
        }

        $post = $this->_request->getParam('calculator');

        if (is_array($post)) {
            /* Let's find out square-feet value and decide the final price */
            $sqft = $post['height'] * $post['width'];
            $price = $sqft * $item->getPrice();
            $item->setCustomPrice($price);
            $item->setOriginalCustomPrice($price);
            $item->getProduct()->setIsSuperMode(true);

            foreach ($post as $key => $value) {
                if ($key == '' || $value == '') {
                    continue;
                }

                $additionalOptions[] = [
                    'label' => $key,
                    'value' => $value,
                ];
            }

            /* Add calculated square-feet options */
            $additionalOptions[] = [
                'label' => 'SQFT',
                'value' => $sqft,
            ];
        }

        /*
        * Add values to additional options so that we can see in cart page items
        */
        if (count($additionalOptions) > 0) {
            $item->addOption([
                'code' => 'additional_options',
                'value' => $this->serialize->serialize($additionalOptions),
            ]);
        }
    }
}
