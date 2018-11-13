<?php
/**
 * Video Services
 *
 * @package VideoService
 * @author kevin olson <acidjazz@gmail.com>
 * @version 0.1
 * @copyright (C) 2018 kevin olson <acidjazz@gmail.com>
 * @license APACHE
 */

namespace App\Services;

use acidjazz\tubestuff\TubeStuff;

use Carbon\Carbon;
use App\Models\Boost;
use App\Models\Video;

class VideoService {

    public function  currentBoosts()
    {
        return Boost::where('updated_at', '>=', Carbon::now()->subDay());
    }

    public function getBoostsVideoIds($boosts)
    {
        return array_unique($boosts->pluck('video_id')->toArray());
    }

    public function getViews($boosts)
    {
        return (new TubeStuff)->getVideos($this->getBoostsVideoIds($boosts));
    }

    public function updateViews($videos, $views)
    {
        foreach ($views as $video_id=>$data) {
            $video = Video::find($video_id);
            $video->logs()->create(['views' => $data['views']]);
            $video->views = $data['views'];
            $video->save();
        }
    }
}
