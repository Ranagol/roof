{{-- Reminder: the $savedFilterId ?? '' id being sent from the relevant controller to the adList.blade.php--}}
<div class="d-flex flex-row mb-4">
    <a class="text-white mr-3" href="/ad-list/non-processed/{{ $savedFilterId ?? '' }}">
        <p class="text-white h6">Non-processed ads</p>
    </a>
    <a class="text-white mr-3 h6" href="/ad-list/starred/{{ $savedFilterId ?? '' }}">
        <p class="text-white">Starred ads</p>
    </a>
    <a class="text-white mr-3" href="/ad-list/dismissed/{{ $savedFilterId ?? '' }}">
        <p class="text-white h6">Dismissed ads</p>
    </a>
</div>
