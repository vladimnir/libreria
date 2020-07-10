<?php

namespace Ppsoft\TipoDocumento\Model\Customer\Attribute\Source;

class DocumentTypeId extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource
{
    const PE = 1;
    const CI = 2;
    const DISMAC_CLUB = 3;

    /**
     * Retrieve All options
     *
     * @return array
     */
    public function getAllOptions()
    {
        if ($this->_options === null) {
            $this->_options = [
                ['label' => __('Seleccione Tipo Documento'), 'value' => null],
                ['label' => __('CI'), 'value' => self::CI],
                ['label' => __('Pasaporte Extranjero'), 'value' => self::PE],
            ];
        }
        return $this->_options;
    }
}