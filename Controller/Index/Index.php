<?php
/**
 * @author      John Herholz <info@longneckdesigns.de>
 * @copyright   Longneck Designs, 2018
 * @license     See license.txt
 **/
namespace Longneck\SampleRequest\Controller\Index;

use Magento\Framework\Controller\ResultFactory;

class Index extends \Magento\Contact\Controller\Index
{
    /**
     * Show Contact Us page
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        return $this->resultFactory->create(ResultFactory::TYPE_PAGE);
    }
}
