<div class="col-lg-4">
    <div class="sidebar mb-0">
        <div class="sidebar-widget">
            <h3 class="widget-title">Search</h3>
            <div class="stroke-shape mb-4"></div>
            {{--    In the action, we have to be carful to always put / at the beginning. / means, that this action will replace
completely the previous url. Without the /, this action will be added to the previous url. --}}
            <form action="/ad-list/{{ $adTypeForUrl }}" class="form-box" method="POST">
                @csrf
                <div class="mt-2">Location</div>
                <div class="d-flex flex-row">
                    {{--                This here is a nice example how forms can 'remember' previous values after refresh. Basically, a controller simply resends the
                    previous input data to the form again. The previous input data here is the $savedFilter. For cases when we may not have $savedFilter, we will display a
                    empty string '', and for this we use a ternary operator.--}}
                    <input type="text" class="form-control" id="city" placeholder="city" name="city" value="{{ isset($savedFilter['city']) ? $savedFilter['city'] : '' }}">
                    <input type="text" class="form-control" id="location" placeholder="location in city" name="location_in_city" value="{{ isset($savedFilter['location_in_city']) ? $savedFilter['location_in_city'] : ''  }}">
                </div>
                <div class="mt-2">Number of rooms</div>
                <div class="d-flex flex-row">
                    <input type="text" class="form-control" id="minRooms" placeholder="minRooms" name="min_rooms" value="{{ isset($savedFilter['min_rooms']) ? $savedFilter['min_rooms'] : ''  }}">
                    <input type="text" class="form-control" id="maxRooms" placeholder="maxRooms" name="max_rooms" value="{{ isset($savedFilter['max_rooms']) ? $savedFilter['max_rooms'] : ''  }}">
                </div>
                <div class="mt-2">Surface</div>
                <div class="d-flex flex-row">
                    <input type="text" class="form-control" id="minSurface" placeholder="minSurface" name="min_surface" value="{{ isset($savedFilter['min_surface']) ? $savedFilter['min_surface'] : ''  }}">
                    <input type="text" class="form-control" id="maxSurface" placeholder="maxSurface" name="max_surface" value="{{ isset($savedFilter['max_surface']) ? $savedFilter['max_surface'] : ''  }}">
                </div>
                <div class="mt-2">Floor</div>
                <div class="d-flex flex-row">
                    <input type="text" class="form-control" id="minFloor" placeholder="minFloor" name="min_floor" value="{{ isset($savedFilter['min_floor']) ? $savedFilter['min_floor'] : ''  }}">
                    <input type="text" class="form-control" id="maxFloor" placeholder="maxFloor" name="max_floor" value="{{ isset($savedFilter['max_floor']) ? $savedFilter['max_floor'] : ''  }}">
                </div>
                <div class="mt-2">Price</div>
                <div class="d-flex flex-row">
                    <input type="text" class="form-control" id="minPrice" placeholder="minPrice" name="min_price" value="{{ isset($savedFilter['min_price']) ? $savedFilter['min_price'] : ''  }}">
                    <input type="text" class="form-control" id="maxPrice" placeholder="maxPrice" name="max_price" value="{{ isset($savedFilter['max_price']) ? $savedFilter['max_price'] : ''  }}">
                </div>
                <div class="mt-2">Price by surface</div>
                <div class="d-flex flex-row">
                    <input type="text" class="form-control" id="minPriceBySurface" placeholder="minPriceBySurface" name="min_price_by_surface" value="{{ isset($savedFilter['min_price_by_surface']) ? $savedFilter['min_price_by_surface'] : ''  }}">
                    <input type="text" class="form-control" id="maxPriceBySurface" placeholder="maxPriceBySurface" name="max_price_by_surface" value="{{ isset($savedFilter['max_price_by_surface']) ? $savedFilter['max_price_by_surface'] : ''  }}">
                </div>

                <div class="btn-box">
                    <button type="submit" class="theme-btn gradient-btn border-0 w-100 mt-3">Search now</button>
                </div><!-- end btn-box -->
            </form>

        @auth
{{--            This is the Vue saveFilter component. It is receiveng a $savedFilter object from Laravel and blade. The
Vue us receiving it, which is amazing. The $savedFilter will be received through props, and will be used to create a new
filter object in the db. Now, the props variable will be called savedFilter, and because of that, here we bind it to
 :saved-filter --}}

            @if($savedFilterId === null)
                <save-filter :saved-filter="{{ json_encode($savedFilter) }}"></save-filter>
            @endif

            {{--DELETE BUTTON--}}
            @if($savedFilterId)
                <ad-list-delete :saved-filter-id="{{ $savedFilterId ?? ''}} "></ad-list-delete>
            @endif
        @endauth
        </div><!-- end sidebar-widget -->
    </div><!-- end sidebar -->
</div><!-- end col-lg-4 -->
