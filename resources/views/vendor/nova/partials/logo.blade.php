<div class="flex justify-center">
    @if(!is_null(nova_get_setting('logo')))
        <img src="/storage/{{nova_get_setting('logo')}}" alt="logo of the application" style="height: 100px;">
    @else
        SYSTEM LOGO HERE
    @endif
</div>
