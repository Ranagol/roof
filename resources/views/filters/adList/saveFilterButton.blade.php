<form method="POST" action="/save-filter">
    {{ csrf_field() }}
    <div class="col">
        {{--                                            SAVE FILTER BUTTON--}}
        <button type="submit"  style="width: 7rem" class="btn btn-outline-info">Save filter</button>
    </div>
</form>
