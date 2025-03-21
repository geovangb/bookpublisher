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

namespace GB\PublisherBook\Controller\Adminhtml\Publisher;

use Exception;
use GB\PublisherBook\Api\Data\PublisherInterface;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\Result\Json;
use Magento\Framework\File\Csv;
use Magento\Framework\File\UploaderFactory;
use Magento\Framework\Filesystem;
use Magento\Framework\App\Filesystem\DirectoryList as AppDirectoryList;
use GB\PublisherBook\Api\PublisherRepositoryInterface;
use GB\PublisherBook\Api\Data\PublisherInterfaceFactory;
use Magento\Framework\Controller\Result\JsonFactory;

class ProcessImport extends Action
{
    /**
     * @var Csv
     */
    protected Csv $csvProcessor;
    /**
     * @var UploaderFactory
     */
    protected UploaderFactory $uploaderFactory;
    /**
     * @var Filesystem
     */
    protected Filesystem $filesystem;
    /**
     * @var PublisherRepositoryInterface
     */
    protected PublisherRepositoryInterface $publisherRepository;
    /**
     * @var PublisherInterfaceFactory
     */
    protected PublisherInterfaceFactory $publisherFactory;
    /**
     * @var JsonFactory
     */
    protected JsonFactory $jsonFactory;
    /**
     * @var AppDirectoryList
     */
    private AppDirectoryList $directoryList;

    /**
     * @param Context $context
     * @param Csv $csvProcessor
     * @param UploaderFactory $uploaderFactory
     * @param Filesystem $filesystem
     * @param AppDirectoryList $directoryList
     * @param PublisherRepositoryInterface $publisherRepository
     * @param PublisherInterfaceFactory $publisherFactory
     * @param JsonFactory $jsonFactory
     */
    public function __construct(
        Context                      $context,
        Csv                          $csvProcessor,
        UploaderFactory              $uploaderFactory,
        Filesystem                   $filesystem,
        AppDirectoryList             $directoryList,
        PublisherRepositoryInterface $publisherRepository,
        PublisherInterfaceFactory    $publisherFactory,
        JsonFactory                  $jsonFactory
    ) {
        parent::__construct($context);
        $this->csvProcessor = $csvProcessor;
        $this->uploaderFactory = $uploaderFactory;
        $this->filesystem = $filesystem;
        $this->publisherRepository = $publisherRepository;
        $this->publisherFactory = $publisherFactory;
        $this->jsonFactory = $jsonFactory;
        $this->directoryList = $directoryList;
    }

    /**
     * Execute
     *
     * @return Json
     */
    public function execute()
    {
        try {
            $uploadDirectory = $this->directoryList->getPath(DirectoryList::MEDIA) . '/label/icon';

            $uploader = $this->uploaderFactory->create(['fileId' => 'import_file']);
            $uploader->setAllowedExtensions(['csv']);
            $uploader->setAllowRenameFiles(true);
            $uploader->setFilesDispersion(false);

            $mediaDirectory = $this->filesystem->getDirectoryWrite(AppDirectoryList::VAR_DIR);
            $filePath = $mediaDirectory->getAbsolutePath('import/') .
                $uploader->save($mediaDirectory->getAbsolutePath('import/'))['file'];

            $data = $this->csvProcessor->getData($filePath);
            array_shift($data);

            foreach ($data as $row) {
                if (count($row) < 2) {
                    continue;
                }

                /** @var PublisherInterface $publisher */
                $publisher = $this->publisherFactory->create();
                $publisher->setName($row[0]);
                $publisher->setStatus($row[1]);

                $this->publisherRepository->save($publisher);
            }

            return $this->jsonFactory->create()->setData(['success' => true,
                'message' => __('Publishers imported successfully.')]);
        } catch (Exception $e) {
            return $this->jsonFactory->create()->setData(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}
