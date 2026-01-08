@forelse ($vehicles as $vehicle)
    <div class="col-md-4 mb-3">
        <div class="single-offers">
            <div class="offer-image">
                <a href="{{ route('vehicle.detail',['id'=>$vehicle->id ]) }}">
                    <img src="{{ asset('assets/img/vehicle_images/' . $vehicle->vehicleImages->first()->image_url) }}" alt="{{ $vehicle->title }}" />
                </a>
            </div>
            <div class="offer-text">
                 <h3 style="margin-bottom: 0"> {{ $vehicle->year }} {{ \Illuminate\Support\Str::limit($vehicle->title, 35) }} </h3>
                        <p>{{ \Illuminate\Support\Str::limit($vehicle->short_Description, 70)  }}</p>
              

                <ul class="card_ul">
                    <li><i class="fa fa-car"></i> {{ $vehicle->make->name }}</li>
                    <li><i class="fa fa-cogs"></i> {{ $vehicle->transmission }}</li>
                  
                    <li><i class="fa fa-eye"></i>  {{ $vehicle->vehiclemodel->name }}</li>
                            <li><i class="fa fa-tint"></i> {{ $vehicle->fuel_type }}</li>
                         
                           
                            <li><i class="fa fa-road"></i>  {{ $vehicle->mileage }} Milles</li>
                            
                </ul>
                <div class="offer-action">
                    {{-- <a href="{{ route('vehicle.detail',['id'=>$vehicle->id ]) }}" class="offer-btn-1">{{ $vehicle->status }}</a> --}}
                    <a href="{{ route('vehicle.detail',['id'=>$vehicle->id ]) }}" class="offer-btn-2">£{{ number_format($vehicle->rent, 0) }} - Details </a>
                </div>
            </div>
        </div>
    </div>
@empty
    <div class="w-100 text-center py-5">
        <h3>No Vehicles Found</h3>
    </div>
@endforelse



@if ($vehicles->hasPages())
    <div id="paginate" class="pagination-box-row text-center col-12">
        <p>Page {{ $vehicles->currentPage() }} of {{ $vehicles->lastPage() }}</p>
        <ul class="pagination">
            @if (!$vehicles->onFirstPage())
                <li><a href="{{ $vehicles->previousPageUrl() }}"><i class="fa fa-angle-double-left"></i></a></li>
            @endif

            @foreach(range(1, $vehicles->lastPage()) as $page)
                @if ($page == $vehicles->currentPage())
                    <li class="active"><a href="javascript:void(0)">{{ $page }}</a></li>
                @elseif ($page == 1 || $page == $vehicles->lastPage() || abs($page - $vehicles->currentPage()) < 2)
                    <li><a href="{{ $vehicles->url($page) }}">{{ $page }}</a></li>
                @elseif ($page == 2 || $page == $vehicles->lastPage() - 1)
                    <li><span>...</span></li>
                @endif
            @endforeach

            @if ($vehicles->hasMorePages())
                <li><a href="{{ $vehicles->nextPageUrl() }}"><i class="fa fa-angle-double-right"></i></a></li>
            @endif
        </ul>
    </div>
@endif