<form method="GET" action="{{ $action }}">
    <div class="py-2 flex">
        <div class="flex">
            <input type="search" name="search" value="{{ request()->input('search') }}" class="input input-bordered w-full max-w-xs" placeholder="Search">
            <button type='submit' class='btn px-6 ml-3 normal-case btn-primary'>
                {{ __('Search') }}
            </button>
        </div>
    </div>
</form>