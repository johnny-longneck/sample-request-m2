<?php
/**
 * @author      John Herholz <info@longneckdesigns.de>
 * @copyright   Longneck Designs, 2018
 * @license     See license.txt
 **/
namespace Longneck\SampleRequest\Controller\Index;

use Magento\Contact\Model\ConfigInterface;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\HTTP\PhpEnvironment\Request;
use Psr\Log\LoggerInterface;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\DataObject;


class Post extends \Magento\Contact\Controller\Index
{
    /**
     * @var DataPersistorInterface
     */
    private $dataPersistor;
    protected $contactsConfig;
    /**
     * @var Context
     */
    private $context;

    /**
     * @var TransportBuilder
     */
    protected $transportBuilder;

    /**
     * @var LoggerInterface
     */
    private $logger;

    protected $inlineTranslation;
    /**
     * @param Context $context
     * @param ConfigInterface $contactsConfig
     * @param MailInterface $mail
     * @param DataPersistorInterface $dataPersistor
     * @param LoggerInterface $logger
     */
    public function __construct(
        Context $context,
        ConfigInterface $contactsConfig,
        TransportBuilder $transportBuilder,
        DataPersistorInterface $dataPersistor,
        LoggerInterface $logger = null,
        \Magento\Framework\Translate\Inline\StateInterface $inlineTranslation
    ) {
        parent::__construct($context, $contactsConfig);
        $this->contactsConfig = $contactsConfig;
        $this->context = $context;
        $this->transportBuilder = $transportBuilder;
        $this->dataPersistor = $dataPersistor;
        $this->inlineTranslation = $inlineTranslation;
        $this->logger = $logger ?: ObjectManager::getInstance()->get(LoggerInterface::class);
    }

    /**
     * Post user question
     *
     * @return Redirect
     */
    public function execute()
    {
        if (!$this->isPostRequest()) {
            return $this->resultRedirectFactory->create()->setPath('*/*/');
        }
        try {
            $this->sendEmail($this->validatedParams());
            $this->messageManager->addSuccessMessage(
                __('Thanks for contacting us with your sample request. We\'ll respond to you very soon.')
            );
            $this->dataPersistor->clear('sample_request');
        } catch (LocalizedException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
            $this->dataPersistor->set('sample_request', $this->getRequest()->getParams());
        } catch (\Exception $e) {
            $this->logger->critical($e);
            $this->messageManager->addErrorMessage($e->getMessage());
            $this->dataPersistor->set('sample_request', $this->getRequest()->getParams());
        }

        return $this->resultRedirectFactory->create()->setRefererOrBaseUrl();
    }

    /**
     * @param array $post Post data from sample request form
     * @return void
     */
    private function sendEmail($post)
    {
        $this->inlineTranslation->suspend();
        /** @see \Magento\Contact\Controller\Index\Post::validatedParams() */
        //$replyToName = !empty($variables['data']['lastname']) ? $variables['data']['lastname'] : null;
        $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
        $transport = $this->transportBuilder
            ->setTemplateIdentifier('samplerequest_email_template')
            ->setTemplateOptions(
                [
                    'area' => 'frontend',
                    'store' => \Magento\Store\Model\Store::DEFAULT_STORE_ID,
                ]
            )
            ->setTemplateVars(['data' => new DataObject($post)])
            ->setFrom([
                'email' => $post['sample-email'],//$this->scopeConfig->getValue(self::EMAIL_TEMPLATE, ScopeInterface::SCOPE_STORE),
                'name' => $post['sample-firstname'] . " " . $post['sample-lastname']//$this->scopeConfig->getValue('trans_email/ident_general/email', ScopeInterface::SCOPE_STORE)
            ])//$this->scopeConfig->getValue(self::XML_PATH_EMAIL_SENDER, $storeScope))
            ->addTo($this->contactsConfig->emailRecipient())//$this->scopeConfig->getValue(self::XML_PATH_EMAIL_RECIPIENT, $storeScope))
            ->setReplyTo($post['sample-email'])
            ->getTransport();

        $transport->sendMessage();
        $this->inlineTranslation->resume();
    }

    /**
     * @return bool
     */
    private function isPostRequest()
    {
        /** @var Request $request */
        $request = $this->getRequest();
        return !empty($request->getPostValue());
    }

    /**
     * @return array
     * @throws \Exception
     */
    private function validatedParams()
    {
        $request = $this->getRequest();
        if (trim($request->getParam('sample-lastname')) === '') {
            throw new LocalizedException(__('Name is missing'));
        }
        if (false === \strpos($request->getParam('sample-email'), '@')) {
            throw new LocalizedException(__('Invalid email address'));
        }
        if (trim($request->getParam('hideit')) !== '') {
            throw new \Exception();
        }

        return $request->getParams();
    }
}
