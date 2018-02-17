@includeWhen(auth()->check() && auth()->user()->confirmed, 'modals.new-thread')
@include('modals.login')
@include('modals.register')
