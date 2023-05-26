<?php $page = TCG\Voyager\Models\Page::first(); ?>

@can('edit', $page)

You can edit

@elsecan('browse', $page)

You can browse

@else

You have no permission to edit

@endcan