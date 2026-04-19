<?php

namespace App\Actions;

use Statamic\Actions\Action;
use Statamic\Contracts\Forms\Submission as SubmissionContract;

class MarkSubmissionReviewed extends Action
{
    public static function title()
    {
        return __('Mark Reviewed');
    }

    public function visibleTo($item)
    {
        return $item instanceof SubmissionContract && ! $item->get('admin_reviewed', false);
    }

    public function authorize($user, $item)
    {
        return $user->can('view', $item);
    }

    public function buttonText()
    {
        return 'Mark reviewed|Mark :count submissions reviewed';
    }

    public function confirmationText()
    {
        return 'Mark this submission as reviewed?|Mark these :count submissions as reviewed?';
    }

    public function run($items, $values)
    {
        $items->each(function ($submission) {
            $submission->set('admin_reviewed', true);
            $submission->save();
        });

        return trans_choice('Submission marked reviewed|Submissions marked reviewed', $items->count());
    }
}