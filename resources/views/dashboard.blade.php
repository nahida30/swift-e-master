@extends('shared.layout')
@include('shared.header')
@include('shared.sidebar')
@include('shared.footer')

@section('title', 'Dashboard')

@push('styles')
<style>

</style>
@endpush

@section('content')
<main id="main" class="main">
  @include('shared.breadcrumb', ['pagename' => 'Dashboard'])

  <section class="section dashboard">
    <div class="row">
      <div class="col-lg-8">
        <div class="row">
          <div class="col">
            @if(!$user->isAdmin())
            <a href="{{ route('requests.add', 0) }}">
              @endif
              <div class="card info-card sales-card">
                <div class="card-body">
                  <h5 class="card-title">New Request</h5>
                  <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                      <i class="bi bi-layout-text-window-reverse"></i>
                    </div>
                    @if($user->isAdmin())
                    <div class="ps-3">
                      <h1 class="mb-0">{{ $newRequests }}</h1>
                    </div>
                    @endif
                  </div>
                </div>
              </div>
              @if(!$user->isAdmin())
            </a>
            @endif
          </div>
          <div class="col">
            <a href="{{ route('requests.index', ['status' => 'Issue']) }}">
              <div class="card info-card customers-card">
                <div class="card-body">
                  <h5 class="card-title">Issues</h5>
                  <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                      <i class="bi bi-layout-text-window-reverse"></i>
                    </div>
                    <div class="ps-3">
                      <h1 class="mb-0">{{ $issues }}</h1>
                    </div>
                  </div>
                </div>
              </div>
            </a>
          </div>
          <div class="col-12">
            <div class="card">
              <div class="card-body">
                <h5 class="card-title">Reports</h5>
                <div id="reportsChart"></div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-lg-4 d-flex flex-column">
        <a class="btn btn-primary w-100 mb-4" href="{{ route('requests.index') }}">See All Requests</a>
        <div class="card flex-fill">
          <div class="card-body p-0">
            <h5 class="card-title p-3 m-0">Recent Activities</h5>
            <div class="activity px-3 overflow-auto" style="height: 32rem;">
              @if(count($activities))
              @foreach($activities as $activity)
              <div class="activity-item d-flex">
                <div class="activite-label">{{ date('h:i A', strtotime($activity->created_at_local)) }}<br />{{ date('Y-m-d', strtotime($activity->created_at_local)) }}</div>
                <i class='bi bi-circle-fill activity-badge text-success align-self-start'></i>
                @if($activity->type == 'request')
                <div class="activity-content">{{ $activity->user->name }} {{ $activity->description }} {{ $activity->reference->tag_it ?? '' }} #{{ $activity->reference->count ?? '' }}</div>
                @endif
                @if($activity->type == 'note')
                <div class="activity-content">{{ $activity->user->name }} {{ $activity->description }} regarding {{ $activity->reference->request->tag_it ?? '' }} #{{ $activity->reference->request->count ?? '' }}</div>
                @endif
                @if($activity->type == 'invoice')
                <div class="activity-content">{{ $activity->user->name }} {{ $activity->description }} #{{ $activity->reference->id }}</div>
                @endif
              </div>
              @endforeach
              @else
              <div>No Activities</div>
              @endif
            </div>
          </div>
        </div>
      </div>

      <div class="{{ $user->isAdmin() ? 'col-lg-8' : 'col-lg-12' }}">
        <div class="card recent-sales overflow-auto">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
              <h5 class="card-title">Last 10 Documents</h5>
              <a href="{{ route('requests.index') }}">All Documents</a>
            </div>
            <div class="table-responsive">
              <table class="table table-bordered table-striped datatable">
                <thead>
                  <tr>
                    @if($user->isAdmin())<th>Username</th>@endif
                    <th>Tag It</th>
                    <th>Doc Type</th>
                    <th>Uploaded</th>
                    <th>Submitted</th>
                    <th>State</th>
                    <th>County</th>
                    <th>Status</th>
                    <th class="text-center" width="240">Action</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($documents as $document)
                  <tr>
                    @if($user->isAdmin())<td>{{ $document->user->name }}</td>@endif
                    <td><a href="{{ route('requests.details', $document->id) }}">{{ $document->tag_it }} #{{ $document->count }}</a></td>
                    <td>{{ $requestTypes[$document->doc_type] }}</td>
                    <td>{{ $document->uploaded_at_local }}</td>
                    <td>{{ $document->created_at_local }}</td>
                    <td>{{ $document->state }}</td>
                    <td>{{ $document->county }}</td>
                    <td>{{ $document->status }}</td>
                    <td class="text-center">

                    <a class="btn btn-secondary btn-sm" data-toggle="tooltip" data-placement="top" title="Details Verify" href="{{ route('verify.request', $document->id) }}">
                        <i class="bi bi-send"></i>
                    </a>
                    
                      @if($user->isAdmin())
                      <a class="btn btn-secondary btn-sm" data-toggle="tooltip" data-placement="top" title="Details" href="{{ route('requests.details', $document->id) }}">
                        <i class="bi bi-receipt"></i>
                      </a>

                      <!-- <a class="btn btn-warning btn-sm" data-toggle="tooltip" data-placement="top" title="Swift-E Notes" href="{{ route('requests.notes', $document->id) }}">
                        <i class="bi bi-journal-text"></i>
                      </a> -->
                      <a class="btn btn-primary btn-sm" data-toggle="tooltip" data-placement="top" title="View" onclick="onViewFile('{{ $document->file }}')">
                        <i class="bi bi-eye"></i>
                      </a>
                      <a class="btn btn-info btn-sm" data-toggle="tooltip" data-placement="top" title="Edit" href="{{ route('requests.add', $document->id) }}">
                        <i class="bi bi-pencil-square"></i>
                      </a>
                      @if($document->status == 'Sent')
                      <a class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="top" title="Accept" href="{{ route('requests.accept', $document->id) }}" onclick="return confirm('Are you sure you want to ACCEPT this Request?');">
                        <i class="bi bi-check"></i>
                      </a>
                      @endif
                      <form action="{{ route('requests.delete', $document->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Cancel" onclick="return confirm('Are you sure you want to CANCEL this Request? This action can not be undone if performed.');">
                          <i class="bi bi-trash"></i>
                        </button>
                      </form>
                      @else
                      @if($document->completed_file)
                      <a class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="top" title="Download" href="{{ $document->completed_file }}" target="_blank">
                        <i class="bi bi-download"></i>
                      </a>
                      @endif
                      <a class="btn btn-secondary btn-sm" data-toggle="tooltip" data-placement="top" title="Details" href="{{ route('requests.details', $document->id) }}">
                        <i class="bi bi-receipt"></i>
                      </a>
                      <!-- <a class="btn btn-warning btn-sm" data-toggle="tooltip" data-placement="top" title="Swift-E Notes" href="{{ route('requests.notes', $document->id) }}">
                        <i class="bi bi-journal-text"></i>
                      </a> -->
                      <a class="btn btn-primary btn-sm" data-toggle="tooltip" data-placement="top" title="View" onclick="onViewFile('{{ $document->file }}')">
                        <i class="bi bi-eye"></i>
                      </a>
                      @if($document->status == 'Sent')
                      <a class="btn btn-info btn-sm" data-toggle="tooltip" data-placement="top" title="Edit" href="{{ route('requests.add', $document->id) }}">
                        <i class="bi bi-pencil-square"></i>
                      </a>
                      <form action="{{ route('requests.delete', $document->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Cancel" onclick="return confirm('Are you sure you want to CANCEL this Request? This action can not be undone if performed.');">
                          <i class="bi bi-trash"></i>
                        </button>
                      </form>
                      @endif
                      @endif
                    </td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>

      @if($user->isAdmin())
      <div class="col-lg-4">
        <div class="card">
          <div class="card-body pb-0">
            <h5 class="card-title">Expense Tracker</h5>
            <div id="expenseChart" style="min-height: 400px;" class="echart"></div>
          </div>
        </div>
      </div>
      @endif
    </div>
  </section>
</main>

@include('includes.modals.pdf-view')

@endsection


@push('scripts')
<script>
  document.addEventListener("DOMContentLoaded", () => {
    let datasets = [],
      categories = []
    '@foreach($graphDatasets as $graphDataset)'
    datasets.push('{{ $graphDataset["count"] }}')
    categories.push('{{ $graphDataset["month"] }}')
    '@endforeach'

    new ApexCharts(document.querySelector("#reportsChart"), {
      series: [{
        name: 'Request Count',
        data: datasets,
      }],
      chart: {
        height: 350,
        type: 'area',
        toolbar: {
          show: false
        },
      },
      markers: {
        size: 4
      },
      colors: ['#4154f1', '#2eca6a', '#ff771d'],
      fill: {
        type: "gradient",
        gradient: {
          shadeIntensity: 1,
          opacityFrom: 0.3,
          opacityTo: 0.4,
          stops: [0, 90, 100]
        }
      },
      dataLabels: {
        enabled: false
      },
      stroke: {
        curve: 'smooth',
        width: 2
      },
      xaxis: {
        categories: categories
      },
    }).render()

    if (document.querySelector("#expenseChart")) {
      echarts.init(document.querySelector("#expenseChart")).setOption({
        tooltip: {
          trigger: 'item'
        },
        legend: {
          top: '5%',
          left: 'center'
        },
        series: [{
          name: 'Expense Tracker',
          type: 'pie',
          radius: ['40%', '70%'],
          avoidLabelOverlap: false,
          label: {
            show: false,
            position: 'center'
          },
          emphasis: {
            label: {
              show: true,
              fontSize: '18',
              fontWeight: 'bold'
            }
          },
          labelLine: {
            show: false
          },
          data: JSON.parse('{!! $expenseData !!}'),
        }]
      })
    }
  })
</script>
@endpush