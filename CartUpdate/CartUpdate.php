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

namespace CartUpdate;

use Propel\Runtime\Connection\ConnectionInterface;
use Symfony\Component\Finder\Finder;
use Thelia\Install\Database;
use Thelia\Module\BaseModule;

class CartUpdate extends BaseModule
{
    /** @var string */
    const DOMAIN_NAME = 'cartupdate';
    const UPDATE_PATH = __DIR__ . DS . 'Config' . DS . 'update';


    public function postActivation(ConnectionInterface $con = null): bool
    {
        if (! self::getConfigValue('is_initialized', false)) {
            $database = new Database($con);
            $database->insertSql(null, array(__DIR__ . '/Config/thelia.sql'));

            self::setConfigValue('is_initialized', true);
        }
        return true;
    }

//
//    public function update($currentVersion, $newVersion, ConnectionInterface $con = null)
//    {
//
//        $finder = (new Finder())->files()->name('#.*?\.sql#')->sortByName()->in(self::UPDATE_PATH);
//
//        if ($finder->count() === 0) {
//            return;
//        }
//
//        $database = new Database($con);
//
//        /** @var \Symfony\Component\Finder\SplFileInfo $updateSQLFile */
//        foreach ($finder as $updateSQLFile) {
//            if (version_compare($currentVersion, str_replace('.sql', '', $updateSQLFile->getFilename()), '<')) {
//                $database->insertSql(null, [$updateSQLFile->getPathname()]);
//            }
//        }
//    }
    /*
     * You may now override BaseModuleInterface methods, such as:
     * install, destroy, preActivation, postActivation, preDeactivation, postDeactivation
     *
     * Have fun !
     */
}
