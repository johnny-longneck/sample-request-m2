<?php
/**
 * @author      John Herholz <info@longneckdesigns.de>
 * @copyright   Longneck Designs, 2018
 * @license     See license.txt
 **/
namespace Longneck\SampleRequest\Block;


class SampleRequestForm extends \Magento\Directory\Block\Data
{
    /**
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Directory\Model\ResourceModel\Region\CollectionFactory $regionCollectionFactory,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        \Magento\Directory\Model\ResourceModel\Country\CollectionFactory $countryCollectionFactory,
        \Magento\Directory\Helper\Data $directoryHelper,
        \Magento\Framework\Json\EncoderInterface $jsonEncoder,
        \Magento\Framework\App\Cache\Type\Config $configCacheType,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Store\Model\StoreManagerInterface $storeManager

    )
    {
        $this->_regristry = $registry;
        $this->_productCollectionFactory = $productCollectionFactory;
        $this->_scopeConfig = $scopeConfig;
        $this->_storeManager = $storeManager;
        parent::__construct(
            $context,
            $directoryHelper,
            $jsonEncoder,
            $configCacheType,
            $regionCollectionFactory,
            $countryCollectionFactory
        );
    }

    /**
     * Returns action url for sample request form
     *
     * @return string
     */
    public function getFormAction()
    {
        return $this->getUrl('samplerequest/index/post', ['_secure' => true]);
    }

    /** Returns Message from Backend Configuration */
    public function getTextHint() {
        return $this->_scopeConfig->getValue('samplerequest/configuration/message', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    /** Populates select field with all enabled products for samplerequest */
    public function getProductCollection()
    {
        $collection = $this->_productCollectionFactory->create();
        $collection->addAttributeToSelect('*')
            ->addFieldToFilter('samplerequest',1);
        $collection->setPageSize(500); // fetching only 500 products
        return $collection;
    }

    public function getProductSku()
    {
        if($this->_regristry->registry('current_product')) {
            return $this->_regristry->registry('current_product')->getSku();
        } else {
            return '';
        }
    }
    public function getIsSampleRequest()
    {
        if($this->_regristry->registry('current_product')) {
            return $this->_regristry->registry('current_product')->getData('samplerequest');
        } else {
            return '';
        }
    }
}
