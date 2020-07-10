<?php
/**
 * Created by PhpStorm.
 * User: oscar
 * Date: 26-11-19
 * Time: 04:57 PM
 */

namespace Ppsoft\Home\Controller\Product;


use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\ResponseInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Customer\Model\Session;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Data\Form\FormKey\Validator;
use Magento\MultipleWishlist\Model\WishlistEditor;
use Magento\Wishlist\Controller\WishlistProviderInterface;

class AddWishlist extends \Ppsoft\Home\Controller\Index\Add
{
    /**
     * @var WishlistProviderInterface
     */
    protected $wishlistProvider;
    /**
     * @var ProductRepositoryInterface
     */
    protected $productRepository;
    /**
     * @var Validator
     */
    protected $formKeyValidator;

    public function __construct(Context $context,
                                Session $customerSession,
                                WishlistProviderInterface $wishlistProvider,
                                ProductRepositoryInterface $productRepository,
                                Validator $formKeyValidator,
                                WishlistEditor $wishlistEditor)
    {
        parent::__construct(
            $context,
            $customerSession,
            $wishlistProvider,
            $productRepository,
            $formKeyValidator);
        $this->wishlistEditor = $wishlistEditor;
    }

    /**
     * Execute action based on request and return result
     *
     * Note: Request will be added as operation argument in future
     *
     * @return \Magento\Framework\Controller\ResultInterface|ResponseInterface
     * @throws \Magento\Framework\Exception\NotFoundException
     */
    public function execute()
    {
        /** @var \Magento\Framework\Controller\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        if (!$this->formKeyValidator->validate($this->getRequest())) {
            return $resultRedirect->setPath('*/');
        }

        $customerId = $this->_customerSession->getCustomerId();
        $name = $this->getRequest()->getParam('name');
        $visibility = $this->getRequest()->getParam('visibility', 0) === 'on' ? 1 : 0;
        if ($name !== null) {
            try {
                $wishlist = $this->wishlistEditor->edit($customerId, $name, $visibility);
                $this->messageManager->addSuccess(
                    __(
                        'Wish list "%1" was saved.',
                        $this->_objectManager->get(\Magento\Framework\Escaper::class)->escapeHtml($wishlist->getName())
                    )
                );
                $this->wishlistData->refreshFormKey();
                $this->getRequest()->setParam('wishlist_id', $wishlist->getId());
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addException($e, __('We can\'t create the wish list right now.'));
            }
        }
        return parent::execute();
    }
}