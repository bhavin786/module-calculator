<?php
/**
 * Bhavin Calculator.
 *
 * Integrates Magento with the custom price calculator based on user input on product view page.
 *
 * @author Rajan Soni
 * @copyright Copyright (c) 2021 Bhavin (https://bhavin-shah.com)
 * @license https://opensource.org/licenses/OSL-3.0.php Open Software License 3.0
 */

declare(strict_types=1);

use Magento\Framework\Component\ComponentRegistrar;

ComponentRegistrar::register(ComponentRegistrar::MODULE, 'Bhavin_Calculator', __DIR__);
