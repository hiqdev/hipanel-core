<?php
declare(strict_types=1);

namespace hipanel\tests\_support\Page\Widget\Input\Exception;

use Codeception\Util\Locator;

class ElementNotDisappearedException extends \PHPUnit\Framework\AssertionFailedError
{
    public function __construct($selector, $message = null)
    {
        $selector = Locator::humanReadableString($selector);
        parent::__construct($message . " element with $selector has to disappear but still is on page.");
    }
}
