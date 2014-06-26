<?php
/*************************************************************************************/
/*      This file is part of the Thelia package.                                     */
/*                                                                                   */
/*      Copyright (c) OpenStudio                                                     */
/*      email : dev@thelia.net                                                       */
/*      web : http://www.thelia.net                                                  */
/*                                                                                   */
/*      For the full copyright and license information, please view the LICENSE.txt  */
/*      file that was distributed with this source code.                             */
/*************************************************************************************/

namespace Thelia\Form\Brand;

use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Constraints\NotBlank;
use Thelia\Core\Translation\Translator;
use Thelia\Form\StandardDescriptionFieldsTrait;

/**
 * Class BrandModificationForm
 * @package Thelia\Form\Brand
 * @author  Franck Allimant <franck@cqfdev.fr>
 */
class BrandModificationForm extends BrandCreationForm
{
    use StandardDescriptionFieldsTrait;

    protected function buildForm()
    {
        parent::buildForm();

        $this->formBuilder
            ->add(
                'id',
                'hidden',
                [
                    'constraints' => [ new GreaterThan(['value' => 0]) ],
                    'required'    => true,
                ]
            )
            // Brand title
            ->add(
                'title',
                'text',
                [
                    'constraints' => [ new NotBlank() ],
                    'required'    => true,
                    'label'       => Translator::getInstance()->trans('Brand name'),
                    'label_attr'  => [
                        'for'         => 'title',
                        'placeholder' => Translator::getInstance()->trans('The brand name or title'),
                    ]
                ]
            )
        ;

        // Add standard description fields, excluding title and locale, which are already defined
        $this->addStandardDescFields(array('title', 'locale'));
    }

    public function getName()
    {
        return "thelia_brand_modification";
    }
}
