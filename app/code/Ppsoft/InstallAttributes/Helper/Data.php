<?php

namespace Wagento\InstallAttributes\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;

class Data extends AbstractHelper
{

    /**
     * Construct
     */
    public function __construct(
        Context $context
    )
    {

    }

    
    public function getOptionsMarca()
    {
        $options = array(
                    'LYRA',
'STABILO',
'BIC',
'STAEDLER',
'SABONIS',
'MILCAR',
'SABONIS',
'ACRILEX',
'FABER CASTELL',
'MERLETTO',
'MONAMI',
'MILAN',
'ABC',
'PELIKAN',
'WEX',
'MAPED',
'RHEIN',
'CASIO',
'PRINCO',
'HP',
'CARIOCA',
'FIVE STICK',
'DELI',
'PRO ARTE',
'DELI',
'MONOPOL',
'PARKER',
'ISOFIT',
'MADEPA',
'VISIONER',
'PHILIPS',
'TRAMONTINA',
'PRO ARTE',
'PILOT',
'ARTESCO',
'PENTEL',
'RAPID',
'KANGARO',
'TILIBRA',
'WINNER',
'TOP'
                    );
                    
        return $options;
    }
}