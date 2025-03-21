<?php
/**
 * GB Developer
 *
 * @category GB_Developer
 * @package  GB
 *
 * @copyright Copyright (c) 2025 GB Developer.
 *
 * @author Geovan Brambilla <geovangb@gmail.com>
 */
declare(strict_types=1);

namespace GB\PublisherBook\Controller\Adminhtml\Publisher;

use Exception;
use GB\PublisherBook\Api\Data\PublisherInterface;
use GB\PublisherBook\Api\PublisherRepositoryInterface;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\LocalizedException;

class InlineEdit extends Action
{

    /**
     * @var JsonFactory
     */
    protected JsonFactory $jsonFactory;
    /**
     * @var PublisherRepositoryInterface
     */
    protected PublisherRepositoryInterface $publisherRepository;
    /**
     * @var PublisherInterface
     */
    protected PublisherInterface $publisher;

    /**
     * Edit inLine
     *
     * @param Context $context
     * @param JsonFactory $jsonFactory
     * @param PublisherInterface $publisher
     * @param PublisherRepositoryInterface $publisherRepository
     */
    public function __construct(
        Context $context,
        JsonFactory $jsonFactory,
        PublisherInterface $publisher,
        PublisherRepositoryInterface $publisherRepository
    ) {
        parent::__construct($context);
        $this->jsonFactory = $jsonFactory;
        $this->publisherRepository = $publisherRepository;
        $this->publisher = $publisher;
    }

    /**
     * Inline edit action
     *
     * @return ResultInterface
     * @throws LocalizedException
     */
    public function execute()
    {
        $resultJson = $this->jsonFactory->create();
        $error = false;
        $messages = [];

        if ($this->getRequest()->getParam('isAjax')) {
            $postItems = $this->getRequest()->getParam('items', []);

            if (!count($postItems)) {
                $messages[] = __('Please correct the data sent.');
                $error = true;
            } else {
                foreach (array_keys($postItems) as $publisherId) {
                    $model = $this->publisherRepository->getById($publisherId);
                    try {
                        $modelData = $model->getData();

                        if (isset($postItems[$publisherId])) {
                            $modelData = array_merge_recursive($modelData, $postItems[$publisherId]);
                        }

                        $model->setData($modelData);
                        $this->publisherRepository->save($model);

                    } catch (Exception $e) {
                        $messages[] = "[Publisher ID: {$publisherId}]  {$e->getMessage()}";
                        $error = true;
                    }
                }
            }
        }

        return $resultJson->setData([
            'messages' => $messages,
            'error' => $error
        ]);
    }
}
