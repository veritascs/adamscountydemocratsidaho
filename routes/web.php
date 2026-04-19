<?php

use App\Http\Controllers\SubmissionReviewController;
use Illuminate\Support\Facades\Route;

// Route::statamic('example', 'example-view', [
//    'title' => 'Example'
// ]);

Route::middleware(['statamic.cp', 'statamic.cp.authenticated'])
	->prefix(config('statamic.cp.route'))
	->name('statamic.cp.')
	->group(function () {
		Route::get('forms/{form}/submissions/{submission}/mark-reviewed', [SubmissionReviewController::class, 'review'])
			->name('forms.submissions.mark-reviewed');

		Route::get('forms/{form}/submissions/{submission}/mark-unreviewed', [SubmissionReviewController::class, 'unreview'])
			->name('forms.submissions.mark-unreviewed');
	});
