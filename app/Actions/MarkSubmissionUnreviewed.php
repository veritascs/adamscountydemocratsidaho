<?php

namespace App\Actions;

use Statamic\Actions\Action;
use Statamic\Contracts\Forms\Submission as SubmissionContract;

class MarkSubmissionUnreviewed extends Action
{
    public static function title()
    {
        return __('Mark Unreviewed');
    }

    public function visibleTo($item)
    {
        return $item instanceof SubmissionContract && (bool) $item->get('admin_reviewed', false);
    }

    public function authorize($user, $item)
    {
        return $user->can('view', $item);
    }

    public function buttonText()
    {
        return 'Mark unreviewed|Mark :count submissions unreviewed';
    }

    public function confirmationText()
    {
        return 'Mark this submission as unreviewed?|Mark these :count submissions as unreviewed?';
    }

    public function run($items, $values)
    {
        $items->each(function ($submission) {
            $submission->set('admin_reviewed', false);
            $submission->save();
        });

        return trans_choice('Submission marked unreviewed|Submissions marked unreviewed', $items->count());
    }
}