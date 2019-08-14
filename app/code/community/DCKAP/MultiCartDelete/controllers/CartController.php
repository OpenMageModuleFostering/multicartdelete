<?php
require_once(Mage::getModuleDir('controllers','Mage_Checkout').DS.'CartController.php');

class DCKAP_MultiCartDelete_CartController extends Mage_Checkout_CartController
{
	/**
     * Update shopping cart data action
     */
    public function updatePostAction()
    {
        if (!$this->_validateFormKey()) {
            $this->_redirect('*/*/');
            return;
        }

        $updateAction = (string)$this->getRequest()->getParam('update_cart_action');

        switch ($updateAction) {
            case 'empty_cart':
                $this->_emptyShoppingCart();
                break;
            case 'update_items':
                $massDelete = $this->getRequest()->getParam('massCartDelete');
                if(count($massDelete)) $this->_removeCartItems($massDelete);
                break;
            case 'update_qty':
                $this->_updateShoppingCart();
                break;
            default:
                $this->_updateShoppingCart();
        }

        $this->_goBack();
    }

    protected function _removeCartItems($massDelete){

        try {
            foreach ($massDelete as $_itemQuoteId) {
                $this->_getCart()->removeItem($_itemQuoteId)->save();
            }
        } catch (Exception $e) {
            $this->_getSession()->addError($this->__('Cannot remove the items.'));
            Mage::logException($e);
        }
    }
}