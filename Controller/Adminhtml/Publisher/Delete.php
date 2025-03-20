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

use GB\PublisherBook\Api\Data\PublisherInterface;
use GB\PublisherBook\Api\PublisherRepositoryInterface;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;

class Delete extends Action implements HttpGetActionInterface
{
    private PublisherInterface $publisher;
    private PublisherRepositoryInterface $publisherRepository;

    /**
     * @param Context $context
     * @param PublisherInterface $publisher
     * @param PublisherRepositoryInterface $publisherRepository
     */
    public function __construct(
        Context                      $context,
        PublisherInterface           $publisher,
        PublisherRepositoryInterface $publisherRepository,
    )
    {
        parent::__construct($context);
        $this->publisher = $publisher;
        $this->publisherRepository = $publisherRepository;
    }

    /**
     * Delete action
     *
     * @return ResultInterface
     */
    public function execute(): ResultInterface
    {
        $id = $this->getRequest()->getParam('entity_id');
        $result = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        if ($id) {
            try {
                $model = $this->publisherRepository->getById($id);
                $this->publisherRepository->delete($model);
                // display success message
                $this->messageManager->addSuccessMessage(__('You deleted the Publisher.'));
                // go to grid
                return $result->setPath('*/*/');
            } catch (\Exception $e) {
                // display error message
                $this->messageManager->addErrorMessage($e->getMessage());
                // go back to edit form
                return $result->setPath('*/*/add', ['entity_id' => $id]);
            }
        }
        // display error message
        $this->messageManager->addErrorMessage(__('We can\'t find a Publisher to delete.'));
        // go to grid
        return $result->setPath('*/*/');
    }
}

