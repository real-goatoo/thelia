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

namespace Thelia\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Thelia\Core\Event\Currency\CurrencyUpdateRateEvent;
use Thelia\Core\Event\TheliaEvents;
use Thelia\Model\Currency;
use Thelia\Model\CurrencyQuery;

/**
 * Class UpdateCurrenciesRates
 * @package Thelia\Command
 * @author Franck Allimant <thelia@cqfdev.fr>
 */
class UpdateCurrenciesRates extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName("currency:update-rates")
            ->setDescription("Update currency rates")
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var EventDispatcherInterface $dispatcher */
        try {
            $event = new CurrencyUpdateRateEvent();

            $this->getDispatcher()->dispatch(TheliaEvents::CURRENCY_UPDATE_RATES, $event);

            if ($event->hasUndefinedRates()) {
                $output->writeln("<comment>Rate was not found for the following currencies:");

                $undefinedCurrencies = CurrencyQuery::create()
                    ->filterById($event->getUndefinedRates())
                    ->find();

                /** @var Currency $currency */
                foreach ($undefinedCurrencies as $currency) {
                    $output->writeln("  -" . $currency->getName() . " (".$currency->getCode()."), current rate is " . $currency->getRate());
                }

                $output->writeln("Update done with errors</comment>");
            } else {
                $output->writeln("<info>Update done withour errors</info>");

                return 1;
            }
        } catch (\Exception $ex) {
            // Any error
            $output->writeln("<error>Update failed: " . $ex->getMessage() . "</error>");

            return 2;
        }

        return 0;
    }
}
