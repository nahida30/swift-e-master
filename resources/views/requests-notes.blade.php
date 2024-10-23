@extends('shared.layout')
@include('shared.header')
@include('shared.sidebar')
@include('shared.footer')

@section('title', $request->tag_it.' #'.$request->count)

@push('styles')
<style>

</style>
@endpush

@section('content')
<main id="main" class="main">
  @include('shared.breadcrumb', [
    'pagename' => $request->tag_it.' #'.$request->count,
    'button' => '<div><a class="btn btn-primary" onclick="onAddNote('.$request->id.')"><i class="bi bi-plus-lg"></i> Add Note</a><a class="btn btn-danger ms-2" onclick="history.back()"><i class="bi bi-x-lg"></i></a></div>',
  ])

  <section class="section">
    <div class="row">
      <div class="col-12">
        @if(count($request->notes))
        <ul class="list-unstyled">
          @foreach($request->notes as $note)
          <li class="d-flex justify-content-{{ $note->sender_id == $user->id ? 'start' : 'end' }}">
            @if($note->sender_id == $user->id)
            <img src="{{ asset('img/profile-img.png') }}" alt="avatar" class="rounded-circle d-flex align-self-start me-3 shadow-1-strong" width="60" />
            @endif
            <div class="card chat-card">
              <div class="card-header d-flex justify-content-between p-3">
                <p class="fw-bold mb-0">{{ $note->sender_id == $user->id ? 'Me' : $note->sender->name }}</p>
                <p class="text-muted small mb-0 ms-5"><i class="far fa-clock"></i> {{ $note->created_at_local }}</p>
              </div>
              <div class="card-body p-3">
                <p class="mb-0">{{ $note->note }}</p>
              </div>
            </div>
            @if($note->sender_id != $user->id)
            <img src="{{ asset('img/profile-img.png') }}" alt="avatar" class="rounded-circle d-flex align-self-start ms-3 shadow-1-strong" width="60" />
            @endif
          </li>
          @endforeach
        </ul>
        @else
        <div class="card">
          <div class="card-body">
            <h5 class="card-title text-center mt-3">No Notes Added</h5>
          </div>
        </div>
        @endif
      </div>
    </div>
  </section>
</main>

@include('includes.modals.note-add')

@endsection


@push('scripts')
<script>

</script>
@endpush