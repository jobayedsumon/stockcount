<footer class="app-footer">
    <div class="site-footer-right">
        @if (rand(1,100) == 100)
            <i class="voyager-rum-1"></i> {{ __('voyager::theme.footer_copyright2') }}
        @else
            {!! __('voyager::theme.footer_copyright') !!} <a href="#" target="_blank">Emami Group</a>
        @endif
        @php $version = Voyager::getVersion(); @endphp
        @if (!empty($version))
            - v1.0
        @endif
    </div>
</footer>