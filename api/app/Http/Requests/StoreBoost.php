<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

use Auth;
use App\Models\Video;
use App\Models\Boost;

class StoreBoost extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $video = Video::find($this->input('video_id'));

        if ($video->user_id !== $this->user()->id) {
            return false;
        }

        $existing = Boost
            ::where('user_id', Auth::user()->id)
            ->where('status', '<>', Boost::COMPLETE)
            ->count();

        if ($existing > 0) {
            return false;
        }

        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            // 'video_id' => 'required|exists:videos,id',
            // 'views' => 'required|in:'.join(',', Boost::options),
        ];
    }
}
