<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Statamic\Contracts\Forms\Submission as SubmissionContract;
use Statamic\Facades\User;

class SubmissionReviewController extends Controller
{
    public function review($form, $submission): RedirectResponse
    {
        return $this->updateReviewedState($form, $submission, true);
    }

    public function unreview($form, $submission): RedirectResponse
    {
        return $this->updateReviewedState($form, $submission, false);
    }

    protected function updateReviewedState($form, $submission, bool $reviewed): RedirectResponse
    {
        $submission = $submission instanceof SubmissionContract
            ? $submission
            : $form->submission($submission);
        abort_unless($submission, 404);
        abort_unless($submission->form()->handle() === $form->handle(), 404);

        $user = User::current();
        abort_unless($user && $user->can('view', $submission), 403);

        $submission->set('admin_reviewed', $reviewed);
        $submission->save();

        return redirect()->back();
    }
}