<?php
/**
 * Bhavin Calculator.
 *
 * Integrates Magento with the custom price calculator based on user input on product view page.
 *
 * @author Bhavin Shah
 * @copyright Copyright (c) 2021 Bhavin (https://bhavin-shah.com)
 * @license https://opensource.org/licenses/OSL-3.0.php Open Software License 3.0
 */

namespace Bhavin\Calculator\Observer;

use Magento\Framework\Event\Observer as EventObserver;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Serialize\Serializer\Json;

/**
 * Observer for sales_model_service_quote_submit_before event.
 *
 * @see \Magento\Quote\Model\QuoteManagement::submitQuote(Quote $quote, $orderData = [])
 */
class SalesModelServiceQuoteSubmitBeforeObserver implements ObserverInterface
{
    /**
     * @var \Magento\Framework\Serialize\Serializer\Json
     */
    public $serialize;

    public function __construct(Json $serialize)
    {
        $this->serialize = $serialize;
    }

    /**
     * {@inheritdoc}
     */
    public function execute(EventObserver $observer)
    {
        $quote = $observer->getQuote();
        $order = $observer->getOrder();
        $quoteItems = [];

        // Map Quote Item with Quote Item Id
        foreach ($quote->getAllVisibleItems() as $quoteItem) {
            $quoteItems[$quoteItem->getId()] = $quoteItem;
        }

        foreach ($order->getAllVisibleItems() as $orderItem) {   
            $quoteItemId = $orderItem->getQuoteItemId();
            $quoteItem = $quoteItems[$quoteItemId];
            $additionalOptions = $quoteItem->getOptionByCode('additional_options');
            if (isset($additionalOptions) && $additionalOptions->getOptionId()) {
                // Get Order Item's other options
                $options = $orderItem->getProductOptions();
                // Set additional options to Order Item
                $options['additional_options'] = $this->serialize->unserialize($additionalOptions->getValue());
                $orderItem->setProductOptions($options);
            }
        }
    }
}
