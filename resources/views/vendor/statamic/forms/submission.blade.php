@extends('statamic::layout')
@section('title', Statamic::crumb('Submission ' . $submission->id(), $submission->form->title(), 'Forms'))

@section('content')

    @include('statamic::partials.breadcrumb', [
        'url' => cp_route('forms.show', $submission->form->handle()),
        'title' => $submission->form->title()
    ])

    <header class="mb-6">
        <div class="flex items-center gap-2">
            <h1 v-pre class="flex-1">{{ $title }}</h1>

            <dropdown-list>
                <button class="btn" slot="trigger">{{ __('Actions') }}</button>

                @if ($submission->get('admin_reviewed'))
                    <dropdown-item
                        text="{{ __('Mark Unreviewed') }}"
                        redirect="{{ cp_route('forms.submissions.mark-unreviewed', [$submission->form->handle(), $submission->id()]) }}"
                    ></dropdown-item>
                @else
                    <dropdown-item
                        text="{{ __('Mark Reviewed') }}"
                        redirect="{{ cp_route('forms.submissions.mark-reviewed', [$submission->form->handle(), $submission->id()]) }}"
                    ></dropdown-item>
                @endif
            </dropdown-list>
        </div>
    </header>

    <publish-form
        title="{{ $title }}"
        :blueprint='@json($blueprint)'
        :meta='@json($meta)'
        :values='@json($values)'
        read-only
    ></publish-form>

@endsection