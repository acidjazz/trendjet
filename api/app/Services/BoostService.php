<?php
/**
 * Boost service for scheduling puppets
 *
 * @package BoostService
 * @author kevin olson <acidjazz@gmail.com>
 * @version 0.1
 * @copyright (C) 2018 kevin olson <acidjazz@gmail.com>
 * @license APACHE
 */

namespace App\Services;
use App\Models\Boost;
use App\Services\PuppetService;

class BoostService {

    /*
     * Maximum amount of boosts that can be active at once
     *
     * @var integer
     */
    const LIMIT = 10;

    /*
     *
     * 1. get a count of all currently ACTIVE boosts
     * 2. get any PENDING boosts self::LIMIT-activesCount
     * 3. activate them
     * 4. check for any active boosts w/ no remaining <= 0
     * 5. if any exist,  complete them with Boost::COMPLETE
     * 6. send off notifications for any of those completed Boosts
     * 7. pluck the boost ids of any remaining active boosts
     * 8. check for any existing instances
     * 9. if any exist, abort.
     * 10. if none exist, deploy 10 servers w/ the boosts
     */

    public function activate()
    {
        return Boost::where('status', Boost::PENDING)->limit(
            self::LIMIT - Boost::where('status', Boost::ACTIVE)->count())
            ->update(['status' => Boost::ACTIVE]);
    }

    public function complete()
    {
        return Boost::where('status', Boost::ACTIVE)
            ->where('remaining', '<',  1)
            ->update(['status' => Boost::COMPLETE]);
    }

    public function deploy()
    {
        $ps = new PuppetService();
        if ($ps->describe() === false) {
            $boost_ids = Boost::where('status', Boost::ACTIVE)->pluck('id')->toArray();
            $ps->deploy($boost_ids, 10);
            return implode(',', $boost_ids);
        }
        return false;
    }


}
